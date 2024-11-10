<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soluciones OGGK</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{asset('/assets/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('/assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->


    <!-- Theme JS files -->
    <script type="text/javascript" src="{{asset('/assets/js/core/app.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body class="login-container">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <!-- <a class="navbar-brand" href="/"><img src="assets/images/logo_light.png" alt=""></a> -->
        <a class="navbar-brand" href="/" >Soluciones OGGK</a>

        <!--<ul class="nav navbar-nav pull-right visible-xs-block hide">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>-->
    </div>

    <!--<div class="navbar-collapse collapse hide" id="navbar-mobile">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="#">
                <i class="icon-display4"></i> <span class="visible-xs-inline-block position-right">  </span>
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="icon-user-tie"></i> <span class="visible-xs-inline-block position-right"> </span>
                </a>
            </li>

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-cog3"></i>
                    <span class="visible-xs-inline-block position-right"> Opciones</span>
                </a>
            </li>
        </ul>
    </div>-->
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">

                @yield('contenido')

            </div>
                    <!-- Footer -->
                    <div class="footer text-muted text-center">
                        &copy; 2020 Todos los derechos reservados.<a href="https://solucionesoggk.com/contactenos.html"> Soluciones OGGK - LimitLess</a> 
                    </div>
                    <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>
