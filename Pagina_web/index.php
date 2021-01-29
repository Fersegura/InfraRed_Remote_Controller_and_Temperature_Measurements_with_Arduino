<?php
    //This line will make the page auto-refresh each 15 seconds
    $page = $_SERVER['PHP_SELF'];
    $sec = "15";
    // LO PRIMERO QUE HAGO ES CONECTARME A LA BD  
    include("../php/database_connect.php"); //We include the database_connect.php which has the data for the connection to the database

    // Check the connection
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
?>

<!-- Por el momento solo le voy a agregar el coso de que se refresque cada $sec del index de NOOBIX, 
     Creo que no haran falta las otras librerias -->
     
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--//I've used bootstrap for the tables, so I inport the CSS files for taht as well...-->
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">		
    <title>Prueba NOOBIX</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
    <!-- Web Browser icon -->
    <link  rel="shortcut icon"   href="dist/img/SyFLogo.ico" type="image/ico" />


    <!-- Muevo estas librerias que antes estaban al fondo del archivo -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>

    <!-- PRUEBA PARA VER SI SE PODIA INSERTAR UN GRAFICO DE HIGHCHARTS EN LA PAGINA -->
    <!-- PARA EL GRAFICO DE HIGHCHARTS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  </head>


  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Inicio</a>
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
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="https://github.com/OtroCuliau/InfraRed_Remote_Controller_and_Temperature_Measurements_with_Arduino" class="brand-link">
          <img src="dist/img/SyFLogo.png" alt="SyF Logo" class="brand-image img-circle elevation-3" style="opacity: .99">
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
                        <a href="#" class="nav-link active">
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
                        <a href="pages/charts/my_chartjs.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Informe diario</p>
                        </a>
                        </li>
                        <li class="nav-item">
                              <a href="pages/charts/mensual.php" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Informe mensual</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="pages/charts/historico.php" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Informe histórico</p>
                              </a>
                          </li>
                    </ul>
                </li><!-- /.graficos -->

                <!-- Enviar datos -->
                <li class="nav-item">
                  <a href="pages/UI/my_buttons.php" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>
                        Enviar datos
                    </p>
                  </a>
                </li><!-- ./enviar-datos -->

                <!-- Datos Recibidos -->
                <li class="nav-item">
                    <a href="pages/my_recibidos.php" class="nav-link">
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
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Pagina principal</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active">Pagina principal</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
      
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12 col-6">
                <!-- Grafico de humedad-->
                <h5>Grafico ejemplo</h5><br>

                <figure class="highcharts-figure">
                    <div id="container2"></div>
                    <p class="highcharts-description">
                        Este texto demuestra la posibilidad de escribir un <b>epigrafe</b> de foto
                        totalmente editable.
                    </p>
                </figure>

                <script type="text/javascript">
                    Highcharts.chart('container2', {
                      chart: {
                          type: 'spline',
                          scrollablePlotArea: {
                              minWidth: 600,
                              scrollPositionX: 1
                          }
                      },
                      title: {
                          text: 'Temperatura y Humedad sensados',
                          align: 'center'
                      },
                      subtitle: {
                          text: 'Ubicacion: pieza del Fer',
                          align: 'left'
                      },
                      xAxis: {
                          type: 'datetime',
                          labels: {
                              overflow: 'justify'
                          }
                      },
                      yAxis: {
                          title: {
                              text: 'Grados [°] y Porcentaje humedad [%]'
                          },
                          minorGridLineWidth: 0,
                          gridLineWidth: 0,
                          alternateGridColor: null,
                          plotBands: [{ // Falta agua
                              from: 15,
                              to: 35,
                              color: 'rgba(68, 170, 213, 0.1)',
                              label: {
                                  text: 'Falta agua',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // Nivel optimo de agua
                              from: 35,
                              to: 50,
                              color: 'rgba(0, 0, 0, 0)',
                              label: {
                                  text: 'Nivel optimo de agua',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }]
                      },
                      tooltip: {
                          valueSuffix: ' Grados'
                      },
                      plotOptions: {
                          spline: {
                              lineWidth: 4,
                              states: {
                                  hover: {
                                      lineWidth: 5
                                  }
                              },
                              marker: {
                                  enabled: false
                              }
                              // ,
                              // pointInterval: 3600000, // one hour
                              // pointStart: Date.UTC(2018, 1, 13, 0, 0, 0)
                          }
                      },
                      series: [{
                          name: 'Temperatura',
                          data: [
                                  <?php 
                                    require_once('../php/func_temp.php');
                                    temperatura_diaria("","0","","","temperatura",$con);
                                  ?>
                          ]

                      }, {
                          name: 'Humedad',
                          data: [
                            <?php 
                              temperatura_diaria("","0","","","humedad",$con);
                            ?>
                          ]
                      }],
                      navigation: {
                          menuItemStyle: {
                              fontSize: '10px'
                          }
                      }
                  });
                  
                </script>

              </div>
              <!-- ./col -->

            </div>
            <!-- /.row -->  

            
              
            </div>

          </div>
          <!-- /.container-fluid -->

        </section>
        <!-- /.content -->

      </div>
      <!-- /.content-wrapper -->

      

      <!-- Page Footer -->
      <footer class="main-footer">
        <br></br>
        <strong>Copyright &copy; 2020-2021 <a href="https://github.com/OtroCuliau/InfraRed_Remote_Controller_and_Temperature_Measurements_with_Arduino">Git-Hub page</a>.</strong>
        Todos los derechos reservados.
        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 0.0.1-rc
        </div>
      </footer>
      <!-- Page Footer -->


      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


  </body>
</html>
