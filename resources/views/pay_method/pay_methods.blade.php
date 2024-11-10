@extends('index')

<!-- TITULO PAGINA -->
@section('titulo')
    <h4><i class="icon-list position-left"></i> <span class="text-semibold">Listado de Métodos de pago</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li class="active">Listado de Métodos de pago</li>
@stop

<!-- MENU AUXLIAR -->
@section('menu')
    <li>
        <a href="nuevo_paymethod" id="nuevo_paymethod">
            <i class="icon-box-add position-left"></i>
            Nuevo Método de pago
        </a>

    </li>
@stop

<!-- CONTENIDO DE LA PAGINA -->
@section('contenido')
<?PHP
    header("Access-Control-Allow-Origin:*");
 ?>
<style type="text/css">
    .colorpicker-basic { z-index: 9999; }
    .sp-container { z-index: 9999 !important;}
    .sp-preview {z-index: 9999 !important;}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{ URL::to('/') }}">

<script>
        $(document).ready(function(){
            $("#input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#paymethods_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>  

          
        <!--LISTA DE METODOS DE PAGO-->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="pay_methods_table">
            <thead>
            <tr>            
                <th>ID</th>
                <th>Método de pago</th>
                <th>Descripción corta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="paymethods_table">
            @foreach ($pay_methods as $pay_method)
                <tr>
                    <td>{{$pay_method->id_paymethod}}</td>   
                    <td>{{$pay_method->nombre}}</td>
                    <td>{{$pay_method->descripcion_pre}}</td>                                               
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-id_paymethod ="{{$pay_method->id_paymethod}}"
                                data-nombre="{{$pay_method->nombre}}"
                                data-descripcion_pre="{{$pay_method->descripcion_pre}}"
                                data-descripcion_det="{{$pay_method->descripcion_det}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-id_paymethod ={{$pay_method->id_paymethod}} id="eliminar">
                            <i class="icon-cancel-square2 position-center"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


<script src="{{ URL::asset('/javascript/pay_methods.js') }}" type="text/javascript"></script>
    @stop