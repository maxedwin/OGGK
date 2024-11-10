@extends('index')

<!-- TITULO PAGINA -->
@section('titulo')
    <h4><i class="icon-pen6 position-left"></i> <span class="text-semibold">Editar Método de pago</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_paymethods">Listado de Métodos de pago</a></li>
    <li class="active">Editar Métodos de pago</li>
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

    <form action="{{ route('paymethod_edit_store') }}" method="POST" enctype="multipart/form-data">
        <div class="panel panel-flat">   
            <div class="panel-body">
                <fieldset>
                    <legend class="text-semibold">Información del Método de pago</legend>

                    <div class="row">
                        <div class="col-md-6"> 
                            <div id="namegroup" class="form-group">
                                <label class="col-lg-3 control-label">Nombre:</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ $pay_method->nombre }}">
                                <input type="hidden"  id="id_paymethod" name="id_paymethod" value="{{ $pay_method->id_paymethod }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="descripcionpre_group" class="form-group">
                                <label class="col-lg-3 control-label">Descripción corta:</label>
                                <textarea type="text" class="form-control" rows="5" name="descripcion_pre" id="descripcion_pre" >{{ $pay_method->descripcion_pre }}</textarea>
                                <input type="hidden"  id="id_paymethod" name="id_paymethod" value="{{ $pay_method->id_paymethod }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="descripciondet_group" class="form-group">
                                <label class="col-lg-3 control-label">Descripción detallada:</label>
                                <textarea type="text" class="form-control" rows="5" name="descripcion_det" id="descripcion_det" >{{ $pay_method->descripcion_det }}</textarea>
                                <input type="hidden"  id="id_paymethod" name="id_paymethod" value="{{ $pay_method->id_paymethod }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="imagengroup" class="form-group ">
                            <label class="col-lg-3 control-label">Imagen:</label>                                               
                            @if($pay_method->imagen!='' || $pay_method->imagen!=null )                                                    
                                <img src="{{url('images/large/',$pay_method->imagen)}}" width="170" height="170" alt="" style="margin:2vh">
                            @endif 
                            <br/>                     
                        </div>
                        <div style="display:flex; flex-direction: row;"><label class="col-lg-3 control-label">Cambiar imagen:</label><input type="file" name="imagen" id="imagen" accept="image/*" /></div>
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