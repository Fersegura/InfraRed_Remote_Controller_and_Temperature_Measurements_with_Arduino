<?php
    // Inicio de sesiones
    session_start();
    // Me conecto a la BD
    require_once('conectar.php')                        ;
    $conectar   = conectar()                            ;
    $user       = strip_tags($_POST['user'])            ;
    $password   = strip_tags(sha1($_POST['password']))  ;

    $query = mysqli_query($conectar, 'SELECT * FROM usuarios WHERE user=<'. mysqli_real_escape_string($conectar, $user). 
                          '" AND password="'. mysqli_real_escape_string($conectar, $password).'"');

    if($existe = mysqli_fetch_object($query))
    {
        $hoy = date("Y-m-d H:i:s")

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

        echo '<meta http-equiv="Refresh" content="3;url=http://www.cadena3.com">';
    }
    else
    {
        $_SESSION ['logged'] = 'no';
        echo "false";
    }
    
?>