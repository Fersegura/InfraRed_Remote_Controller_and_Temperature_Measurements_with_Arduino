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
                              <a href="./my_chartjs.php" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Informe diario</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Informe mensual</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="./historico.php" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Informe histórico</p>
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
                <h1>Temperatura y humedad mensual</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                  <li class="breadcrumb-item active">Temperatura y humedad mensual</li>
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
                      <h3 class="card-title">Progreso de la temperatura en los últimos 30 días</h3>

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

                    <figure class="highcharts-figure">
                        <div id="temperatura"></div>
                        <p class="highcharts-description">
                            Temperatura sensada en los últimos 30 días.
                        </p>
                    </figure>

                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
              </div><!-- /.col (LEFT) -->

              <div class="col-lg-12 col-6">
                  <!-- Humedad -->
                  <div class="card card-success">
                    <div class="card-header">
                      <h3 class="card-title">Progreso de la humedad en los últimos 30 días</h3>

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

                    <figure class="highcharts-figure">
                        <div id="humedad"></div>
                        <p class="highcharts-description">
                            Humedad sensada en los últimos 30 días.
                        </p>
                    </figure>

                    </div><!-- /.card-body -->
                  </div><!-- /.card -->
                </div><!-- /.col (LEFT) -->

            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <br></br>
      <br></br>
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


    <!-- Page specific script -->
    <script type="text/javascript">
      Highcharts.chart('temperatura', {
        chart: {
            type: 'spline',
            scrollablePlotArea: {
                minWidth: 600,
                scrollPositionX: 1
            }
        },
        title: {
            text: 'Temperatura mensual',
            align: 'center'
        },
        subtitle: {
            text: 'Ubicacion: Pieza del Fer. Fecha: <?php require_once("../../../php/func_temp.php"); 
                                                      get_fecha($con);?>',
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
                text: 'Grados [°C]'
            },
            minorGridLineWidth: 0,
            gridLineWidth: 1,
            alternateGridColor: null,
            plotBands: [{ // Falta agua
                from: 15,
                to: 25,
                color: 'rgba(0, 0, 0, 0)',
                label: {
                    text: 'Hace frio',
                    style: {
                        color: '#606060'
                    }
                }
            }, { // Nivel optimo de agua
                from: 25,
                to: 35,
                color: 'rgba(68, 170, 213, 0.1)',
                label: {
                    text: 'Temperatura ideal',
                    style: {
                        color: '#606060'
                    }
                }
            }, { // Nivel optimo de agua
                from: 35,
                to: 50,
                color: 'rgba(0, 0, 0, 0)',
                label: {
                    text: 'Hace calor',
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
                      require_once('../../../php/func_temp.php');
                      temperatura_diaria("","0"," "," ","temperatura",$con);    // Notar que $dia y $mes son un espacio para tener datos mensuales
                    ?>
            ]

        }],
        navigation: {
            menuItemStyle: {
                fontSize: '10px'
            }
        }
      });

      Highcharts.chart('humedad', {
        chart: {
            type: 'spline',
            scrollablePlotArea: {
                minWidth: 600,
                scrollPositionX: 1
            }
        },
        title: {
            text: 'Humedad mensual',
            align: 'center'
        },
        subtitle: {
            text: 'Ubicacion: pieza del Fer. Fecha: <?php require_once("../../../php/func_temp.php"); 
                                                      get_fecha($con);?>',
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
                text: 'Porcentaje de humedad [%]'
            },
            minorGridLineWidth: 0,
            gridLineWidth: 1,
            alternateGridColor: null,
            plotBands: [{ // Poca humedad
                from: 0,
                to: 65,
                color: 'rgba(68, 170, 213, 0.1)',
                label: {
                    text: 'Poca humedad',
                    style: {
                        color: '#606060'
                    }
                }
            }, { // Mucha humedad
                from: 65,
                to: 100,
                color: 'rgba(0, 0, 0, 0)',
                label: {
                    text: 'Mucha humedad',
                    style: {
                        color: '#606060'
                    }
                }
            }]
        },
        tooltip: {
            valueSuffix: '% de humedad'
        },
        plotOptions: {
            spline: {
                lineWidth: 4,
                lineColor: '#000000',
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
            name: 'Humedad',
            data: [
                    <?php 
                      require_once('../../../php/func_temp.php');
                      temperatura_diaria("","0"," "," ","humedad",$con); // Notar que $dia y $mes son un espacio para tener datos mensuales
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
       
  </body>
</html>
