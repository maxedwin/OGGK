@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-list position-left"></i> <span class="text-semibold">Listado de Tags de búsqueda</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li class="active">Listado de Tags de búsqueda</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_tag">
            <i class="icon-box-add position-left"></i>
            Nuevo Tag de búsqueda
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
                $("#tags_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>  

          
        <!--LISTA DE TAGS-->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="tags_H_table">
            <thead>
            <tr>            
                <th>ID</th>
                <th>Tag</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="tags_table">
            @foreach ($tags as $tag)
                <tr>
                    <td>{{$tag->idtag}}</td>
                    <td>{{$tag->nombre}}</td>                       
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-idtag ="{{$tag->idtag}}"                                
                                data-nombre="{{$tag->nombre}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-idtag ={{$tag->idtag}} id="eliminar">
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
                                <legend class="text-semibold">Información de Tag</legend>

                                <div id="nombregroup" class="form-group">
                                    <label class="col-lg-3 control-label">Tag:</label>
                                    <div class="col-lg-9">
                                        <input type="text"  id="nombre" class="form-control" oninput="this.value = this.value.toUpperCase()" placeholder="Nombre de Tag">
                                        <input type="hidden"  id="idtag">
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

<script src="{{ URL::asset('/javascript/tags.js') }}" type="text/javascript"></script>
    @stop