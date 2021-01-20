<?php

function temperatura_diaria ($serie, $intervalo, $mes, $dia,$variable, $conectar)
    {
        // $serie  = "";   // N° de serie del dispositivo que quiero consultar
        // $mes    = "";   // Fecha del dia que quiero consultar la temperatura
        // $dia    = "";
        // $intervalo = 0; // Se usa esta variable para no graficar tooooodos los datos (se saltea el nro de 'intervalos' de datos para graficar menos ptos)

        $ano = date("Y");   // Consulto siempre sobre el año actual

        // Esto funciona en tanto y cuanto tenga los datos cargados de esta forma en la BD, si no hay que hacer un query distinto
        // $resultado = mysqli_query($conectar, "SELECT UNIX_TIMESTAMP(`fecha`), temperatura FROM datos WHERE year(`fecha`) = '$ano' 
        //                                     AND month(`fecha`) = '$mes' AND day(`fecha`) = '$dia' AND `serie`='$serie'" );

        // Para pruebas:
        $resultado = mysqli_query($conectar, "SELECT `$variable` FROM `datos`" );

        
        while ($row = mysqli_fetch_array($resultado))
        {
            // Se realiza el formato que requiere highcharts (los corchetes y eso)
            
            echo ($row["$variable"].",");

            for ($x=0; $x<$intervalo; $x++)
            {
                $row = mysqli_fetch_array($resultado);
            }
        }
        
        // Si se quiere llamar varias veces la funcion con distintos parametros 
        // mysqli_close($conectar);
    }
?>