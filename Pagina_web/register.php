<?php

    require_once('../php/database_connect.php');
    $conectar = $con;

    // -------------------MIS PRUEBAS----------------------------
    // mysqli_select_db($conectar, "usuarios");
    // -----------------------------------------------
    
    $usuario    = strip_tags($_POST['usuario'])             ;   // strip_tags actua como barrera si me quieren mandar codigo HTML a traves del formulario
    $mail       = sha1(strip_tags($_POST['mail']))          ;
    $mail_paraenviar = strip_tags($_POST['mail'])           ;   // Almaceno temporalmente una copia del mail para poder enviarle un correo al nuevo usuario
    $password   = sha1(strip_tags($_POST['password']))      ;   // sha1 es un metodo de encriptado para mayor seguridad
    $password_paramedir = strip_tags($_POST['password'])    ;   // Para ver la longitud necesito que NO este encriptada
    $r_password   = sha1(strip_tags($_POST['r_password']))  ;   // Confirmacion de password
    $acuerdo      = isset($_POST['terms'])                  ;   // Aceptacion de terminos y condiciones
    $tamaño     = strlen($password_paramedir)               ;
    $hoy        = date("Y-m-d H:i:s")                       ;   // Calculo la fecha con el mismo formato del TIME_STAMP

    $mensaje_error = "";

    // Reviso que la contraseña tenga un minimo de tamaño
    if($tamaño<8)
    {
        $mensaje_error = "Hey! la contraseña al menos tiene que tener 8 caracteres. Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_register-v2.html">';   // Luego de 3 segundos me redirige a esa pag.
        die();  // Esto evita que se ejecute el resto del codigo .php
    } 

    // Reviso si llenaron todos los casilleros
    if($usuario == NULL || $mail == NULL || $password == NULL || $r_password == NULL)
    {
        $mensaje_error = "No pueden quedar campos vacios! Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_register-v2.html">';   // Luego de 3 segundos me redirige a esa pag.
        die();
    }

    // Reviso que se haya tildado la caja de acuerdo
    if($acuerdo == NULL)
    {
        $mensaje_error = "Si no hay acuerdo que no haya nada. Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_register-v2.html">';   // Luego de 3 segundos me redirige a esa pag.
        die();
    }

    $query  = mysqli_query($conectar, "SELECT `usuario` FROM `usuarios` WHERE usuario='$usuario'");   
    $row    = mysqli_fetch_array($query);
    // Reviso si ya hay un usuario registrado con ese nombre
    if(isset($row[0]) == $usuario)
    {
        $mensaje_error = "Ya hay un usuario con ese nombre! Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_register-v2.html">';   // Luego de 3 segundos me redirige a esa pag.
        die();
    }
    else
    {
        $query  = mysqli_query($conectar, "SELECT `mail` FROM usuarios WHERE mail='$mail'");
        $row    = mysqli_fetch_array($query);
        // Reviso si no hay alguien registrado con el mail
        if(isset($row[0]) == $mail)
        {
            $mensaje_error = "Ya hay un alguien con ese mail! Regrese e intente nuevamente.";
            echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
            echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_register-v2.html">';   // Luego de 3 segundos me redirige a esa pag.
            die();
        }
        else
        {
            // Reviso que las dos contraseñas coincidan. No importa que esten encriptadas, porque si son iguales las encriptadas deben ser iguales tambien 
            if($password != $r_password)
            {
                $mensaje_error = "Las contraseñas no coinciden. Regrese e intente nuevamente.";
                echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
                echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_register-v2.html">';   // Luego de 3 segundos me redirige a esa pag.
                die();
            }
            // Ahora una vez que se haya verificado todo, se guarda el usuario en la BD
            else
            {
                $query  = mysqli_query($conectar, "INSERT INTO `usuarios` (`id`, `fecha_registro`,`fecha_ultimo_ingreso`, `usuario`, `password`, `mail`) 
                                                  VALUES (NULL, '$hoy', NULL, '$usuario', '$password', '$mail')");
                
                // Enviamos un mail al mail del usuario que se acaba de registrar
                // Esto no va a funcionar en XAMPP. Funcionara cuando lo suba a un server real
                // $para       = $mail_paraenviar;
                // $titulo     = "Usuario registrado en el servidor de Santi y Fer";
                // $mensaje    = 'Hola, "'.$usuario. '" tu usuario es: '.$usuario. ' ya puedes entrar al sistema.';
                // $cabeceras  = 'From: ---'."\r\n".'Reply-To: ---'."\r\n".'X-Mailer: PHP/'.phpversion();
                // mail($para, $titulo, $mensaje, $cabeceras);

                sleep(1);
                header("Location: ./pages/examples/my_login-v2.html");
            }
        }
    }
    mysqli_close($con);
    mysqli_close($conectar);
?>