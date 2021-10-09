#include <Arduino.h>
// Librerias necesarias.
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

// D1 (GPIO5): Rele1
// D2 (GPIO4): Rele2
// D5 (GPIO14): Rele3
// D6 (GPIO12): Rele4

// D4 (GPIO2): PullUp (boton para cambiar manualmente los estados de reles)
// D8 (GPIO15): PullDown (boton para cambiar manualmente los estados de reles)
// D3 (GPIO0): PullUp (boton para cambiar manualmente los estados de reles)

// Variables globales y definicion de pines y reles.
double millisactuales,anteriores;
#define rele1 5
#define rele2 4
#define rele3 14
#define rele4 12
#define boton1 2
#define boton2 15
#define boton3 0
boolean rel1=false;
boolean rel2=false;
boolean rel3=false;
boolean rel4=false;

// Es necesario que sea de tipo "ICACHE_RAM_ATTR" la interrupcion para que funcione correctamente
IRAM_ATTR void inter(); 

void setup() 
{
	Serial.begin(115200);
    // Para cancelar rebote de la interr.
    anteriores=millis();
    // Se inicializan las entradas y salidas, el monitor serial y se activan las interrupciones de los 3 pines.
    pinMode(boton1,INPUT);
    pinMode(boton2,INPUT);
    pinMode(boton3,INPUT);
    pinMode(rele1, OUTPUT);
    pinMode(rele2,OUTPUT);
    pinMode(rele3,OUTPUT);
    pinMode(rele4,OUTPUT);
    Serial.begin(115200);
    attachInterrupt(boton1,inter,RISING);
    attachInterrupt(boton2,inter,RISING);
    attachInterrupt(boton3,inter,RISING);
}
 
void loop() {
	// No se hace nada en el loop, todo se maneja por interrupciones
}

void inter()
{


    millisactuales=millis();    // Tiempo actual  

    // Se hace esto para cancelar el rebote de los botones. Basicamente se puede entrar en esta interrupcion cada 250 [ms] o mas (se supone que el rebote desaparece más rápido que 250ms)
    if(millisactuales-anteriores>500)   
    {
        	Serial.println();
	Serial.print("El boton 1 esta: ");
	Serial.println(String(digitalRead(boton1)));
	Serial.print("El boton 2 esta: ");
	Serial.println(String(digitalRead(boton2)));
	Serial.print("El boton 3 esta: ");
	Serial.println(String(digitalRead(boton3)));
	Serial.println("----------------------------");
	    
	    anteriores=millisactuales;  // Se actualiza el tiempo anterior como el actual para una futura interrupcion 
    
        // Se checkea que boton causó entrar a la interrupción y se activa el relé asociado
        if(digitalRead(boton2)) 
        {
            rel3=!rel3;
            digitalWrite(rele3,rel3);
        
        }
        else
        {
            if(digitalRead(boton1) && digitalRead(boton3))    // Si se presiono el boton multiplexado se cambia el estado de este rele
            {   
                rel4=!rel4;
                digitalWrite(rele4,rel4);
            }
            else
            {
                if(digitalRead(boton1))
                {
                    rel1=!rel1;
                    digitalWrite(rele1,rel1);
                }
                if(digitalRead(boton3))
                {
                    rel2=!rel2;
                    digitalWrite(rele2,rel2);
                }
            }
        }
    }
}
