<?php

    require_once './database_connect.php';
    
    // Checkeamos si la conexion fue exitosa
    if (mysqli_connect_errno()) 
    {
        die("Connection failed: " . mysqli_connect_errno());
    }
    else
    { 
        echo "Connected to mysql database. "; 
    }
    
    Si los valores recibidos del NodeMCU no estan vacios, se realiza un query de los valores de la pagina web
    if(!empty($_POST['id_serial']))
    {
        $id = $_POST['id_serial'];

        $resultado = mysqli_query($con, 'SELECT `RECEIVED_BOOL1`, `RECEIVED_BOOL2`, `RECEIVED_BOOL3`, 
                                                `RECEIVED_BOOL4`, `RECEIVED_BOOL5`, `RECEIVED_NUM1`, 
                                                `RECEIVED_NUM2`, `RECEIVED_NUM3`, `RECEIVED_NUM4`,
                                                `RECEIVED_NUM5`, `TEXT_1` 
                                            FROM `ESPtable2` 
                                            WHERE `id`='.$id);
    
        $row = mysqli_fetch_array($resultado);
    
        if ($row != NULL) 
        {
            // Se imprime el resultado (se que es una sola fila por dispositivo por la estructura de la BD)
            echo "RECEIVED_BOOL1:" . $row["RECEIVED_BOOL1"]. " ,RECEIVED_BOOL2:" . $row["RECEIVED_BOOL2"]. 
            " ,RECEIVED_BOOL3:" . $row["RECEIVED_BOOL3"]. " ,RECEIVED_BOOL4:" . $row["RECEIVED_BOOL4"].
            " ,RECEIVED_BOOL5:" .$row["RECEIVED_BOOL5"]." ,RECEIVED_NUM1:" .$row["RECEIVED_NUM1"].
            " ,RECEIVED_NUM2:" .$row["RECEIVED_NUM2"]." ,RECEIVED_NUM3:" .$row["RECEIVED_NUM3"].
            " ,RECEIVED_NUM4:" .$row["RECEIVED_NUM4"]." ,RECEIVED_NUM5:" .$row["RECEIVED_NUM5"].
            " ,RECEIVED_NUM4:" .$row["RECEIVED_NUM4"]." ,TEXT_1:" .$row["TEXT_1"];
        } 
        else 
        {
            echo "No hubo resultados";
        }
    }

    // Se cierra la conexion
    mysqli_close($con);
?>