<!-- Funcion definida para conectarse a una base de datos (en este caso a 
     la de 'frigorifico') -->

<?php

    function conectar()
    {
        $conectar = mysqli_connect("localhost","root","","frigorifico");
        $conectar -> set_charset("utf8");
        if (mysqli_connect_errno())
        {
            echo "Fallo en la conexion a MySQL: " . mysqli_connect_error();
        }
        return $conectar;
    }

?>