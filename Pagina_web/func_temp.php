<?php

    function temperatura_diaria ($serie, $intervalo="0", $mes="", $dia="",$variable, $conectar)
    {
        // $serie  = N° de serie del dispositivo que quiero consultar
        // $mes    = Fecha del mes que quiero consultar
        // $dia    = Fecha del dia que quiero consultar
        // $intervalo Se usa esta variable para no graficar tooooodos los datos (se saltea el nro de 'intervalos' de datos para graficar menos ptos)

        // $ano = date("Y");   // Consulto siempre sobre el año actual

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
            // Para pedir los datos de una fecha en especifico (se usa la convencion de $dia=" " para pedir los datos diarios)
            if($dia != " " && $dia != "")
            {
                $desde = mysqli_query($conectar, "SELECT `fecha_desde` FROM `ESPtable2`"); // En caso de haber muchos dispositivos agregar WHERE unit_id = numeroDisp.
                $hasta = mysqli_query($conectar, "SELECT `fecha_hasta` FROM `ESPtable2`");
                $desde = mysqli_fetch_array($desde)[0];
                $hasta = mysqli_fetch_array($hasta)[0];
                $fecha_desde = date("U", strtotime($desde));    // Hay que castearlo a tipo tiempo
                $fecha_hasta = date("U", strtotime($hasta));
                // Por alguna razon, cuando lo convierte le resta un dia (ej. si la $desde es el 27 cuando pasa a unix es 26)
                // Por lo tanto le sumo un dia harcodeado:
                $fecha_desde = $fecha_desde + (24*60*60);
                $fecha_hasta = $fecha_hasta + (24*60*60);

                $resultado = mysqli_query($conectar, "SELECT `fecha`, `$variable` FROM `datos` WHERE `fecha` >= '$fecha_desde' AND `fecha` <= '$fecha_hasta' " );
                while ($row = mysqli_fetch_array($resultado))
                {
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
                if($dia == " " && $mes == "")
                {
                    // A la fecha UNIX actual le resto el equivalente en segundos de un dia entero.
                    $dia_anterior = date("U") - 60*60*24;
                    $resultado = mysqli_query($conectar, "SELECT `fecha`, `$variable` FROM `datos` WHERE `fecha` >= '$dia_anterior' " );

                    while ($row = mysqli_fetch_array($resultado))
                    {
                        echo "[".($row["fecha"]*1000 - 10800000).",".$row["$variable"]."],"; 
                        
                        for ($x=0; $x<$intervalo; $x++)
                        {
                            $row = mysqli_fetch_array($resultado);
                        }
                    }
                }
                // Si no es ninguno de los casos anteriores se desea obtener los datos de los ultimos 30 dias seria que $mes = algo
                else
                {
                    // A la fecha UNIX actual le resto el equivalente en segundos de 30 dias.
                    $dia_anterior = date("U") - 60*60*24*30;
                    $resultado = mysqli_query($conectar, "SELECT `fecha`, `$variable` FROM `datos` WHERE `fecha` >= '$dia_anterior' " );

                    while ($row = mysqli_fetch_array($resultado))
                    {
                        echo "[".($row["fecha"]*1000 - 10800000).",".$row["$variable"]."],"; 
                        
                        for ($x=0; $x<$intervalo; $x++)
                        {
                            $row = mysqli_fetch_array($resultado);
                        }
                    }
                }
            }

        }
    }


    function get_fecha($conectar, $inicio="inicio")
    {
        $fecha = date_create();

        if($inicio === "inicio")
        {
            // Devuelve la fecha del primer elemento que se subio a la BD (se convierte de UNIX a TIMESTAMP)
            $resultado = mysqli_query($conectar, "SELECT `fecha` FROM `datos`" );
            $row = mysqli_fetch_array($resultado);
            date_timestamp_set($fecha, $row["fecha"]);
            echo date_format($fecha, 'Y-m-d'); //Formato en ymd para que sea apto para la parte de solicitar informe
        }
        else
        {
            // Devuelve la fecha del ultimo elemento que se subio a la BD (se convierte de UNIX a TIMESTAMP)
            $resultado = mysqli_query($conectar, "SELECT `fecha` FROM `datos` ORDER BY `fecha` DESC LIMIT 1" );
            $row = mysqli_fetch_array($resultado);
            date_timestamp_set($fecha, $row["fecha"]);
            echo date_format($fecha, 'Y-m-d');
        }

    }



?>