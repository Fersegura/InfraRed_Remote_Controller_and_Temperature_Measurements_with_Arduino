/*
    Ejemplo interrupcion con timer1
    Autor: Santiago Raimondi
    Para mas informacion ir a: https://github.com/esp8266/Arduino/blob/eea9999dc5eaf464a432f77d5b65269f9baf198d/cores/esp8266/Arduino.h
*/
#include <Arduino.h>
// Librerias necesarias.
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"  
#include <EEPROM.h>      

// Puertos del nodeMCU ESP8266-E:
// D1 (GPIO5): Rele1
// D2 (GPIO4): Rele2
// D5 (GPIO14): Rele3
// D6 (GPIO12): Rele4
// D4 (GPIO2): PullUp (boton para cambiar manualmente los estados de reles)
// D8 (GPIO15): PullDown (boton para cambiar manualmente los estados de reles)
// D3 (GPIO0): PullUp (boton para cambiar manualmente los estados de reles)
// D7 (GPIO13): DHT11
// D0 (GPIO16) desocupado por el momento (no anda con el DHT11). En teoria es el segundo LED de la placa (???)

#define DHTTYPE DHT11   // Asocia un define a la clase DHT11 para mayor legibilidad
#define dht_dpin 13     // Pin al cual estara conectado el sensor.
#define rele1 5			// Pines a los cuales estaran conectados los actuadores
#define rele2 4
#define rele3 14
#define rele4 12
#define boton1 2		// Pines para botones para cambiar manualmente estado de actuadores
#define boton2 15
#define boton3 0
#define SEGUNDO_LED 16


DHT dht(dht_dpin, DHTTYPE); // Inicializacion del sensor.


//Estructura de datos para guardar en la EEPROM
struct Condiciones
{
	String SSIDEEPROM;
	String PASSEEPROM;
	boolean Conectarse;
};

// Variables globales:
float tempe[20], hume[20]; 
String temp, hum, postData;
IPAddress local_IP(192,168,4,22);		// IP a la que se tiene que conectar el usuario para conectar el dispositivo a su red WiFi
IPAddress gateway(192,168,4,9);
IPAddress subnet(255,255,255,0);
String ssid = "peinito";          		// SSID de la red wifi que generara el dispositivo (cuando este en modo AP).
String pass = "0123456789";       		// Contraseña de dicha red.
String header;	
String redes[15];                 		// Arreglo que guarda hasta 15 redes.
int cantidadredes = 0;			  		// Cantidad de redes encontradas.
String datos;					  		// Para almacenar la informacion devuelta por el cliente. 
const int ID_SERIAL=99999;				// N° Serial del dispositivo.
unsigned long currentTime = millis(); 	// Tiempo actual.
unsigned long previousTime = 0;       	// Tiempo anterior.
const long timeoutTime = 2000;        	// Se define un timeout en milisegundos (example: 2000ms = 2s).
WiFiServer server(80);
Condiciones conexion;					//es la estructura que guarda todos los parámetros de conexción
int receivedNum1,receivedNum2,receivedNum3,receivedNum4,receivedNum5;	// Variables recibidas desde la pag. web
String text_1;
double millisactuales,anteriores;		// Para cancelar el rebote de los botones en el ISR
boolean rel1=false;						// Almacena estado actual de los reles
boolean rel2=false;
boolean rel3=false;
boolean rel4=false;
boolean flag_actualizar;
boolean flag_enviarDatos;

// Prototipos de funciones:
void configPines();
void configInterrupciones(); 
void seleccionarRedWifi();
void capturarDatosDeRed();
void conectarseAWifi();
void transmitirDatos();
void guardarRedes(int );
void guardarEEPROM();
void recuperarEEPROM();
void grabar(int , String );
String leer(int );
void buscardatos();
void analizardatos(String);
void desconectarWifi();
void actualizarDatos();
// Es necesario que sea de tipo "ICACHE_RAM_ATTR" la interrupcion para que funcione correctamente
ICACHE_RAM_ATTR void ISRbotones();
ICACHE_RAM_ATTR void ISRtimer1();


// Codigo:
void setup() 
{	
	Serial.begin(115200);						//Se inicializa el monitor serial con un baudaje de 115200
		
	// ------------ Conexion del WiFi y generacion de red propia o conexion a red guardada. -----------------------

	WiFi.mode(WIFI_AP_STA);  					//Se inicializa el modo WiFi para que funcione en modo AP y STA 
	
	EEPROM.begin(512);							//Para poder usar la memoria EEPROM se inicia (valor maximo es de 4096 Bytes)
	recuperarEEPROM(); 							//Apenas iniciamos buscamos si habia alguna red guardada por el caso que se corte la luz
	if(conexion.Conectarse)	conectarseAWifi();	//Si encontramos redes guardadas nos conectamos.
	
	Serial.println();
	Serial.print("Estableciendo configuración Soft-AP... ");
	Serial.println(WiFi.softAPConfig(local_IP, gateway, subnet) ? "Listo" : "Falló!");
	Serial.print("Configurando soft-AP ... \n");
	
	WiFi.softAP(ssid , pass);					//Arrancamos la generacion de wifi de red local del dispositivo que permite configurarlo
	server.begin();								//Iniciamos el servidor

	// ------------ Resto de setup necesario para el funcionamiento del dispositivo. ------------------------------
	
    configPines();			// Se inicializan los pines
    configInterrupciones(); // Se setean las interrupciones

}

void loop() 
{
	// Se guardan en un arreglo las redes WiFi
	cantidadredes =  WiFi.scanNetworks();
	guardarRedes(cantidadredes);
	seleccionarRedWifi();

	if(conexion.Conectarse)
		{
			if(flag_enviarDatos){
				flag_enviarDatos=false;
				transmitirDatos();
			}
			
			if(flag_actualizar){
				flag_actualizar=false;
				actualizarDatos();
				delay(1500);
			}
			else{
				buscardatos();
			}
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
							String nombre= conexion.SSIDEEPROM;
							Serial.print("AHORA ES CUANDO SALE EL NOMBRE:");
							Serial.println(nombre);
							Serial.println(conexion.SSIDEEPROM);
							client.print("<body><h1>Conectado a:</h1>" );
							client.print("</b><h1> "+ nombre+"</h1>");
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
	while (WiFi.status() != WL_CONNECTED  && intentos<120 ) //intenta conectarse durante 25 seg y parpadea mientras tanto bien rápido a modo indicativo.
	{ 
		digitalWrite(SEGUNDO_LED,HIGH);
		Serial.print(".");
		delay(250); 
		intentos++;
		digitalWrite(SEGUNDO_LED,LOW);
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

void guardarEEPROM()	
{
	//Grabamos en los primeros 50 Bytes la SSID y en los siguientes 50 la PASS y el booleano de conectarse en la 100
	grabar(0,conexion.SSIDEEPROM);
	grabar(50,conexion.PASSEEPROM);
	EEPROM.put(100,conexion.Conectarse);
	EEPROM.commit();		//Esta linea es la que confirma que se quede guardado en la EEPROM los datos

}

void recuperarEEPROM()	
{	
	//Obtenemos los valores guardados
	conexion.SSIDEEPROM=leer(0);
	conexion.PASSEEPROM=leer(50);
	conexion.Conectarse=EEPROM.read(100);
	
}

void transmitirDatos() 
{
	// Se lee la humedad y temperatura del DHT11
	float h = dht.readHumidity();
	float t = dht.readTemperature();

	static int index=0;	// Al ser una variable static, no se borra su valor al salir de la funcion
	
	// Para debuggear
	// Serial.print("el valor del index es =");
	// Serial.println(index);

	tempe[index]=t;
	hume[index]=h;
	
	// Si ya tome 20 valores, reinicio el indice y promedio y envio los datos sensados
	if(index==19)	
	{
		index=0;

		float tempsend=0;
		float humsend=0;
		
		for(int i=0; i<20;i++)
		{
			tempsend+=tempe[i];
			humsend+=hume[i];
		}

		tempsend=tempsend/20;
		humsend=humsend/20;

		// Se crea un objeto http de la clase HTTPClient
		HTTPClient http;    

		// Se castean los valores censados a String (para enviarlos a la base de datos)
		temp = String(tempsend);  
		hum = String(humsend);
		String id_serial = String(ID_SERIAL);  

		// Se empieza a construir el url a enviar
		postData = "temp=" + temp + "&hum=" + hum;

		// Se pueden postear valores al archivo PHP como: example.com/dbwrite.php?name1=val1&name2=val2&name3=val3
		// Para mayor informacion visitar:- https://www.tutorialspoint.com/php/php_get_post.htm

		// Cambiar por el url del host propio con la ubicacion del PHP que recibira los datos  
		
		http.begin("http://irresponsible-toolb.000webhostapp.com/my_php/dbwrite.php");	// Se conecta al host donde esta la base de datos MySQL
		http.addHeader("Content-Type", "application/x-www-form-urlencoded");	// Se especifica el content-type del header
		
		// Se envia un request del tipo POST al archivo PHP y se guarda la respuesta del servidor en la variable httpCode
		int httpCode = http.POST(postData);   

		// Si fallo la conexion entonces cierro, retorno y comienzo nuevamente
		if (httpCode != 200) 
		{ 
			Serial.println(httpCode); 
			Serial.println("Fallo al subir los valores. \n"); 
			http.end(); 
			return; 
		}
		
	}
	else	// Si no tome 20 valores todavia, incremento el indice
		index++;

	return;
}

void grabar(int addr, String a) 
{  //Funcion auxiliar para convertir el String en un Char Array y guardarlo, si el dato ocupa menos de 50 valores rellena con 255.
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

String leer(int addr) 
{   //Funcion auxiliar para recuperar los valores guardados y obtenerlos como String
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

void buscardatos()
{
	HTTPClient http;    // Objeto HTTP de la clase HTTPClient

	// Se convierte el serial del dispositivo a una variable String para generar el mensaje que se enviara con POST
	String id_serial = String(ID_SERIAL);  
	postData = "id_serial=" + id_serial;

	// Modificar con el URL del host propio:
	http.begin("http://irresponsible-toolb.000webhostapp.com/my_php/dbread.php");	// Se conecta al host donde esta la base MySQL
	http.addHeader("Content-Type", "application/x-www-form-urlencoded");            //Se especifica el contentHeader

	int httpCode = http.POST(postData);   // Se envia un mensaje POST al archivo PHP y se guarda la respuesta en httpCode
	// Si la conexion fue exitosa (codigo 200) entonces se analizan los datos recibidos
	if (httpCode == 200) 
	{ 
		// LINEAS PARA DEBUGG
		// Serial.println("Se busacaron los datos correctamente."); 

		String webpage = http.getString();    // Se obtiene la respuesta de la pagina web (el mensaje que imprime el PHP es)
		// LINEAS PARA DEBUGG
		// Serial.println(webpage + "\n");
		
		analizardatos(webpage); 
	}
	else 
	{ 
		// Si no se conecto se vuelve 
		// LINEAS PARA DEBUGG
		Serial.println(httpCode); 
		Serial.println("Fallo en buscardatos. \n"); 

		http.end(); 
		return; 
	}
}

void analizardatos(String aux)
{    //toma los datos que mandamos a pedir al server y define el estado de los reles y almacena los enteros y el string.


	//Definimos donde vamos a encontrar los limites de cada dato
	int primer 	= aux.indexOf(":");
	int segunda = aux.indexOf(":", primer+1);
	int tercer 	= aux.indexOf(":", segunda+1);
	int cuarta 	= aux.indexOf(":", tercer+1);
	int quinta 	= aux.indexOf(":", cuarta+1);
	int num1A   = aux.indexOf(":", quinta+1);
	int num1B 	= aux.indexOf(",", num1A);
	int num2A   = aux.indexOf(":", num1B+1);
	int num2B 	= aux.indexOf(",", num2A);
	int num3A   = aux.indexOf(":", num2B+1);
	int num3B 	= aux.indexOf(",", num3A);
	int num4A   = aux.indexOf(":", num3B+1);
	int num4B 	= aux.indexOf(",", num4A);
	int num5A   = aux.indexOf(":", num4B+1);
	int num5B 	= aux.indexOf(",", num5A);
	int str 	= aux.indexOf(":",num5B+1); 

	//Llevamos a cabo la asignacion de los valores a cada lugar correspondiente
	int r= aux.substring(primer+1,primer+2).toInt();
	if(r==1)
	{
		digitalWrite(rele1,HIGH);
		Serial.println("el estado del rele1=1");
	}
	else
	{
		digitalWrite(rele1,LOW);
		Serial.println("el estado del rele1=0");
	}
	r=aux.substring(segunda+1,segunda+2).toInt();
	if(r==1)
	{
		digitalWrite(rele2,HIGH);
		Serial.println("el estado del rele2=1");
	}
	else
	{
		digitalWrite(rele2,LOW);
		Serial.println("el estado del rele2=0");
	}
	r=aux.substring(tercer+1,tercer+2).toInt();
	if(r==1)
	{
		digitalWrite(rele3,HIGH);
		Serial.println("el estado del rele3=1");
	}
	else
	{
		digitalWrite(rele3,LOW);
		Serial.println("el estado del 3=0");
	}
	r=aux.substring(cuarta+1,cuarta+2).toInt();
	if(r==1)
	{
		digitalWrite(rele4,HIGH);
		Serial.println("el estado del rele4=1\n");
	}
	else
	{
		digitalWrite(rele4,LOW);
		Serial.println("el estado del rele4=0\n");
	}
	// NO HAY 5 RELES (A PESAR QUE SI HAY 5 BOTONES EN LA PAG WEB)
	// r=aux.substring(quinta+1,quinta+2).toInt();
	// if(r==1)
	// {
	// 	digitalWrite(rele5,HIGH);
	// 	Serial.println("el estado del 5=1");
	// }
	// else
	// {
	// 	digitalWrite(rele5,LOW);
	// 	Serial.println("el estado del 5=0");
	// }
	receivedNum1=aux.substring(num1A+1,num1B-1).toInt();
	receivedNum2=aux.substring(num2A+1,num2B-1).toInt();
	receivedNum3=aux.substring(num3A+1,num3B-1).toInt();
	receivedNum4=aux.substring(num4A+1,num4B-1).toInt();
	receivedNum5=aux.substring(num5A+1,num5B-1).toInt();
	text_1=aux.substring(str);

	//Parte del testeo, revisar que estamos recibiendo, veo que recibimos 2 veces el NUM4, dejar esto hasta corregir lo otro.
	// Serial.println(receivedNum1);
	// Serial.println(receivedNum2);
	// Serial.println(receivedNum3);
	// Serial.println(receivedNum4);
	// Serial.println(receivedNum5);
	// Serial.println(text_1);
	// Serial.println()
}

void configPines()
{   //Inicializamos los pines para el uso que van a tener
	pinMode(SEGUNDO_LED,OUTPUT);
	pinMode(rele1,OUTPUT);
	pinMode(rele2,OUTPUT);
	pinMode(rele3,OUTPUT);
	pinMode(rele4,OUTPUT);
	// NO HAY 5 RELES
	// pinMode(rele5,OUTPUT);
	pinMode(boton1,INPUT);
	pinMode(boton2,INPUT);
	pinMode(boton3,INPUT);

}

void configInterrupciones()
{
    noInterrupts(); // Desactivo interrupciones hasta que termino de configurar

    anteriores = millis();	// Se toma nota del tiempo actual para la interrupcion por botones
	attachInterrupt(digitalPinToInterrupt(boton1),ISRbotones,RISING);
    attachInterrupt(digitalPinToInterrupt(boton2),ISRbotones,RISING);
    attachInterrupt(digitalPinToInterrupt(boton3),ISRbotones,RISING);

    //Inicializa el timer1 para que genere interrupciones
    timer1_enable(TIM_DIV256, TIM_EDGE, TIM_LOOP); 
    timer1_write(1562500); // En teoria son 5 seg con TIM_DIV256. El máximo valor que se puede escribir es: 8388607
    timer1_attachInterrupt(ISRtimer1);  // Defino la función que tiene que ejecutar cuando haga overflow

    interrupts();   // Activo nuevamente interrupciones
}

ICACHE_RAM_ATTR void ISRbotones()
{
    millisactuales=millis();    // Tiempo actual  
	
    // Se hace esto para cancelar el rebote de los botones. Basicamente se puede entrar en esta interrupcion cada 250 [ms] o mas (se supone que el rebote desaparece más rápido que 250ms)
    if(millisactuales-anteriores>250)   
    {
		boolean estado1 = digitalRead(boton1);
		boolean estado2 =digitalRead(boton2);
		boolean estado3 = digitalRead(boton3);
        
		anteriores=millisactuales;  // Se actualiza el tiempo anterior como el actual para una futura interrupcion 
		flag_actualizar=true;
		
        // Se checkea que boton causó entrar a la interrupción y se activa el relé asociado
        if(estado3) 
        {
            rel3=!rel3;
            digitalWrite(rele3,rel3);
        
        }
        else
        {
            if(estado1 && estado2)    // Si se presiono el boton multiplexado se cambia el estado de este rele
            {   
                rel4=!rel4;
                digitalWrite(rele4,rel4);
            }
            else
            {
                if(estado1)
                {
                    rel1=!rel1;
                    digitalWrite(rele1,rel1);
                }
                if(estado2)
                {
                    rel2=!rel2;
                    digitalWrite(rele2,rel2);
                }
            }
        }

	}
}

ICACHE_RAM_ATTR void ISRtimer1()
{
	digitalWrite(SEGUNDO_LED, !digitalRead(SEGUNDO_LED));	// Toggleo de LED para saber que hubo interrupcion de timer1 

	// Lo unico que se hace es setear una bandera que es revisada en el loop
	flag_enviarDatos=true;
}

void desconectarWifi(){
	WiFi.disconnect();				//desconectamos el dispositivo y limpiamos la variable conexion
	conexion.Conectarse=false;
	conexion.PASSEEPROM="";
	conexion.SSIDEEPROM="";
}

void actualizarDatos(){

	// Se hace la rutina de enviar datos con el cliente HTTP y el mensaje a enviar por metodo POST

	HTTPClient http;

	postData = "rele1=" + String(rel1) + "&rele2=" + String(rel2) + "&rele3=" + String(rel3) +"&rele4=" + String(rel4) + "&rele5="+String(rel1)+ "&id_serial=" + String(ID_SERIAL);
	Serial.println(String(rel1)+" "+String(rel2)+ " " + String(rel3)+ " "+ String(rel4));
	
	http.begin("http://irresponsible-toolb.000webhostapp.com/my_php/dbwrite.php");
	http.addHeader("Content-Type", "application/x-www-form-urlencoded");	
	
	int httpCode = http.POST(postData);   

	if (httpCode != 200) 
	{ 
		Serial.println(httpCode); 
		Serial.println("Fallo al subir los valores. \n"); 
		http.end(); 
		return; 
	}

}
