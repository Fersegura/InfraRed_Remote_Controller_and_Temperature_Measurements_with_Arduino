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
        <title>Recibidos Prueba NOOBIX</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
        <!-- jQuery -->
        <script src="../plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../dist/js/demo.js"></script>

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
            <a href="../my_index.php" class="nav-link">Inicio</a>
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
          <img src="../dist/img/SyFLogo.png" alt="SyF Logo" class="brand-image img-circle elevation-3" style="opacity: .99">
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
                    <a href="../my_index.php" class="nav-link active">
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
                        <a href="charts/my_chartjs.php" class="nav-link">
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
                            <a href="UI/my_buttons.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Botones</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="UI/sliders.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sliders</p>
                            </a>
                        </li>
                    </ul>
                </li><!-- ./botones -->

                <!-- Datos Recibidos -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
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
                        <h1 class="m-0">Datos Recibidos</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../my_index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Datos Recibidos</li>
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
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title" style="font-size: 20px;">
                                    <i class="fas fa-edit"></i>
                                    Datos de los estados de los dispositivos 
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pad table-responsive">

                                    <!-- Aca entra el codigo .php que hace fetching a la BD -->
                                    <?php
                                        //Again, we grab the table out of the database, name is ESPtable2 in this case
                                        $result = mysqli_query($con,"SELECT * FROM `esptable2`");//table select

                                        //loop through the table and print the data into the table
                                        while($row = mysqli_fetch_array($result)) {
                                            
                                            $unit_id = $row['id'];

                                            $cur_sent_bool_1 = $row['SENT_BOOL_1'];
                                            $cur_sent_bool_2 = $row['SENT_BOOL_2'];
                                            $cur_sent_bool_3 = $row['SENT_BOOL_3'];
                                            
                                        
                                            if($cur_sent_bool_1 == 1){
                                            $label_sent_bool_1 = "btn-success";
                                            $text_sent_bool_1 = "Active";
                                            }
                                            else{
                                            $label_sent_bool_1 = "btn-danger";
                                            $text_sent_bool_1 = "Inactive";
                                            }
                                            
                                            
                                            if($cur_sent_bool_2 == 1){
                                            $label_sent_bool_2 = "btn-success";
                                            $text_sent_bool_2 = "Active";
                                            }
                                            else{
                                            $label_sent_bool_2 = "btn-danger";
                                            $text_sent_bool_2 = "Inactive";
                                            }
                                            
                                            
                                            if($cur_sent_bool_3 == 1){
                                            $label_sent_bool_3 = "btn-success";
                                            $text_sent_bool_3 = "Active";
                                            }
                                            else{
                                            $label_sent_bool_3 = "btn-danger";
                                            $text_sent_bool_3 = "Inactive";
                                            }
                                        }
                                    ?>

                                    <!-- Tabla indicadores booleanos -->
                                    <table class='table' style='font-size: 25px;'>
                                        <thead>
                                            <tr>
                                            <th>Indicadores de encendido</th>	
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <tr class='active' style="font-size: 18px">
                                                <td>Numero del dispositivo</td>
                                                <td>Indicador 1</td>
                                                <td>Indicador 2 </td>
                                                <td>Indicador 3 </td>
                                            </tr>
                                            <tr class='info'>
                                                <td><?php echo $unit_id;?></td> <!-- .row['id']. -->
                                                <!-- El tamaño de los botones depende del tamaño de la letra -->
                                                <td>
                                                <span class='btn <?php echo $label_sent_bool_1; ?>'> <?php echo $text_sent_bool_1; ?> </td>
                                                </span>

                                                <td>
                                                <span class='btn <?php echo $label_sent_bool_2; ?>'> <?php echo $text_sent_bool_2; ?> </td>
                                                </span>

                                                <td>
                                                <span class='btn <?php echo $label_sent_bool_3; ?>'> <?php echo $text_sent_bool_3; ?> </td>
                                                </span>
                                            </tr>
                                        </tbody>
                                    </table><!-- /.tabla-indicadores-booleanos -->

                                </div><!-- /.card-body -->
                            
                            </div><!-- /.card -->
                        </div><!-- /.col -->

                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title" style="font-size: 20px;">
                                    <i class="fas fa-edit" ></i>
                                    Datos sensados por los dispositivos 
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body pad table-responsive">

                                    <!-- Aca entra el codigo .php que hace fetching a la BD -->
                                    <?php
                                        //Again, we grab the table out of the database, name is ESPtable2 in this case
                                        $result = mysqli_query($con,"SELECT * FROM `esptable2`");//table select

                                        // Por el momento, como no hay mas de un dispositivo no hace falta el ciclo while
                                        $row = mysqli_fetch_array($result);
                                    ?>

                                    <!-- Tabla indicadores numericos -->
                                    <table class='table' style='font-size: 25px;'>
                                        <thead>
                                            <tr>
                                            <th>Valores sensados</th>	
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <tr class='active' style="font-size: 18px">
                                                <td>Numero del dispositivo</td>
                                                <td>Valor 1</td>
                                                <td>Valor 2 </td>
                                                <td>Valor 3 </td>
                                                <td>Valor 4 </td>
                                            </tr>
                                            <tr class='info'>
                                                <td><?php echo $unit_id;?></td> <!-- .row['id']. -->
                                                <!-- El tamaño de los botones depende del tamaño de la letra -->
                                                <td>
                                                    <?php echo $row['SENT_NUMBER_1']; ?>
                                                </td>
                                                
                                                <td>
                                                <?php echo $row['SENT_NUMBER_2']; ?>
                                                </td>

                                                <td>
                                                <?php echo $row['SENT_NUMBER_3']; ?>
                                                </td>

                                                <td>
                                                <?php echo $row['SENT_NUMBER_4']; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table><!-- /.tabla-indicadores-numericos -->

                                </div><!-- /.card-body -->
                            
                            </div><!-- /.card -->
                        </div><!-- /.col -->
                    </div><!-- ./row -->
                
                </div><!-- /.container-fluid -->
            </section><!-- /.content -->

      </div>
      <!-- /.content-wrapper -->
   

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
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


  </body>
</html>
