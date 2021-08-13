/**
 * Fecha: 12/08/2021.
 * Autor: Santiago Raimondi.
 * 
 * Descripcion: 
 * Se sensa la temperatura y presion ambiente utilizando el sensor BMP280 y se 
 * transmite la informacion a un broker MQTT.
 * En caso de no haber conexion a internet, se guarda en memoria no volatil
 * hasta que haya conexion a internet, donde se interrumpe el funcionamiento 
 * para enviar todo lo almacenado hasta vaciar la memoria y reanudar el sensado.
 * 
 * Se hace uso de la libreria WiFiManager para gestionar la interfaz y creacion
 * del punto de acceso para que el usuario configure la conexion del dispositivo
 * a una red WiFi. Hay un boton exterior para desconectar al dispositivo de la 
 * red wifi a la que esta conectado para conectarlo a otra red.
 * 
 * 
 * 
 * 
 * TODO: 
		Completar con algoritmo de guardar en memoria EEPROM el valor sensado 
		hasta que haya conexion.

		Agregar condicion de que si no hay conexion a internet O AL BROKER se 
		guarde la informacion en EEPROM.

		Agregar algoritmo de retransmision de la informacion guardada en memoria.

		IDEA: Agregar que en el access point el usuario pueda ingresar los 
		topicos o los parametros del broker propio.

		Agregar alarmas para valores determinados????

		Agregar botones????  actuadores????

		Agregar modo deep sleep????

		Agregar interfaz para que sea un datalogger en memoria SD????
 * 
 * Conexiones en la ESP32:
		GPIO13: Boton de desconexion. Pull-down interno, hay que conectarlo a 3.3[V].
		GPIO21: I2C SDA.
		GPIO22: I2C SCL.
 * 
 * 
*/

#include <Arduino.h>
#include <WiFiManager.h>	// https://github.com/tzapu/WiFiManager
#include <PubSubClient.h>
#include <Wire.h>			
#include <Adafruit_BMP280.h>

/* === Variable para debugg === */
// #define __DEBUGG (bool) false
#ifndef	__DEBUGG
#define __DEBUGG (bool) true	/* Debugg activado  */
#endif

/* === Variables wifi === */
#define WIFI_DISCONNECT_GPIO (uint8_t) 13	/* Pin del boton de desconexion de WiFi */
const char *ssid = "ESP32-WiFi";    // SSID de la red wifi que generara el dispositivo (cuando este en modo AP).
const char *pass = "123456789";    	// Contraseña de dicha red.
WiFiManager wm;						/* Objeto de la clase WiFiManager */
bool desconectarse = false;			// Bandera que indica si se pulso el boton de desconexion.

/* === Parámetros de configuracion y topicos del broker MQTT === */
#define MSG_BUFFER_SIZE	(50)
#define PORT (uint16_t) 1883
const char* mqtt_server = "ioticos.org";
const char *mqtt_user = "pdmlO2qrY6s8h7y";
const char *mqtt_pass = "m1bGUlqz27SMsmX";
const char *topico_pub_temyhum = "KMb6809yr8FThW1/88888/BD/temyhum";      				// Mandamos la ultima temperatura y humedad promediada
const char *topico_pub_botones = "KMb6809yr8FThW1/88888/BD/botones";	 				// Mandamos el ultimo estado de los reles modificados con los botones
const char *topico_pub_consultabotones = "KMb6809yr8FThW1/88888/BD/consultabotones";  	// Preguntamos como estaban los botones en la BD
const char *topico_pub_alarma = "KMb6809yr8FThW1/88888/python/trigger_alarma";			// Para avisar que se supero un valor limite
const char *topico_sub_botones = "KMb6809yr8FThW1/python/88888/consultabotones";	 	// Nos subcribimos a todas las fuentes que muestren el estado de los reles
const char *topico_sub_limites = "KMb6809yr8FThW1/python/88888/get_limites";

WiFiClient clientWiFi;
PubSubClient clientMQTT(clientWiFi);	//GLOBAL???? /* Se crea una instancia del cliente MQTT */
char msg[MSG_BUFFER_SIZE];

const int ID_SERIAL=88888;	// NO SERIA MEJOR UN DEFINE???? // N° Serial del dispositivo.

/* === Valores recibidos de la pagina web === */
int receivedNum1,receivedNum2,receivedNum3,receivedNum4,receivedNum5;
String text_1;
boolean rel1=false;					// Almacena estado actual de los reles
boolean rel2=false;
boolean rel3=false;
boolean rel4=false;
boolean flag_actualizar;
boolean flag_enviarDatos;
float temp_max=200, temp_min=-200, hum_max=200, hum_min=-200;    //donde se almacena los valores de temp y hum criticos, por default estan valores inalcanzables

/* === Sensor BMP280 === */
#define DIRECCION_BMP280 (uint8_t) 0x76
#define PRESION_NIVEL_MAR_HP (1013.25)	/* Para altura relativa al mar */
float  PRESION_REALATIVA_HP=0;	/* Para medir altura relativa al punto de inicio*/
Adafruit_BMP280 bmp280;	

/* === Funciones wifi === */

void configWifi();

/* === Funciones MQTT === */

void callback(char* , byte* , unsigned int);
void reconnect();
void pubData(const char* topico, float dato1 = NULL, float dato2 = NULL);

/* === Configuracion de pines === */

void configPines();

/* === Configuracion de sensores === */

void configSensores();

void setup(){

	/* Configuraciones basicas */
	Serial.begin(115200);
	configPines();
	configSensores();
	configWifi();

	/* === Configuración de conexion a broker MQTT === */
	clientMQTT.setServer(mqtt_server, PORT);
  	clientMQTT.setCallback(callback);
}

void loop() {
	
	/* Se toman mediciones */
	float temp = bmp280.readTemperature();	
	float presion = bmp280.readPressure()/100;	

	if(WiFi.isConnected())
	{	

		reconnect();	/* Se intenta reconectar al broker MQTT si se habia desconectado */
		pubData(topico_pub_temyhum, temp, presion);	/* Se publica el mensaje */
		clientMQTT.loop();   //esta atento a que lleguen nuevos mensajes del broker

		if(__DEBUGG)
		{
			delay(750);
			digitalWrite(LED_BUILTIN, HIGH);
			delay(750);
			digitalWrite(LED_BUILTIN, LOW);
			
			Serial.print("Temperatura: ");		
			Serial.print(temp);					
			Serial.println(" [°C] ");				
			Serial.print("Presion: ");		
			Serial.print(presion);				
			Serial.println(" [hPa]");	
		}
	}
	else
	{
		/* ------ TESTEAR ------ */
		/* Probar si funciona el autoconnect 
		   simulando que se corta la señal de wifi
		   para ver si se puede reconectar con las
		   credenciales que ya estaban guardadas*/

		wm.process();	/* Se procesa la informacion del webserver*/

		/* ------ COMPLETAR ------ */
		/*Completar con algoritmo de 
		  guardar en memoria EEPROM el 
		  valor sensado hasta que haya conexion
		*/


		/* Señalizacion visual de que no hay internet, quizas deshabilitar esta opcion para mejorar consumo */
		delay(100);
		digitalWrite(LED_BUILTIN, HIGH);
		delay(100);
		digitalWrite(LED_BUILTIN, LOW);
	}

	/* Por unica vez se hace la desconexion del wifi y del broker y se inicia el modo AP */
	if(desconectarse)
	{
		clientMQTT.disconnect();
		WiFi.disconnect();
		wm.disconnect();
		/* Elimina las credenciales para que no se conecte a la misma red. 
		   Esto se hace SOLO en el caso que la desconexion sea voluntaria. */
		wm.resetSettings();	
		configWifi();
		desconectarse = false;
	}
}

/**
 * Se inicializa el objeto WiFiManager en modo no bloqueante.
 * Se intenta conectar de forma automatica con las credenciales que encuentra guardadas.
 * Si no, inicia en modo AP con una red de nombre y contraseña establecida por referencia.
 */
void configWifi()
{	
	wm.setDebugOutput(__DEBUGG);	/* Para debugguear */

	WiFi.mode(WIFI_STA); /* Modo de funcionamiento seteado explicitamente. Por defecto STA+AP */     

	if(__DEBUGG)
		wm.resetSettings();	/* Se resetean las credenciales, solo para debugg, luego las recuerda (EEPROM) */


	wm.setConfigPortalBlocking(false);	/* Trabaja en modo no bloqueante */

	/* Esta funcion si no se conecta automaticamente, crea un AP */
	if(wm.autoConnect(ssid, pass) && __DEBUGG){
		Serial.println("Conectada al wifi...");
	}
	else {
		if(__DEBUGG)
			Serial.println("Se inicio el portal de configuracion en modo AP");
		
		/* Se  configura la pagina del AP */
		wm.setTitle("RSA - IOT");
		wm.setDarkMode(true);
	}
}

/**
 * Desconectamos el dispositivo.
*/
void desconectarWifi()
{
	if(WiFi.isConnected())
	{	
		if(__DEBUGG)
		{
			Serial.println("Desconectando WiFi...");
		}
		desconectarse = true;
	}
	else{
		if(__DEBUGG)
		{
			Serial.println("Ya esta desconectado del WiFi...");
		}
	}

}

/* ============ Funciones de MQTT ============ */

/**
 * Funcion que se llama cuando se publica un mensaje en uno de los topicos a los cuales
 * esta suscripto el dispositivo.
 * De acuerdo al topico recibido y al payload, se toman acciones respecto a los actuadores
 * @param char* topic: puntero al char array que contiene el topico.
 * @param byte* payload: puntero al byte array que tiene el mensaje recibido.
 * @param unsigned int length: longitud en caracteres del payload.
*/
void callback(char* topic, byte* payload, unsigned int length) 
{
	if(__DEBUGG)
	{
		Serial.print("Mensaje recibido [");
		Serial.print(topic);
		Serial.print("] ");
		for (unsigned int i = 0; i < length; i++) {
			Serial.print((char)payload[i]);
		}
		Serial.println();
	}

	// --------------------------------------
  	/*
	if (strcmp(topic,topico_sub_botones) == 0)
	{
		for (unsigned int i = 0; i < length; i++) 
		{
			boolean estado= (payload[i]==49);	// Se comprueba el valor recibido (en ASCII '0'=48 '1'=49), si llega 0 es false lo cual pone en bajo el pin.
		
			switch (i) 
			{			
				case 0:	//formato del mensaje recibido x/x/x/x motivo por el cual se revisan los casos pares.
					digitalWrite(rele1,estado);
					break;
				case 2:
					digitalWrite(rele2,estado);			
					break;
				case 4:
					digitalWrite(rele3,estado);
					break;
				case 6:
					digitalWrite(rele4,estado);			
						break;
				default:
					break;
			}
		}
	}
	else
	{
		if (strcmp(topic,topico_sub_limites) == 0)
		{
			String aux1 = "";	// Auxiliar para ir guardando el valor que se va decodificando
			int aux2 = 1;		// Auxiliar para saber que valor estoy leyendo

			for (unsigned int i = 0; i < length; i++) 
			{
				// El payload que llega es de la pinta de x/x/x/x/ Necesito la ultima barra si o si para que se asignen todas las variables correctamente
				if ((char)payload[i] != '/')
				{
					aux1 += (char)payload[i];	// Mientras leo el mismo valor decodifico y almaceno los bytes en un aux1
					
				}
				else
				{
					switch (aux2)
					{
						case 1:	// Asigno el valor leido a la variable que corresponda casteando el String a float
							aux2++;
							temp_max = aux1.toFloat();	
							break;
						case 2:
							aux2++;
							temp_min = aux1.toFloat();
							break;
						case 3:
							aux2++;
							hum_max = aux1.toFloat();
							break;
						case 4:
							aux2++;
							hum_min = aux1.toFloat();
							break;
						default:
							break;
					}

					aux1 = "";	// Limpio el valor que ya se guardo para arrancar a guardar un nuevo valor 
				}
			}
		}
	}
	*/

}

/**
 * Funcion que reconecta el dispositivo al broker MQTT y hace la suscripcion a 
 * los topicos de publicacion y suscripcion.
 * En caso de no poder conectarse, se queda en un bucle que intenta cada 5
 * segundos durante 6 intentos.
*/
void reconnect() 
{
	// Se loopea hasta conectar
	if(clientMQTT.connected()) return;
	
	int intentos=0; /* Contador de intentos de conexion al broker */

	while (!clientMQTT.connected() && intentos<=5) 
	{
		if(__DEBUGG)
		{
			Serial.print("Intentando conexión MQTT...");

		}

		intentos++;
		
		// Se crea un cliente random para conectarse al broker
		String clientId = "ESP8266Client-" + String(ID_SERIAL);
		clientId += String(random(0xffff), HEX);
		
		// Intenta conectarse con las credenciales
		if (clientMQTT.connect(clientId.c_str(),mqtt_user,mqtt_pass)) 
		{
			if(__DEBUGG)
				Serial.println("Conectado con exito al broker!!");

			/* Se publica en el topico */
			if(clientMQTT.publish("KMb6809yr8FThW1/outTopic", "hello world") && __DEBUGG)
				Serial.println("Ya se puede publicar en: KMb6809yr8FThW1/outTopic");

			// Se vuelve a suscribir a todo lo que haga falta escuchar
			if(clientMQTT.subscribe("KMb6809yr8FThW1/inTopic") && __DEBUGG)
				Serial.println("Suscripcion realizada.");


			// clientMQTT.subscribe(topico_sub_botones);
			// clientMQTT.subscribe(topico_sub_limites);
		} 
		else 
		{
			/* Se imprime mensaje con codigo de error */
			if(__DEBUGG)
			{
				Serial.print("Fallo al conectar, rc=");
				Serial.print(clientMQTT.state());
				Serial.println(" intentando nuevamente en 5 segundos");
			}
			delay(5000);
		}
	}
}

/**
 * @brief: Se arma el paquete de informacion y se publica en el topico.
 * Por el momento solo acepta dos valores en el campo de datos.
 * @param const char* topico: Topico al cual publicar el mensaje.
 * @param float dato1: dato a publicar (paramentro opcional).
 * @param float dato2: dato a publicar (paramentro opcional).
 */
void pubData(const char* topico, float dato1, float dato2)
{
	String post_data = "";

	if(dato1 != NULL)
		post_data += String(dato1);

	if(dato2 != NULL)
		post_data += "+" + String(dato2);

	clientMQTT.publish(topico, post_data.c_str());

	if(__DEBUGG)
		clientMQTT.publish("KMb6809yr8FThW1/outTopic", post_data.c_str()); 
}

/**
 * Se configuran los pines de entrada y salida.
 */
void configPines()
{
	/* LED indicador */
	pinMode(LED_BUILTIN, OUTPUT);

	pinMode(WIFI_DISCONNECT_GPIO, INPUT_PULLDOWN);	/* Pin para interrupcion (ver conectarseAWifi()) */	
	/* Se habilita interrupcion para desconectar el dispositivo */
	attachInterrupt(digitalPinToInterrupt(WIFI_DISCONNECT_GPIO), desconectarWifi, HIGH); /* Con high anda, con low no*/
}

/**
 * Se configuran los sensores.
 * EL PROGRAMA SE BLOQUEA SI NO SE PUEDE CONFIGURAR EL SENSOR!!!!
 * 
*/
void configSensores()
{
	if ( !bmp280.begin(DIRECCION_BMP280) ) 
	{	// si falla la comunicacion con el sensor mostrar texto y detener flujo del programa
		Serial.println("BMP280 no encontrado !");	
		while (1);
	}

	PRESION_REALATIVA_HP = bmp280.readPressure()/100;	// almacena en la variable el valor actual de presion en HP
}
