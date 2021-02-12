/*
    Ejemplo interrupcion con timer1
    Autor: Santiago Raimondi
    Para mas informacion ir a: https://github.com/esp8266/Arduino/blob/eea9999dc5eaf464a432f77d5b65269f9baf198d/cores/esp8266/Arduino.h
*/

#include <ESP8266WiFi.h>

int cont=0;

ICACHE_RAM_ATTR void ISRtimer1();

void setup()
{
    Serial.begin(115200);
    Serial.println("");

    pinMode(LED_BUILTIN,OUTPUT);

    noInterrupts(); // Desactivo interrupciones hasta que termino de configurar
    //Inicializa el timer1 para que genere interrupciones
    // Primero habilito el timer1, con un divisor de 16 veces de clk, con accion por flanco
    // Con TIM_LOOP indico que cada vez que interrumpa, se recargue con el valor que tenía.
    // Si fuese TIM_SINGLE, interrumpe la primer vez, y si adentro de la ISR no recargo el timer no va a interrumpir de nuevo
    // Si pongo TIM_DIV1, y 80000000 en el write, no hace un segundo de interrupcion, pero con TIM_DIV16 si lo hace (con 80000000)
    timer1_enable(TIM_DIV16, TIM_EDGE, TIM_LOOP); 
    timer1_write(80000000); // 1 seg con TIM_DIV16. El máximo valor que se puede escribir es: 8388607
    timer1_attachInterrupt(ISRtimer1);  // Defino la función que tiene que ejecutar cuando haga overflow
    interrupts();   // Activo nuevamente interrupciones
}

void loop()
{
    // No se hace nada, todo es por interrupciones
}

void ISRtimer1()
{
    cont++;
    digitalWrite(LED_BUILTIN,!(digitalRead(LED_BUILTIN)));  // Se togglea el LED
    Serial.print("El contador de interrupciones es: ");
    Serial.println(cont);
}

