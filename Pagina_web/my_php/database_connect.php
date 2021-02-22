<?php
    // DESCOMENTAR PARA USAR LA BD DE 000webhost (obviamente comentar la linea de abajo)
    // $con=mysqli_connect("localhost","id15900605_santiyfer","_<F}(%^\Mn+L3}za","id15900605_esp8266");// server, user, password, database

    // DESCOMENTAR PARA USAR LA BD DE remotemysql (obviamente comentar la linea de abajo)
    $con=mysqli_connect("remotemysql.com","C4gd1lgeA2","mUngxZTVJj","C4gd1lgeA2");// server, user, password, database   

    // DESCOMENTAR PARA USAR LA BD LOCAL (XAMPP) 
    // $con=mysqli_connect("localhost","root","","id15900605_esp8266");// server, user, password, database

    // Check the connection
    if (mysqli_connect_errno()) 
    {
        die("Failed to connect to MySQL: " . mysqli_connect_error());
    }

?>
