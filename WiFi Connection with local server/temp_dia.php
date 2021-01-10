<!-- Video tutorial de este codigo: https://www.youtube.com/watch?v=V-K5nJGed2s&list=PLVnDMG-Nwzxl_5B65dlJsTKOa8xoIKqBm&index=8 -->

<?php

    // IMPORTANTE: conectarse antes de entrar en la funcion (si no, no anda).
    require_once('conectar.php');
    $conectar = conectar();     // Me conecto a la BD

    
    function temperatura_diaria ($serie, $intervalo, $mes, $dia, $conectar)
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
        $resultado = mysqli_query($conectar, "SELECT UNIX_TIMESTAMP(`fecha`), temperatura FROM datos WHERE year(`fecha`) = '$ano' 
                                            AND month(`fecha`) = '$mes' AND day(`fecha`) = '$dia'" );

        
        while ($row = mysqli_fetch_array($resultado))
        {
            // Se realiza el formato que requiere highcharts (los corchetes y eso)
            
            echo"[".($row[0]*1000).",".($row[1])."],";

            for ($x=0; $x<$intervalo; $x++)
            {
                $row = mysqli_fetch_array($resultado);
            }
        }
        
        mysqli_close($conectar);
    }

?>

<!-- Incluimos las librerias que haran falta, se podrian descargar a nuestro propio servidor
     pero para hacer pruebas alcanza y sobra con usarlas desde los servers de highcharts  -->

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<!-- Necesitamos esta libreria si o si para que ande y que no esta en la pag, highcharts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        This chart shows how symbols and shapes can be used in charts.
        Highcharts includes several common symbol shapes, such as squares,
        circles and triangles, but it is also possible to add your own
        custom symbols. In this chart, custom weather symbols are used on
        data points to highlight that certain temperatures are warm while
        others are cold.
    </p>
</figure>

<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Temperatura'
    },
    subtitle: {
        text: 'Primer prueba que anda'
    },
    xAxis: {
        type: 'datetime'    // Este es el 'type' que usa iotico
    },
    yAxis: {
        title: {
            text: 'Temperatura'
        },
        labels: {
            formatter: function () {
                return this.value + '°';
            }
        }
    },
    tooltip: {
        crosshairs: true,
        shared: true
    },
    plotOptions: {
        spline: {
            marker: {
                radius: 4,
                lineColor: '#666666',
                lineWidth: 1
            }
        }
    },
    series: [{
        name: 'Temperatura',
        marker: {
            symbol: 'square'
        },
        data: 
            [
                <?php                                              
                temperatura_diaria("111", "0", "01","06", $conectar);
                ?>
            ]
        }
    ]
});
</script>