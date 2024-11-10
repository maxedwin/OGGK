@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-cube position-left"></i> <span class="text-semibold">Nuevo Producto</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="/list_product">Listado de Productos</a></li>
    <li class="active">Nuevo Producto</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-success btn-lg" id="guardar_cambios">
                <i class="glyphicon glyphicon-save"></i>
                Guardar
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

    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/wysihtml5.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/toolbar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/parsers.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>

                  <div class="panel panel-flat">
                    <div class="panel-body">
                        <fieldset>
                            <legend class="text-semibold">Información del Producto</legend>

                            <div class="row">
                                <div class="col-md-5">

                                    <div id="barcodegroup" class="form-group">
                                        <label class="col-lg-3 control-label">Código:</label>
                                        <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="barcode" class="form-control">
                                    </div>

                                    <div id="nombregroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="nombre" class="form-control">
                                            <input type="hidden"  id="idproducto">
                                    </div>

                                    <div id="descripciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Descripción:</label>
                                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" id="descripcion"  class="form-control" >
                                    </div>

                                    <div id="marcagroup" class="form-group">
                                        <label class="col-lg-3 control-label">Marca:</label>
                                            <select id="idmarca" class="form-control"  style="width: 100%;">
                                                <option value="0">- OTROS -</option>
                                                @foreach ($marcas as $marca)
                                                    <option value="{{ $marca->id}}">{{$marca->nombre}}</option>
                                                @endforeach
                                            </select>
                                    </div>

                                    <div id="volumengroup" class="form-group">
                                        <label class="col-lg-3 control-label">Volumen x Und:</label>
                                        <div class="row">
                                            <div class = "col-sm-3">
                                                <input type="number" class="form-control" id="volumen">
                                            </div>

                                            <div class = "col-sm-3">
                                                <select id="volumen_und" class="form-control"  style="width: 80%;">
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
                                        <input type="number" class="form-control" id="cantidad_caja">
                                    </div>
                            
                                    <div id="pesogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Peso x Und:</label>
                                        <div class="row">
                                            <div class = "col-sm-3">
                                                <input type="number" class="form-control" id="peso_unidad">
                                            </div>

                                            <div class = "col-sm-3">
                                                <select id="peso_unidad_und" class="form-control"  style="width: 80%;">
                                                    <option value="0">--</option>         
                                                    @foreach ($unidades as $unidad)
                                                        <option value="{{$unidad->nombre}}">{{$unidad->nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
 
                                </div>
                                
                                <div class="col-md-5">
                                
                                    <div id="categoriagroup" class="form-group">
                                        <label class="col-lg-3 control-label">SubFamilia:</label>
                                            <select id="idcategoria" class="form-control"  style="width: 100%;">
                                                <option value="0">--</option>
                                                @foreach ($categorias as $category)
                                                    <option value="{{ $category->idcategoria}}">{{$category->descripcion}}</option>
                                                @endforeach
                                            </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="sunat_search">Código Sunat:</label>
                                        <input type="text" class="form-control input-lg"  id="sunat_search"  placeholder="Buscar... (ingrese más de 3 caracteres)">
                                        <div id="search_results" style="margin-top:-1px;" class="list-group col-md-12 hide"></div>
                                    </div> 

                                    <div id="colorgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Color:</label>
                                            <select id="color" class="form-control"  style="width: 100%;">
                                                <option value="0" >--</option>
                                                @foreach ($colores as $color)
                                                <option value="{{ $color->id_color}}">{{$color->color_nombre}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    
                                    <div id="medventgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Medida de Venta / Servicio:</label>
                                        <select id="medida_venta" class="form-control"  style="width: 100%;">
                                                <option value="0">--</option>
                                                <option value="NIU"> UNIDAD</option>
                                                <option value="CAJA">   CAJA</option>
                                                <option value="MILLAR"> MILLAR</option>
                                                <option value="ZZ"> SERVICIO</option>
                                        </select>
                                    </div>                 

                                    <div id="proveedorgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Proveedor:</label>
                                        <select id="idproveedor" class="form-control"  style="width: 100%;">
                                            <option value="0">--</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->idproveedor}}">{{$proveedor->razon_social}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- <div id="costogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Costo Promedio:</label>
                                        <input type="text" class="form-control" id="costo">
                                    </div>-->

                                    <div id="preciogroup" class="form-group">
                                        <label class="col-lg-3 control-label">Precio Tienda:</label>
                                        <input type="number" class="form-control" id="precio">
                                    </div> 

                                    <div id="medventgroup" class="form-group">
                                        <label class="col-lg-3 control-label">Tienda:</label>
                                        <select id="state" class="form-control"  style="width: 100%;">
                                                <option value="1">--</option>
                                                <option value="0">NO MOSTRAR</option>
                                                <option value="1">NO DESTACADO</option>
                                                <option value="2">SI DESTACADO</option>
                                        </select>
                                    </div>  
                                    <div id="categoriausogroup" class="form-group">
                                        <label class="col-lg-6 control-label">Categoría de Uso Tienda:</label>
                                        <select id="idcategoria_uso" class="form-control"  style="width: 100%;">
                                            @foreach ($categorias_uso as $category)
                                                <option value="{{ $category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
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
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
        
        $('#idproveedor').select2();

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
            //var stock_total = $('#stock_total').val();
            var stock_total = 0;
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
        
            /*if(typeof sunat === "undefined"){
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
            var cod_sunat = "0";
            if(typeof sunat !== "undefined"){
                cod_sunat = sunat.codigo;
            }

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
            
            console.log(formData);

            var object = {};
            formData.forEach(function(value, key){
                object[key] = value;
            });
            var json = JSON.stringify(object);
            console.log(json);*/

            $.post(currentLocation+'product_store',{idproducto:idproducto, idcategoria:idcategoria, idproveedor:idproveedor, barcode:barcode, nombre:nombre, descripcion:descripcion, stock_total:stock_total, volumen:volumen, volumen_und:volumen_und, cantidad_caja:cantidad_caja, medida_venta:medida_venta, peso_unidad:peso_unidad, peso_unidad_und:peso_unidad_und, color:color, costo:costo, precio:precio, state:state, idmarca:idmarca, cod_sunat:cod_sunat,idcategoria_uso: idcategoria_uso},function(data){
                obj = JSON.parse(data);
                if(obj.mensaje === 201){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok!",
                        text: "Se guardo correctamente!.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.replace(currentLocation+'list_product');
                        /*window.close();
                        window.opener.location.reload();*/
                    });
                    return;
                }else if(obj.mensaje === 999){
                    swal({
                        title: "Error!",
                        text: "Código repetido! Revisa por favor.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#guardar_cambios').prop( "disabled", false );
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

@stop        