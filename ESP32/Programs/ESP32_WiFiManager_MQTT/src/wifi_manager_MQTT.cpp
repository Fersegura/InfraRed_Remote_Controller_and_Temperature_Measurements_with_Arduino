#include <Arduino.h>
#include <WiFiManager.h>	// https://github.com/tzapu/WiFiManager
#include <PubSubClient.h>

/* === Variable para debugg === */
// #define __DEBUGG (bool) false
#ifndef	__DEBUGG
#define __DEBUGG (bool) true
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


/* === Funciones wifi === */

void configWifi();

/* === Funciones MQTT === */

void callback(char* , byte* , unsigned int );
void reconnect();

/* === Configuracion de pines === */

void configPines();

void setup() {

	/* Configuraciones basicas */
	Serial.begin(9600);
	configPines();
	configWifi();

	/* === Configuración de conexion a broker MQTT === */
	clientMQTT.setServer(mqtt_server, PORT);
  	clientMQTT.setCallback(callback);
}

void loop() {
	
	if(WiFi.isConnected())
	{
		if(__DEBUGG)
		{
			delay(750);
			digitalWrite(LED_BUILTIN, HIGH);
			delay(750);
			digitalWrite(LED_BUILTIN, LOW);
		}
		reconnect();	/* Se intenta reconectar al broker MQTT si se habia desconectado */
		clientMQTT.loop();   //esta atento a que lleguen nuevos mensajes del broker
	}
	else
	{
		wm.process();	/* Se procesa la informacion del webserver*/

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
		wm.resetSettings();	/* Elimina las credenciales para que no se conecte a la misma red */
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
 * Se configuran los pines de entrada y salida.
 */
void configPines()
{
	pinMode(LED_BUILTIN, OUTPUT);
	pinMode(WIFI_DISCONNECT_GPIO, INPUT_PULLDOWN);	/* Pin para interrupcion (ver conectarseAWifi()) */	
	/* Se habilita interrupcion para desconectar el dispositivo */
	attachInterrupt(digitalPinToInterrupt(WIFI_DISCONNECT_GPIO), desconectarWifi, HIGH); /* Con high anda, con low no*/
}

