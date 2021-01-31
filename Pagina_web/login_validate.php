<?php
    // Inicio de sesiones
    session_start();    // Inicializo para crear variables de sesiones que permite compartir informacion del usuario 
                        // con otros archivos .php
    // Me conecto a la BD
    require_once('../php/database_connect.php')                ;
    $user       = strip_tags($_POST['usuario'])         ;
    $password   = sha1(strip_tags($_POST['password']))  ;   // Encripto el PW para compararlo con el que esta en la BD

    $resultado = mysqli_query($con, 'SELECT * FROM `usuarios` WHERE `usuario`="'. mysqli_real_escape_string($con, $user).    // El real_escape se usa para mayor seguridad para evitar caracteres especiales en los datos ingresados
    '" AND `password`="'. mysqli_real_escape_string($con, $password).'"'); //Este query solo da resultado si el nombre y PW existen

    // Si el usuario existe, se loggea 
    if($existe = mysqli_fetch_object($resultado))
    {
        // Se guarda ultimo inicio de sesion
        $hoy = date("Y-m-d H:i:s");
        mysqli_query($con, "UPDATE `usuarios` SET `fecha_ultimo_ingreso`='$hoy' WHERE `usuario`='$user'");

        $resultado = mysqli_query($con, 'SELECT * FROM `usuarios` WHERE `usuario`="'. mysqli_real_escape_string($con, $user).    // El real_escape se usa para mayor seguridad para evitar caracteres especiales en los datos ingresados
        '" AND `password`="'. mysqli_real_escape_string($con, $password).'"'); //Este query solo da resultado si el nombre y PW existen

        // Extraigo los datos (columnas) de ese usuario en particular y los guardo en las variable de sesion
        $row = mysqli_fetch_array($resultado);
        $_SESSION['logged'] = 'yes';
        $_SESSION['user'] = $row["usuario"];
        $_SESSION['user_id'] = $row["id"]; // Importantisimo trabajar con el user_id en vez del nombre de usuario para permitir que sea modificable este ultimo
        $_SESSION['mail'] = $row["mail"];

        // echo "true";

        // Se usa este pedacito de codigo HTML para redirigirme a otra pagina web
        // echo '<meta http-equiv="Refresh" content="3;url=https://github.com/OtroCuliau">';   // Luego de 3 segundos me redirige a esa pag.
        // Esta tambien se usa para redirigir, pero para volver luego de ingresar mal algun dato es mejor la anterior
        header("Location: ./index.php");

    }
    else
    {
        $_SESSION ['logged'] = 'no';
        // echo "false";
        echo "<script type='text/javascript'>alert('Usuario o Password incorrecto.');</script>";
        echo '<meta http-equiv="Refresh" content="1;url=./pages/examples/my_login-v2.html">';
    }
    mysqli_close($con);
?>