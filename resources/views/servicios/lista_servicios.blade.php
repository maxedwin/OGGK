@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Lista Servicios</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
    <li>Inventario</li>
    <li class="active">Lista de Servicios</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="servicio_nuevo" target="_blank" id="nuevo_producto">
            <i class="icon-box-add position-left"></i>
            Nuevo Servicio
        </a>

    </li>
@stop

<!-- CONTENIDO DE LA PAGINA -->

@section('contenido')
    <?PHP
    header("Access-Control-Allow-Origin:*");
    ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ URL::to('/') }}">
    <style type="text/css">
        .imgbtn
        {
            border:none;
            background-color: #fff;
            border: dashed  #d2d2d2 1px;
            transition-duration: 0.4s;
            margin: 10px 5px !important;
            text-decoration: none;
        }
        .imgLoad {
            border:none;
            background-color: #fff;

        }


    </style>

    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/toolbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/parsers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>

    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="heading-elements">



            </div>

        </div>

        <div class="panel-body">
            <form class="form-inline">
                <div class="form-group">
                    <input type="text" id="busqueda_nombre" class="form-control input-lg" id="formGroupExampleInput" placeholder="Nombre del Producto">
                    <button type="button" id="btnbusqueda" class="btn btn-primary"><i class="icon-search4 position-left"></i> Bucar Nombre</button>
                </div>
                <div class="form-group">
                    <input type="text" id="busqueda_barcode" class="form-control input-lg" id="formGroupExampleInput" placeholder="BARCODE">
                    <button type="button" id="btnbusqueda" class="btn btn-primary"><i class="icon-search4 position-left"></i> Bucar BARCODE</button>
                </div>
            </form>
            <div class="text-right">
                {{ $products->links() }}
            </div>
            <!--LISTA DE PRODUCTOS -->
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" id="products_table">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Costo Unitario</th>
                    <th>Precio Unitario</th>
                    <th>Ubicacion</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="products">
                @foreach ($products as $product)
                    <tr>
                        <?PHP $img = json_decode($product->imagenes); ?>

                        <td><b>{{$product->nombre}}</b></td>
                        <td>S/. {{ round($product->costo,2)}}</td>
                        <td>S/. {{ round($product->precio,2)}}</td>
                        <td>{{$product->ubicacion}}</td>
                        <?PHP  if($product->state == 1) {
                            echo '<td><button id="status" class="btn btn-success" data-idproducto="'.$product->idproducto.'" data-status="0"><i class="glyphicon glyphicon-star"></i></button></td>';
                        }else{
                            echo '<td><button id="status" class="btn btn-default" data-idproducto="'.$product->idproducto.'"  data-status="1"><i class="glyphicon glyphicon-star-empty"></i></button></td>';
                        }  ?>
                        <td>
                            <button type="button" class="btn btn-info btn-xs" id="editar"
                                    data-idproducto="{{$product->idproducto}}">
                                <i class="icon-pen6 position-center"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" data-idproducto="{{$product->idproducto}}"  id="eliminar">
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="text-right">
                {{ $products->links() }}
            </div>
        </div>

    </div>
    <script type="text/javascript" src="{{ asset('javascript/servicios.js') }}"></script>


@stop
