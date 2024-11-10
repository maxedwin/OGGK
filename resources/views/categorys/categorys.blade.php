@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-list position-left"></i> <span class="text-semibold">Listado de Familias</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li><a href="/list_product">Listado de Productos </a></li>
    <li class="active">Listado de Familias</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_producto">
            <i class="icon-box-add position-left"></i>
            Nueva Familia/SubFamilia
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
                $("#categorias_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>  

          
        <!--LISTA DE FAMILIAS 
        <input type="text" id="input" class="form-control input-lg" id="formGroupExampleInput" placeholder="Busca por Familia o SubFamilia">-->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="products_table">
            <thead>
            <tr>            
                <!--<th>Estado</th>-->
                <th>Familia</th>
                <th>Nombre (SubFamilia)</th>
                <th>Tienda</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="categorias_table">
            @foreach ($categorias as $categoria)
                <tr>
                    <td>{{$categoria->padre}}</td>
                    <td>{{$categoria->descripcion}}</td>
                    <?PHP  if($categoria->state_tienda == 0) {
                            echo '<td> NO MOSTRAR </td>';
                        }      else {
                            echo '<td> MOSTRAR </td>';
                        }
                    ?> 
                    <td>{{$categoria->categoriauso}}</td>                       
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-idcategoria ="{{$categoria->idcategoria}}"
                                data-estado="{{$categoria->state_tienda}}"
                                data-descripcion="{{$categoria->descripcion}}"
                                data-padre="{{$categoria->idpadre}}"
                                data-categoriauso="{{$categoria->idcategoriauso}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-idcategoria ={{$categoria->idcategoria}} id="eliminar">
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
                <h6 class="modal-title">Nueva/Editar Familia</h6>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading">

                        </div>

                        <div class="panel-body">
                            <fieldset>
                                <legend class="text-semibold">Información de Familia/SubFamilia</legend>

                                <div id="nombregroup" class="form-group">
                                    <label class="col-lg-3 control-label">Familia/SubFamilia:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="descripcion" class="form-control" placeholder="Nombre de la Familia/SubFamilia">
                                        <input type="hidden"  id="idcategoria">
                                    </div>
                                </div>

                                <div id="categoriagroup" class="form-group">
                                    <label class="col-lg-3 control-label">Familia:</label>
                                    <div class="col-lg-9">
                                        <select id="padre" class="form-control">
                                            <option value="0">-- --</option>
                                            @foreach ($categorias_padre as $category)
                                                <option value="{{ $category->idcategoria}}">{{$category->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div id="tiendagroup" class="form-group">
                                    <label class="col-lg-3 control-label">Tienda:</label>
                                    <div class="col-lg-9">
                                        <select id="state" class="form-control">
                                                <option value="0">NO MOSTRAR</option>
                                                <option value="1">MOSTRAR</option>
                                        </select>
                                    </div>
                                </div>


                                <div id="categoriausogroup" class="form-group">
                                    <label class="col-lg-3 control-label">Categoría:</label>
                                    <div class="col-lg-9">
                                        <select id="categoriauso" class="form-control">
                                            <option value="0">-- --</option>
                                            @foreach ($categorias_uso as $categoryuso)
                                                <option value="{{ $categoryuso->id}}">{{$categoryuso->name}}</option>
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

<script src="{{ URL::asset('/javascript/categorys.js') }}" type="text/javascript"></script>
    @stop