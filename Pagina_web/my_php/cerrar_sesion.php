<?php
    // Se cierra la sesion del usuario y se vuelve al inicio
    session_start();
    session_destroy();

    header("Location: ../index.php");
?>