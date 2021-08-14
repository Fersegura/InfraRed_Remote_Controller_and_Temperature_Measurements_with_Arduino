#include "MiConfig.h"
#include <WiFiManager.h>	// https://github.com/tzapu/WiFiManager

/* === Variables wifi === */
#define WIFI_DISCONNECT_GPIO (uint8_t) 13	/* Pin del boton de desconexion de WiFi */
const char *ssid = "ESP32-WiFi";    // SSID de la red wifi que generara el dispositivo (cuando este en modo AP).
const char *pass = "123456789";    	// Contraseña de dicha red.
WiFiManager wm;						/* Objeto de la clase WiFiManager */
bool desconectarse = false;			// Bandera que indica si se pulso el boton de desconexion.

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
	if(WiFi.isConnected() || !desconectarse)
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


