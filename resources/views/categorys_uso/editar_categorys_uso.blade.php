@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-pen6 position-left"></i> <span class="text-semibold">Editar Categoria</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_categorias_uso">Listado de Categorías</a></li>
    <li class="active">Editar categorias</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')


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

    <?php if($mensaje == 404)
        {echo "ERROR";}else{
    ?>

    <form action="{{ route('categorys_uso_edit_store') }}" method="POST" enctype="multipart/form-data">

        <div class="panel panel-flat">   
                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Información de la Categoría</legend>

                            <div class="row">
                                <div class="col-md-5">                                    

                                    <div id="namegroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="name" name="name" class="form-control" value="{{ $categoria_uso->name }}">
                                            <input type="hidden"  id="id" name="id" value="{{ $categoria_uso->id }}">
                                    </div>
                                    <div class="row">
                                        <div id="imagegroup" class="form-group ">
                                            <label class="col-lg-3 control-label">Imagen:</label>

                                               
                                                @if($categoria_uso->image!='' || $categoria_uso->image!=null )                                                    
                                                    <img src="{{url('images/large/',$categoria_uso->image)}}" width="170" height="170" alt="" style="margin:2vh">
                                                @endif
  
                                            <div style="display:flex; flex-direction: row;"><label class="col-lg-3 control-label">Cambiar imagen:</label><input type="file" name="image" id="image" accept="image/*" /></div>

                                        </div>
                                    </div>  
                                    
                                    <div class="row" style="margin-bottom:4vh;">
                                        <div class="from-group" id="marcas_group" style="margin-top:15px;">
                                            <label for="marcas_select">Marcas:</label>
                                            <select class="selectpicker" id="marcas_select" name="marcas_select[]" multiple="multiple" data-live-search="true" title="Busca tu/s Marca/s..."> 
                                                @foreach ($marcas as $marca)                                                
                                                <option value="{{$marca->id}}" 
                                                    @foreach ($selectedmarcas as $selectedmarca)
                                                        @if ( $marca->id == $selectedmarca->idmarca )
                                                            selected
                                                        @endif
                                                    @endforeach
                                                    >{{$marca->nombre}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                                                               
                                </div>                                

                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>

            </div>
        </div>
        </form>
        <?php } ?>
        
        <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
        <script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

        <script rel="script" type="text/javascript">
        var currentLocation = window.location.protocol + "//" + window.location.host + "/";

        


        

        


        
@stop