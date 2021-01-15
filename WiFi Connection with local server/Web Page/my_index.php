<?php
    //This line will make the page auto-refresh each 15 seconds
    $page = $_SERVER['PHP_SELF'];
    $sec = "15";
    // LO PRIMERO QUE HAGO ES CONECTARME A LA BD  
    include("D:\\xampp\\htdocs\\mis_pruebas\\php\\database_connect.php"); //We include the database_connect.php which has the data for the connection to the database

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
                            <p>Temperatura</p>
                        </a>
                        </li>
                    </ul>
                </li><!-- /.graficos -->

                <!-- Botones -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Enviar datos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages/UI/my_buttons.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Botones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/sliders.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sliders</p>
                            </a>
                        </li>
                    </ul>
                </li><!-- ./botones -->

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
                  <li class="breadcrumb-item active">Pruebas NOOBIX</li>
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

              <div class="col-lg-6 col-3">  <!-- Esta linea es para darle el tamaño al grafico -->

                <!-- Grafico de temperatura-->
                <h5>Grafico 1</h5><br>
                <figure class="highcharts-figure">
                    <div id="container"></div>
                    <p class="highcharts-description">
                        Este texto demuestra la posibilidad de escribir un <b>epigrafe</b> de foto
                        totalmente editable.
                    </p>
                </figure>

                <script type="text/javascript">
                    Highcharts.chart('container', {

                    title: {
                        text: 'Variacion de la temperatura en funcion del tiempo'
                    },

                    subtitle: {
                        text: 'Fuente: ----'
                    },

                    yAxis: {
                        title: {
                            text: 'Temperatura'
                        }
                    },

                    xAxis: {
                        accessibility: {
                            rangeDescription: 'Tiempo'
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

                    // series: [{
                    //     name: 'Sensor 1 - Sahara.',
                    //     data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
                    // },{
                    //     name: 'Sensor 2 - Alaska.',
                    //     data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
                    // }],

                    series: [{
                      name: 'Santi R.',
                      data: [
                      <?php 
                        require_once('D:\\xampp\\htdocs\\mis_pruebas\\php\\func_temp.php');
                        temperatura_diaria("","0","","",$con);
                        
                      ?>
                    ]
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

              </div>
              <!-- /.col -->

              <div class="col-lg-6 col-3">
                <!-- Grafico de humedad-->
                <h5>Grafico 2</h5><br>

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
                          text: 'Wind speed during two days',
                          align: 'left'
                      },
                      subtitle: {
                          text: '13th & 14th of February, 2018 at two locations in Vik i Sogn, Norway',
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
                              text: 'Wind speed (m/s)'
                          },
                          minorGridLineWidth: 0,
                          gridLineWidth: 0,
                          alternateGridColor: null,
                          plotBands: [{ // Light air
                              from: 0.3,
                              to: 1.5,
                              color: 'rgba(68, 170, 213, 0.1)',
                              label: {
                                  text: 'Light air',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // Light breeze
                              from: 1.5,
                              to: 3.3,
                              color: 'rgba(0, 0, 0, 0)',
                              label: {
                                  text: 'Light breeze',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // Gentle breeze
                              from: 3.3,
                              to: 5.5,
                              color: 'rgba(68, 170, 213, 0.1)',
                              label: {
                                  text: 'Gentle breeze',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // Moderate breeze
                              from: 5.5,
                              to: 8,
                              color: 'rgba(0, 0, 0, 0)',
                              label: {
                                  text: 'Moderate breeze',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // Fresh breeze
                              from: 8,
                              to: 11,
                              color: 'rgba(68, 170, 213, 0.1)',
                              label: {
                                  text: 'Fresh breeze',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // Strong breeze
                              from: 11,
                              to: 14,
                              color: 'rgba(0, 0, 0, 0)',
                              label: {
                                  text: 'Strong breeze',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }, { // High wind
                              from: 14,
                              to: 15,
                              color: 'rgba(68, 170, 213, 0.1)',
                              label: {
                                  text: 'High wind',
                                  style: {
                                      color: '#606060'
                                  }
                              }
                          }]
                      },
                      tooltip: {
                          valueSuffix: ' m/s'
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
                              },
                              pointInterval: 3600000, // one hour
                              pointStart: Date.UTC(2018, 1, 13, 0, 0, 0)
                          }
                      },
                      series: [{
                          name: 'Hestavollane',
                          data: [
                              3.7, 3.3, 3.9, 5.1, 3.5, 3.8, 4.0, 5.0, 6.1, 3.7, 3.3, 6.4,
                              6.9, 6.0, 6.8, 4.4, 4.0, 3.8, 5.0, 4.9, 9.2, 9.6, 9.5, 6.3,
                              9.5, 10.8, 14.0, 11.5, 10.0, 10.2, 10.3, 9.4, 8.9, 10.6, 10.5, 11.1,
                              10.4, 10.7, 11.3, 10.2, 9.6, 10.2, 11.1, 10.8, 13.0, 12.5, 12.5, 11.3,
                              10.1
                          ]

                      }, {
                          name: 'Vik',
                          data: [
                              0.2, 0.1, 0.1, 0.1, 0.3, 0.2, 0.3, 0.1, 0.7, 0.3, 0.2, 0.2,
                              0.3, 0.1, 0.3, 0.4, 0.3, 0.2, 0.3, 0.2, 0.4, 0.0, 0.9, 0.3,
                              0.7, 1.1, 1.8, 1.2, 1.4, 1.2, 0.9, 0.8, 0.9, 0.2, 0.4, 1.2,
                              0.3, 2.3, 1.0, 0.7, 1.0, 0.8, 2.0, 1.2, 1.4, 3.7, 2.1, 2.0,
                              1.5
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
