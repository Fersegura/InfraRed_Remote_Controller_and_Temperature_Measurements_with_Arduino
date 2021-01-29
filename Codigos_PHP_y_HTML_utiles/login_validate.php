<?php
    // Inicio de sesiones
    session_start();    // Inicializo para crear variables de sesiones que permite compartir informacion del usuario 
                        // con otros archivos .php
    // Me conecto a la BD
    require_once('conectar.php')                        ;
    $conectar   = conectar()                            ;
    $user       = strip_tags($_POST['user'])            ;
    $password   = strip_tags(sha1($_POST['password']))  ;   // Encripto el PW para compararlo con el que esta en la BD

    $query = mysqli_query($conectar, 'SELECT * FROM usuarios WHERE user="'. mysqli_real_escape_string($conectar, $user).    // El real_escape se usa para mayor seguridad para evitar caracteres especiales en los datos ingresados
                          '" AND password="'. mysqli_real_escape_string($conectar, $password).'"'); //Este query solo da resultado si el nombre y PW existen

    // Si el usuario existe, se loggea 
    if($existe = mysqli_fetch_object($query))
    {
        $hoy = date("Y-m-d H:i:s");

        $_SESSION['logged'] = 'yes';

        // Extraigo todos los datos (columnas) de ese usuario en particular
        $query = mysqli_query($conectar, "SELECT * FROM `usuarios`");
        $row = mysqli_fetch_array($query);

        echo $id=$row[0];
        echo "<br>";
        echo $fecha = $row[1];
        echo "<br>";
        echo $user = $row[2];
        echo "<br>";
        echo $mail = $row[4];
        echo "<br>";

        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = $id;
        $_SESSION['mail'] = $mail;

        mysqli_close($conectar);

        echo "true";

        // Se usa este pedacito de codigo HTML para redirigirme a otra pagina web
        echo '<meta http-equiv="Refresh" content="3;url=https://github.com/OtroCuliau">';   // Luego de 3 segundos me redirige a esa pag.
    }
    else
    {
        $_SESSION ['logged'] = 'no';
        echo "false";
    }

?>
