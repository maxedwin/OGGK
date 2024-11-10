@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-cube position-left"></i> <span class="text-semibold">Nuevo Catálogo Virtual</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_catalogos_virtual">Listado de Catálogos Virtuales</a></li>
    <li class="active">Nuevo Catálogo</li>
@stop
<!-- MENU AUXLIAR -->



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


    <form action="{{ route('catalogos_virtual_store') }}" method="POST" enctype="multipart/form-data">
                  <div class="panel panel-flat">
                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Información del catálogo</legend>

                            <div class="row">
                                <div class="col-md-5">
                                    <div id="namegroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase()" name="name" id="name" class="form-control" required="required">
                                    </div>                                                                     
                                </div>
                            </div>
                            <div class="row">
                                <div id="imagegroup" class="form-group col-md-5">
                                    <label class="col-lg-3 control-label">Imagen:</label>
                                    <div class="col-lg-9">
                                        <input type="file" name="image" id="image" accept="image/*" required="required"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div id="imagegroup" class="form-group col-md-5">
                                    <label class="col-lg-3 control-label">PDF:</label>
                                    <div class="col-lg-9">
                                            <input type="file" name="ficha" id="ficha" accept=".pdf" required="required"/>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>

                        </fieldset>
            </div>
        </div>
    </form>


       

        

@stop     