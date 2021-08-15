#include "MiConfig.h"

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
	wm.setWiFiAutoReconnect(true);		/* Para que se reconecte si se corta el wifi externamente */
	wm.setEnableConfigPortal(false);	/* Para que el autoconnect no inicie el AP si falla la conexion */
										/* Basicamente el AP solo se crearia de forma manual en el configWifi()*/
	
	/* Esta funcion si no se conecta automaticamente, crea un AP, excepto que se haya usado wm.setEnableConfigPortal(false); */
	if(wm.autoConnect(ssid, pass) && __DEBUGG){
		Serial.println("Conectada a la red: " + wm.getWiFiSSID(true));
	}
	else {
		if(__DEBUGG)
			Serial.println("Se inicio el portal de configuracion en modo AP");
		
		/* Se  configura la pagina del AP */
		wm.setTitle("RSA - IOT");
		wm.setDarkMode(true);
		wm.startConfigPortal(ssid, pass);
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

/**
 * Funcion que revisa si hay informacion de una red guardada y se intenta reconectar
*/
void reconnectWifi()
{	
	if(wm.getWiFiIsSaved())
		wm.autoConnect();	/* Se intenta conectar nuevamente al WiFi guardado si no hay internet */

	if(__DEBUGG)
		Serial.println("intentando reconectarse a: " + wm.getWiFiSSID(true));	
}

/**
 * Funcion que desconecta del wifi y elimina las credenciales si se presiono
 * el boton de desconexion.
*/
void checkDisconnect()
{
	/* Por unica vez se hace la desconexion del wifi y del broker y se inicia el modo AP */
	if(desconectarse)
	{
		// clientMQTT.disconnect();	
		WiFi.disconnect();
		wm.disconnect();
		/* Elimina las credenciales para que no se conecte a la misma red. 
		   Esto se hace SOLO en el caso que la desconexion sea voluntaria. */
		wm.resetSettings();	
		configWifi();
		desconectarse = false;
	}
}

