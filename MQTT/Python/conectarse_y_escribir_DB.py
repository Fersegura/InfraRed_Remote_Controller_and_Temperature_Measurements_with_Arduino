import pymysql.cursors
import sys
# Nos conectamos a la base de datos, en este caso esta para la base de datos local de XAMPP
try:
    connection = pymysql.connect(host='freedb.tech',
                             user='freedbtech_santiyfer',
                             password='QF92azHKY@2gFm7',
                             database='freedbtech_RSA_IOT',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)
    print("Si se pudo conectar a la base de datos...")
except:
    print("No se pudo conectar a la base de datos")
    print("Cerrando el programa")
    sys.exit()
#-------------------------------------------------------------------------------------
#Inserto datos a la tabla datos
with connection:
    with connection.cursor() as cursor:
        # Se escribe un dato de prueba
        a="Santiaguito"
        b="Fernandito"
        sql = "INSERT INTO `prueba1`(`id`, `nombre`, `apellido`) VALUES (NULL, '" + str(a) + "' , '" + str(b) + "');"
        try:
            cursor.execute(sql,)
            print("se pudo enviar")
        except:
            print("no se pudo enviar")
            
    # Hay que hacer un commit para que se impacten los cambnios
    connection.commit()
#Pedimos todas las columnas de la tabla datos
    with connection.cursor() as cursor:
        # Read a single record
        sql = "SELECT * FROM `prueba1`"
        cursor.execute(sql,  )
        result = cursor.fetchall()
        print(result)
        