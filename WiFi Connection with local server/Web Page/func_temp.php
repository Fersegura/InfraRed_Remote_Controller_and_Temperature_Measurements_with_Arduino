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
        
        // Si se llama para obtener datos historicos
        if($dia == "" && $mes == "")
        {
            $resultado = mysqli_query($conectar, "SELECT `fecha`, `$variable` FROM `datos`" );

        
            while ($row = mysqli_fetch_array($resultado))
            {
                // Se multiplica por 1000 porque highcharts se maneja en miliseg. y le resto 3 horas (3600*3*1000) 
                // para que este en mi zona horaria.
                // Se realiza el formato que requiere highcharts (los corchetes y eso)

                echo "[".($row["fecha"]*1000 - 10800000).",".$row["$variable"]."],"; 
                

                for ($x=0; $x<$intervalo; $x++)
                {
                    $row = mysqli_fetch_array($resultado);
                }
            }
        }
        else
        {
            // Si se llama para obtener datos diarios
            if($mes == "")
            {
                // A la fecha UNIX actual le resto el equivalente en segundos de un dia entero.
                $dia_anterior = date("U") - 60*60*24;
                $resultado = mysqli_query($conectar, "SELECT `fecha`, `$variable` FROM `datos` WHERE `fecha` >= '$dia_anterior' " );

                while ($row = mysqli_fetch_array($resultado))
                {
                    // Se multiplica por 1000 porque highcharts se maneja en miliseg. y le resto 3 horas (3600*3*1000) 
                    // para que este en mi zona horaria.
                    // Se realiza el formato que requiere highcharts (los corchetes y eso)
        
                    echo "[".($row["fecha"]*1000 - 10800000).",".$row["$variable"]."],"; 
                    
        
                    for ($x=0; $x<$intervalo; $x++)
                    {
                        $row = mysqli_fetch_array($resultado);
                    }
                }
            }
            // Si no es ninguno de los dos casos anteriores se desea obtener los datos de los ultimos 30 dias
            else
            {
                // A la fecha UNIX actual le resto el equivalente en segundos de 30 dias.
                $dia_anterior = date("U") - 60*60*24*30;
                $resultado = mysqli_query($conectar, "SELECT `fecha`, `$variable` FROM `datos` WHERE `fecha` >= '$dia_anterior' " );

                while ($row = mysqli_fetch_array($resultado))
                {
                    // Se multiplica por 1000 porque highcharts se maneja en miliseg. y le resto 3 horas (3600*3*1000) 
                    // para que este en mi zona horaria.
                    // Se realiza el formato que requiere highcharts (los corchetes y eso)
        
                    echo "[".($row["fecha"]*1000 - 10800000).",".$row["$variable"]."],"; 
                    
        
                    for ($x=0; $x<$intervalo; $x++)
                    {
                        $row = mysqli_fetch_array($resultado);
                    }
                }
            }
        }
    }


    function get_fecha($conectar)
    {
        // Super basica la funcion, devuelve la fecha del primer elemento que saca (se convierte de UNIX a TIMESTAMP)
        $resultado = mysqli_query($conectar, "SELECT `fecha` FROM `datos`" );
        $row = mysqli_fetch_array($resultado);
        $fecha = date_create();
        date_timestamp_set($fecha, $row["fecha"]);
        echo date_format($fecha, 'd-m-Y');
    }

?>