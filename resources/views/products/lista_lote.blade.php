@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-cube position-left"></i> <span class="text-semibold">Listado de Lotes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/list_product"></i>Listado de Productos</a></li>
    <li class="active">Listado de Lotes</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="producto_nuevo"  id="nuevo_producto">
            <i class="icon-box-add position-left"></i>
            Nuevo Producto
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
    
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/toolbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/parsers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <script>
        $(document).ready(function(){
            $("#input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#products tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>        

    

            <!--LISTA DE PRODUCTOS -->
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" id="products_table">
                <thead>
                <tr>
                    <th>Familia</th>
                    <th>SubFamilia</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Stock x Lote</th>
                    <th>Almacen</th>
                    <th>Lote</th>
                    <th>F. Vencimiento</th>
                </tr>
                </thead>
                <tbody id="products">
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->fami}} </a></td>
                        <td>{{$product->subfami}} </a></td>
                        <td>{{$product->barcode}} </a></td>
                        <td>{{$product->nombre_prod}}</td>
                        <td><b>{{$product->stockT}}</b></td>
                        
                        <td>{{$product->almacen}}</td>
                        <td>{{$product->lote}}</td>
                        <td>{{$product->fvenc}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
    
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>  
    <script type="text/javascript" src="{{ asset('javascript/lotes.js') }}"></script>

@stop
