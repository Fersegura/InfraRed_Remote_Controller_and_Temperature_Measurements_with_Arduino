<?php
    require_once './database_connect.php';
    require_once "./send_mail.php";

    
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
        echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>"; // Vuelvo a la pagina de registrar
        die();  // Esto evita que se ejecute el resto del codigo .php
    } 

    // Reviso si llenaron todos los casilleros
    if($usuario == NULL || $mail == NULL || $password == NULL || $r_password == NULL)
    {
        $mensaje_error = "No pueden quedar campos vacios! Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>";
        die();
    }

    // Reviso que se haya tildado la caja de acuerdo
    if($acuerdo == NULL)
    {
        $mensaje_error = "No acepto los terminos y condiciones. Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>";
        die();
    }

    $query  = mysqli_query($con, "SELECT `usuario` FROM `usuarios` WHERE usuario='$usuario'");   
    $row    = mysqli_fetch_array($query);
    // Reviso si ya hay un usuario registrado con ese nombre
    if(isset($row[0]) == $usuario)
    {
        $mensaje_error = "Ya hay un usuario con ese nombre! Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>";
        die();
    }
    // Reviso si el mail es valido
    if(!filter_var($mail_paraenviar, FILTER_VALIDATE_EMAIL))
    {
        $mensaje_error = "Mail invalido! Regrese e intente nuevamente.";
        echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
        echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>";
        die();
    }
    else
    {

        $query  = mysqli_query($con, "SELECT `mail` FROM usuarios WHERE mail='$mail'");
        $row    = mysqli_fetch_array($query);
        // Reviso si no hay alguien registrado con el mail
        if(isset($row[0]) == $mail)
        {
            $mensaje_error = "Ya hay un alguien con ese mail! Regrese e intente nuevamente.";
            echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
            echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>";
            die();
        }
        else
        {
            // Reviso que las dos contraseñas coincidan. No importa que esten encriptadas, porque si son iguales las encriptadas deben ser iguales tambien 
            if($password != $r_password)
            {
                $mensaje_error = "Las contraseñas no coinciden. Regrese e intente nuevamente.";
                echo "<script type='text/javascript'>alert('$mensaje_error');</script>";
                echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_register-v2.html';</script>";
                die();
            }
            else
            {
                // Enviamos un mail al mail del usuario que se acaba de registrar
                // Por el metodo GET paso el nombre de usuario para ubicarlo en la BD (se que no hay usuarios repetidos)

                $url = 'http://'.$_SERVER["SERVER_NAME"].'/my_php/activar_cuenta.php?usuario='.$usuario;  
                $para       = $mail_paraenviar;
                $asunto     = "Activar cuenta para utilizar el servicio de Santi y Fer.";
                $mensaje    = '<div align="center"><h1>¡BIENVENIDO!</h1><br></br><h2>Hola '.$usuario.' debes activar tu cuenta para poder logearte.</h2><br></br>
                               <h3>Haz click en el siguiente enlace para activarla: <a href='.$url.'><b>Activar</b></a></h3></div>';
                
                if(send_mail($para, $usuario, $asunto, $mensaje))
                {
                    // Ahora una vez que se haya verificado todo, se guarda el usuario en la BD
                    $query  = mysqli_query($con, "INSERT INTO `usuarios` (`id`, `fecha_registro`,`fecha_ultimo_ingreso`, `usuario`, `password`, `mail`) 
                                                VALUES (NULL, '$hoy', NULL,'$usuario','$password', '$mail')");

                    sleep(1);
                    header("Location: ../pages/examples/my_login-v2.php");
                }
                else
                {   
                    echo '<p style="color:red">No se pudo enviar el mensaje..';
                    echo 'Error enviando mail de verificacion';
                    echo "</p>";
                }            
            }
        }
    }
    
    mysqli_close($con);
?>