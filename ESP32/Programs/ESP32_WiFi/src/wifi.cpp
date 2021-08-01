#include <Arduino.h>
#include <WiFi.h>
#include <EEPROM.h>


/* === Estructura de datos para guardar en la EEPROM ==== */ 
struct Condiciones
{
	String SSIDEEPROM;
	String PASSEEPROM;
	boolean Conectarse;
};

/* === Variables wifi === */
const char *ssid = "ESP32-WiFi";    // SSID de la red wifi que generara el dispositivo (cuando este en modo AP).
const char *pass = "0123456789";    // Contraseña de dicha red.
String redes[15]; 	                // Arreglo que guarda hasta 15 redes.
WiFiServer server(80);              // Servidor 
Condiciones conexion;				// Es la estructura que guarda todos los parámetros de conexión

/* ===  Funciones wifi === */
void seleccionarRedWifi(int);
void capturarDatosDeRed(String);
void conectarseAWifi();
void desconectarWifi();
void guardarRedes(int);

/* === Funciones EEPROM === */
void guardarEEPROM();
void recuperarEEPROM();
void grabar(int, String);
String leer(int);

void setup()
{
    /* === Seteo puerto serie === */
    
    Serial.begin(9600);
	pinMode(LED_BUILTIN, OUTPUT);

    /* === Configuración del WiFi === */

	WiFi.mode(WIFI_AP_STA);     // Se inicializa el modo WiFi para que funcione en modo AP y STA
    EEPROM.begin(512);			// Para poder usar la memoria EEPROM se inicia
	recuperarEEPROM(); 			// Apenas iniciamos buscamos si habia alguna red guardada por el caso que se corte la luz
	if(conexion.Conectarse){	// Si encontramos redes guardadas nos conectamos al WiFi
        conectarseAWifi();
	}

    /* === Configuración de red local del dispositivo (modo Access Point) y su servidor web === */
    
    Serial.println();
	Serial.print("Estableciendo configuración Soft-AP... ");
    IPAddress local_IP(192,168,4,22); 	// IPv4 privado a la que se tiene que conectar el usuario para conectar el dispositivo a su red WiFi
    IPAddress gateway(192,168,4,9);
    IPAddress subnet(255,255,255,0);
	Serial.println(WiFi.softAPConfig(local_IP, gateway, subnet) ? "Listo" : "Falló!");
	Serial.print("Configurando soft-AP ... \n");
	WiFi.softAP(ssid, pass);	//Arrancamos la generacion de wifi de red local del dispositivo que permite configurarlo   
    server.begin();				//Iniciamos el servidor
}

void loop()
{
    // Se guardan en un arreglo las redes WiFi
	int  cantidad_redes = WiFi.scanNetworks();
	guardarRedes(cantidad_redes);
	seleccionarRedWifi(cantidad_redes);

    /* === Ejecucion del algoritmo de sensado y comunicacion === */
    
    if(conexion.Conectarse)
	{
        /*---------------COMPLETAR---------------*/
		delay(500);
		digitalWrite(LED_BUILTIN, HIGH);
		delay(500);
		digitalWrite(LED_BUILTIN, LOW);
	}
    else{

    }
}

/* ============ Funciones WiFi ============ */

/**
 * Se almacenan los SSID de las redes encontradas.
 * @param cantidad_redes: cantidad de redes encontradas al hacer el escaneo.
*/
void guardarRedes(int cantidad_redes)
{
	for (int i = 0; i < cantidad_redes; i++)
	{
		redes[i]=WiFi.SSID(i);  
	}
}

/**
 * Esta funcion muestra en una pagina web servida por el dispositivo en la direccion IPv4 configurada en el setup().
 * En dicha pagina web, se muestran las redes disponibles en caso de que el dispositivo no se haya conectado a una red.
 * Si no, muestra un boton para desconectarse de la red.
 * @param cantidad_redes
*/
void  seleccionarRedWifi(int cantidad_redes)
{
    String datos;   // Datos de red
    String header;	// Header del request http
    unsigned long currentTime = millis(); 	// Tiempo actual.
    unsigned long previousTime = 0;       	// Tiempo anterior.
    const long timeoutTime = 2000;        	// Se define un timeout en milisegundos (example: 2000ms = 2s). 
	
    // Se crea un objeto cliente
	WiFiClient client = server.available();  
	
    if (client) 
	{
		// Si se conecta un cliente nuevo, se muestra un mensaje en el puerto serial
		Serial.println("New Client.");
		String currentLine = "";	// Se crea un String para guardar la informacion proviniente del cliente
		currentTime = millis();
		previousTime = currentTime;
		// Entramos a un loop mientras el cliente se mantenga conectado y mientras no se exceda el timeout
		while (client.connected() && currentTime - previousTime <= timeoutTime) 
		{ 
			currentTime = millis();
	        //  Si hay informacion del cliente, se lee
			if (client.available())  
			{
				char c = client.read(); 	// Se lee el byte que llego
				Serial.write(c);            // Se imprime el byte por el monitor serial
				datos +=c;                                           
				header += c;
				if (c == '\n') 
				{	
					// Si el caracter que llego es de 'newline' y la linea actual esta en blanco, entonces llegaron
					// dos caracteres 'newline' seguidos y eso significa el final del request del cliente HTTP, asi
					// que hay que enviar una respuesta:
					if (currentLine.length() == 0) 
					{
						// Los headers HTTP siempre empiezan con un codigo de respuesta (ej. HTTP/1.1 200 OK)
						// y luego un content-type para que el cliente sepa que esta por llegar, luego una linea en blanco:
						client.println("HTTP/1.1 200 OK");
						client.println("Content-type:text/html");
						client.println("Connection: close");
						client.println();
						
						
						// Se muestra la pagina web en HTML
						client.println("<!DOCTYPE html><html>");
						client.println("<head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">");
						client.println("<link rel=\"icon\" href=\"data:,\">");

						// Se aplica un poco de CSS a los botones y texto
						// Se pueden cambiar los colores, fuentes, tamaño, etc... segun conveniencia y preferencia
						client.println("<style>html { font-family: Helvetica; display: inline-block; margin: 0px auto; text-align: center;}");
						client.println(".button { background-color: #195B6A; border: none; color: white; padding: 16px 40px;");
						client.println("text-decoration: none; font-size: 30px; margin: 2px; cursor: pointer;}");
						client.println(".button2 {background-color: #77878A;}</style></head>");
						
						// Cuerpo de la pagina web
						if(!conexion.Conectarse){ //Si NO estamos conectados mostramos las redes disponibles y el lugar para poner el PASS
							client.println("<body><h1><b>Seleccione una red</b></h1>");
							client.print("<form><select name=\"ssidelegida\">");
							// Se listan todas las redes que se encontraron previamente
							for(int i=0;i<cantidad_redes;i++)
							{
								client.print("<option>"+redes[i]+"</option>");
							}
							// Campo para ingresar contraseña de la red que se seleccione en la web y boton para elegirla
							client.print(" <input type= \"password\" name=\"clavewifi\" placeholder=\"Clave wifi\" value=\"\">");
							client.print(" <input type=\"submit\" name=\"Formulario wifi\" value=\"conectar\">");
							client.print("</select></form>");
						}
						else{	//-->Si SI estamos conectados mostramos en que red y un boton para desconectarnos de esa red FALTA IMPLEMENTAR<--------
							// String nombre= conexion.SSIDEEPROM;
							String nombre = leer(0);
							client.print("<body><h1>Conectado a:</h1>" );
							client.print("</b><h1> "+ nombre +"</h1>");
							client.print("<form>");
							client.print(" <input type=\"submit\" name=\"botondesconectar\" value=\"Desconectar\">");
							client.print("</form>");

						}
						client.println("</body></html>");
						
						// La respuesta HTTP termina con otra linea en blanco
						client.println();
						// Cuando terminamos se sale del ciclo while
						break;
					} 
					else 
					{// Si llego un newline entonces limpiar la linea actual
						currentLine = "";
					}
				} 
				else if (c != '\r') 
				{// Si recibo cualquier cosa excepto un car return se adiciona a la linea actual
					currentLine += c;
				}
			}
		}
		// Se limpia la variable header
		header = "";
		// Se cierra la conexion
		client.stop();
		Serial.println("Client disconnected.");
		Serial.println("");
	}

	// Se procede a capturar la informacion de la red a la que se conecto el dispositivo
	capturarDatosDeRed(datos);
	// Se valida la informacion de la red a la cual se conecto.
	// Recordar que el protocola WAP2 establece SSID mayores a 4 caracteres y contraseñas de al menos 8 caracteres.
	// Si esta todo okay me conecto a la red elegida.
	if(conexion.SSIDEEPROM.length()>4 && conexion.PASSEEPROM.length()>6)     //----------------------->Ver de modificar la forma de obtener los valores<-----------------
	{
		Serial.print("La red wifi es: ");
		Serial.println(conexion.SSIDEEPROM);
		Serial.print("La clave es: ");
		Serial.println(conexion.PASSEEPROM);
		conectarseAWifi();
	}

}

/**
 * Se parsea el mensaje proveniente del cliente para encontrar el SSID y la contraseña de la red seleccionada.
 * Tambien se utiliza la informacion recibida para saber si el usuario se desea desconectar de la red.
 * @param datos: datos provenientes del formulario de la pagina web.
*/
void capturarDatosDeRed(String datos)   
{	
	int desco = datos.indexOf("Desconectar");  //buscamos si es que apretamos el boton de desconectar
	if(desco >5){
		desconectarWifi();		//en caso afirmativo desconectamos el disposivito del wifi
	}					  
	int primercoincidencia = datos.indexOf("=");
	int segundacoincidencia = datos.indexOf("&",primercoincidencia+1);
	int tercera = datos.indexOf("=",segundacoincidencia+1);
	int cuarta = datos.indexOf("&",tercera+1);
	
	if(primercoincidencia<20)
	{
		// Una vez encontrada la informacion se la almacena en la EEPROM
		conexion.SSIDEEPROM= datos.substring(primercoincidencia+1,segundacoincidencia);
		conexion.PASSEEPROM= datos.substring(tercera+1,cuarta);
		// Se reemplazan los simbolos '+' por espacios vacios (' ') para tener el nombre de la red y contraseña en forma correcta
		while(conexion.SSIDEEPROM.indexOf("+")>=1)
		{
			int a= conexion.SSIDEEPROM.indexOf("+");
			conexion.SSIDEEPROM.setCharAt(a, ' ');
		}
		while(conexion.PASSEEPROM.indexOf("+")>=1)
		{
			int a= conexion.PASSEEPROM.indexOf("+");
			conexion.PASSEEPROM.setCharAt(a, ' ');
		}
	}

	return;
}

/**
 * Nos intentamos conectar a un red Wifi, si se puede conectar dejamos la bandera de estar conectados, si no se puede conectar
 * borra los datos guardados en esa variable conexion.
*/
void conectarseAWifi()
{	
	int intentos=0;   									  //variable para que no quede en un bucle infinito
    
    const char *ssid_eeprom = conexion.SSIDEEPROM.c_str();  /* Se castean a const char* para la funcion WiFi.begin() */
    const char *pw_eeprom = conexion.PASSEEPROM.c_str();
	
    WiFi.begin(ssid_eeprom,pw_eeprom); // Se intenta conectar a la red WiFi que eligio el usuario en la pagina web
	while (WiFi.status() != WL_CONNECTED  && intentos<120 ) //intenta conectarse durante 25 seg y parpadea mientras tanto bien rápido a modo indicativo.
	{ 
		digitalWrite(BUILTIN_LED,HIGH);
		Serial.print(".");
		delay(250); 
		intentos++;
		digitalWrite(BUILTIN_LED,LOW);
		delay(250) ;       
	}
	
	if(WiFi.status() == WL_CONNECTED){                    
		// Se imprime mensaje de conexion e informacion sobre la conexion
        Serial.println();
        Serial.print("Connected to ");
        Serial.println(conexion.SSIDEEPROM);
        Serial.print("IP Address is : ");
        Serial.println(WiFi.localIP());
        // Se setea el flag 
        conexion.Conectarse = true;
        // Importantisimo este delay para que funcione todo correctamente
        delay(30);
        // Se inicializa el sensor
        /**
         * 
         * 
         * 
         * 
         * 
         * 
         *              DESCOMENTAR EL DHT CUANDO LO TENGAMOS PARA PROBARLO
         * 
         * 
         * 
         * 
         * 
         */
        // dht.begin();
	}
	else	
	{
        // Si no se conecta lo mostramos por serial y borramos todos los datos de la EEPROM esperando una nueva conexion
		boolean corroborar=false;
        int cantidad_redes = WiFi.scanNetworks();
		for(int i=0; i<cantidad_redes; i++){
			if(conexion.SSIDEEPROM.equals(redes[i])){
				corroborar= true;
			}
		}
		if(corroborar){
			Serial.println("No se pudo conectar a la red. Intente con otra clave");
		}
		else{
			Serial.println("No se encontro esa red wifi");
		}
		
		conexion.Conectarse=false;
		conexion.PASSEEPROM="";
		conexion.SSIDEEPROM="";
	}
    
    /* Se graban en memoria los cambios realizados */
	guardarEEPROM();

	return;
}

/**
 * Desconectamos el dispositivo y limpiamos la variable conexion.
*/
void desconectarWifi()
{
	WiFi.disconnect();				
	conexion.Conectarse=false;
	conexion.PASSEEPROM="";
	conexion.SSIDEEPROM="";
}

/* ============ Funciones de EEPROM ============ */

/**
 *  Se recuperan en la estructura global los valores de SSID y PW guardados
*/
void recuperarEEPROM()	
{	
	conexion.SSIDEEPROM=leer(0);
	conexion.PASSEEPROM=leer(50);
	conexion.Conectarse=EEPROM.read(100);
}

/**
 *  Se graba en los primeros 50 Bytes la SSID y en los siguientes 50 la PASS y el booleano de conectarse en la 100
 *  
*/
void guardarEEPROM()	
{
	grabar(0,conexion.SSIDEEPROM);
	grabar(50,conexion.PASSEEPROM);
	EEPROM.put(100,conexion.Conectarse);
	EEPROM.commit();		//Esta linea es la que confirma que se quede guardado en la EEPROM los datos
}

/**
 * Funcion auxiliar para convertir el String en un Char Array y guardarlo, si el dato ocupa menos de 50 valores
 * rellena con 255 (valor que se detecta para saber si es o no un caracter valido).
 * @param addr: Direccion del primer byte del dato a guardar.
 * @param a: Dato a guardar.
*/
void grabar(int addr, String a) 
{  
	int tamano = a.length(); 
	char inchar[50]; 
	a.toCharArray(inchar, tamano+1);
	for (int i = 0; i < tamano; i++) {
		EEPROM.write(addr+i, inchar[i]);
	}
	for (int i = tamano; i < 50; i++) {
		EEPROM.write(addr+i, 255);
	}
	EEPROM.commit();
}

/**
 * Funcion auxiliar para recuperar los valores guardados y obtenerlos como String.
 * @param addr: Direccion del primer byte del dato a leer.
*/
String leer(int addr) 
{   
	byte lectura;
	String strlectura;

	for (int i = addr; i < addr+50; i++) 
	{
		lectura = EEPROM.read(i);
		if (lectura != 255) 
		{
			strlectura += (char)lectura;
		}
	}
	return strlectura;
}

