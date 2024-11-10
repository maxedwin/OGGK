@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-import position-left"></i> <span class="text-semibold">Crear Ficha de Recepción</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/listado_ficha_recepcion"></i>Listado de Fichas de Recepción</a></li>
    <li class="active">Crear Ficha de Recepción </li>
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

    <div class="content">

            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">

                        <div class="form-group form-inline">
                            <div class="input-group">
                                <label for="factbol_search">Buscar Orden de Compra:</label>
                                <input type="text" class="form-control input-lg"  id="factbol_search"  placeholder="Buscar una Orden de Compra">
                                <div id="search_results2" class="list-group col-md-12 hide"></div>
                            </div>
                            
                            <div class="input-group">
                                <label for="proveedor_search">Buscar Proveedor:</label>
                                <input type="text" class="form-control input-lg"  id="proveedor_search"  placeholder="Buscar por Razon Social o RUC">
                                <div id="search_results" style="margin-top:30px;" class="list-group col-md-12 hide"></div>
                            </div>
                            
                            <div class="input-group pull-right">
                                <!-- <button type="button" class="btn btn-default btn-lg" id="btn_nuevoProveedor">
                                    <i class="glyphicon glyphicon-user"></i>
                                    Agregar Proveedor
                                </button> -->
                                <button type="button" class="btn btn-default btn-lg" id="eliminar_all" >
                                    <i class="glyphicon glyphicon-trash"></i>
                                    Limpiar
                                </button>
                                <div id="submit-control">
                                    <button class="btn btn-info btn-lg" id="btn_guardar">
                                        <i class="glyphicon glyphicon-save"></i>
                                        Guardar
                                    </button>
                                </div>
                            </div>
                            <div class="input-group ">
                                <label for="f_recepcion">Fecha de Recepción:</label>
                                <input type="date" class="form-control" id="f_recepcion" >
                            </div>
                            <div class="input-group ">
                                <label for="almacen">Almacén:</label>
                                <select class="form-control" name="almacen" id="almacen" >
                                        <option value="0">--</option>
                                    @foreach ($almacenes as $almacen)
                                        <option value="{{ $almacen->idalmacen }}">{{$almacen->nombre}}</option>
                                    @endforeach
                                </select>
                                <!-- <input type="text" class="form-control" id="serie" > -->
                            </div>
                            
                        </div>

                        <div class="form-group form-inline">
                            <div class="input-group ">
                                <label for="f_emision">Fecha de Emisión(GC):</label>
                                <input type="date" class="form-control" id="f_emision" >
                            </div>
                            <div class="input-group ">
                                <label for="serie">Serie(GC):</label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="serie" >
                            </div>
                            <div class="input-group ">
                                <label for="numeracion">Numeración(GC):</label>
                                <input type="number" class="form-control" id="numeracion" >
                            </div>
                        </div>

                        <div class="form-group form-inline">
                            <div class="input-group ">
                                <label for="flete_trans">Flete Transporte:</label> 
                                <input type="text" class="form-control input-lg"  id="flete_trans"  placeholder="Buscar por Razon Social o RUC">
                                <div id="search_results3"  class="list-group col-md-12 hide"></div>
                            </div>
                            <div class="input-group ">
                                <label for="flete_costo">Flete Costo:</label>
                                <input type="number" class="form-control" id="flete_costo" placeholder="EN SOLES">
                            </div>
                            <div class="input-group ">
                                <label for="comentarios">Comentarios:</label>
                                <textarea onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="comentarios" placeholder="Se agregaron otros productos a las orden?" rows="2"></textarea>
                            </div>
                        </div>    

                    </div>
                </div>
            </div>


        <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="form-group form-inline">
                            <div class="input-group">
                                <input type="text" class="form-control input-lg" style="width: 400px;" id="busqueda_query"  placeholder="Buscar por codigo, nombre o familia">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-lg" id="buscar_producto" type="button">
                                        &nbsp; <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </span>
                                <!-- <span class="input-group-btn">
                                    <button class="btn btn-default btn-lg" id="btnlista" type="button">
                                        &nbsp; <i class="glyphicon glyphicon-list"></i>
                                    </button>
                                </span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-lg" id="btngrid" type="button">
                                        &nbsp; <i class="glyphicon glyphicon-th"></i>
                                    </button>
                                </span> -->
                            </div>
                        </div>

                        <div class="form-group form-inline" id="lista_breadcrumb">
                        </div>

                        <div id="lista_productos">
                            <table class="table table-borderless table-hover" >
                                <thead>
                                    <tr class="bg-primary-700">
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>En Stock</th>
                                        <th>Unidad Medida</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody id="lista" >
                                    <tr>
                                        <td style="vertical-align: text-top;">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot style="text-align: right">

                                </tfoot>

                            </table>
                        </div>
                        <div id="grid_productos">

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table  class="table table-borderless">
                            <thead>
                                <tr class="bg-primary-700">
                                    <th>Producto</th>
                                    <th>Unidad Compra</th>
                                    <th>Cantidad Compra</th>
                                    <th>Lote </th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody id="lista_carrito" >
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
    </div>

    
<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>

   <script>
        window.onload = function () {
            calc.init();
        };
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
        
        var Lproductos = [];
        var costo_total = 0.00;

        var proveedors = [];
        var proveedor;

        var proveedors2 = [];
        var proveedor2;

        var facturas = [];
        var factura;

        $('#grid_productos').hide();

        /*$('#comentarios').wysihtml5({
            parserRules:  wysihtml5ParserRules,
            stylesheets: ["assets/css/components.css"],
            "image": false,
            "link": false,
            "font-styles": false,
            "emphasis": false
        });*/


        $('#busqueda_query').on('keydown', function(event) {
            if (event.which == 13 || event.keyCode == 13) {
                var query = $('#busqueda_query').val();
                var Lcategorias = [];
                $.get(currentLocation+"buscarProductoCoti?query="+query+"", function( data ) {
                    var productos = jQuery.parseJSON( data );
                    var string = '';
                    Lproductos = [];
                    var card = '<div class="row" style="padding: 0 20px; ">';
                    var k = 0 ;
                    $.each(productos, function(key,value){
                            Lproductos.push(value);

                            if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria  }) == null){
                                Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria });
                            }

                            string += '<tr>';
                            string += '<td>'+value.barcode+'</td>';
                            string += '<td>'+value.nombre+'</td>';
                            string += '<td>'+value.stockT+'</td>';
                            string += '<td>'+value.medida_venta+'</td>';
                            string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';
                    });
                    $('#lista').html('').append(string);
                    $('#grid_productos').html('').append(card);
                    var lista_breadcrumb = '';
                    $.each(Lcategorias,function(key,value){
                        lista_breadcrumb += ' <button type="button" id="btn_categoria" style="background-color: '+'" data-id='+value.idcategoria+' class="btn btn-lg bg-info-600">'+value.descripcion+'</button>';
                    });
                    $('#lista_breadcrumb').html('').append(lista_breadcrumb);
                });
            }
        });

        $('#buscar_producto').click(function(){
            var query = $('#busqueda_query').val();
            var Lcategorias = [];
            $.get(currentLocation+"buscarProductoCoti?query="+query+"", function( data ) {
                var productos = jQuery.parseJSON( data );
                var string = '';
                Lproductos = [];
                var card = '<div class="row" style="padding: 0 20px; ">';
                var k = 0 ;
                $.each(productos, function(key,value){
                        Lproductos.push(value);

                        if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria }) == null){
                            Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria });
                        }

                        string += '<tr>';
                        string += '<td>'+value.barcode+'</td>';
                        string += '<td>'+value.nombre+'</td>';
                        string += '<td>'+value.stockT+'</td>';
                        string += '<td>'+value.medida_venta+'</td>';
                        string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

                });
                $('#lista').html('').append(string);
                $('#grid_productos').html('').append(card);
                var lista_breadcrumb = '';
                $.each(Lcategorias,function(key,value){
                    lista_breadcrumb += ' <button type="button" id="btn_categoria" data-id='+value.idcategoria+' style="background-color: '+ '"  class="btn btn-lg bg-info-600">'+value.descripcion+'</button>';
                });
                $('#lista_breadcrumb').html('').append(lista_breadcrumb);
            });


        });

        $('#lista_breadcrumb').on('click', '#btn_categoria', function(){
            var cat = $(this).data('id');
            //var cat_prod = _.where(Lproductos,{idcategotiria: cat});
            var cat_prod = [];
            for(var i =0 ; i < Lproductos.length ; i++){
                if(Lproductos[i].idcategoria === parseInt(cat)){
                    cat_prod.push(Lproductos[i]);
                }
            }
            var string = '';
            var card = '<div class="row" style="padding: 0 20px; ">';
            var k = 0;
            $.each(cat_prod, function(key,value){
                string += '<tr id='+value.idproducto +'>';
                string += '<td>'+value.barcode+'</td>';
                string += '<td>'+value.nombre+'</td>';
                string += '<td>'+value.stockT+'</td>';
                string += '<td>'+value.medida_venta+'</td>';
                string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

            });
            $('#lista').html('').append(string);
            $('#grid_productos').html('').append(card);
        });

        $('#lista').on('click','#btn_agregar',function(event){
            var idproducto =  $(this).data('idprod');
            var producto = _.find(Lproductos,{idproducto : idproducto });
            //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, costo: producto.costo, descuento: 0.0, total_costo: 0.0});

            var string = '<tr id="tr_item" >';
            string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-costound="'+producto.costo_und+'" data-costo1a9="'+producto.costo_1a9+'" data-costo10a19="'+producto.costo_10a19+'" data-costo20a24="'+producto.costo_20a24+'" data-costo25a29="'+producto.costo_25a29+'" data-costo30="'+producto.costo_30+'" >'+producto.nombre+'</div></td>';
            string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
            string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';
            string += '<td id="lote"><input class="form-control" type="text" style="width: 200px" id="input_lote" name="input_lote"> @if ($errors->has('input_lote')) <div class="error">{{ $errors->first('input_lote') }}</div> @endif </td>';
            string += '<td id="f_vencimiento"><input class="form-control" id="input_f_vencimiento" type="date" ></td> ';
            string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

            string += '</tr> ';                 

            $('#lista_carrito').append(string);

        });

        $('#grid_productos').on('click','.row  .col-lg-3 .card', function(event){
            var idproducto = $(this).attr('id');
            var producto = _.find(Lproductos,{idproducto : parseInt(idproducto) });
            //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, costo: producto.costo, descuento: 0.0, total_costo: 0.0});

            var string = '<tr id="tr_item">';
            string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-costound="'+producto.costo_und+'" data-costo1a9="'+producto.costo_1a9+'" data-costo10a19="'+producto.costo_10a19+'" data-costo20a24="'+producto.costo_20a24+'" data-costo25a29="'+producto.costo_25a29+'" data-costo30="'+producto.costo_30+'">'+producto.nombre+'</div></td>';
            string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
            string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0" ></td> ';
            // string += '<td id="lote"><input class="form-control" type="text" id="input_lote" "></td>';
            string += '<td id="lote"><input class="form-control" type="text" style="width: 200px" id="input_lote" name="input_lote"> @if ($errors->has('input_lote')) <div class="error">{{ $errors->first('input_lote') }}</div> @endif </td>';
            string += '<td id="f_vencimiento"><input class="form-control" id="input_f_vencimiento" type="date" ></td> ';
            string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

            string += '</tr> ';

            $('#lista_carrito').append(string);


        });

        /********************CALCULAR TOTALES***********************************/

        function calcular_totales(){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_costo');
                total_detalle += parseFloat(importe.val());
            });
            if ($('#con_igv').is(':checked')) {
                costo_total = (total_detalle);
                $('#total').val(costo_total ) ;

            }else{
                var igv = $('#igv').val();
                costo_total = (total_detalle + parseFloat(igv));
                $('#total').val(costo_total ) ;
            }
            calcular_descuento();
        }

        function calcular_igv(){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_costo');
                total_detalle += parseFloat(importe.val());
            });
            var igv = total_detalle * 0.18;
            $('#total_detalle').val(total_detalle.toFixed(2));
            $('#igv').val(igv.toFixed(2));
        }

        $('#con_igv').on('change',function(event){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_costo');
                total_detalle += parseFloat(importe.val());
            });
            if ($('#con_igv').is(':checked')) {
                costo_total = (total_detalle);
                $('#total').val(costo_total ) ;
            }else{
                var igv = $('#igv').val();
                costo_total = (total_detalle + parseFloat(igv));
                $('#total').val(costo_total ) ;
            }
            calcular_descuento()
        })


        function calcular_descuento(){
            var descuento = $('#descuento').val();
            $('#descuento').val( parseFloat(descuento).toFixed(2) );
            var costo = costo_total - descuento;
            $('#total').val(costo.toFixed(2));

            var paga = 0.00;//$('#paga').val();
            if(paga.length < 1)paga = '0';
            $(this).val( parseFloat(paga).toFixed(2));
            var total = $('#total').val();
            var vuelto = paga - total;
            if(paga.length !== 0){
                $('#vuelto').val(vuelto.toFixed(2));
            }
        }

        $('#descuento').on('change',function(event){
            var descuento = $('#descuento').val();
            $('#descuento').val( parseFloat(descuento).toFixed(2) );
            var costo = costo_total - descuento;
            $('#total').val(costo.toFixed(2));
        });

        /*********ELIMINAR Y LIMPIAR DE BOLETA ****************/

        $('#lista_carrito').on('click','#eliminar', function(event){
            $(this).closest('tr').remove();
            calcular_totales();
            calcular_igv();
        });

        // $('#lista_carrito').on('click','#ver', function(event){
        //     var idproducto = $(this).data('idproducto');
        //     var tipo = $(this).data('tipo');

        //     if(tipo === 1){
        //         window.open(currentLocation+"producto_editar?id="+idproducto, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
        //     }else{
        //         window.open(currentLocation+"servicio_editar?id="+idproducto, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");

        //     }

        // });

        $('#eliminar_all').click(function(event){
            swal({
                title: "Estas segur@?",
                text: "Se limpiaran los datos de los productos!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#EF5350",
                confirmButtonText: "Si, limpiar!",
                cancelButtonText: "No, continuar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {
                    $('#lista_carrito').html('');
                    $('#total').val('0.00');
                    $('#descuento').val('0.00');
                    $('#igv').val('0.00');
                    $('#total_detalle').val('0.00');
                    $('#paga').val('0.00');
                    $('#vuelto').val('0.00');                    

                    swal({
                        title: "Limpio!",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    });
                }
                else {
                    swal({
                        title: "Tranquil@",
                        text: "Continua",
                        confirmButtonColor: "#2196F3",
                        type: "success"
                    });
                }
            });

        });

        /*******CANTIDAD Y DESCUENTOS INPUT CHANGE****************/

        $('#lista_carrito').on('change','#tr_item #input_cantidad', function(event){
            var costo = parseFloat( ($(this).parent().parent().find( "#costo_unitario #costo_prod" )).val());
            var total = $(this).parent().parent().find( "#detalle_total #input_costo" );
            var cantidad = parseFloat( $(this).val());
            var tcosto = (costo * cantidad).toFixed(2);
            total.val(tcosto);
            calcular_totales();
            calcular_igv();
        });

        $('#lista_carrito').on('change','#tr_item #costo_prod', function(event){
            var cantidad = parseFloat( ($(this).parent().parent().find( "#detalle_cantidad #input_cantidad" )).val());
            var total = $(this).parent().parent().find( "#detalle_total #input_costo" );
            var costo = parseFloat( $(this).val());
            $(this).val(costo.toFixed(2));
            var tcosto = (costo * cantidad).toFixed(2);
            total.val(tcosto);
            calcular_totales();
            calcular_igv();
        });


        $('#btnlista').click(function(event){
            $('#lista_productos').show();
            $('#grid_productos').hide();

        });

        $('#btngrid').click(function(event){
            $('#lista_productos').hide();
            $('#grid_productos').show();
        });

        /**************************BUSCAR cotizaciones*********/
        $('#factbol_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                factura = temp;
            }
        });

        $('#factbol_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  0){
                $.get(currentLocation+"buscarOCNumInc?query="+query+"", function( data ) {
                    $('#search_results2').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(facturas,{id_orden_comprah:value.id_orden_comprah}) == null){
                            facturas.push(value); }

                        $('#search_results2')
                            .removeClass('hide')
                            .append("<div id='item_to_add2' class='list-group-item' name='"
                                +value.id_orden_comprah+"' >"+ 'OC' + ' - ' + value.numeracion+'</div>');

                    });
                });
            }else{
                $('#search_results2').addClass('hide').html('');

            }
        });

        $('#item_to_add2').hover(function() {
                $( this ).toggleClass( "active" );
            }, function() {
                $( this ).removeClass( "active" );
            }
        );

        var idproveedorfinal;
        $( "#search_results2" ).on( "click","#item_to_add2", function() {
            $('#lista_carrito').html('');
            var id_orden_comprah = $(this).attr('name');
            factura = _.findWhere(facturas, {id_orden_comprah: parseInt(id_orden_comprah)});
            $('#factbol_search').val('OC'+' - '+factura.numeracion);
            $('#search_results2').addClass('hide').html('');

            var query = factura.id_orden_comprah;
            console.log(query);

            $.get(currentLocation+"buscarOCTodo?query="+query+"", function( data ) {
                var obj = JSON.parse(data);

                console.log(obj);
                idproveedorfinal = obj.idproveedor;
                $('#proveedor_search').val(obj.razon_social);

                var string = '';

                $.each(obj.detalle, function(index, producto) {

                    if (producto.cantidad_fal > 0) {
                        string += '<tr id="tr_item">';
                        string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-costound="'+producto.costo_und+'" data-costo1a9="'+producto.costo_1a9+'" data-costo10a19="'+producto.costo_10a19+'" data-costo20a24="'+producto.costo_20a24+'" data-costo25a29="'+producto.costo_25a29+'" data-costo30="'+producto.costo_30+'">'+producto.nombre+'</div></td>';
                        string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                        string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad_fal+'" ></td> ';
                        // string += '<td id="lote"><input class="form-control" type="text" id="input_lote" "></td>';
                        string += '<td id="lote"><input class="form-control" type="text" style="width: 200px" id="input_lote" name="input_lote"> @if ($errors->has('input_lote')) <div class="error">{{ $errors->first('input_lote') }}</div> @endif </td>';
                        string += '<td id="f_vencimiento"><input class="form-control" id="input_f_vencimiento" type="date" ></td> ';
                        string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                        string += '</tr> ';
                    }

                });

                $('#lista_carrito').append(string);


                $.get(currentLocation+"buscarProvGR?query="+idproveedorfinal+"", function( data ) {
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(proveedors,{idproveedor:value.idproveedor}) == null){
                            proveedors.push(value); }                   
                    });
                });

            }); 
        });


        /**************************BUSCAR PROVEEDOR*********/
        $('#proveedor_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                proveedor = temp;
            }
        });

        $('#proveedor_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  3){
                $.get(currentLocation+"buscarProveedor?query="+query+"", function( data ) {
                    $('#search_results').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(proveedors,{idproveedor:value.idproveedor}) == null){
                            proveedors.push(value); }

                        $('#search_results')
                            .removeClass('hide')
                            .append("<div id='item_to_add' class='list-group-item' name='"
                                +value.idproveedor+"' >"+value.ruc_dni +' ' + value.razon_social+'</div>');

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
            var idproveedor = $(this).attr('name');
            proveedor = _.findWhere(proveedors, {idproveedor: parseInt(idproveedor)});
            $('#proveedor_search').val(proveedor.ruc_dni+' '+proveedor.razon_social);
            $('#search_results').addClass('hide').html('');

        });

        /**************************BUSCAR TRANSPORTE*********/
        $('#flete_trans').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                proveedor2 = temp;
            }
        });

        $('#flete_trans').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  3){
                $.get(currentLocation+"buscarProveedor?query="+query+"", function( data ) {
                    $('#search_results3').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(proveedors2,{idproveedor:value.idproveedor}) == null){
                            proveedors2.push(value); }

                        $('#search_results3')
                            .removeClass('hide')
                            .append("<div id='item_to_add' class='list-group-item' name='"
                                +value.idproveedor+"' >"+value.ruc_dni +' ' + value.razon_social+'</div>');

                    });
                });
            }else{
                $('#search_results3').addClass('hide').html('');

            }
        });

        $('#item_to_add3').hover(function() {
                $( this ).toggleClass( "active" );
            }, function() {
                $( this ).removeClass( "active" );
            }
        );

        $( "#search_results3" ).on( "click","#item_to_add", function() {
            var idproveedor = $(this).attr('name');
            proveedor2 = _.findWhere(proveedors2, {idproveedor: parseInt(idproveedor)});
            $('#flete_trans').val(proveedor2.ruc_dni+' '+proveedor2.razon_social);
            $('#search_results3').addClass('hide').html('');

        });

        /*******************************************NUEVO CLIENTE**********************/
        $('#btn_nuevoProveedor').click(function(event){
            window.open("nuevo_proveedor", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
        });



        

        /***********************PAGA CON O VUELTO********/

        $('#total').change(function(event){
            var paga = 0.00;//$('#paga').val();
            var total = $(this).val();
            var vuelto = paga - total;
            if( paga){
                $('#vuelto').val(vuelto.toFixed(2));
            }

        });

        $('#paga').change(function(event){
            var paga = 0.00;//$(this).val();
            if(paga.length < 1)paga = '0';
            $(this).val( parseFloat(paga).toFixed(2));
            var total = $('#total').val();
            var vuelto = paga - total;
            if(paga.length !== 0){
                $('#vuelto').val(vuelto.toFixed(2));
            }
        });

////////////////////////////////////////////
    
        $('#btn_guardar').click(function(event){
            $('#btn_guardar').prop( "disabled", true );
            if (idproveedorfinal != null)
                proveedor = _.findWhere(proveedors, {idproveedor: parseInt(idproveedorfinal)});

            if(typeof proveedor === "undefined"){
                swal({
                    title: "Falta el campo Proveedor",
                    text: "Debes seleccionar un proveedor.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            if(typeof factura === "undefined"){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Orden de Compra correspondiente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            if(typeof proveedor2 === "undefined"){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Transporte del Flete",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }


            var idproveedor = proveedor.idproveedor;
            var id_orden_comprah = factura.id_orden_comprah;
            var almacen = $('#almacen').val();            
            var medida_venta = $('#medida_venta').val();
            var comentarios = $('#comentarios').val();
            var f_emision = $('#f_emision').val();
            var f_recepcion = $('#f_recepcion').val();
            var serie = $('#serie').val();
            var numeracion = $('#numeracion').val();
            var flete_trans = proveedor2.idproveedor;
            var flete_costo = $('#flete_costo').val();
            var productos = [];
            var paga = 0.00;//$('#paga').val();
            var vuelto = 0.00;//$('#vuelto').val();
            /*var f_emision = $('#f_emision').val();
            var serie = $('#serie').val();
            var numeracion = $('#numeracion').val();*/
        
            
            if(f_recepcion.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Recepción",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            if(almacen == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Almacen",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }


            
            if(f_emision.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Emisión",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            if(serie.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Serie",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            if(numeracion.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Numeración",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }


            if(flete_costo.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Costo Total del Flete",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            var producto = [];
            var tmp_lote=0;
            $("#lista_carrito #tr_item").each(function(){
                var nombre = $(this).find('#nombre_producto #ver').html();
                if( $(this).find('#lote #input_lote').val() == '' ){
                    tmp_lote=1;
                }
                producto.push({nombre:nombre});
            });


            if(producto.length == 0){
                swal({
                    title: "Falta agregar productos",
                    text: "Debes escoger un producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }

            if(tmp_lote == 1){
                swal({
                    title: "Upss!",
                    text: "Debes Agregar un Lote",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    tmp_lote=0;
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }


            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var lote = $(this).find('#lote #input_lote').val();
                var f_vencimiento = $(this).find('#f_vencimiento #input_f_vencimiento').val();
                var medida_venta = $(this).find('#medida_venta #med').val();
                productos.push({idproducto:producto,stock_total:stock_total,lote:lote,f_vencimiento:f_vencimiento});
            });

            console.log(productos);
            var json_prod = JSON.stringify(productos);
            //  return;
            console.log("entra2");
            $.post(currentLocation+'crear_ficha_recepcion',{id_orden_comprah:id_orden_comprah,idproveedor:idproveedor,almacen:almacen, serie:serie, numeracion:numeracion, medida_venta:medida_venta,comentarios:comentarios,f_emision:f_emision,f_recepcion:f_recepcion,productos:json_prod, flete_costo:flete_costo, flete_trans:flete_trans},function(data){
                console.log(data);

                var mensaje = 500;
                /*var obj = JSON.parse(data, function (key, value) {
                    if (key == "created") {
                        mensaje = value;
                    } 
                });*/
                var obj = JSON.parse(data);
                console.log(mensaje);
                console.log(obj);

                if(obj[0]['created'] === 200){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok!",
                        text: "Se guardo correctamente!.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.open(currentLocation+"listado_ficha_recepcion");
                        window.location.reload(); 
                    });
                    return;
                }else if(obj[0]['created'] === 500){
                    if (obj[1]['id'] === 9999999997) {
                        swal({
                            title: "NO!",
                            text: obj[2]['msg'],
                            confirmButtonColor: "#66BB6A",
                            type: "error"
                        },function(){ 
                            $('#btn_guardar').prop( "disabled", false );
                        });
                    } else {
                        swal({
                            title: "NO!",
                            text: "Error! Revisa por favor.",
                            confirmButtonColor: "#66BB6A",
                            type: "error"
                        },function(){ 
                            $('#btn_guardar').prop( "disabled", false );
                        });
                    }
                    return;
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar la factura de compra, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
                }
            });
        });

   </script>
@stop