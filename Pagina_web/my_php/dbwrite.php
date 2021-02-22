<?php

  require_once("./database_connect.php");
  $conn = $con;

  // Get date and time variables
  $fecha = date_create();
  $d= date_timestamp_get($fecha);

  // If values send by NodeMCU are not empty then insert into MySQL database table

  if(!empty($_POST['temp']) && !empty($_POST['hum']) )
  {
    $temperatura = strip_tags($_POST['temp']);
    $humedad = strip_tags($_POST['hum']);

    // Update your tablename here
    $sql = "INSERT INTO `datos`(`serial`, `fecha`, `temperatura`, `humedad`) VALUES (2,$d,$temperatura,$humedad)"; 

    if ($conn->query($sql) === TRUE) 
    {
        echo "Values inserted in MySQL database table.";
    } else 
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
  
  if(!empty($_POST['rele1']) && !empty($_POST['rele2']) && !empty($_POST['rele3']) && !empty($_POST['rele4']) && !empty($_POST['id_serial']) )
  {
    $rele1 = strip_tags($_POST['rele1']);
    $rele2 = strip_tags($_POST['rele2']);
    $rele3 = strip_tags($_POST['rele3']);
    $rele4 = strip_tags($_POST['rele4']);
    $id_serial = strip_tags($_POST['id_serial']);

    $sql = "UPDATE `ESPtable2` SET `RECEIVED_BOOL1`=$rele1,`RECEIVED_BOOL2`=$rele2,`RECEIVED_BOOL3`=$rele3,`RECEIVED_BOOL4`=$rele4 WHERE `id`=$id_serial"; 
    
    if ($conn->query($sql) === TRUE) 
    {
        echo "Values inserted in MySQL database table.";
    } else 
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }


  // Close MySQL connection
  $conn->close();



?>
