<?php

    require_once('conectar.php');
    $conectar = conectar();

    // -------------------MIS PRUEBAS----------------------------
    // mysqli_select_db($conectar, "usuarios");
    // -----------------------------------------------
    
    $user       = strip_tags($_POST['user'])            ;   // strip_tags actua como barrera si me quieren mandar codigo HTML a traves del formulario
    $mail       = strip_tags($_POST['mail'])            ;
    $password   = sha1(strip_tags($_POST['password']))  ;   // sha1 es un metodo de encriptado para mayor seguridad
    $password_paramedir = strip_tags($_POST['password']);   // Para ver la longitud necesito que NO este encriptada
    $tamaño     = strlen($password_paramedir)           ;
    $hoy        = date("Y-m-d H:i:s")                   ;   // Calculo la fecha con el mismo formato del TIME_STAMP

    if($tamaño<8)
    {
        echo "Hey! la contraseña al menos tiene que tener 8 caracteres";
        die();                                              // Esto evita que se ejecute el resto del codigo .php
    }

    $r_password   = sha1(strip_tags($_POST['r_password']))  ;
    $acuerdo      = isset($_POST['terms'])                  ;  

    // Reviso si llenaron todos los casilleros
    if($user == NULL || $mail == NULL || $password == NULL || $r_password == NULL)
    {
        echo "No pueden quedar campos vacios!";
        die();
    }

    if($acuerdo == NULL)
    {
        echo "Si no hay acuerdo que no haya nada";
        die();
    }

    // Reviso si ya hay un usuario registrado con ese nombre
    $query  = mysqli_query($conectar, "SELECT `user` FROM usuarios WHERE user='$user'");   
    $row    = mysqli_fetch_array($query);
    if($row[0] == $user)
    {
        echo "Ya hay un usuario con ese nombre!";
        die();
    }
    else
    {
        // Reviso si no hay alguien registrado con el mail
        $query  = mysqli_query($conectar, "SELECT `mail` FROM usuarios WHERE mail='$mail'");
        $row    = mysqli_fetch_array($query);

        if($row[0] == $mail)
        {
            echo "Ya hay un alguien con ese mail!";
            die();
        }
        else
        {
            // Reviso que las dos contraseñas coincidan. No importa que esten encriptadas, porque si son iguales las encriptadas deben ser iguales tambien 
            if($password != $r_password)
            {
                echo "Las contraseñas no coinciden";
                die();
            }
            // Ahora una vez que se haya verificado todo, se guarda el usuario en la BD
            else
            {
                $query  = mysqli_query($conectar, "INSERT INTO `usuarios` (`id`, `fecha`, `user`, `password`, `mail`) 
                                                  VALUES (NULL, '$hoy', '$user', '$password', '$mail')");
                
                // Enviamos un mail al mail del usuario que se acaba de registrar
                // Esto no va a funcionar en XAMPP. Funcionara cuando lo suba a un server real
                $para       = $mail;
                $titulo     = "Usuario registrado en el servidor IOT";
                $mensaje    = 'Hola, "'.$user. '" tu usuario es: '.$user. ' ya puedes entrar al sistema.';
                $cabeceras  = 'From: ---'."\r\n".'Reply-To: ---'."\r\n".'X-Mailer: PHP/'.phpversion();
                mail($para, $titulo, $mensaje, $cabeceras);
            }
        }
    }
?>