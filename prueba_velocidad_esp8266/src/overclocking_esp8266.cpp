/**
 * 	Segun el datasheet, el ESP8266 funciona con un clock de 80[MHz] por lo tanto 
 * vamos a medir el delay que introduce usar digitalWrite().
 * 	El caso ideal seria que se escriba un alto e inmediatamente despues un bajo
 * tardando 1/(80*10^6) [seg] = 12,5 [ns].
 * 
 * 	Conclusiones:
 * 		1) Utilizando digitalWrite() hay un delay de 970[ns] entre el flanco de
 * 		   subida y el de bajada.
 * 		2) Utilizando los registros GPIOS y GPIOC (Output Set y Clear) se obtie
 * 		   ne una diferencia de aproximadamente 300[ns] entre flancos.
 * 		3) Esos registros funcionan escribiendo un '1' en las posiciones corres
 * 		   pondientes (no hace falta escribir un '0').
 * 		4) Se puede modificar la frecuencia del clock del ESP8266 siguiendo lo 
 * 		   mencionado en https://github.com/esp8266/Arduino/issues/579.
 * 		   De cualquier manera, independientemente de lo que hace la funcion
 * 		   'system_update_cpu_freq(uint8 freq);' no encontre como probar ese
 * 		   cambio (las pruebas 1, 2 y 3 arrojaron los mismos resultados).
 * 
*/
#include <Arduino.h>

/* Prueba 4. */
#include <user_interface.h>	// De acuerdo a lo escrito en el issue: https://github.com/esp8266/Arduino/issues/579

void setup() 
{
	/* Cambio de frec. de CLK */
	Serial.begin(9600);
	Serial.println();
	bool a = false;
	a = system_update_cpu_freq(SYS_CPU_160MHZ);
	a ? Serial.println("La velocidad del clk se cambio a 160 MHZ ") : Serial.println("La velocidad de clk NO se afecto ");
	Serial.println("Velocidad de clk: " + String(system_get_cpu_freq()));

	pinMode(D1, OUTPUT);
}

void loop() 
{
	/* Prueba 1. */
	// digitalWrite(D1, HIGH);	
	// digitalWrite(D1, LOW);
	
	/* Prueba 2. */
	// GPOS |= 0x20;	// Supuestamente poniendo en 1 el bit del pin D1(GPIO5) del reg. GPIOSet
	// GPOC |= 0x20;	// Supuestamente poniendo en 1 el bit del pin D1(GPIO5) del reg. GPIOClr

	/* Prueba 3 (junto con prueba 2). */
	// GPOS &= 0xEF; 	// Supuestamente limpiamos el bit que pusimos en '1'. En teoria, no deberia 
	// 				// hacer nada xq estos registros solo importan los valores que estan en '1'.
	// GPOC &= 0xEF;	
	
	delay(100);
}