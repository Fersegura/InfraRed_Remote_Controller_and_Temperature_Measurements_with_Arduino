/*
    Archivo con variables y librerias globales al resto de dependencias del
    proyecto. Son solo las variables que estan acopladas en las funciones de los
    distintos modulos.
*/

#include <Arduino.h>
#include <WiFiManager.h>	// https://github.com/tzapu/WiFiManager
#include <PubSubClient.h>


/* === Variable para debugg === */
// #define __DEBUGG (bool) false
#ifndef	__DEBUGG
#define __DEBUGG (bool) true	/* Debugg activado  */
#endif

