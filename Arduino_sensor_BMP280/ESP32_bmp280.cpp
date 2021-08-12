/*
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com/esp32-i2c-communication-arduino-ide/
  
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.
  
  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
*/

#include <Arduino.h>
#include <Wire.h>				// incluye libreria de bus I2C
#include <Adafruit_BMP280.h>

#define PRESION_NIVEL_MAR_HP (1013.25)	/* Para altura relativa al mar */
float  PRESION_REALATIVA_HP=0;	/* Para medir altura relativa al punto de inicio*/

Adafruit_BMP280 bmp;	// crea objeto con nombre bmp

void setup() 
{
	Serial.begin(115200);			
	Serial.println("Iniciando:");	
	if ( !bmp.begin(0x76) ) 
	{	// si falla la comunicacion con el sensor mostrar texto y detener flujo del programa
		Serial.println("BMP280 no encontrado !");	
		while (1);
	}
	PRESION_REALATIVA_HP = bmp.readPressure()/100;	// almacena en la variable el valor actual de presion en HP

}										

void loop() 
{
	Serial.println();
	Serial.println(" ======================= ");

	float temp = bmp.readTemperature();	
	float presion = bmp.readPressure()/100;	
										
	Serial.print("Temperatura: ");		
	Serial.print(temp);					
	Serial.print(" C ");				

	Serial.print("Presion: ");		
	Serial.print(presion);				
	Serial.println(" hPa");			

	Serial.print("Altitud relativa al punto de inicializacion: ");	
	Serial.print(bmp.readAltitude(PRESION_REALATIVA_HP));	
	Serial.println(" m");

	Serial.print("Altitud relativa al mar: ");	
	Serial.print(bmp.readAltitude(PRESION_NIVEL_MAR_HP));	
	Serial.println(" m");			

	Serial.println(" ======================= ");
	Serial.println();


	delay(1000);						
}