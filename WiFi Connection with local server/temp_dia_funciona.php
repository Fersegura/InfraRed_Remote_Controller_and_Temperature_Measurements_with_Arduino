<!-- Video tutorial de este codigo: https://www.youtube.com/watch?v=V-K5nJGed2s&list=PLVnDMG-Nwzxl_5B65dlJsTKOa8xoIKqBm&index=8 -->

<?php

    $serie = "111"; // N° de serie del dispositivo que quiero consultar
    $mes = "01";    // Se usan estas variables para simular la fecha del dia de hoy 
    $dia = "09";
    $intervalo = 0; // Se usa esta variable para no graficar tooooodos los datos (se saltea el nro de 'intervalos' de datos para graficar menos ptos)
    require_once('conectar.php');
    $conectar = conectar();     // Me conecto a la BD
    $datos="" ;
    $ano = date("Y");

    // Esto funciona en tanto y cuanto tenga los datos cargados de esta forma en la BD, si no hay que hacer un query distinto
    // $resultado = mysqli_query($conectar, "SELECT UNIX_TIMESTAMP(`fecha`), temperatura FROM datos WHERE year(`fecha`) = '$ano' 
    //                                     AND month(`fecha`) = '$mes' AND day(`fecha`) = '$dia' AND `serie`='$serie'" );

    // Para pruebas:
    $resultado = mysqli_query($conectar, "SELECT UNIX_TIMESTAMP(`fecha`), temperatura FROM datos WHERE year(`fecha`) = '$ano' 
                                        AND month(`fecha`) = '$mes' AND day(`fecha`) = '$dia'" );

    // En un principio no estamos usando esta funcion para nada
    function temperatura_diaria ($serie, $intervalo, $mes, $dia)
    {
        require_once('conectar.php');
        $conectar = conectar();     // Me conecto a la BD
        $ano = date("Y");

        // Esto funciona en tanto y cuanto tenga los datos cargados de esta forma en la BD, si no hay que hacer un query distinto
        // $resultado = mysqli_query($conectar, "SELECT UNIX_TIMESTAMP(`fecha`), temperatura FROM datos WHERE year(`fecha`) = '$ano' 
        //                                     AND month(`fecha`) = '$mes' AND day(`fecha`) = '$dia' AND `serie`='$serie'" );

        // Para pruebas:
        $resultado = mysqli_query($conectar, "SELECT UNIX_TIMESTAMP(`fecha`), temperatura FROM datos WHERE year(`fecha`) = '$ano' 
                                            AND month(`fecha`) = '$mes' AND day(`fecha`) = '$dia'" );

        
        while ($row = mysqli_fetch_array($resultado))
        {
            $datos.="[".($row[0]*1000).",".($row[1])."],";
            // // // Se realiza el formato que requiere highcharts (los corchetes y eso)
            
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
                <?php                                               // Si lo ponemos crudo al codigo anda, si lo llamamos como funcion
                while ($row = mysqli_fetch_array($resultado))       // (como hace iotico) no se imprime ningun grafico
                {
                    echo"[".($row[0]*1000).",".($row[1])."],";
                    
                }
                ?>
            ]
        }
    ]
});
</script>
