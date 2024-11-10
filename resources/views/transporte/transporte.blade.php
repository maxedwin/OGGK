@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-send position-left"></i> <span class="text-semibold">Listado de Transportes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li><a href="/list_product">Listado de Productos </a></li>
    <li class="active">Listado de Transportes</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_transporte">
            <i class="icon-box-add position-left"></i>
            Nuevo Transporte
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
                $("#transportes_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
</script>  


        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="products_table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Placa</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="transportes_table">
            @foreach ($transportes as $transporte)
                <tr>
                   
                    <td>{{$transporte->nombre_trans}}</td>
                    <td>{{$transporte->marca}}</td>
                    <td>{{$transporte->tipo}}</td>
                    <td>{{$transporte->placa}}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-idtransporte ={{$transporte->idtransporte}}
                                data-state="{{$transporte->state}}"
                                data-nombre_trans="{{$transporte->nombre_trans}}"
                                data-marca="{{$transporte->marca}}"
                                data-tipo="{{$transporte->tipo}}"
                                data-placa="{{$transporte->placa}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-idtransporte ={{$transporte->idtransporte}} id="eliminar">
                            <i class="icon-cancel-square2 position-center"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

   

<div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h6 class="modal-title">Nuevo/Modificar Transporte</h6>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading">

                        </div>

                        <div class="panel-body">
                            <fieldset>
                                <legend class="text-semibold">Información del Transporte</legend>

                                <div id="nombre_transgroup" class="form-group">
                                    <label class="col-lg-3 control-label">Nombre:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="nombre_trans" class="form-control">
                                        <input type="hidden"  id="idtransporte">
                                    </div>
                                </div>

                                <div id="marcagroup" class="form-group">
                                    <label class="col-lg-3 control-label">Marca:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="marca" class="form-control">
                                    </div>
                                </div>

                                <div id="tipogroup" class="form-group">
                                    <label class="col-lg-3 control-label">Tipo:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="tipo" class="form-control">
                                    </div>
                                </div>

                                <div id="placagroup" class="form-group">
                                    <label class="col-lg-3 control-label">Placa:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="placa" class="form-control">
                                    </div>
                                </div>

                                
                            </fieldset>

                        </div>
                    </div>
                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ URL::asset('/javascript/transporte.js') }}" type="text/javascript"></script>

@stop