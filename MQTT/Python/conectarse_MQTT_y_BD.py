# Este archivo es de la ultima version del python usado en la tesis
import paho.mqtt.client as mqtt
import sys
import pymysql.cursors
import time # Para tener el tiempo en UNIX para guardar en la BD
import smtplib, ssl # Para enviar el correo de alarma
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from email.utils import formataddr

"""
    Funcion para conectarnos a la base de datos de forma remota.
    @return: connection= ojeto de pymysql que permite conectarse a la BD
"""
def conectarse_bd():                                        #ver de agregar mas de una base de datos con distintos nombres ej connection_remote connection_freedb etc
    try:
        connection = pymysql.connect(host='remotemysql.com',
                                user='C4gd1lgeA2',
                                password='mUngxZTVJj',
                                database='C4gd1lgeA2',
                                charset='utf8mb4',
                                cursorclass=pymysql.cursors.DictCursor)
        print("Si se pudo conectar a la base de datos...")
        return connection
    except:
        print("No se pudo conectar a la base de datos...")
        print("Cerrando el programa...")
        sys.exit()

"""
    Funcion de callback a la que se llama cuando el cliente recibe un CONNACK desde el broker.   --------->Esto no deberia ser se conecta al Broker y se suscribe a todos los topicos???<----------
    @param: client= objeto cliente MQTT
    @param: userdata
    @param: flags
    @param: rc
"""
def on_connect(client, userdata, flags, rc):                
    print("Conectado - Codigo de resultado: "+str(rc))
    # Esto se hace por si se pierde conexion en algun momento, se renueven las suscripciones que habia
    client.subscribe("KMb6809yr8FThW1/#")
    client.message_callback_add(sub="KMb6809yr8FThW1/python/#", callback=ignorar)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/BD/temyhum", callback=temyhum)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/+/botones", callback=actualizarbotones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/BD/consultabotones", callback=consultabotones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/python/consultabotones", callback=consultabotones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/python/trigger_alarma", callback=trigger_alarma)
    client.message_callback_add(sub="KMb6809yr8FThW1/web/python/set_limites", callback=set_limites)

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

    print("TOPICO DESCONOCIDO")
    return

"""
    Funcion para conectarse al broker MQTT:
    @return: objeto client de MQTT
"""
def conectarse_mqtt():
    client = mqtt.Client() # REVISAR LOS DISTINTOS ARGUMENTOS QUE PUEDE RECIBIR
    # Asignamos los callbacks para los distintos casos y topicos
    client.on_connect = on_connect    #necesito una explicacion de esta parte
    client.on_message = on_message
    client.message_callback_add(sub="KMb6809yr8FThW1/python/#", callback=ignorar)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/BD/temyhum", callback=temyhum)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/+/botones", callback=actualizarbotones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/BD/consultabotones", callback=consultabotones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/python/consultabotones", callback=consultabotones)
    client.message_callback_add(sub="KMb6809yr8FThW1/+/python/trigger_alarma", callback=trigger_alarma)
    client.message_callback_add(sub="KMb6809yr8FThW1/web/python/set_limites", callback=set_limites)

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
    Funcion de callback para el topico actualizar botones.
    Debe cambiar el estado de los botones en la BD.
"""
def actualizarbotones(client, userdata, msg):
    topico = msg.topic.split("/")   
    origen = topico[1]

    mensaje = msg.payload.decode("utf-8")
    mensaje = mensaje.split("/")
    boton1, boton2, boton3, boton4 = mensaje[0], mensaje[1], mensaje[2], mensaje[3]

    # Implementacion de prueba !!!!
    if origen == "web":
        destinatario = topico[2]    # Es el id_serial de la placa que hay que actualizar los botones
        sql = "UPDATE `ESPtable2` SET `RECEIVED_BOOL1`='" + boton1 + "',`RECEIVED_BOOL2`='" + boton2 + "',`RECEIVED_BOOL3`='" + boton3 + "',`RECEIVED_BOOL4`='" + boton4 + "' WHERE `id`='" + destinatario + "'"
    else:
        sql = "UPDATE `ESPtable2` SET `RECEIVED_BOOL1`='" + boton1 + "',`RECEIVED_BOOL2`='" + boton2 + "',`RECEIVED_BOOL3`='" + boton3 + "',`RECEIVED_BOOL4`='" + boton4 + "' WHERE `id`='" + origen + "'"
    # =============================

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
    if (topico[1]=="BD" or topico[1]=="web"):
        destino = msg.payload.decode("utf-8")   # Si el mensaje viene de la web, el serial_id de la placa viene en el payload
    else:
        destino =topico[1]  # Desde le PHP se deberia publicar a un topico roon/web/consultabotones/99999  ----->capaz no publicar desde el php solo hacer la consulta y publicar desdel el python

                            
    sql = "SELECT `RECEIVED_BOOL1`, `RECEIVED_BOOL2`, `RECEIVED_BOOL3`, `RECEIVED_BOOL4` FROM `ESPtable2` WHERE `id`='" + destino + "'"             

    with connection.cursor() as cursor:
        cursor.execute(sql,  )
        result = cursor.fetchall()  # Es del tipo lista, y adentro tiene un diccionario, con key=NombreColumna, value=ValorDeLaColumnaEnLaBD
    connection.commit()
    # Parseo el resultado de la busqueda para armar el payload del publish que voy a hacer
    boton1, boton2, boton3, boton4 = result[0]['RECEIVED_BOOL1'], result[0]['RECEIVED_BOOL2'], result[0]['RECEIVED_BOOL3'], result[0]['RECEIVED_BOOL4']

    payload = str(boton1)+"/"+str(boton2)+"/"+str(boton3)+"/"+str(boton4)
    topic="KMb6809yr8FThW1/python/"+destino+"/consultabotones"

    client.publish(topic=topic, payload=payload, qos=0, retain=False)

    return

"""
    Funcion de callback para el topico de trigger_alarma.
    Debe enviar un mail al usuario dueño de la placa para avisarle que se excedió algún límite indicandole la placa que emitio la alarma.
"""
def trigger_alarma(client, userdata, msg):
    topico = msg.topic.split("/")   
    origen = topico[1]
    # En el payload viene que alarma es (temp o hum) y el valor que se detectó
    payload = msg.payload.decode("utf-8")
    alarma = payload.split("/")[0] 
    valor  = payload.split("/")[1]

    sql = "SELECT `id_usuario` FROM `ESPtable2` WHERE `id`='" + origen + "'"    # Busco al dueño de la placa     

    with connection.cursor() as cursor:
        cursor.execute(sql,  )
        result = cursor.fetchall() 
        id_usuario = result[0]['id_usuario']
        sql = "SELECT `usuario`, `mail` FROM `usuarios` WHERE `id`='" + str(id_usuario) + "'"    # Busco el mail del dueño 
        cursor.execute(sql,  )
        result = cursor.fetchall()
        mail_usuario = result[0]['mail']
        nombre_usuario = result[0]['usuario']
    connection.commit()

    # Credenciales para conexión STMP segura con SSL:
    smtp_server = 'smtp.gmail.com'
    port = 465
    sender = 'santiyfer21@gmail.com'
    password = 'v2FX4k0xD1sj26d9'
    context = ssl.create_default_context()

    # Se construye el mensaje en formato HTML y texto, por si falla el formateo a HTML se envie el de texto
    message = MIMEMultipart("alternative")
    message["Subject"] = "ALARMA!"
    message["From"] = formataddr(('R.S.A', sender))
    message["To"] = mail_usuario

    # Create the plain-text and HTML version of your message
    text = """\
    R.S.A

    HA SALTADO UNA ALARMA DE TU DISPOSITIVO!

    Hola """+ nombre_usuario +""", este es un correo automatico para avisarte que se disparo una de las alarmas que habias establecido."""
    
    html = """\
    <html>
        <body>
            <div align="center">
                <h1>HA SALTADO UNA ALARMA DE TU DISPOSITIVO!</h1><br>
            </div>
            <div align="left">
                <h2>Hola """+ nombre_usuario +""", este es un correo automatico para avisarte que se disparo una de las alarmas que habias establecido.</h2><br>
                <p><h3>La alarma que se disparó es: """+ alarma +""". El valor sensado es: """+ valor +"""<br>
                El dispositivo de origen de la alarma es el de serial: """+ origen +"""</h3></p>
            </div>
        </body>
    </html>
    """

    # Convierto estas partes en objetos plain/html MIMEText 
    part1 = MIMEText(text, "plain")
    part2 = MIMEText(html, "html")

    # Agregamos las partes HTML/plain-text al mensaje MIMEMultipart
    # El cliente email va a tratar de renderizar la parte HTML primero y si falla hace lo otro
    message.attach(part1)
    message.attach(part2)
    
    with smtplib.SMTP_SSL(host=smtp_server, port=port, context=context) as server:
        server.login(sender, password)
        # Enviamos el correo:
        server.sendmail(from_addr=sender, to_addrs=mail_usuario, msg=message.as_string())
        server.quit()
    
    return

"""
    Funcion de callback para el topico de set_limites. 
    El PHP es el publicador a este tópico.
    Se buscan los limites y se los publica en el topico al cual está suscripto el ESP8266
"""
def set_limites(client, userdata, msg):
    destino = msg.payload.decode("utf-8")   # El serial_id de la placa viene en el payload

    sql = "SELECT  `RECEIVED_NUM1`, `RECEIVED_NUM2`, `RECEIVED_NUM3`, `RECEIVED_NUM4` FROM `ESPtable2` WHERE `id`='" + destino + "'"        

    with connection.cursor() as cursor:
        cursor.execute(sql,  )
        result = cursor.fetchall() 
    connection.commit()

    # Se obtiene el resultado y se publican en el topico correspondiente
    num1, num2, num3, num4 = result[0]['RECEIVED_NUM1'], result[0]['RECEIVED_NUM2'], result[0]['RECEIVED_NUM3'], result[0]['RECEIVED_NUM4']

    payload = str(num1)+"/"+str(num2)+"/"+str(num3)+"/"+str(num4)+"/"
    topic="KMb6809yr8FThW1/python/"+destino+"/get_limites"

    client.publish(topic=topic, payload=payload, qos=0, retain=False)

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
