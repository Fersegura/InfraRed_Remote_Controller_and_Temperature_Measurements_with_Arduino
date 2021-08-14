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

		IDEA: Que el uC se despierte cada tanto y tome una muestra, y se conecte
		a internet cada cierta cantidad de muestras, así no gasta tanto.
 * 
 * Conexiones en la ESP32:
		GPIO13: Boton de desconexion. Pull-down interno, hay que conectarlo a 3.3[V].
		GPIO21: I2C SDA.
		GPIO22: I2C SCL.
 * 
 * 
*/

#include "MiConfig.h"	/* Variables globales de configuracion y se incluye la libreria Arduino.h*/
#include "MiWifi.h"
#include "MiMQTT.h"
#include <Wire.h>			
#include <Adafruit_BMP280.h>


/* === Sensor BMP280 === */
#define DIRECCION_BMP280 (uint8_t) 0x76
#define PRESION_NIVEL_MAR_HP (1013.25)	/* Para altura relativa al mar */
float  PRESION_REALATIVA_HP=0;	/* Para medir altura relativa al punto de inicio*/
Adafruit_BMP280 bmp280;	

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
		   credenciales que ya estaban guardadas
		   
		   HAY PROBLEMA CUANDO SE CORTA EL WIFI
		   Si se corta el WiFi por X razon, el condicional
		   de la interrupcion de desconectarse deja de
		   funcionar y no se puede olvidar la configuracion
		   del WiFi sin hacer un reset del uC...
		   Para arreglarlo se puede quitar el chekeo de 
		   validacion si esta conectado o buscarle otra
		   vuelta al asunto.
		   -> IDEA: chekear la bandera de desconectarse 
		   if(!desconectarse)... Asi te podes dar cuenta 
		   si me estoy desconectando por primera vez.
		   (es casi lo mismo que no poner condicion 
		   en realidad...)
		   */

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
