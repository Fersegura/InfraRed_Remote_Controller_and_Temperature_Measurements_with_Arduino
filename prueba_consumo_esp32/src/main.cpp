#include <Arduino.h>
#define TOUCH_THRESHOLD (uint16_t)30	
void callback();
void setup() {
	Serial.begin(9600);
	Serial.println("Estamos en el setup");
	touchAttachInterrupt(T0, callback, TOUCH_THRESHOLD);	
	setCpuFrequencyMhz(10);	/* Setea la frecuencia del CPU */
	Serial.println("Frecuencia del CPU: " + String(getCpuFrequencyMhz()));
	Serial.println("Frecuencia del APB: " + String(getApbFrequency()));
	delay(1000);
	esp_sleep_enable_touchpad_wakeup();
	esp_light_sleep_start();	/* Entra en light sleep */
}
void loop() {
	Serial.println(".");
	delay(1000);	/* Para que la ejecución del loop() sea similar al del setup() */
	esp_deep_sleep_start();	/* Entra en deep sleep*/
}
void callback(){
	Serial.println("Estamos en callback");
}
