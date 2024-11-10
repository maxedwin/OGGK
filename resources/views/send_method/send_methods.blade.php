@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-list position-left"></i> <span class="text-semibold">Listado de Métodos de envío</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li class="active">Listado de Métodos de envío</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_sendmethod">
            <i class="icon-box-add position-left"></i>
            Nuevo Método de envío
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
                $("#sendmethods_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>  

          
        <!--LISTA DE METODOS DE ENVIO-->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="send_methods_table">
            <thead>
            <tr>            
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="tags_table">
            @foreach ($send_methods as $send_method)
                <tr>
                    <td>{{$send_method->id_sendmethod}}</td>
                    <td>{{$send_method->nombre}}</td> 
                    <td>{{$send_method->precio}}</td>                   
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-id_sendmethod ="{{$send_method->id_sendmethod}}"                                
                                data-nombre="{{$send_method->nombre}}"
                                data-descripcion="{{$send_method->descripcion}}"
                                data-precio="{{$send_method->precio}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-id_sendmethod ={{$send_method->id_sendmethod}} id="eliminar">
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
                <h6 class="modal-title">Nuevo/Editar Método de envío</h6>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading">

                        </div>

                        <div class="panel-body">
                            <fieldset>
                                <legend class="text-semibold">Información de Método de envío</legend>

                                <div id="nombregroup" class="form-group">
                                    <label class="col-lg-3 control-label">Nombre:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="nombre" class="form-control" placeholder="Nombre de Método de envío">
                                        <input type="hidden"  id="id_sendmethod">
                                    </div>
                                </div>
                                
                                <div id="descripciongroup" class="form-group">
                                    <label class="col-lg-3 control-label">Descripción:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="descripcion" class="form-control" placeholder="Descripción de Método de envío">
                                        <input type="hidden"  id="id_sendmethod">
                                    </div>
                                </div>

                                <div id="preciogroup" class="form-group">
                                    <label class="col-lg-3 control-label">Precio:</label>
                                    <div class="col-lg-9">
                                        <input type="number" step="0.1" id="precio" class="form-control" placeholder="Precio de Método de envío">
                                        <input type="hidden"  id="id_sendmethod">
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

<script src="{{ URL::asset('/javascript/send_methods.js') }}" type="text/javascript"></script>
    @stop