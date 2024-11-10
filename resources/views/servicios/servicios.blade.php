@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Lista de Servicios</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
    <li>Inventario</li>
    <li class="active">Servicios</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_producto">
            <i class="icon-box-add position-left"></i>
            Nuevo servicio
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

    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="heading-elements">



            </div>

        </div>

        <div class="panel-body">
            <form class="form-inline">
                <input type="text" id="busqueda_nombre" class="form-control input-lg" id="formGroupExampleInput" placeholder="Nombre del Producto">
                <button type="button" id="btnbusqueda" class="btn btn-primary"><i class="icon-search4 position-left"></i> Bucar</button>
            </form>
            <!--LISTA DE PRODUCTOS -->
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" id="servicio_table">
                <thead>
                <tr>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Costo</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="servicio_table">
                @foreach ($servicios as $servicio)
                    <tr>
                        <?PHP  if($servicio->state == 1) {
                            echo '<td><span class="label label-success">Activo</span></td>';
                        }else{
                            echo '<td><span class="label label-default">Inactivo</span></td>';
                        }  ?>
                        <td>{{$servicio->nombre}}</td>
                        <td><?PHP echo substr($servicio->descripcion,0,50).' ...' ?></td>
                        <td>{{$servicio->costo}}</td>
                        <td>{{$servicio->precio}}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-idservicio="{{$servicio->idservicio}}"
                                    data-state="{{$servicio->state}}"
                                    data-precio="{{$servicio->precio}}"
                                    data-nombre="{{ $servicio->nombre }}"
                                    data-descripcion="{{$servicio->descripcion}}"
                                    data-costo="{{$servicio->costo}}"
                                    id="editar" data-toggle="modal">
                                <i class="icon-pen6 position-left"></i> Editar
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" data-idservicio="{{$servicio->idservicio}}" id="eliminar">
                                <i class="icon-cancel-square2 position-left"></i> Eliminar
                            </button>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <div class="panel-footer"><a class="heading-elements-toggle"><i class="icon-more"></i></a>
            <div class="text-right">

            </div>
        </div>

    </div>

    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h6 class="modal-title">Modificar servicio</h6>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="panel panel-flat">
                            <div class="panel-heading">

                            </div>

                            <div class="panel-body">
                                <fieldset>
                                    <legend class="text-semibold">Informacion de servicio</legend>

                                    <div id="nombregroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                        <div class="col-lg-9">
                                            <input type="text"  id="nombre" class="form-control" placeholder="Nombre de la servicio">
                                            <input type="hidden"  id="idservicios">
                                        </div>
                                    </div>

                                    <div id="descripciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Descripcion:</label>
                                        <div class="col-lg-9">
                                            <textarea  id="descripcion"  class="wysihtml5 wysihtml5-min form-control" rows="50" cols="50">
                                            </textarea>
                                        </div>
                                    </div>
                                    <div id="seriegroup" class="form-group">
                                        <label class="col-lg-3 control-label">Costo:</label>
                                        <div class="col-lg-9">
                                            <input type="text"  id="costo" class="form-control" placeholder="00.00">
                                        </div>
                                    </div>
                                    <div id="marcagroup" class="form-group">
                                        <label class="col-lg-3 control-label">Precio:</label>
                                        <div class="col-lg-9">
                                            <input type="text"  id="precio" class="form-control" placeholder="00.00">
                                        </div>
                                    </div>
                                    <div id="stategroup" class="form-group">
                                        <label class="col-lg-3 control-label"><i class="glyphicon glyphicon-star position-left" ></i>Estado Actual:</label>
                                        <div class="col-lg-9">
                                            <select id="status" class="form-control"  style="width: 100%;" >
                                                <option value="0">Inactivado</option>
                                                <option value="1">Activo</option>

                                            </select>
                                        </div>
                                    </div>

                                </fieldset>

                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('/javascript/servicios.js') }}" type="text/javascript"></script>
@stop