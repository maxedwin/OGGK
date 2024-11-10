@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-list position-left"></i> <span class="text-semibold">Listado de Categorías</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li class="active">Listado de Categorías</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="nuevo_categorys_uso" id="nueva_categoria">
            <i class="icon-box-add position-left"></i>
            Nueva Categoría
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
                $("#categorias_uso_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>  

          
        <!--LISTA DE CATEGORIAS
        <input type="text" id="input" class="form-control input-lg" id="formGroupExampleInput" placeholder="Busca por Familia o SubFamilia">-->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="categorias_table">
            <thead>
            <tr>            
                <!--<th>Estado</th>-->
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="categorias_uso_table">
            @foreach ($categorias_uso as $categoria_uso)
                <tr>
                    <td>{{$categoria_uso->name}}</td>
                                            
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-id ="{{$categoria_uso->id}}"
                                data-name="{{$categoria_uso->name}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-id ={{$categoria_uso->id}} id="eliminar">
                            <i class="icon-cancel-square2 position-center"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


<script src="{{ URL::asset('/javascript/categorys_uso.js') }}" type="text/javascript"></script>
    @stop