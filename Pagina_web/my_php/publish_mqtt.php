
<?php

    function publicar ($topico,$serial)
    {   
        require('./phpMQTT.php');

        $server = 'ioticos.org';            // Servidor del brokerMQTT
        $port = 1883;                       // Puerto que usa el broker
        $username = 'pdmlO2qrY6s8h7y';      // Usuario
        $password = 'm1bGUlqz27SMsmX';      // Contraseña
        $client_id = uniqid('phpMQTT-publisher');   // Cliente único - se puede usar uniqid()
        $topico = 'KMb6809yr8FThW1/'.$topico;   // Se crea el topico que necesita iotico con el topico raiz

        $mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

        if ($mqtt->connect(true, NULL, $username, $password)) {
            // Si la conexion es exitosa, se publica y luego se cierra la conexion
            $mqtt->publish($topico, $serial,$qos = 0,$retain = false);
            $mqtt->close();
        } else {
            echo "Time out!\n";
        }
        return;
    }

?>