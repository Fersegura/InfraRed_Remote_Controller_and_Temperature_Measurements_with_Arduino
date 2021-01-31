#include <Arduino.h>
// Librerias necesarias.
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"  
#include <EEPROM.h>      


#define DHTTYPE DHT11   // Se crea un objeto DHT11.
#define dht_dpin 0      // Pin al cual estara conectado el sensor.

DHT dht(dht_dpin, DHTTYPE); // Inicializacion del sensor.

// Ingresar el URL del host propio.
#define HOST ""          // Ingresar el URL del host sin "http:// "  y "/" al final del URL.
//Estructura de datos para guardar en la EEPROM
struct Condiciones
{
	String SSIDEEPROM;
	String PASSEEPROM;
	boolean Conectarse;
};

// Variables globales:
String temp, hum, postData;
IPAddress local_IP(192,168,4,22);
IPAddress gateway(192,168,4,9);
IPAddress subnet(255,255,255,0);
String ssid = "peinito";          		// SSID de la red wifi que generara el dispositivo (cuando este en modo AP).
String pass = "0123456789";       		// Contraseña de dicha red.
String header;	
String redes[15];                 		// Arreglo que guarda hasta 15 redes.
int cantidadredes = 0;			  		// Cantidad de redes encontradas.
String datos;					  		// Para almacenar la informacion devuelta por el cliente 

unsigned long currentTime = millis(); 	// Tiempo actual.
unsigned long previousTime = 0;       	// Tiempo anterior.
const long timeoutTime = 2000;        	// Se define un timeout en milisegundos (example: 2000ms = 2s).
WiFiServer server(80);
Condiciones conexion;					//es la estructura que guarda todos los parámetros de conexción
Condiciones actuales; //la use para verificar cosas
// Prototipos de funciones:
void seleccionarRedWifi();
void capturarDatosDeRed();
void conectarseAWifi();
void transmitirDatos();
void guardarRedes(int );
void guardarEEPROM();
void recuperarEEPROM();
void grabar(int , String );
String leer(int );
// Codigo:

void setup() 
{
	pinMode(LED_BUILTIN,OUTPUT);
	EEPROM.begin(512);							//Para poder usar la memoria EEPROM se inicia (valor maximo es de 4096 Bytes)
	recuperarEEPROM(); 							//Apenas iniciamos buscamos si habia alguna red guardada por el caso que se corte la luz
	Serial.begin(115200);						//Se inicializa el monitor serial con un baudaje de 115200
	Serial.println();
	WiFi.mode(WIFI_AP_STA);  					//Se inicializa el modo WiFi para que funcione en modo AP y STA 
	Serial.print("Estableciendo configuración Soft-AP... ");
	Serial.println(WiFi.softAPConfig(local_IP, gateway, subnet) ? "Listo" : "Falló!");
	Serial.print("Setting soft-AP ... ");
	WiFi.softAP(ssid , pass);					//Arrancamos la generacion de wifi de red local del dispositivo que permite configurarlo
	server.begin();								//Iniciamos el servidor
	if(conexion.Conectarse)	conectarseAWifi();	//Si encontramos redes guardadas nos conectamos.
}


void loop() 
{
	// Se guardan en un arreglo las redes WiFi
	cantidadredes =  WiFi.scanNetworks();
	guardarRedes(cantidadredes);
	seleccionarRedWifi();
	// Si estoy conectado a una red WiFi con acceso a internet transmito la informacion
	if(conexion.Conectarse)
	{
		transmitirDatos();
	}
}


void guardarRedes(int networksFound)
{
	for (int i = 0; i < networksFound; i++)
	{
		redes[i]=WiFi.SSID(i);  // Se almacenan los SSID de las redes encontradas.
	}
}


void seleccionarRedWifi()
{
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
						if(!conexion.Conectarse){               //Si NO estamos conectados mostramos las redes disponibles y el lugar para poner el PASS
							client.println("<body><h1><b>Alarmas rispiro</b></h1>");
							client.print("<form><select name=\"ssidelegida\">");
							// Se listan todas las redes que se encontraron previamente
							for(int i=0;i<cantidadredes;i++)
							{
								client.print("<option>"+redes[i]+"</option>");
							}
							// Campo para ingresar contraseña de la red que se seleccione en la web y boton para elegirla
							client.print(" <input type= \"password\" name=\"clavewifi\" placeholder=\"Clave wifi\" value=\"\">");
							client.print(" <input type=\"submit\" name=\"Formulario wifi\" value=\"conectar\">");
							client.print("</select></form>");
						}
						else{	//---------------------->Si SI estamos conectados mostramos en que red y un boton para desconectarnos de esa red FALTA IMPLEMENTAR<--------
							client.print("<body><h1>Conectado a:</h1>"+conexion.SSIDEEPROM);
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
	delay(5000);

	// Se procede a capturar la informacion de la red a la que se conecto el dispositivo
	capturarDatosDeRed();
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
	
	// Limpio los datos provenientes del cliente
	datos="";
}


void capturarDatosDeRed()   //VER SI SE MODIFICA PARA OBTENER LOS DATOS
{
	// Se parsea el mensaje proveniente del cliente para encontrar el SSID y la contraseña de la red seleccionada.
	// Esto se hace por dos motivos: 1) Disponer de esta informacion por si se desconecta por alguna razon para reconectarse
	// 								  
	int primercoincidencia = datos.indexOf("=");
	int segundacoincidencia = datos.indexOf("&",primercoincidencia+1);
	int tercera = datos.indexOf("=",segundacoincidencia+1);
	int cuarta = datos.indexOf("&",tercera+1);

	if(primercoincidencia<20)
	{
		// Una vez encontrada la informacion se la almacena en variables globales (EN UN FUTURO PUEDE GUARDARSE EN EEPROM)
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


void conectarseAWifi()
{
	int intentos=0;   									  //variable para que no quede en un bucle infinito
	WiFi.begin(conexion.SSIDEEPROM, conexion.PASSEEPROM); // Se intenta conectar a la red WiFi que eligio el usuario en la pagina web
	Serial.print("Connecting to ");
	Serial.print(conexion.SSIDEEPROM);// esta y la de abajo tambien eran para debugear
	// Se espera hasta que se conecte
	while (WiFi.status() != WL_CONNECTED  && intentos<50 ) //intenta conectarse durante 25 seg y parpadea mientras tanto bien rápido a modo indicativo.
	{ 
		digitalWrite(LED_BUILTIN,HIGH);
		Serial.print(".");
		delay(250); 
		intentos++;
		digitalWrite(LED_BUILTIN,LOW);
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
	dht.begin();
	}
	else	//Si no se conecta lo mostramos por serial y borramos todos los datos de la EEPROM esperando una nueva conexion
	{
		Serial.println("No se pudo conectar a la red. Intente nuevamente");
		conexion.Conectarse=false;
		conexion.PASSEEPROM="";
		conexion.SSIDEEPROM="";
	}
	guardarEEPROM();

	return;
}

void guardarEEPROM()	//Grabamos en los primeros 50 Bytes la SSID y en los siguientes 50 la PASS y el booleano de conectarse en la 100
{
	grabar(0,conexion.SSIDEEPROM);
	grabar(50,conexion.PASSEEPROM);
	EEPROM.put(100,conexion.Conectarse);
	EEPROM.commit();		//Esta linea es la que confirma que se quede guardado en la EEPROM los datos

}

void recuperarEEPROM()	//Obtenemos los valores guardados;
{	
	conexion.SSIDEEPROM=leer(0);
	conexion.PASSEEPROM=leer(50);
	conexion.Conectarse=EEPROM.read(100);
	
}

void transmitirDatos()
{
	// Se lee la humedad y temperatura del DHT11
	float h = dht.readHumidity();
	float t = dht.readTemperature();         
	
	// Se crea un objeto http de la clase HTTPClient
	HTTPClient http;    

	// Se castean los valores censados a String (para enviarlos a la base de datos)
	temp = String(t);  
	hum = String(h);   

	// Se empieza a construir el url a enviar
	postData = "temp=" + temp + "&hum=" + hum;

	// Se pueden postear valores al archivo PHP como: example.com/dbwrite.php?name1=val1&name2=val2&name3=val3
	// Para mayor informacion visitar:- https://www.tutorialspoint.com/php/php_get_post.htm

	// Cambiar por el url del host propio con la ubicacion del PHP que recibira los datos  
	
	http.begin("http://irresponsible-toolb.000webhostapp.com/dbwrite.php");	// Se conecta al host donde esta la base de datos MySQL
	http.addHeader("Content-Type", "application/x-www-form-urlencoded");	// Se especifica el content-type del header
	
	// Se envia un request del tipo POST al archivo PHP y se guarda la respuesta del servidor en la variable httpCode
	int httpCode = http.POST(postData);   
	
	// Linea para debuggear, para ver que estoy mandando
	// Serial.println("Los valores son, temperatura = " + temp + " y humedad = "+hum );

	// Si se establecio la conexion correctamente entonces se imprimen algunos mensajes
	if (httpCode == 200) 
	{ 
		Serial.println("Valores subidos correctamente."); 
		Serial.println(httpCode); 
		// Se obtiene el output de la pagina web y se lo imprime
		String webpage = http.getString();
		Serial.println(webpage + "\n"); 
	}

	// Si fallo la conexion entonces cierro, retorno y comienzo nuevamente
	else 
	{ 
		Serial.println(httpCode); 
		Serial.println("Fallo al subir los valores. \n"); 
		http.end(); 
		return; 
	}

	// Se espera algunos segundos antes de transmitir datos nuevamente 
	delay(3000); 
	digitalWrite(LED_BUILTIN, LOW);
	delay(3000);
	digitalWrite(LED_BUILTIN, HIGH);

	return;
}

void grabar(int addr, String a) {  //Funcion auxiliar para convertir el String en un Char Array y guardarlo, si el dato ocupa menos de 50 valores rellena con 255.
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


String leer(int addr) {   //Funcion auxiliar para recuperar los valores guardados y obtenerlos como String
   byte lectura;
   String strlectura;
   for (int i = addr; i < addr+50; i++) {
      lectura = EEPROM.read(i);
      if (lectura != 255) {
        strlectura += (char)lectura;
      }
   }
   return strlectura;
}
