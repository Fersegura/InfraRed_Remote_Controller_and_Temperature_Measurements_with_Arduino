<!-- Tutorial: https://www.youtube.com/watch?v=-hqvwn58N3I&list=PLVnDMG-Nwzxl_5B65dlJsTKOa8xoIKqBm&index=5 -->

<?php

    require_once ('conectar.php');
    $conectar = conectar();

    $serie = $_POST ['serie'];
    $temperatura = $_POST ['temp'];
    mysqli_query($conectar,"INSERT INTO datos (id, fecha, serie, temperatura) VALUES (null,CURRENT_TIMESTAMP,'$serie','$temperatura')");
    mysqli_close($conectar);
    echo "Datos Ingresados Correctamente";
?>