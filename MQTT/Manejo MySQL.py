import pymysql.cursors
import sys
# Nos conectamos a la base de datos, en este caso esta para la base de datos local de XAMPP
try:
    connection = pymysql.connect(host='localhost',
                             user='root',
                             password='',
                             database='frigorifico',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)
    print("Si se pudo conectar a la base de datos Frigorifico")
except:
    print("No se pudo conectar a la base de datos")
    print("Cerrando el programa")
    sys.exit()
#-------------------------------------------------------------------------------------
# Prueba de insertar datos a la tabla datos
with connection:
    with connection.cursor() as cursor:
        # Se inserta un dato de prueba en la BD
        a=int(2)
        sql = "INSERT INTO `datos` (`ID`, `fecha`, `Serie`, `temperatura`) VALUES (NULL, current_timestamp(),"+str(a)+" , '29.87');"
        try:
            cursor.execute(sql,)
            print("se pudo enviar")
        except:
            print("no se pudo enviar")
            
    # Hay que hacer el commit para que impacten los cambios en la BD.
    connection.commit()
# Prueba de pedir todas las columnas de la tabla datos
    with connection.cursor() as cursor:
        sql = "SELECT * FROM `datos`   "
        cursor.execute(sql,  )
        result = cursor.fetchall()
        print(result)
