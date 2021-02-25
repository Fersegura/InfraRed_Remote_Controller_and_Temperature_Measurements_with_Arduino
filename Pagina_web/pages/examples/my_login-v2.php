<?php
    session_start();
    // Si ya esta la sesion abierta me vuelvo a la pagina desde la cual quise entrar
    // Cuando la sesion no estaba iniciada esto tira un error, para que no aparezca desactivar el eror_reporting (solo cuando ya esta todo verificado que funciona xq si no me anula todos los otros errores)
    error_reporting(0);
    if($_SESSION['logged'] == "yes")
    {
        echo "<script type='text/javascript'>alert('Ya ingreso con una cuenta!');</script>";
        echo "<script type='text/javascript'>window.location.href = '".$_SERVER['HTTP_REFERER']."';</script>";
        // header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ingresar</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
        <!-- Web Browser icon -->
        <link  rel="shortcut icon" href="../../dist/img/SyFLogo.ico" type="image/ico" />
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="https://github.com/OtroCuliau/InfraRed_Remote_Controller_and_Temperature_Measurements_with_Arduino" class="h1">
                        <b>Proyecto Santi</b>&<b>Fer</b>
                    </a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Ingresar para iniciar sesion</p>

                    <form action="../../my_php/login_validate.php" method="post">
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Usuario" name="usuario">
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-user"></span>
                            </div>
                        </div>
                        </div>
                        <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Recordarme
                            </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        </div>
                        <!-- /.col -->
                        </div>
                    </form>

                    <div class="social-auth-links text-center mt-2 mb-3">
                        <a href="#" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Ingresar usando Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> Ingresar usando Google+
                        </a>
                    </div>
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <!-- CAMBIAR EL ARCHIVO DE REDIRECCIONAMIENTO-->
                        <a href="forgot-password.html">Olvide mi contraseña</a> 
                    </p>
                    <p class="mb-0">
                        <a href="./my_register-v2.html" class="text-center">Registrar un usuario nuevo</a>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="../../plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../../dist/js/adminlte.min.js"></script>

    </body>

</html>
