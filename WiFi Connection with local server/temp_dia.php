
<!-- Video tutorial de este codigo: https://www.youtube.com/watch?v=V-K5nJGed2s&list=PLVnDMG-Nwzxl_5B65dlJsTKOa8xoIKqBm&index=8 -->

<?php

    $serie = "777"; // N° de serie del dispositivo que quiero consultar
    $mes = "01";    // Se usan estas variables para simular la fecha del dia de hoy 
    $dia = "06";
    $intervalo = 0; // Se usa esta variable para no graficar tooooodos los datos (se saltea el nro de 'intervalos' de datos para graficar menos ptos)

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

        echo "[";
        while ($row = mysqli_fetch_array($resultado))
        {
            // Se realiza el formato que requiere highcharts (los corchetes y eso)
            echo "[";
            echo $row[0]*1000;  // Se multiplica para transformarlo a miliseg. xq el grafico de highcharts lo usa asi
            echo ",";
            echo $row[1];
            echo ",]";
            
            for ($x=0; $x<$intervalo; $x++)
            {
                $row = mysqli_fetch_array($resultado);
            }
        }
        echo "]";
        
        mysqli_close($conectar);
    }

?>

<!-- Incluimos las librerias que haran falta, se podrian descargar a nuestro propio servidor
     pero para hacer pruebas alcanza y sobra con usarlas desde los servers de highcharts  -->

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!-- Necesitamos esta libreria si o si para que ande y que no esta en la pag, highcharts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        Basic line chart showing trends in a dataset. This chart includes the
        <code>series-label</code> module, which adds a label to each line for
        enhanced readability.
    </p>
</figure>

<script type="text/javascript">
    Highcharts.chart('container', {

    title: {
        text: 'Evolucion de la temperatura en un periodo de tiempo'
    },

    subtitle: {
        text: ''
    },

    yAxis: {
        title: {
            text: 'Temperatura'
        }
    },

    xAxis: {
        accessibility: {
            rangeDescription: 'Fecha'
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Temperatura',                                      // Por alguna razon esto no anda cuando incluyo el codigo .php
        data: <?php temperatura_diaria("110", "0", "01", "06");   // Esto es lo que cambia, puedo usar codigo .php en medio del .html 
              ?>
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

    });
  
</script>
