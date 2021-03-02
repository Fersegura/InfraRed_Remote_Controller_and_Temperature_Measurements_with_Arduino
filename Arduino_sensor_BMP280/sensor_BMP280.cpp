/*
	Link video: https://www.youtube.com/watch?v=LtZ_b2WVMrU&t=734s
	Este codigo funciona perfectamente con ArduinoUNO y ESP8266 con las siguientes conexiones:
	(En modo I2C)
	ArduinoUNO	|	  BMP280
		3v3		|		VCC
		GND		|		GND
		A4		|		SDA
		A5		|		SCL

	ESP8266		|	  BMP280
		3v3		|		VCC
		GND		|		GND
	D2(GPIO4)	|		SDA
	D1(GPIO5)	|		SCL

*/

#include <Wire.h>				// incluye libreria de bus I2C
#include <Adafruit_Sensor.h>	// incluye librerias para sensor BMP280
#include <Adafruit_BMP280.h>

Adafruit_BMP280 bmp;	// crea objeto con nombre bmp

float TEMPERATURA;		// variable para almacenar valor de temperatura
float PRESION, P0;		// variables para almacenar valor de presion atmosferica
				        // y presion actual como referencia para altitud

void setup() 
{
	Serial.begin(115200);			// inicializa comunicacion serie a 9600 bps
	Serial.println("Iniciando:");	// texto de inicio
	if ( !bmp.begin() ) 
	{	// si falla la comunicacion con el sensor mostrar texto y detener flujo del programa
	Serial.println("BMP280 no encontrado !");	
	while (1);
	}
	P0 = bmp.readPressure()/100;		// almacena en P0 el valor actual de presion
}										// en hectopascales para calculo de altitud relativa

void loop() 
{
	TEMPERATURA = bmp.readTemperature();	// almacena en variable el valor de temperatura
	PRESION = bmp.readPressure()/100;		// almacena en variable el valor de presion divido
										// por 100 para covertirlo a hectopascales
	Serial.print("Temperatura: ");		// muestra texto
	Serial.print(TEMPERATURA);			// muestra valor de la variable
	Serial.print(" C ");					// muestra letra C indicando grados centigrados

	Serial.print("Presion: ");			// muestra texto
	Serial.print(PRESION);				// muestra valor de la variable
	Serial.println(" hPa");				// muestra texto hPa indicando hectopascales

	Serial.print("Altitud aprox: ");		// muestra texto
	Serial.print(bmp.readAltitude(P0));	// muestra valor de altitud con referencia a P0
	Serial.println(" m");					// muestra letra m indicando metros

	delay(5000);							// demora de 5 segundos entre lecturas
}