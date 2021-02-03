<?php
    require "./database_connect.php";
    
    // Si me llego algo:
    if(isset($_GET["usuario"]))
    {
        $usuario = strip_tags($_GET["usuario"]);

        if(mysqli_query($con, 'UPDATE `usuarios` SET `activacion`=1 WHERE `usuario`="'.mysqli_real_escape_string($con, $usuario).'"'))
        {
            echo "<script type='text/javascript'>alert('Se activo la cuenta correctamente');</script>";
            echo "<script type='text/javascript'>window.location.href = '../pages/examples/my_login-v2.php';</script>";
        }
    }

?>