import paho.mqtt.client as mqtt
import sys
import pymysql.cursors

"""
    Funcion para conectarnos a la base de datos de forma remota.
    @return: connection= ojeto de pymysql que permite conectarse a la BD
"""
def conectarse_bd():
    try:
        connection = pymysql.connect(host='freedb.tech',
                                user='freedbtech_santiyfer',
                                password='QF92azHKY@2gFm7',
                                database='freedbtech_RSA_IOT',
                                charset='utf8mb4',
                                cursorclass=pymysql.cursors.DictCursor)
        print("Si se pudo conectar a la base de datos...")
        return connection
    except:
        print("No se pudo conectar a la base de datos...")
        print("Cerrando el programa...")
        sys.exit()

"""
    Funcion de callback a la que se llama cuando el cliente recibe un CONNACK desde el servidor.
    @param: client= objeto cliente MQTT
    @param: userdata
    @param: flags
    @param: rc

"""
def on_connect(client, userdata, flags, rc):
    print("Conectado - Codigo de resultado: "+str(rc))
    # Esto se hace por si se pierde conexion en algun momento, se renueven las suscripciones que habia
    client.subscribe("KMb6809yr8FThW1/#")

"""
    Funcion de callback cuando llega un mensaje de PUBLISH desde el servidor.
    Aca se revisa el topico y se hace lo que haga falta para cada topico (guardar en la BD)
    @param: client = clienteMQTT
    @param: userdata
    @param: msg=mensaje recibido. Tiene los siguientes campos: topic, payload, qos, retain.
"""
def on_message(client, userdata, msg):
    print(msg.topic+" "+str(msg.payload))
    topico = msg.topic.split("/")   # Devuelve una lista el split
    print(topico)

    if(topico[1] == "prueba1"):
        
        sql = "INSERT INTO `usuarios` (`id`, `usuario`, `password`, `mail`) VALUES (NULL, 'Prueba1', 'Prueba1', 'Prueba1')" # COMPLETAR CON SQL VALIDO
        
        with connection.cursor() as cursor:        
            try:
                cursor.execute(sql,)
                print("Guardando en base de datos...OK")
            except:
                print("Guardando en base de datos...Falló")
            
        # Hay que hacer un commit para que se impacten los cambios
        connection.commit()
        return

    elif(topico[1] == "prueba2"):

        with connection.cursor() as cursor:
            sql = "SELECT * FROM `usuarios`"
            cursor.execute(sql,  )
            result = cursor.fetchall()
            print(result)
        return





"""
    Funcion para conectarse al broker MQTT:
    @return: objeto client de MQTT
"""
def conectarse_mqtt():
    client = mqtt.Client() # REVISAR LOS DISTINTOS ARGUMENTOS QUE PUEDE RECIBIR
    # REVISAR LOS DISTINTOS CALLBACKS QUE SE PUEDEN IMPLEMENTAR
    client.on_connect = on_connect
    client.on_message = on_message

    try:
        client.connect(host="ioticos.org", port=1883, keepalive=60) #COMPLETAR CON LOS DATOS DE NUESTRO BROKER
        return client
    except:
        print("No se pudo conectar con el Broker MQTT...")
        print("Cerrando...")
        sys.exit()


# -------------------------------------------- Programa principal ---------------------------------------------------

if(__name__ == "__main__"):

    client = conectarse_mqtt()
    connection = conectarse_bd()


    # Credenciales del usuario del broker
    client.username_pw_set("pdmlO2qrY6s8h7y", "m1bGUlqz27SMsmX") 

    try:
        # INVESTIGAR LOS TIPOS DE LOOP QUE HAY (xej LOS NO BLOQUEANTES)
        client.loop_forever()
    except KeyboardInterrupt:  #precionar Crtl + C para salir
        print("Cerrando...")
        connection.close()
        sys.exit()   
