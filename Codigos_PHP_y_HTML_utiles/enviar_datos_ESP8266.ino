// Tutorial donde se ve este codigo: https://youtu.be/zQY6Pfpm_w8?list=PLVnDMG-Nwzxl_5B65dlJsTKOa8xoIKqBm


#include <ESP8266WiFi.h>
const char* ssid = "";      // ssid a la que esta conectado el servidor y la placa 
const char* password = "";  // Contraseña de dicha red
const char* host= "";       // Esto seria la URL del servidor, pero por ahora es la IP donde esta instalado XAMPP 
                            // (para verla entrar en cmd y escribir 'ipconfig')



void setup()
{
    Serial.begin(115200);
    delay(10);

    // Nos conectamos a nuestro WiFi
    Serial.println();
    Serial.println();
    Serial.print("Connecting to ");
    Serial.println(ssid);

	WiFi.begin(ssid, password);

    while(WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }

    Serial.println("");
    Serial.println("WiFi connected");
    Serial.println("IP address: ");
    Serial.println(Wifi.localIP());
}

int value = 0;

void loop()
{
    delay(2000);
    ++value;
    Serial.prinln("connecting to");
    Serial.prinln(host);

    // Creamos una instancia de WIFICLIENT
    WiFiClient client;
    const int httpPort = 80;    // Cuidado si cambie el puerto porque tuve problemas con XAMPP, cambiar aca tambien 
    if(!client.connect(host, httpPort))
    {
        Serial.println("connection failed");
        return;
    }

    // Creamos la direccion para luego usarla en el String del POST que tendremos que enviar
    String url = "http://miDireccionIP/enviar_datos_ESP8266.php"    // Reemplazar con IP y nombre del archivo que tenga
    // Creo un string con los datos que enviare por POST. Lo creo de antemano para luego poder calcular el tamaño del string (length)
    String data = "serie=777&temp=33"   // Reemplazar con los valor que quiera enviar

    // imprimo la url (para debugg nomas)
    Serial.print("Requesting URL: ");
    Serial.println(url);

    // Esta es la solicitud de tipo POST que enviaremos al servidor.
    // Tiene que ser asi (es una convencion, no un capricho) para que el servidor entienda que es un POST.
    client.print(String("POST ") + url + " HTTP/1.0\r\n" + 
                 "Host: " + host + "\r\n" + 
                 "Accept: *" + "/" + "*\r\n" +
                 "Content-Length: " + data.length() + "\r\n" + 
                 "Content-Type: application/x-www-form-urlencoded\r\n" +  
                 "\r\n" + data);
    delay(10);

    // Leemos todas las lineas que nos responde el servidor y las imprimimos por pantalla.
    // No es necesario pero ayuda para saber que esta pasando.
    Serial.println("Respond:");
    while(client.available())
    {
        String line = client.readStringUntil('\r');
        Serial.print(line);
    }

    Serial.println();

    // Se cierra la conexion

    Serial.println("closing connection");
    
}
