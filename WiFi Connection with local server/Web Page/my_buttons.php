<?php
  // LO PRIMERO QUE HAGO ES CONECTARME A LA BD  
  include("D:\\xampp\\htdocs\\mis_pruebas\\php\\database_connect.php"); //We include the database_connect.php which has the data for the connection to the database

  // Check the connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
?>



<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">		
        <title>Botones Prueba NOOBIX</title>


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
        <!-- AdminLTE App -->
        <script src="../../dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../../dist/js/demo.js"></script>
    </head>

    <body class="hold-transition sidebar-mini">
        <!-- Navbar -->
        <div class="wrapper">
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="../../my_index.php" class="nav-link">Inicio</a>
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
                            <a href="../../my_index.php" class="nav-link active">
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
                            <a href="../charts/my_chartjs.php" class="nav-link">
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
                                <a href="#" class="nav-link">
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
                        <h1>Botones</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                        <li class="breadcrumb-item active">Botones</li>
                        </ol>
                    </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    <i class="fas fa-edit"></i>
                                    Botones para encender/apagar dispositivos 
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

                                    <!-- Aca entra el codigo .php que hace fetching a la BD para que los botones hagan algo -->
                                    <?php
                                        //Again, we grab the table out of the database, name is ESPtable2 in this case
                                        $result = mysqli_query($con,"SELECT * FROM `esptable2`");//table select

                                        //loop through the table and print the data into the table
                                        while($row = mysqli_fetch_array($result)) {
                                            
                                            $unit_id = $row['id'];
                                                                                        
                                            $column1 = "RECEIVED_BOOL1";
                                            $column2 = "RECEIVED_BOOL2";
                                            $column3 = "RECEIVED_BOOL3";
                                            $column4 = "RECEIVED_BOOL4";
                                            $column5 = "RECEIVED_BOOL5";
                                            
                                            $current_bool_1 = $row['RECEIVED_BOOL1'];
                                            $current_bool_2 = $row['RECEIVED_BOOL2'];
                                            $current_bool_3 = $row['RECEIVED_BOOL3'];
                                            $current_bool_4 = $row['RECEIVED_BOOL4'];
                                            $current_bool_5 = $row['RECEIVED_BOOL5'];
                                        
                                            if($current_bool_1 == 1){
                                            $inv_current_bool_1 = 0;
                                            $text_current_bool_1 = "ON";
                                            $color_current_bool_1 = "#6ed829";
                                            }
                                            else{
                                            $inv_current_bool_1 = 1;
                                            $text_current_bool_1 = "OFF";
                                            $color_current_bool_1 = "#e04141";
                                            }
                                            
                                            
                                            if($current_bool_2 == 1){
                                            $inv_current_bool_2 = 0;
                                            $text_current_bool_2 = "ON";
                                            $color_current_bool_2 = "#6ed829";
                                            }
                                            else{
                                            $inv_current_bool_2 = 1;
                                            $text_current_bool_2 = "OFF";
                                            $color_current_bool_2 = "#e04141";
                                            }
                                            
                                            
                                            if($current_bool_3 == 1){
                                            $inv_current_bool_3 = 0;
                                            $text_current_bool_3 = "ON";
                                            $color_current_bool_3 = "#6ed829";
                                            }
                                            else{
                                            $inv_current_bool_3 = 1;
                                            $text_current_bool_3 = "OFF";
                                            $color_current_bool_3 = "#e04141";
                                            }
                                            
                                            
                                            if($current_bool_4 == 1){
                                            $inv_current_bool_4 = 0;
                                            $text_current_bool_4 = "ON";
                                            $color_current_bool_4 = "#6ed829";
                                            }
                                            else{
                                            $inv_current_bool_4 = 1;
                                            $text_current_bool_4 = "OFF";
                                            $color_current_bool_4 = "#e04141";
                                            }
                                            
                                            
                                            if($current_bool_5 == 1){
                                            $inv_current_bool_5 = 0;
                                            $text_current_bool_5 = "ON";
                                            $color_current_bool_5 = "#6ed829";
                                            }
                                            else{
                                            $inv_current_bool_5 = 1;
                                            $text_current_bool_5 = "OFF";
                                            $color_current_bool_5 = "#e04141";
                                            }
                                        }

                                    ?>
                                    <!-- Tabla botones -->
                                    <table class='table' style='font-size: 20px;'>
                                        <thead>
                                            <tr>
                                            <th>Indicadores de encendido</th>	
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <tr class='active' style="font-size: 18px">
                                                <td>Numero del dispositivo</td>
                                                <td>Control encendido 1</td>
                                                <td>Control encendido 2 </td>
                                                <td>Control encendido 3 </td>
                                                <td>Control encendido 4</td>
                                                <td>Control encendido 5 </td>
                                            </tr>
                                            <tr class='success'>
                                                <td><?php echo $unit_id;?></td> <!-- .row['id']. -->
                                                <!-- El tamaño de los botones depende del tamaño de la letra -->
                                                <td>
                                                    <form action= "../../../php/my_update_values.php" method= 'post'>
                                                        <input type='hidden' name='value2' value=<?php echo $current_bool_1; ?>   size='15' >	
                                                        <input type='hidden' name='value' value=<?php echo $inv_current_bool_1; ?>  size='15' >	
                                                        <input type='hidden' name='unit' value=<?php echo $unit_id; ?> >
                                                        <input type='hidden' name='column' value=<?php echo $column1; ?> >
                                                        <input type= 'submit' class="btn btn-block btn-primary" name= 'change_but' style=' margin-top: 5%; font-size: 18px; text-align:center; background-color: <?php echo $color_current_bool_1; ?>' value=<?php echo $text_current_bool_1; ?>>
                                                    </form>
                                                </td>
                                            
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action= "../../../php/my_update_values.php" method= 'post'>
                                                        <input type='hidden' name='value2' value=<?php echo $current_bool_2; ?>   size='15' >	
                                                        <input type='hidden' name='value' value=<?php echo $inv_current_bool_2; ?>  size='15' >	
                                                        <input type='hidden' name='unit' value=<?php echo $unit_id; ?> >
                                                        <input type='hidden' name='column' value=<?php echo $column2; ?> >
                                                        <input type= 'submit' class="btn btn-block btn-primary" name= 'change_but' style=' margin-top: 5%; font-size: 18px; text-align:center; background-color:<?php echo $color_current_bool_2; ?>' value=<?php echo $text_current_bool_2; ?>>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action= "../../../php/my_update_values.php" method= 'post'>
                                                        <input type='hidden' name='value2' value=<?php echo $current_bool_3; ?>   size='15' >	
                                                        <input type='hidden' name='value' value=<?php echo $inv_current_bool_3; ?>  size='15' >	
                                                        <input type='hidden' name='unit' value=<?php echo $unit_id; ?> >
                                                        <input type='hidden' name='column' value=<?php echo $column3; ?> >
                                                        <input type= 'submit' class="btn btn-block btn-primary" name= 'change_but' style=' margin-top: 5%; font-size: 18px; text-align:center; background-color:<?php echo $color_current_bool_3; ?>' value=<?php echo $text_current_bool_3; ?>>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action= "../../../php/my_update_values.php" method= 'post'>
                                                        <input type='hidden' name='value2' value=<?php echo $current_bool_4; ?>   size='15' >	
                                                        <input type='hidden' name='value' value=<?php echo $inv_current_bool_4; ?>  size='15' >	
                                                        <input type='hidden' name='unit' value=<?php echo $unit_id; ?> >
                                                        <input type='hidden' name='column' value=<?php echo $column4; ?> >
                                                        <input type= 'submit' class="btn btn-block btn-primary" name= 'change_but' style=' margin-top: 5%; font-size: 18px; text-align:center; background-color:<?php echo $color_current_bool_4; ?>' value=<?php echo $text_current_bool_4; ?>>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action= "../../../php/my_update_values.php" method= 'post'>
                                                        <input type='hidden' name='value2' value=<?php echo $current_bool_5; ?>   size='15' >	
                                                        <input type='hidden' name='value' value=<?php echo $inv_current_bool_5; ?>  size='15' >	
                                                        <input type='hidden' name='unit' value=<?php echo $unit_id; ?> >
                                                        <input type='hidden' name='column' value=<?php echo $column5; ?> >
                                                        <input type= 'submit' class="btn btn-block btn-primary" name= 'change_but' style=' margin-top: 5%; font-size: 18px; text-align:center; background-color:<?php echo $color_current_bool_5; ?>' value=<?php echo $text_current_bool_5; ?>>
                                                    </form>
                                                </td>
                                                






                                            </tr>
                                        </tbody>
                                    </table><!-- ./tabla-botones-->

                                </div><!-- /.card-body -->
                            
                            </div><!-- /.card -->
                        </div><!-- /.col -->
                    </div><!-- ./row -->
                
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
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->


    </body>
</html>
