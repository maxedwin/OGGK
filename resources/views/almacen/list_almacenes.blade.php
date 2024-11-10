@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Listado de Almacenes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li><a href="/list_product">Listado de Productos </a></li>
    <li class="active">Listado de Almacenes</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_almacen">
            <i class="icon-box-add position-left"></i>
            Nuevo Almacen
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
                $("#almacenes_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
</script>  



        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="products_table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Distrito</th>
                <th>Provincia</th>
                <th>Departamento</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="almacenes_table">
            @foreach ($almacenes as $almacen)
                <tr>
                  
                    <td>{{$almacen->nombre}}</td>
                    <td>{{$almacen->direccion}}</td>
                    <td>{{$almacen->distrito}}</td>
                    <td>{{$almacen->provincia}}</td>
                    <td>{{$almacen->departamento}}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-idalmacen ={{$almacen->idalmacen}}
                                data-state="{{$almacen->state}}"
                                data-nombre="{{$almacen->nombre}}"
                                data-direccion="{{$almacen->direccion}}"
                                data-distrito="{{$almacen->distrito}}"
                                data-provincia="{{$almacen->provincia}}"
                                data-departamento="{{$almacen->departamento}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-idalmacen ={{$almacen->idalmacen}} id="eliminar">
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
                <h6 class="modal-title">Nuevo/Modificar Almacen</h6>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading">

                        </div>

                        <div class="panel-body">
                            <fieldset>
                                <legend class="text-semibold">Información del Almacen</legend>

                                <div id="nombregroup" class="form-group">
                                    <label class="col-lg-3 control-label">Nombre:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="nombre" class="form-control">
                                        <input type="hidden"  id="idalmacen">
                                    </div>
                                </div>

                                <div id="direcciongroup" class="form-group">
                                    <label class="col-lg-3 control-label">Dirección:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="direccion" class="form-control">
                                    </div>
                                </div>

                                <div class="from-group" id="depa_group">
                                        <label class="col-lg-3 control-label" >Departamento:</label> 
                                        <div class="col-lg-9">     
                                            <select id="departamento" class="form-control"  style="width: 50%;">
                                                @foreach ($departamentos as $departamento)
                                                    <option value="{{ $departamento->departamento_name}}">{{$departamento->departamento_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                </div>

                                
                                <div class="from-group" id="prov_group">
                                        <label class="col-lg-3 control-label" >Provincia:</label>    
                                        <div class="col-lg-9">  
                                            <select id="provincia" class="form-control"  style="width: 50%;">
                                                @foreach ($provincias as $provincia)
                                                    <option value="{{ $provincia->provincia_name}}">{{$provincia->provincia_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                </div>


                                <div  id="dist_group" class="from-group">
                                        <label class="col-lg-3 control-label" >Distrito:</label>  
                                        <div class="col-lg-9">     
                                            <select id="distrito" class="form-control"  style="width: 50%;">
                                                @foreach ($distritos as $distrito)
                                                    <option value="{{ $distrito->distrito_name}}">{{$distrito->distrito_name}}</option>
                                                @endforeach
                                            </select> 
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

<script src="{{ URL::asset('/javascript/almacen.js') }}" type="text/javascript"></script>

@stop