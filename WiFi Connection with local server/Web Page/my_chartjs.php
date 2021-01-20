<?php
  //This line will make the page auto-refresh each 15 seconds
  $page = $_SERVER['PHP_SELF'];
  $sec = "15";
  include("../../../php/database_connect.php"); //We include the database_connect.php which has the data for the connection to the database

  // Check the connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>


<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--//I've used bootstrap for the tables, so I inport the CSS files for taht as well...-->
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">	
    <title>Graficos / Temperatura</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- PRUEBA PARA VER SI SE PODIA INSERTAR UN GRAFICO DE HIGHCHARTS EN LA PAGINA -->
    <!-- PARA EL GRAFICO DE HIGHCHARTS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  </head>

  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="../../index.php" class="nav-link">Inicio</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contacto</a>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Navbar Search -->
          <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
              <form class="form-inline">
                <div class="input-group input-group-sm">
                  <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li>

          
          <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
              <i class="fas fa-th-large"></i>
            </a>
          </li>
        </ul>
      </nav><!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="https://github.com/OtroCuliau/InfraRed_Remote_Controller_and_Temperature_Measurements_with_Arduino" class="brand-link">
              <img src="../../dist/img/SyFLogo.png" alt="SyF Logo" class="brand-image img-circle elevation-3" style="opacity: .99">
              <span class="brand-text font-weight-light">Proyecto Santi y Fer</span>
            </a>
            
            <!-- Sidebar -->
            <div class="sidebar">
              <!-- Sidebar Menu -->
              <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                  <li class="nav-header">Accesos útiles</li>
                  
                  <!-- Dashboards -->
                  <li class="nav-item menu-open">
                      <a href="#" class="nav-link active">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p>
                              Panel de control
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="../../index.php" class="nav-link active">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Pantalla de inicio</p>
                              </a>
                          </li>
                      </ul>
                  </li><!-- /.dashboards -->

                  <!-- Graficos -->
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fas fa-chart-pie"></i>
                          <p>
                              Gráficos
                              <i class="right fas fa-angle-left"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Temperatura</p>
                              </a>
                          </li>
                      </ul>
                  </li><!-- /.graficos -->

                  <!-- Enviar datos -->
                  <li class="nav-item">
                    <a href="../UI/my_buttons.php" class="nav-link">
                      <i class="nav-icon fas fa-sign-out-alt"></i>
                      <p>
                          Enviar datos
                      </p>
                    </a>
                  </li><!-- ./enviar-datos -->

                  <!-- Datos Recibidos -->
                  <li class="nav-item">
                    <a href="../my_recibidos.php" class="nav-link">
                      <i class="nav-icon fas fa-sign-in-alt"></i>
                      <p>
                        Datos recibidos
                      </p>
                    </a>
                  </li><!-- ./datos-recibidos -->

                </ul>
              </nav><!-- /.sidebar-menu -->
            </div><!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Temperatura</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                  <li class="breadcrumb-item active">Temperatura</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-6">
                  <!-- Temperatura -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Temperatura</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="chart">
                        <canvas id="Temperatura" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      </div>
                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
              </div><!-- /.col (LEFT) -->

              <div class="col-lg-12 col-6">
                  <!-- Humedad -->
                  <div class="card card-success">
                    <div class="card-header">
                      <h3 class="card-title">Humedad</h3>

                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                          <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-body">
                    <div class="chart">
                      <canvas id="Humedad" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
                </div><!-- /.col (LEFT) -->

            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <!-- Page Footer -->
      <footer class="main-footer">
        <strong>Copyright &copy; 2020-2021 <a href="https://github.com/OtroCuliau/InfraRed_Remote_Controller_and_Temperature_Measurements_with_Arduino">Git-Hub page</a>.</strong>
        Todos los derechos reservados.
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 0.0.1-rc
        </div>
      </footer>
      <!-- Page Footer -->

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Add Content Here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../../plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>

      $(function () {

        /* ChartJS
        * -------
        * Here we will create a few charts using ChartJS
        */

        //--------------
        //- AREA CHART -
        //--------------

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#Temperatura').get(0).getContext('2d')

        var areaChartData = {
          labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
          datasets: [
            {
              label               : 'Temperatura',
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : [

                                      <?php 
                                        require_once('../../../php/func_temp.php');
                                        temperatura_diaria("","0","","","temperatura",$con);
                                      ?>
                                    ]
            }
            // Para meter otro trazo sobre el mismo grafico
            // ,
            // {
            //   label               : 'Electronics',
            //   backgroundColor     : 'rgba(210, 214, 222, 1)',
            //   borderColor         : 'rgba(210, 214, 222, 1)',
            //   pointRadius         : false,
            //   pointColor          : 'rgba(210, 214, 222, 1)',
            //   pointStrokeColor    : '#c1c7d1',
            //   pointHighlightFill  : '#fff',
            //   pointHighlightStroke: 'rgba(220,220,220,1)',
            //   data                : [65, 59, 800, 81, 56, 55, 400]
            // },
          ]
        }

        var areaChartOptions = {
          maintainAspectRatio : false,
          responsive : true,
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              gridLines : {
                display : false,
              }
            }],
            yAxes: [{
              gridLines : {
                display : true,
              }
            }]
          }
        }

        // This will get the first returned node in the jQuery collection.
        // Las siguientes lineas hacen que sea un grafico de lineas en vez de area.
        areaChartData.datasets[0].fill = false;
        // Para tener dos lineas descomentar la de abajo
        // areaChartData.datasets[1].fill = false;
        new Chart(areaChartCanvas, {
          type: 'line',
          data: areaChartData,
          options: areaChartOptions
        })

        //-------------
        //- LINE CHART - Es una mentira, es el grafico de area pero sin rellenar
        //--------------
        var lineChartCanvas = $('#Humedad').get(0).getContext('2d')
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        // var lineChartData = $.extend(true, {}, areaChartData)
        var lineChartData = {
          labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
          datasets: [
            {
              label               : 'Humedad',
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : [

                                      <?php 
                                        require_once('../../../php/func_temp.php');
                                        temperatura_diaria("","0","","","humedad",$con);
                                      ?>
                                    ]
            }
            // Para meter otro trazo sobre el mismo grafico
            // ,
            // {
            //   label               : 'Electronics',
            //   backgroundColor     : 'rgba(210, 214, 222, 1)',
            //   borderColor         : 'rgba(210, 214, 222, 1)',
            //   pointRadius         : false,
            //   pointColor          : 'rgba(210, 214, 222, 1)',
            //   pointStrokeColor    : '#c1c7d1',
            //   pointHighlightFill  : '#fff',
            //   pointHighlightStroke: 'rgba(220,220,220,1)',
            //   data                : [65, 59, 800, 81, 56, 55, 400]
            // },
          ]
        }
        
        lineChartData.datasets[0].fill = false;
        // lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false

        var lineChart = new Chart(lineChartCanvas, {
          type: 'line',
          data: lineChartData,
          options: lineChartOptions
        })

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData        = {
          labels: [
              'Chrome',
              'IE',
              'FireFox',
              'Safari',
              'Opera',
              'Navigator',
          ],
          datasets: [
            {
              data: [700,500,400,600,300,100],
              backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }
          ]
        }
        var donutOptions     = {
          maintainAspectRatio : false,
          responsive : true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(donutChartCanvas, {
          type: 'doughnut',
          data: donutData,
          options: donutOptions
        })

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = donutData;
        var pieOptions     = {
          maintainAspectRatio : false,
          responsive : true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
          type: 'pie',
          data: pieData,
          options: pieOptions
        })

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        new Chart(barChartCanvas, {
          type: 'bar',
          data: barChartData,
          options: barChartOptions
        })

        //---------------------
        //- STACKED BAR CHART -
        //---------------------
        var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
        var stackedBarChartData = $.extend(true, {}, barChartData)

        var stackedBarChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          scales: {
            xAxes: [{
              stacked: true,
            }],
            yAxes: [{
              stacked: true
            }]
          }
        }

        new Chart(stackedBarChartCanvas, {
          type: 'bar',
          data: stackedBarChartData,
          options: stackedBarChartOptions
        })
      })
    </script>
       
  </body>
</html>
