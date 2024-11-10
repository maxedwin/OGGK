@extends('index')

<!-- TITULO PAGINA -->
@section('titulo')
    <h4><i class="icon-cube position-left"></i> <span class="text-semibold">Nuevo Método de pago</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_paymethods">Listado de Métodos de pago</a></li>
    <li class="active">Nuevo Método de pago</li>
@stop


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


    <form action="{{ route('paymethod_store') }}" method="POST" enctype="multipart/form-data">
                  <div class="panel panel-flat">
                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Información del Método de pago</legend>

                            <div class="row">
                                <div class="col-md-6">
                                    <div id="namegroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                        <input type="text"  name="nombre" id="nombre" class="form-control" required="required">
                                    </div>                                                                     
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="descripcionpre_group" class="form-group">
                                        <label class="col-lg-3 control-label">Descripción corta:</label>
                                        <textarea type="text" class="form-control" rows="4" name="descripcion_pre" id="descripcion_pre" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="descripciondet_group" class="form-group">
                                        <label class="col-lg-3 control-label">Descripción detallada:</label>
                                        <textarea type="text" class="form-control" rows="5" name="descripcion_det" id="descripcion_det" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div id="imagengroup" class="form-group col-md-5">
                                    <label class="col-lg-3 control-label">Imagen:</label>
                                    <div class="col-lg-9">
                                        <input type="file" name="imagen" id="imagen" accept="image/*" />
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>

                        </fieldset>
                    </div>
                </div>
    </form>  
@stop