@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-pen6 position-left"></i> <span class="text-semibold">Editar Servicio</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_servicios">Listado de Servicios</a></li>
    <li class="active">Editar Servicio</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

<li>
        <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-success btn-lg" id="guardar_cambios" data-idprod="9">
                <i class="glyphicon glyphicon-save"></i>
                Guardar Cambios
            </button>
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

    <?php if($mensaje == 404)
        {echo "ERROR";}else{
    ?>

    <div class="panel panel-flat">   
                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Información del Servicio</legend>

                            <div class="row">
                                <div class="col-md-5">

                                    <div id="barcodegroup" class="form-group">
                                        <label class="col-lg-3 control-label">Código:</label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="barcode" class="form-control" value="{{ $producto->barcode }}" disabled>
                                    </div>

                                    <div id="nombregroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="nombre" class="form-control" value="{{ $producto->nombre }}">
                                            <input type="hidden"  id="idproducto" value="{{ $producto->idproducto }}">
                                    </div>
                                           
                                </div>

                                <div class="col-md-5">

                                    <div class="form-group">
                                        <label for="sunat_search">Código Sunat:</label>
                                        <input type="text" class="form-control input-lg"  id="sunat_search"  placeholder="Buscar... (ingrese más de 3 caracteres)" value="{{ $producto->codigo_sunat }}">
                                        <div id="search_results" style="margin-top:-1px;" class="list-group col-md-12 hide"></div>
                                    </div>

                                    <div id="descripciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Descripción:</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="descripcion"  class="form-control" value="{{ $producto->detalle }}" >
                                    </div>
                                    
                                    <div id="preciogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Precio:</label>
                                        <input type="number" step="0.1" class="form-control" id="precio" value="{{ $producto->precio }}">
                                    </div>

                                </div>

                            </div>
                        </fieldset>
            </div>
        </div>

        <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
        <script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

        <script rel="script" type="text/javascript">
        //var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
        var currentLocation = window.location.protocol + "//" + window.location.host + "/";

        $(".deleteRecord").click(function () {
            var id=$(this).attr('rel');
            var deleteFunction=$(this).attr('rel1');
            swal({
                title:'Estas segur@?',
                type:'warning',
                showCancelButton:true,
                confirmButtonColor:'#3085d6',
                cancelButtonColor:'#d33',
                confirmButtonText:'Si, eliminar!',
                cancelButtonText:'No, cancelar!',
                confirmButtonClass:'btn btn-success',
                cancelButtonClass:'btn btn-danger',
                buttonsStyling:false,
                reverseButtons:true
            },function () {
                window.location.href="/"+deleteFunction+"/"+id;
            });
        });

        // $('#idcategoria').select2();
        // $('#idcategoria').val({{ $producto->idcategoria }}).change();
        var sunats = [];
        var sunat;

        $('#sunat_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                sunat = temp;
            }
        });

        $('#sunat_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  3){
                $.get(currentLocation+"lista_codigo_sunat?query="+query+"", function( data ) {
                    $('#search_results').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(sunats,{codigo:value.codigo}) == null){
                            sunats.push(value); }

                        $('#search_results')
                            .removeClass('hide')
                            .append("<div id='item_to_add' class='list-group-item' name='"
                                +value.codigo+"' >"+value.codigo + ' ' + value.descripcion+'</div>');
                    });
                });

                
            }else{
                $('#search_results').addClass('hide').html('');
            }
        });

        $('#item_to_add').hover(function() {
                $( this ).toggleClass( "active" );
            }, function() {
                $( this ).removeClass( "active" );
            }
        );

        $( "#search_results" ).on( "click","#item_to_add", function() {
            var codigo = $(this).attr('name');
            sunat = _.findWhere(sunats, {codigo: codigo});
            $('#sunat_search').val(sunat.codigo+' '+sunat.descripcion);
            $('#search_results').addClass('hide').html('');

        });


        $('#guardar_cambios').click(function(event){
            $('#guardar_cambios').prop( "disabled", true );
            var idproducto = $('#idproducto').val();
            var barcode = $('#barcode').val();
            var nombre = $('#nombre').val();
            var descripcion = $('#descripcion').val();
            var precio = $('#precio').val();

            
            if(barcode.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el Codigo del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            } 

                        if(nombre.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el Nombre del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }

            if(descripcion.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar la Descripción del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }    

            var cod_sunat;
            cod_sunat=$('#sunat_search').val();
            
            /*if(cod_sunat == "" || cod_sunat == '' || cod_sunat == null ){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el Código de Sunat del Producto.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }*/

            if(cod_sunat == "" || cod_sunat == '' || cod_sunat == null ){
                cod_sunat = "0";
            }         
            
            if(typeof sunat != "undefined")
                cod_sunat=sunat.codigo;

            if(precio.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el Precio del Servicio",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            } 

            var arrayPost = {idproducto:idproducto, barcode:barcode, nombre:nombre, descripcion:descripcion, precio:precio, cod_sunat:cod_sunat };

            /*var file = $('#image')[0].files[0];  
            console.log(file);

            var formData = new FormData();
            formData.append('file', file);
            
            console.log(formData);*/

            $.post(currentLocation+'servicio_store_update', arrayPost,function(data){
                obj = JSON.parse(data);
                if(obj.mensaje === 201){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok!",
                        text: "Se guardo correctamente!.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.close();
                        window.opener.location.reload();
                    });
                    return;
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar el servicio, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                    return;
                }
            });

        });
    </script>

    <?PHP
    }
    ?>

@stop