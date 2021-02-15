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
#Inserto datos a la tabla datos
with connection:
    with connection.cursor() as cursor:
        # Create a new record
        a=int(2)
        sql = "INSERT INTO `datos` (`ID`, `fecha`, `Serie`, `temperatura`) VALUES (NULL, current_timestamp(),"+str(a)+" , '29.87');"
        try:
            cursor.execute(sql,)
            print("se pudo enviar")
        except:
            print("no se pudo enviar")
            
    # connection is not autocommit by default. So you must commit to save
    # your changes.
    connection.commit()
#Pedimos todas las columnas de la tabla datos
    with connection.cursor() as cursor:
        # Read a single record
        sql = "SELECT * FROM `datos`   "
        cursor.execute(sql,  )
        result = cursor.fetchall()
        print(result)
