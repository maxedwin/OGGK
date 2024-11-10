@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Nuevo Servicio</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
    <li>Inventario</li>
    <li class="active"><div class="titleModal text-left" style="color:#00BCD4"></2></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <div class="text-right">
            <button type="button" class="btn btn-info btn-lg" id="guardar_cambios">Guardar</button>
        </div>

    </li>
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

    <div class="panel panel-flat">
        <div class="panel-heading">
            <div class="heading-elements">
            </div>

        </div>

        <div class="panel-body">
            <div class="form-horizontal">
                <div class="panel panel-flat">
                    <div class="panel-heading text-right">
                        <div class="row">
                            <div class="col-md-12" style="font-weight: bolder; font-size: 18px">
                                <h2><div class="titleModal text-left" style="color:#00BCD4"></div></h2>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Informacion del Servicio</legend>

                            <div class="row">
                                <div class="col-md-12">
                                   <div id="nombregroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre del Servicio:</label>
                                        <div class="col-lg-9">
                                            <input type="text"  id="nombre" class="form-control" placeholder="Nombre del Servicio">
                                            <input type="hidden"  id="idproducto">
                                        </div>
                                    </div>

                                    <div id="costogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Costo:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" id="costo" placeholder="000.00">
                                        </div>
                                    </div>

                                    <div id="preciogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Precio:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" id="precio" placeholder="000.00">
                                        </div>
                                    </div>
                                    <div id="ubicaciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Ubicacion en donde se realiza:</label>
                                        <div class="col-lg-9">
                                            <input type="text" class="form-control" id="ubicacion" placeholder="lugar...">
                                        </div>
                                    </div>

                                    <div id="stategroup" class="form-group">
                                        <label class="col-lg-3 control-label"><i class="glyphicon glyphicon-star position-left" ></i>Estado Actual:</label>
                                        <div class="col-lg-9">
                                            <select id="state" class="form-control"  style="width: 100%;" >
                                                <option value="0">Inactivado</option>
                                                <option value="1" selected>Activo</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-12">
                                    <div id="descripciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Descripcion:</label>
                                        <div class="col-lg-9">
                                                    <textarea  id="descripcion"  class="wysihtml5 wysihtml5-min form-control" rows="50" cols="50">

                                                    </textarea>
                                        </div>
                                    </div>
                                </div>
                         </div>


                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" rel="script">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        /*************BUSQUEDA DE PRODUCTOS POR NOMBRE******************/

        $('#btnbusqueda').on('click', function() {
            nombre = $('#busqueda_nombre').val();
            window.location.replace(currentLocation+"list_product?query="+nombre);
        });

        // Default initialization
        $('.wysihtml5').wysihtml5({
            parserRules:  wysihtml5ParserRules,
            stylesheets: ["assets/css/components.css"],
            "image": false,
            "link": false,
            "font-styles": false,
            "emphasis": false
        });


        $('#guardar_cambios').click(function(event){
            //idproducto = $('#idproducto').val();
            idproducto = 0;
            nombre = $('#nombre').val();
            costo = $('#costo').val();
            precio = $('#precio').val();
            descripcion = $('#descripcion').val();
            ubicacion = $('#ubicacion').val();
            estado = $('#state').val();

            var formData = new FormData();

            formData.append('idproducto',idproducto);
            formData.append("nombre",nombre);
            formData.append("costo",costo);
            formData.append("precio",precio);
            formData.append("descripcion",descripcion);
            formData.append("idcategoria",0);
            formData.append("ubicacion",ubicacion);
            formData.append("state",estado);
            formData.append("tipo",2);

            console.log(formData.get('motos'));

            if(validar_datos()){
                $.ajax({
                    url: currentLocation+"product_store", //You can replace this with MVC/WebAPI/PHP/Java etc
                    method: "post",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function () {
                        swal({
                                title: "Bien hecho!",
                                text: "Se guardo correctamente",
                                type: "success"
                            },
                            function(){
                                console.log('ok button');
                                window.opener.location.reload();
                                window.close();
                            });
                        ;
                    },
                    error: function (error) { swal("Error al guardar", "Intentelo nuevamente luego.", "error"); }

                });
            }
        });


        function validar_datos(){
            nombre =  $('#nombre').val();


            if(nombre  === undefined || nombre === ''){
                $('#nombregroup').addClass("has-error");
                return false;
            }


            return true;

        }


    </script>

@stop