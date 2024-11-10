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
    <link href="{{ asset('/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/colors.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/toolbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/parsers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/lista.css') }}" />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script>
        $(window).load(function() {
        // Animate loader off screen
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>

    <!-- /core JS files -->


    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/pickers/color/spectrum.js') }}"></script>

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('/assets/js/core/app.js') }}"></script>
    <!-- /theme JS files -->
    <style type="text/css">
        .no-js #loader { display: none;  }
        .js #loader { display: block; position: absolute; left: 100px; top: 0; }
        .se-pre-con {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("/images/loader.gif") center no-repeat #fff;
        }
        i[title].confirm-alert::after {
            content: attr(title);
            position: absolute;
            padding: 3px 10px 5px;
            background-color: yellow;
            color: #333;
            left: 15px;
            top: -5px;
            border: solid 2px;
            border-radius: 5px;
            /*z-index: 10;*/
            font-size: 14px;
        }

    </style>

</head>

<body class="sidebar-xs has-detached-left">

    <div class="se-pre-con"></div>

    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
        <!-- <a class="navbar-brand" href="/"><img src="assets/images/logofinal.png" width="150" height="990" class="position-right"></a> -->
        <a class="navbar-brand position-right" href="/">Soluciones OGGK</a>

            <ul class="nav navbar-nav pull-right visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
                <!--<li><a class="sidebar-mobile-detached-toggle"><i class="icon-grid7"></i></a></li>-->
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav">
                <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <!-- <img src="assets/images/image.png" alt=""> -->
                        <?php 
                            $puestos = [
                                1 => 'GERENTE GENERAL',
                                2 => 'ADMINISTRADOR',
                                3 => 'ASISTENTE CONTABLE',
                                4 => 'COORDINADOR COMERCIAL',
                                5 => 'DESARROLLADOR TI',
                                6 => 'GESTOR LOGISTICO',
                                7 => 'ASISTENTE LOGISTICO',
                                8 => 'ASISTENTE DE REPARTO',
                                9 => 'ASISTENTE COMERCIAL',
                                10 => 'VENDEDOR',
                                11 => 'ASISTENTE DE RRHH',
                                12 => 'PUBLICIDAD',
                                13 => 'COBRANZA',
                                14 => 'ASISTENTE ADMINISTRATIVO',
                                15 => 'CONTADOR',
                                16 => 'AUDITOR',
                            ];
                        ?>

                        <span style="font-size: 20px;font-weight: bold;">({{ isset($puestos[Auth::user()->puesto]) ? $puestos[Auth::user()->puesto] : '--' }}) {{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <!-- <li><a href="#"><i class="icon-user-plus"></i> Mi Perfil</a></li>
                        <li><a href="#"><i class="icon-coins"></i> Mi Balance</a></li> 
                        <li><a href="#"><span class="badge badge-warning pull-right">58</span> <i class="icon-comment-discussion"></i> Mensajes</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li> -->
                        <li><a href="/asistencia_check"><i class="icon-calendar3"></i> Registrar asistencia</a></li>
                        <li><a href="/logout"><i class="icon-switch2"></i> Logout</a></li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->


    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            <div class="sidebar sidebar-main">
                <div class="sidebar-content">

                    <!-- User menu 
                    <div class="sidebar-user">
                        <div class="category-content">
                            <div class="media">
                                 <a href="#" class="media-left"><img src="assets/images/image.png" class="img-circle img-sm" alt=""></a>
                                <div class="media-body">
                                    <span class="media-heading text-semibold">Arequipa</span>
                                    <div class="text-size-mini text-muted">
                                        <i class="icon-pin text-size-small"></i> &nbsp;Arequipa, PE
                                    </div>
                                </div>

                                <div class="media-right media-middle">
                                    <ul class="icons-list">
                                        <li>
                                            <a href="#"><i class="icon-cog3"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                     /user menu -->


                    <!-- Main navigation -->
                    <div class="sidebar-category sidebar-category-visible">
                        <div class="category-content no-padding">
                            <ul class="navigation navigation-main navigation-accordion">
                                @if(Auth::user()->puesto!=15)
                                <!-- Main -->
                                <!--<li class="navigation-header"><span>Menú</span> <i class="icon-menu" title="Main pages"></i></li>-->
                                <li><a href="/"><i class="icon-home4"></i> <span>Página Principal</span></a></li>
                                
                                <li>
                                    <a href="#"><i class=" icon-users2"></i> <span>Clientes</span></a>
                                    <ul>
                                        <li><a href="list_clientes" ><i class="glyphicon glyphicon-list"></i>Listado de Clientes</a></li>
                                        <li><a href="list_clientes?i=1" ><i class="glyphicon glyphicon-list"></i>List. de Clientes Nuevos</a></li>
                                        <li><a href="list_clientes?i=2" ><i class="glyphicon glyphicon-list"></i>List. de Clientes Frecuentes</a></li>
                                        <li><a href="list_clientes?i=3" ><i class="glyphicon glyphicon-list"></i>List. de Clientes No Frecuentes</a></li>
                                        <li><a href="list_potenciales" ><i class="glyphicon glyphicon-list"></i>List. de Clientes Potenciales</a></li>
                                        <li><a href="list_clientes_tienda" ><i class="glyphicon glyphicon-list"></i>List. de Clientes Tienda</a></li>
                                        <li><a href="{{url('listado_deudores')}}" ><i class="glyphicon glyphicon-list"></i>Ranking deudores</a></li>
                                        <li><a href="{{url('listado_deudores/pending')}}" ><i class="glyphicon glyphicon-list"></i>Lista de deudores (pendientes)</a></li>
                                        <li><a href="listado_reclamos" ><i class="glyphicon glyphicon-book"></i>Listado de Reclamos</a></li>
                                        <li><a href="mapa_clientes" ><i class="glyphicon glyphicon-book"></i>Mapa de Clientes</a></li>


                                    </ul>
                                </li>
                                
                                <li><a href="list_proveedores"><i class=" glyphicon glyphicon-shopping-cart"></i> <span>Proveedores</span></a></li>
                                
                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-retweet"></i> <span>Inventario</span></a>
                                    <ul>
                                        <li><a href="#"><i class="icon-cube"></i>Productos / Lotes</a>
                                            <ul>
                                                <li><a href="list_product" >Listado de Productos</a></li>
                                                <li><a href="lista_lote" >Listado de Lotes</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="list_servicios"><i class="icon-wrench"></i>Servicios</a></li>
                                        <li><a href="list_categorias_uso"><i class="glyphicon glyphicon-list-alt"></i>Categorías</a></li>
                                        <li><a href="list_categorys"><i class="icon-list"></i>Familias</a></li>
                                        <li><a href="list_almacenes"><i class="icon-grid"></i>Almacenes</a></li>
                                        <li><a href="list_transportes"><i class="glyphicon glyphicon-send"></i>Transportes</a></li>
                                        <!--<li><a href="#"><i class="glyphicon glyphicon-refresh"></i>Movimientos de Stock</a>
                                            <ul>
                                                <li><a href="salidas" >Salidas</a></li>
                                                <li><a href="entradas" >Entradas</a></li>    
                                                <li><a href="movimientos" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>                                 
                                            </ul>
                                        </li>-->
                                    </ul>
                                </li>

                                <li><a href="#"><i class="icon-store"></i> <span>Tienda</span></a>
                                    <ul>    
                                        <li><a href="slides" ><i class="glyphicon glyphicon-picture"></i>Banners</a></li>
                                        <li><a href="list_catalogos_virtual" ><i class="glyphicon glyphicon-file"></i>Catálogos</a></li>
                                        <li><a href="list_tags" ><i class="glyphicon glyphicon-tag"></i>Tags</a></li>
                                        <li><a href="list_sendmethods" ><i class="glyphicon glyphicon-envelope"></i>Métodos de envío</a></li>
                                        <li><a href="list_paymethods" ><i class="glyphicon glyphicon-usd"></i>Métodos de pago</a></li>

                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-resize-full "></i> <span>Ventas</span></a>
                                    @if(Session::has('OVTienda') and Session::get('OVTienda'))
                                        <i class="glyphicon glyphicon-alert confirm-alert" style="position: absolute; font-size: 12px; color:yellow; right: 12px; top:5px;" title="Hay nuevas ORDENES DE VENTA de tienda"></i>
                                    @endif
                                    <ul>
                                        <li><a href="#"><i class=""></i>Cotización</a>
                                            <ul>
                                                <li><a href="cotizacion" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_cotizacion" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>                                    
                                            </ul>
                                        </li>
                                        <li><a href="#"><i class=""></i>Orden de Venta</a> 
                                            <ul>
                                                <li><a href="orden_venta" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_orden_venta" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                                <!--<li><a href="listado_orden_venta_detallado" ><i class="glyphicon glyphicon-list"></i>Listado Detallado</a></li>---->
                                                <li><a href="listado_orden_venta_tienda" ><i class="glyphicon glyphicon-list"></i>Listado Tienda</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#"><i class=""></i>Guía de Remisión</a> 
                                            <ul>
                                                <li><a href="guia_remision" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_guia_remision" ><i class="glyphicon glyphicon-list"></i>Listado Todo</a></li>
                                                <!--<li><a href="listado_guia_remision_detallado" ><i class="glyphicon glyphicon-list"></i>Listado Detallado</a></li>-->
                                                <li><a href="listado_GRpendientes" ><i class="glyphicon glyphicon-list"></i>Listado Pendientes</a></li>
                                            </ul>
                                        </li>
                                        <!--li><a href="#"><i class=""></i>Guía de Traslado</a> 
                                            <ul>
                                                <li><a href="guia_traslado" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_guia_traslado" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                            </ul>
                                        </li-->
                                        <li><a href="#"><i class=""></i>Factura / Boleta</a> 
                                            <ul>
                                                <li><a href="caja" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_caja" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>    
                                                <!--<li><a href="listado_caja_detallado" ><i class="glyphicon glyphicon-list"></i>Listado Detallado</a></li>-->
                                            </ul>
                                        </li>
                                        <li><a href="#"><i class=""></i>NC / Devoluciones</a> 
                                            <ul>
                                                <li><a href="nota_credito" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_nota_credito" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>    
                                                <!--<li><a href="listado_nota_credito_detallado" ><i class="glyphicon glyphicon-list"></i>Listado Detallado</a></li>-->
                                            </ul>
                                        </li>
                                        <li><a href="comunicacion_baja"><i class=""></i>Comunicaciones de Baja</a> </li>

                                        <li>
                                            <a href="#"><i class="glyphicon glyphicon-usd"></i> <span>Pagos Recibidos</span></a>
                                            <ul>
                                                <li><a href="listado_FTpendientes" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_pago_recibido" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-resize-small"></i> <span>Compras</span></a>
                                    <ul>
                                        <li><a href="#"><i class=""></i>Orden de Compra</a> 
                                            <ul>    
                                                <li><a href="orden_compra" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_orden_compra" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                                <!--<li><a href="listado_orden_compra_detallado" ><i class="glyphicon glyphicon-list"></i>Listado Detallado</a></li>-->
                                            </ul>
                                        </li>
                                        <li><a href="#"><i class=""></i>Ficha de Recepción (GC)</a>
                                            <ul>
                                                <li><a href="ficha_recepcion" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_ficha_recepcion" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                                <!--<li><a href="listado_ficha_recepcion_detallado" ><i class="glyphicon glyphicon-list"></i>Listado Detallado</a></li>-->
                                            </ul>
                                        </li>
                                        <!--<li><a href="#"><i class=""></i>Guía de Compra</a>
                                            <ul>
                                                <li><a href="guia_compra" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_guia_compra" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>                                    
                                            </ul>
                                        </li>-->
                                        <li><a href="#"><i class=""></i>Factura de Compra</a> 
                                            <ul>
                                                <li><a href="factura_compra" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_factura_compra" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                            </ul>
                                        </li>
                                        

                                        <li>
                                            <a href="#"><i class="glyphicon glyphicon-usd"></i> <span>Pagos Efectuados</span></a>
                                            <ul>
                                                <li><a href="listado_FCpendientes" ><i class="glyphicon glyphicon-pencil"></i>Crear</a></li>
                                                <li><a href="listado_pagos_efectuados" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>
                                            </ul>
                                        </li>

                                    </ul>
                                </li>

                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-signal"></i> <span>Reportes</span></a>
                                    <ul>
                                        <li><a href="ventas"><i class=""></i>Ventas</a> </li>
                                        <li><a href="inventario"><i class=""></i>Inventario</a></li>
                                        <li><a href="ventas_kardex"><i class=""></i>Kardex</a></li>
                                        <li><a href="facturas"><i class=""></i>Facturas Historial</a></li>
                                    </ul>
                                </li>
                                @if(Auth::user()->puesto==1 || Auth::user()->puesto==2 || Auth::user()->puesto==4  || Auth::user()->puesto==6 || Auth::user()->puesto==8 )
                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-road"></i> <span>Hoja de Ruta</span></a>
                                    <ul>
                                        <li><a href="hoja_ruta_admin"><i class=""></i>Administrar</a> </li>
                                        <li><a href="hoja_ruta_reporte"><i class=""></i>Reporte</a></li>
                                    </ul>
                                </li>
                                @endif
                                <li><a href="#"><i class="glyphicon glyphicon-asterisk"></i> <span>RRHH</span></a>
                                    <ul>    
                                        <li><a href="register" ><i class="glyphicon glyphicon-pencil"></i>Registrar Empleado</a></li>
                                        <li><a href="listado_usuarios" ><i class="glyphicon glyphicon-list"></i>Listado de Empleados</a></li> 
                                        <li><a href="{{route('lista-asistencia')}}" ><i class="glyphicon glyphicon-list"></i>Listado de Asistencia</a></li> 
                                        <li><a href="#" ><i class="glyphicon glyphicon-ok"></i>Evaluaciones</a>
                                            <ul>
                                                <li><a href="eval_proveedor"><i class=""></i>Eval. de Proveedores</a></li>
                                                <li><a href="eval_listado"><i class=""></i>Listado de Evaluaciones</a></li>
                                            </ul>
                                        </li>                                    
                                    </ul>
                                </li>
                                <li><a href="#">
                                    <i class="glyphicon glyphicon-briefcase"></i>
                                    @if(Session::has('porConfirmar') and Session::get('porConfirmar'))
                                    <i class="glyphicon glyphicon-alert confirm-alert" style="position: absolute; font-size: 12px; color:yellow; right: 12px; top:5px;" title="Hay nuevas ORDENES DE VENTA por confirmar"></i>
                                    @endif
                                    <span>Gerencia</span></a>
                                    <ul>    
                                        <li><a href="marcas" ><i class="glyphicon glyphicon-usd"></i>Eval. de Precios - Marcas</a></li>
                                        <li><a href="listado_orden_venta_por_confirmar" ><i class="glyphicon glyphicon-list"></i>Ordenes Venta por Confirmar</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="#"><i class="glyphicon glyphicon-check"></i> <span>Supervición</span></a>
                                    <ul>
                                        <li><a href="visitas_llamadas"><i class=""></i>Visitas/LLamadas</a> </li>
                                    </ul>
                                </li>

                                
                                <!-- /main -->
                            @else
                            <li>
                            <a href="#"><i class="glyphicon glyphicon-resize-full"></i> <span>Facturas/Boletas</span></a>
                                <ul>                                                
                                    <li><a href="listado_caja" ><i class="glyphicon glyphicon-list"></i>Listado</a></li> 
                                </ul>
                            </li>

                            <li><a href="#"><i class="glyphicon glyphicon-resize-small"></i> <span>NC / Devoluciones</span></a>
                                <ul>
                                    <li><a href="listado_nota_credito" ><i class="glyphicon glyphicon-list"></i>Listado</a></li>    
                                </ul>
                            </li>

                            <li><a href="#"><i class="glyphicon glyphicon-arrow-down"></i> <span>Comunicaciones de Baja</span></a>
                                <ul>
                                    <li><a href="comunicacion_baja"><i class="glyphicon glyphicon-list"></i>Listado</a> </li>
                                </ul>
                            </li>

                            @endif
                            </ul>
                        </div>
                    </div>
                    <!-- /main navigation -->

                </div>
            </div>
            <!-- /main sidebar -->


            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            @if (!Auth::guest())
                                @yield('titulo')
                            @endif

                        </div>

                        <div class="heading-elements">
                        </div>
                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            @if (!Auth::guest())
                                @yield('breadcrumb')
                            @endif

                        </ul>

                        <ul class="breadcrumb-elements">

                            @if (!Auth::guest())
                                @yield('menu')
                            @endif

                            <!-- <li><a href="#"><i class="icon-comment-discussion position-left"></i> Ayuda</a></li> -->

                        </ul>
                    </div>
                </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="content">

                    <!-- Detached content -->
                    <div class="container-detached">

                                @if (!Auth::guest())
                                    @yield('contenido')
                                @endif

                    </div>
                    <!-- /detached content -->


                    <!-- Footer -->
                    <div class="footer text-muted">
                        <div class="row">
                            <p class="pull-right">&copy; 2020 Todos los derechos reservados.<a href="https://solucionesoggk.com/contactenos.html"> Soluciones OGGK</a></p>
                        </div>
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
