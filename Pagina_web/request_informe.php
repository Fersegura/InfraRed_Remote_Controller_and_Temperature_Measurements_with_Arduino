<?php
    $desde = strip_tags($_POST['from']);    // Fecha de inicio del informe
    $hasta = strip_tags($_POST['to']);      // Fecha de final del informe
    $unit = strip_tags($_POST['unit']);		//Get the id if the unit where we want to update the value

    //connect to the database
    include("../php/database_connect.php"); //We include the database_connect.php which has the data for the connection to the database

    // Check the connection
    if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    //Se guarda la informacion de la fecha de inicio/fin seleccionada por el usuario
    mysqli_query($con,"UPDATE `ESPtable2` SET `fecha_desde` = '{$desde}', `fecha_hasta` = '{$hasta}' WHERE id=$unit");

    // go back to the LTE interface
    header("location: ./pages/charts/solicitar_informe.php");
?>