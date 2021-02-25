<?php
    // Inicio para poder usar variables de sesion
    session_start();
	// Conectarse a la BD e incluir lo necesario para hacer un publish MQTT para avisar que se cambio algun dato de la BD
	require_once("./database_connect.php");
	require_once('./publish_mqtt.php');

	// Este archivo recibe los valores cuando desde la pagina de enviar datos se cambia algun valor.
	
	$value = strip_tags($_POST['value']);		// Valor a cambiar
	$unit = strip_tags($_POST['unit']);			// id_serial del dispositivo a cambiar el valor 
	$column = strip_tags($_POST['column']);		// Columna de la BD que hay que cambiar 
	$id_usuario = $_SESSION['usuario_id'];		// id del dueño de ese dispositivo (por si llega a haber seriales repetidos)

	// Se actualizan los valores de la
	mysqli_query($con,"UPDATE `ESPtable2` SET $column = '{$value}' WHERE id=$unit AND id_usuario='".$id_usuario."'"); 
	
	// Se envía un publish para que el python se entere que cambiaron los valores de la BD y le avise al dispositivo que tenga que ser 
	$topico = 'web/python/consultabotones';
	publicar($topico,$unit );
	// Se vuelve a la pagina de enviar datos
    echo "<script>window.location.href = '../pages/UI/my_buttons.php';</script>";
?>