@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-pen6 position-left"></i> <span class="text-semibold">Editar Producto</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_product">Listado de Productos</a></li>
    <li class="active">Editar Producto</li>
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

    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/toolbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/parsers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>

    <?php if($mensaje == 404)
        {echo "ERROR";}else{
    ?>

    <div class="panel panel-flat">   
                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Información del Producto</legend>

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

                                    <div id="descripciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Descripción:</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="descripcion"  class="form-control" value="{{ $producto->detalle }}" >
                                    </div>

                                    <div id="marcagroup" class="form-group">
                                        <label class="col-lg-3 control-label">Marca:</label>
                                            <select id="idmarca" class="form-control"  style="width: 100%;">
                                                <option value="0">- OTROS -</option>
                                                @foreach ($marcas as $marca)
                                                    <?php if ($marca->id == $producto->idmarca) { ?>
                                                        <option value="{{ $marca->id}}" selected="selected">{{$marca->nombre}}</option>
                                                    <?php } else { ?>
                                                        <option value="{{ $marca->id}}">{{$marca->nombre}}</option>
                                                    <?php } ?>
                                                @endforeach
                                            </select>
                                    </div>

                                    <div id="volumengroup" class="form-group">
                                        <label class="col-lg-3 control-label">Volumen x Und:</label>
                                        <div class="row">
                                            <div class = "col-sm-3">
                                                <input type="number" class="form-control" id="volumen" value="{{ $producto->volumen }}">
                                            </div>

                                            <div class = "col-sm-3">
                                                <select id="volumen_und" class="form-control"  style="width: 80%;">
                                                    <option value="{{ $producto->volumen_und }}">{{ $producto->volumen_und }}</option>  
                                                    <option value="0">--</option>        
                                                    @foreach ($unidades as $unidad)
                                                        <option value="{{ $unidad->nombre}}">{{$unidad->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div> 

                                    <div id="cantcajagroup" class="form-group">
                                        <label class="col-lg-3 control-label">Cantidad x Caja:</label>
                                        <input type="number" class="form-control" id="cantidad_caja" value="{{ $producto->cantidad_caja }}">
                                    </div>
                                    <div id="preciogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Precio:</label>
                                        <input type="number" step="0.1" class="form-control" id="precio" value="{{ $producto->precio }}">
                                    </div>
                                    <div id="costogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Costo:</label>
                                        <input type="number" step="0.1" class="form-control" id="costo" value="{{ $producto->costo }}">
                                    </div>
                            
                                    <div id="pesogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Peso x Unidad:</label>
                                        <div class="row">
                                            <div class = "col-sm-3">
                                                <input type="number" class="form-control" id="peso_unidad" value="{{ $producto->peso_unidad }}">
                                            </div>

                                            <div class = "col-sm-3">
                                                <select id="peso_unidad_und" class="form-control"  style="width: 80%;">
                                                    <option value="{{$producto->peso_unidad_und}}">{{ $producto->peso_unidad_und }}</option> 
                                                    <option value="0">--</option>         
                                                    @foreach ($unidades as $unidad)
                                                        <option value="{{$unidad->nombre}}">{{$unidad->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="featuresgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Características:</label>
                                            <textarea type="text" class="form-control" rows="5" name="features" id="features" >{{ $producto->features }}</textarea>
                                            <input type="hidden"  id="idproducto" value="{{ $producto->idproducto }}">
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Imagen</label>
                                        <div class="controls">
                                            <form action="{{ route('subir_imagen') }}" method="POST" enctype="multipart/form-data">
                                                <input type="file" name="image" id="image" accept="image/*"/>
                                                <input type="hidden"  name="idproducto" id="idproducto" value="{{ $producto->idproducto }}">
                                                <input type="hidden"  name="nombre" id="nombre" value="{{ $producto->nombre }}">
                                                <input type="submit" value="Subir"/>
                                            </form>
                                                <span class="text-danger">{{$errors->first('image')}}</span>
                                                @if($producto->image!='' || $producto->image!=null )
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="javascript:" rel="{{$producto->idproducto}}" rel1="delete-image" class="btn btn-danger btn-mini deleteRecord">Eliminar Imagen</a>
                                                    <img src="{{url('images/small/',$producto->image)}}" width="170" height="170" alt="">
                                                @endif
                                        </div>
                                    </div>

                                           
                                </div>

                                <div class="col-md-5">

                                    <div id="categoriagroup" class="form-group">
                                        <label class="col-lg-3 control-label">SubFamilia: </label>
                                            <select id="idcategoria" class="form-control"  style="width: 100%;">
                                                <option value="{{ $producto->idcategoria}}"> {{ $producto->descripcion }} </option>
                                                <option value="0">--</option>
                                                @foreach ($categorias as $category)
                                                    <option value="{{ $category->idcategoria}}">{{$category->descripcion}}</option>
                                                @endforeach
                                            </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="sunat_search">Código Sunat:</label>
                                        <input type="text" class="form-control input-lg"  id="sunat_search"  placeholder="Buscar... (ingrese más de 3 caracteres)" value="{{ $producto->codigo_sunat }}">
                                        <div id="search_results" style="margin-top:-1px;" class="list-group col-md-12 hide"></div>
                                    </div>

                                    <div id="colorgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Color:</label>
                                            <select id="color" class="form-control"  style="width: 100%;">
                                                <option value="{{ $producto->id_color}}"> {{ $producto->color_nombre }} </option>
                                                <option value="0">--</option>
                                                @foreach ($colores as $color)
                                                    <option value="{{ $color->id_color}}">{{$color->color_nombre}}</option>
                                                @endforeach
                                                
                                            </select>
                                        <!-- <input type="text" class="form-control" id="color"> -->
                                    </div>
                                    
                                    <div id="medventgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Medida de Venta / Servicio:</label>
                                        <select id="medida_venta" class="form-control"  style="width: 100%;" >
                                                <option value="{{ $producto->medida_venta }}">{{ $producto->medida_venta }}</option> 
                                                <option value="0">--</option>
                                                <option value="NIU">   UNIDAD</option>
                                                <option value="CAJA">     CAJA</option>
                                                <option value="MILLAR">   MILLAR</option>
                                                <option value="ZZ">   SERVICIO</option>
                                        </select>
                                    </div>                  

                                    <div id="proveedorgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Proveedor:</label>
                                        <select id="idproveedor" class="form-control"  style="width: 100%;">
                                            <option value="{{ $producto->idproveedor }}"> {{ $producto->razon_social }} </option>
                                            <option value="0">--</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->idproveedor}}">{{$proveedor->razon_social}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- <div id="costogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Costo Promedio:</label>
                                        <input type="text" class="form-control" id="costo" value="{{ $producto->costo }}">
                                    </div>-->

                                    <div id="preciogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Precio Tienda:</label>
                                        <input type="number" class="form-control" id="precio" value="{{ $producto->precio }}">
                                    </div> 

                                    <div id="medventgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Tienda:</label>
                                        <select id="state" class="form-control"  style="width: 100%;">
                                                <option value="1">--</option>
                                                <option <?php if ($producto->desta == 0 ) echo 'selected' ; ?> value="0">NO MOSTRAR</option>
                                                <option <?php if ($producto->desta == 1 ) echo 'selected' ; ?> value="1">NO DESTACADO</option>
                                                <option <?php if ($producto->desta == 2 ) echo 'selected' ; ?> value="2">SI DESTACADO</option>
                                        </select>
                                    </div>
                                    <div id="categoriausogroup" class="form-group">
                                        <label class="col-lg-6 control-label">Categoría de Uso Tienda:</label>
                                        <select id="idcategoria_uso" class="form-control"  style="width: 100%;">
                                            <option value="0">--</option>
                                            @foreach ($categorias_uso as $category)
                                                <?php if ($category->id == $producto->idcategoria_uso) { ?>
                                                    <option value="{{ $category->id}}" selected="">{{$category->name}}</option>
                                                <?php } else { ?>
                                                    <option value="{{ $category->id}}">{{$category->name}}</option>
                                                <?php } ?>                                            
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="taggroup" class="form-group">
                                        <label class="col-lg-6 control-label">Tags de búsqueda Tienda:</label>
                                        <select class="selectpicker" id="tags_select" name="tags_select[]" multiple="multiple" data-live-search="true" title="Buscar Tag/s..."> 
                                            @foreach ($tags as $tag)                                                
                                            <option value="{{$tag->idtag}}" 
                                                @foreach ($selectedtags as $selectedtag)
                                                    @if ( $tag->idtag == $selectedtag->idtag )
                                                        selected
                                                    @endif
                                                @endforeach
                                                >{{$tag->nombre}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="aplicationsgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Aplicaciones:</label>
                                            <textarea type="text" class="form-control" rows="5" name="aplications" id="aplications" >{{ $producto->aplications }}</textarea>
                                            <input type="hidden"  id="idproducto" value="{{ $producto->idproducto }}">
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label">Ficha Técnica</label>
                                        <div class="controls">
                                            <form action="{{ route('subir_ficha_tecnica') }}" method="POST" enctype="multipart/form-data">
                                                <input type="file" name="ficha" id="ficha" accept=".pdf"/>
                                                <input type="hidden"  name="idproducto" id="idproducto" value="{{ $producto->idproducto }}">
                                                <input type="hidden"  name="nombre" id="nombre" value="{{ $producto->nombre }}">
                                                <input type="submit" value="Subir"/>
                                            </form>
                                                <span class="text-danger">{{$errors->first('adjunto')}}</span>
                                                @if($producto->adjunto!='' || $producto->adjunto!=null )
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="javascript:" rel="{{$producto->idproducto}}" rel1="delete-ficha" class="btn btn-danger btn-mini deleteFicha">Eliminar Ficha</a>
                                                    <a href="{{url('files/',$producto->adjunto)}}" download>{{$producto->adjunto}}</a>
                                                @endif
                                        </div>
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

        $(".deleteFicha").click(function () {
            var id=$(this).attr('rel');
            var deleteFunction=$(this).attr('rel1');
            swal({
                title:'Estas segur@?',
                type:'warning',
                showCancelButton:true,
                confirmButtonColor:'#3085d6',
                cancelButtonColor:'#d33',
                confirmButtonText:'Sí, eliminar!',
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
            var idcategoria = $('#idcategoria').val();
            var idproveedor = $('#idproveedor').val();
            var idcategoria_uso = $('#idcategoria_uso').val();
            var barcode = $('#barcode').val();
            var nombre = $('#nombre').val();
            var descripcion = $('#descripcion').val();
            var stock_total = $('#stock_total').val();
            var volumen = $('#volumen').val();
            var volumen_und = $('#volumen_und').val();
            var cantidad_caja = $('#cantidad_caja').val();
            var medida_venta = $('#medida_venta').val();
            var peso_unidad = $('#peso_unidad').val();
            var peso_unidad_und = $('#peso_unidad_und').val();
            var color = $('#color').val();
            var costo = $('#costo').val();
            var precio = $('#precio').val();
            var state = $('#state').val();
            var idmarca = $('#idmarca').val();

            var tags_select = $('#tags_select').val();
            var features = $('#features').val();
            var aplications = $('#aplications').val();

            
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

            /*if(idmarca == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar la Marca del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }*/       

            if(idcategoria == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar la SubFamilia del Producto",
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

            if(color == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el Color del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }   

            if(medida_venta == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar la Medida de Venta del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }      

            if(idproveedor == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el Proveedor del Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }            

            /*var file = $('#image')[0].files[0];  
            console.log(file);

            var formData = new FormData();
            formData.append('file', file);
            
            console.log(formData);*/

            $.post(currentLocation+'product_store_update',{idproducto:idproducto, idcategoria:idcategoria, idproveedor:idproveedor, barcode:barcode, nombre:nombre, descripcion:descripcion, stock_total:stock_total, volumen:volumen, volumen_und:volumen_und, cantidad_caja:cantidad_caja, medida_venta:medida_venta, peso_unidad:peso_unidad, peso_unidad_und:peso_unidad_und, color:color, costo:costo, precio:precio, cod_sunat:cod_sunat, state:state, idmarca:idmarca,idcategoria_uso:idcategoria_uso,
            tags_select:tags_select, features:features, aplications:aplications},function(data){
                obj = JSON.parse(data);
                if(obj.mensaje === 201){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok",
                        text: "Se guardó correctamente.",
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
                        text: "No se puede guardar el producto, intentalo de nuevo luego.",
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
<!-- 
    <script type="text/javascript" rel="script">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
        var table;

        var edit  = 0;
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
        $("#state").select2();
        $('#categoria').select2();

        $("#state").val({{ $producto->state}}).change();
        $('#categoria').val({{ $producto->idcategoria }}).change();


        $('#guardar_cambios').click(function(event){
            idproducto = $('#idproducto').val();
            nombre = $('#nombre').val();
            categoria = $('#categoria').val();
            stock_total = 0;
            costo = $('#costo').val();
            precio = $('#precio').val();
            descripcion = $('#descripcion').val();
            estado = $('#state').val();
            barcode = $('#barcode').val();

            var formData = new FormData();
            
            formData.append('idproducto',idproducto);
            formData.append("nombre",nombre);
            formData.append("categoria",categoria);
            formData.append("stock_total",stock_total);
            formData.append("costo",costo);
            formData.append("precio",precio);
            formData.append("descripcion",descripcion);
            formData.append("state",estado);
            formData.append("barcode",barcode);
            formData.append("tipo",1);

            if(validar_datos()){
                $.ajax({
                    url: currentLocation+"product_store", //You can replace this with MVC/WebAPI/PHP/Java etc
                    method: "post",
                    data: formData,
                    async: false,
                    contentType: false,
                    processData: false,
                    success: function () {
                        swal({
                                title: "Bien hecho!",
                                text: "Se modifico correctamente",
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


    </script> -->

    <?PHP
    }
    ?>
@stop