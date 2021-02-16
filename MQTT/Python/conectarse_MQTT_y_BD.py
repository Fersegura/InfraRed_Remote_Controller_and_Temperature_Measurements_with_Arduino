import paho.mqtt.client as mqtt
import sys
import pymysql.cursors
import time # Para tener el tiempo en UNIX para guardar en la BD

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
    Funcion de callback a la que se llama cuando el cliente recibe un CONNACK desde el broker.
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
    Funcion de callback por defecto cuando llega un mensaje de PUBLISH al broker.
    Aca se revisa el topico y se hace lo que haga falta para cada topico (guardar en la BD)
    @param: client = clienteMQTT
    @param: userdata
    @param: msg=mensaje recibido. Tiene los siguientes campos: topic, payload, qos, retain.
"""
def on_message(client, userdata, msg):
    print(msg.topic+" "+str(msg.payload))
    topico = msg.topic.split("/")   # Devuelve una lista el split
    origen = topico[1]              # En nuestra estructura de topicos, despues del root viene quien hizo la publicacion (el origen del publish)

    if topico[2] == "prueba2":

        with connection.cursor() as cursor:
            sql = "SELECT * FROM `usuarios`"
            cursor.execute(sql,  )
            result = cursor.fetchall()
            print(result)
        return
    
    # Este es el default del "switch" hecho con los elif
    else:
        print("TOPICO DESCONOCIDO")
        return

"""
    Funcion para conectarse al broker MQTT:
    @return: objeto client de MQTT
"""
def conectarse_mqtt():
    client = mqtt.Client() # REVISAR LOS DISTINTOS ARGUMENTOS QUE PUEDE RECIBIR
    # Asignamos los callbacks para los distintos casos y topicos
    client.on_connect = on_connect
    client.on_message = on_message
    client.message_callback_add(sub="KMb6809yr8FThW1/python/#", callback=ignorar)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/temyhum", callback=temyhum)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/botones", callback=botones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/consultabotones", callback=consultabotones)

    try:
        client.connect(host="ioticos.org", port=1883, keepalive=60) #COMPLETAR CON LOS DATOS DE NUESTRO BROKER
        return client
    except:
        print("No se pudo conectar con el Broker MQTT...")
        print("Cerrando...")
        sys.exit()

"""
    Funcion de callback para el topico temyhum (temperatura y humedad).
    Debe guardar en la BD la informacion de forma correcta.
"""
def temyhum(client, userdata, msg):
    
    topico = msg.topic.split("/")   # Devuelve una lista el split
    origen = topico[1]              # En nuestra estructura de topicos, despues del root viene quien hizo la publicacion (el origen del publish)
    # Hay que transformar el payload que es de tipo 'byte' a str
    mensaje = msg.payload.decode("utf-8")
    mensaje = mensaje.split("/")
    temp, hum = mensaje[0], mensaje[1]
    tiempo_actual_unix = str(int(time.time()))  # Se castea primero a int para sacar lo de punto flotante y luego a str para concatenar en el sql
    
    sql = "INSERT INTO `datos`(`id`, `fecha`, `serial`, `temperatura`, `humedad`) VALUES (NULL,'" + tiempo_actual_unix + "','" + origen + "', '" + temp + "','" + hum + "')" # COMPLETAR CON SQL VALIDO
    
    with connection.cursor() as cursor:        
        try:
            cursor.execute(sql,)
            print("Guardando en base de datos TEMPERATURA...OK")
        except:
            print("Guardando en base de datos...Falló")
        
    # Hay que hacer un commit para que se impacten los cambios
    connection.commit()
    return

"""
    Funcion de callback para el topico botones.
    Debe cambiar el estado de los botones en la BD.
"""
def botones(client, userdata, msg):
    topico = msg.topic.split("/")   
    origen = topico[1]             
    mensaje = msg.payload.decode("utf-8")
    mensaje = mensaje.split("/")
    boton1, boton2, boton3, boton4 = mensaje[0], mensaje[1], mensaje[2], mensaje[3]

    sql = "UPDATE `ESPtable2` SET `RECEIVED_BOOL1`='" + boton1 + "',`RECEIVED_BOOL2`='" + boton2 + "',`RECEIVED_BOOL3`='" + boton3 + "',`RECEIVED_BOOL4`='" + boton4 + "' WHERE `id`='" + origen + "'"

    with connection.cursor() as cursor:        
        try:
            cursor.execute(sql,)
            print("Guardando en base de datos BOTONES ...OK")
        except:
            print("Guardando en base de datos...Falló")
        
    # Hay que hacer un commit para que se impacten los cambios
    connection.commit()
    return

"""
    Funcion de callback para el topico consultabotones.
    Debe publicar el estado de los botones del dispositivo que consulta.
"""
def consultabotones(client, userdata, msg):
    topico = msg.topic.split("/")   
    origen = topico[1]

    # if(origen == "python")  # Para ignorar los mensajes que salen de aca mismo
    #     return

    sql = "SELECT `RECEIVED_BOOL1`, `RECEIVED_BOOL2`, `RECEIVED_BOOL3`, `RECEIVED_BOOL4` FROM `ESPtable2` WHERE `id`='" + origen + "'"             

    with connection.cursor() as cursor:
        cursor.execute(sql,  )
        result = cursor.fetchall()  # Es del tipo lista, y adentro tiene un diccionario, con key=NombreColumna, value=ValorDeLaColumnaEnLaBD
    connection.commit()

    # Parseo el resultado de la busqueda para armar el payload del publish que voy a hacer
    boton1, boton2, boton3, boton4 = result[0]['RECEIVED_BOOL1'], result[0]['RECEIVED_BOOL2'], result[0]['RECEIVED_BOOL3'], result[0]['RECEIVED_BOOL4']
    
    payload = str(boton1)+"/"+str(boton2)+"/"+str(boton3)+"/"+str(boton4)
    topic="KMb6809yr8FThW1/python/consultabotones/"+origen

    client.publish(topic=topic, payload=payload, qos=0, retain=False)

    return

"""
    Funcion de callback para topicos publicados por este sript Python.
    No hace nada la funcion.
"""
def ignorar(client, userdata, msg):
    return

# -------------------------------------------- Programa principal ---------------------------------------------------

if(__name__ == "__main__"):

    # Primero, intento conectarme a la BD
    connection = conectarse_bd()

    # Segundo, intento conectarme al broker
    client = conectarse_mqtt()

    # Si todas las etapas anteriores fueron exitosas, ingreso las credenciales y me quedo escuchando
    # Credenciales del usuario del broker
    client.username_pw_set("pdmlO2qrY6s8h7y", "m1bGUlqz27SMsmX")

    try:
        # INVESTIGAR LOS TIPOS DE LOOP QUE HAY (xej LOS NO BLOQUEANTES)
        client.loop_forever()
    except KeyboardInterrupt:  #precionar Crtl + C para salir
        print("Cerrando...")
        connection.close()
        sys.exit()   

