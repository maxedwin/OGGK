@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-import position-left"></i> <span class="text-semibold">Guía de Compra</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li> Guía de Compra </li>
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
                        </div>
                        
                        <div class="form-group form-inline">
                            <div class="input-group ">
                                <label for="f_emision">Fecha de Emisión:</label>
                                <input type="date" class="form-control" id="f_emision" >
                            </div>
                            <div class="input-group ">
                                <label for="serie">Serie:</label>
                                <input type="text" class="form-control" id="serie" >
                            </div>
                            <div class="input-group ">
                                <label for="numeracion">Numeración:</label>
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
                                <input type="number" class="form-control" id="flete_costo" >
                            </div>
                        </div>    

                    </div>
                </div>
            </div>



        <div class="col-md-9">
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
                                    <tr class="bg-danger-700">
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

            <div class="col-md-9">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table  class="table table-borderless">
                            <thead>
                                <tr class="bg-danger-700">
                                    <th>Producto</th>
                                    <th>Unidad Compra</th>
                                    <th>Cantidad Compra</th>
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

    <div id="boleta_modal" class="modal modal-wide fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 id="bol_empresa"></h3>
                        </div>
                        <div class="col-sm-6 text-right" id="datos-recibo">
                            <b>FECHA:</b>
                            <span id="bol_fecha"></span><br>
                            <b>DIRECCION:</b>
                            <span id="bol_sucursal"></span><br>
                            <b>RUC:</b>
                            <span id="bol_empresaRuc"></span><br>
                            <b>TELEFONO:</b>
                            <span id="bol_empresaTelefono"></span><br>
                      
                        </div>

                    </div>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-sm-6" id="datos_proveedor">
                            <!-- <h5>CLIENTE:</h5> -->
                            <b>CLIENTE:</b>
                            <span id="bol_proveedor"></span><br>
                            <!-- <b>DNI:</b>
                            <span id="bol_proveedorDni"></span><br> -->
                            <b>RUC:</b>
                            <span id="bol_proveedorRUC"></span><br>
                            <b>TELEFONO:</b>
                            <span id="bol_proveedorTELF"></span><br>
                            <b>DIRECCION:</b>
                            <span id="bol_proveedorDireccion"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table  class="table" id="validar_boleta">
                                <thead>
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th>UNIDAD MEDIDA</th>
                                        <th>CANTIDAD</th>
                                        <th>PRECIO UNIT.</th>                                        
                                        <th>IMPORTE</th>
                                    </tr>
                                </thead>
                                <tbody id="bol_detalle">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 col-sm-offset-9">
                            <table  class="table" id="validar_boleta">
                                <tr>
                                    <th>SUB-TOTAL</th>
                                    <td id="bol_total_detalle"></td>
                                </tr>
                                <tr>
                                    <th>DESCUENTO</th>
                                    <td id="bol_descuento"></td>
                                </tr>
                                <tr>
                                    <th>IGV</th>
                                    <td id="bol_igv"></td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td id="bol_total"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                <button class="btn btn-info btn-lg" id="btn_confirmar" data-idprod="9">
                                    <i class="glyphicon glyphicon-save"></i>
                                    Confirmar Transaccion
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

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

        var ordenes = [];
        var orden;

        $('#grid_productos').hide();

        $('#comentarios').wysihtml5({
            parserRules:  wysihtml5ParserRules,
            stylesheets: ["assets/css/components.css"],
            "image": false,
            "link": false,
            "font-styles": false,
            "emphasis": false
        });


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
            //if ($('#con_igv').is(':checked')) {
                //costo_total = (total_detalle);
                //$('#total').val(costo_total ) ;

            //}else{
                var igv = $('#igv').val();
                costo_total = (total_detalle + parseFloat(igv));
                $('#total').val(costo_total ) ;
            //}
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

            calcular_totales();
        }

        $('#con_igv').on('change',function(event){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_costo');
                total_detalle += parseFloat(importe.val());
            });
            //if ($('#con_igv').is(':checked')) {
                //costo_total = (total_detalle);
                //$('#total').val(costo_total ) ;
            //}else{
                var igv = $('#igv').val();
                costo_total = (total_detalle + parseFloat(igv));
                $('#total').val(costo_total ) ;
            //}
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
                orden = temp;
            }
        });

        $('#factbol_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  0){
                $.get(currentLocation+"buscarOCNum?query="+query+"", function( data ) {
                    $('#search_results2').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(ordenes,{id_orden_comprah:value.id_orden_comprah}) == null){
                            ordenes.push(value); }

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
            orden = _.findWhere(ordenes, {id_orden_comprah: parseInt(id_orden_comprah)});
            $('#factbol_search').val('OC'+' - '+orden.numeracion);
            $('#search_results2').addClass('hide').html('');

            var query = orden.id_orden_comprah;
            console.log(query);

            $.get(currentLocation+"buscarOCTodo?query="+query+"", function( data ) {
                var obj = JSON.parse(data);

                console.log('todo');
                console.log(obj);
                idproveedorfinal = obj.idproveedor;
                $('#proveedor_search').val(obj.razon_social);

                var string = '';

                $.each(obj.detalle, function(index, producto) {

                    string += '<tr id="tr_item" >';
                    string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-costound="'+producto.costo_und+'" data-costo1a9="'+producto.costo_1a9+'" data-costo10a19="'+producto.costo_10a19+'" data-costo20a24="'+producto.costo_20a24+'" data-costo25a29="'+producto.costo_25a29+'" data-costo30="'+producto.costo_30+'">'+producto.nombre+'</div></td>';
                    string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                    string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+'" ></td> ';
                    string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                    string += '</tr> ';

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

        /****************************************************GUARDAR BOLETA****/

        // $('#btn_guardar').click(function(event){

        //     if(typeof proveedor === "undefined"){
        //         swal({
        //             title: "Falta el campo Proveedor",
        //             text: "Debes seleccionar un proveedor.",
        //             confirmButtonColor: "#66BB6A",
        //             type: "error"
        //         },function(){
        //             //window.location.reload();
        //         });
        //         return;
        //     }

        //     if( proveedor.ruc === null && doc === '2' ){
        //         swal({
        //             title: "Falta el RUC del Proveedor",
        //             text: "Debes registar el ruc de "+ proveedor.ruc_dni+ ' '+ proveedor.razon_social,
        //             confirmButtonColor: "#66BB6A",
        //             type: "error"
        //         },function(){
        //             //window.location.reload();
        //         });
        //         return;
        //     }

        //     var producto = [];
        //     $("#lista_carrito #tr_item").each(function(){
        //         var cantidad = $(this).find('#detalle_cantidad #input_cantidad').val();
        //         var idpro = $(this).find('#detalle_cantidad #input_cantidad').data('idprod');
        //         var nombre = $(this).find('#nombre_producto').html();
        //         var costo = $(this).find('#detalle_total #input_costo').val();
        //         var medida_venta = $(this).find('#medida_venta #med').val();
        //         producto.push({idproducto:idpro, nombre:nombre, cantidad:cantidad,costo:costo, medida_venta:medida_venta});
        //     });


        //     if(producto.length == 0){
        //         swal({
        //             title: "Falta agregar productos",
        //             text: "Debes escoger un producto",
        //             confirmButtonColor: "#66BB6A",
        //             type: "error"
        //         },function(){
        //             //window.location.reload();
        //         });
        //         return;
        //     }


        //     $('#bol_proveedor').html('').append(proveedor.razon_social );
        //     $('#bol_proveedorDireccion').html('').append(proveedor.direccion+', '+proveedor.distrito+', '+proveedor.provincia+', '+proveedor.departamento );
        //     // $('#bol_proveedorDni').html('').append(proveedor.dni);
        //     $('#bol_proveedorRUC').html('').append(proveedor.ruc_dni);
        //     $('#bol_proveedorTELF').html('').append(proveedor.contacto_telefono);

        //     var today = new Date();
        //     var dd = today.getDate();
        //     var mm = today.getMonth()+1; //January is 0!
        //     var yyyy = today.getFullYear();

        //     if(dd<10) {
        //         dd = '0'+dd
        //     }

        //     if(mm<10) {
        //         mm = '0'+mm
        //     }

        //     today = mm + '/' + dd + '/' + yyyy;

        //     $('#bol_fecha').html('').append(today);
        //     $('#bol_empresa').html('').append('{{ $usuario->empresa }}');
        //     $('#bol_sucursal').html('').append('{{ $usuario->direccion  }}');
        //     $('#bol_empresaRuc').html('').append('{{$usuario->ruc}}');
        //     $('#bol_empresaTelefono').html('').append('{{ $usuario->telefono }}');

        //     $('#bol_total').html('').append($('#total').val());
        //     $('#bol_igv').html('').append($('#igv').val());
        //     $('#bol_total_detalle').html('').append($('#total_detalle').val());
        //     $('#bol_descuento').html('').append($('#descuento').val());
        //     $('#bol_pago').html('').append($('#paga').val());
        //     $('#bol_vuelto').html('').append($('#vuelto').val());

        //     var string = '';

        //     $("#lista_carrito #tr_item").each(function(){
        //         string += '<tr>';
        //         string += '<td>'+$(this).find('#nombre_producto #ver').html()+'</td>';
        //         string += '<td>'+$(this).find('#medida_venta #med').html()+'</td>';
        //         string += '<td>'+$(this).find('#detalle_cantidad #input_cantidad').val()+'</td>';
        //         string += '<td>'+$(this).find('#costo_unitario #costo_prod').val()+'</td>';
        //         string += '<td>'+$(this).find('#detalle_total #input_costo').val()+'</td>';
        //         string += '</tr>';
        //     });
        //     console.log(string);
        //     $('#bol_detalle').html('').append(string);
            
        //     $('#boleta_modal').modal();

        // });

        $('#btn_guardar').click(function(event){

            if (idproveedorfinal != null)
                proveedor = _.findWhere(proveedors, {idproveedor: parseInt(idproveedorfinal)});

            if(typeof proveedor === "undefined"){
                swal({
                    title: "Falta el campo Proveedor",
                    text: "Debes seleccionar un proveedor.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(typeof orden === "undefined"){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Orden de Compra correspondiente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
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
                    //window.location.reload();
                });
                return;
            }

            var idproveedor = proveedor.idproveedor;
            var id_orden_comprah = orden.id_orden_comprah;
            var igv = $('#igv').val();
            var subtotal = $('#total_detalle').val();
            var descuento = $('#descuento').val();
            var total = $('#total').val();
            var medida_venta = $('#medida_venta').val();
            var comentarios = $('#comentarios').val();
            var f_emision = $('#f_emision').val();
            var serie = $('#serie').val();
            var numeracion = $('#numeracion').val();
            var flete_trans = proveedor2.idproveedor;
            var flete_costo = $('#flete_costo').val();
            var productos = [];
            var paga = 0.00;//$('#paga').val();
            var vuelto = 0.00;//$('#vuelto').val();

          

            if( proveedor.ruc === null && doc === '2' ){
                swal({
                    title: "Falta el RUC del Proveedor",
                    text: "Debes registar el ruc de "+ proveedor.ruc_dni+ ' '+ proveedor.razon_social,
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
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
                    //window.location.reload();
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
                    //window.location.reload();
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
                    //window.location.reload();
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
                    //window.location.reload();
                });
                return;
            }

            var producto = [];
            $("#lista_carrito #tr_item").each(function(){
                var nombre = $(this).find('#nombre_producto #ver').html();
                producto.push({nombre:nombre});
            });


            if(producto.length == 0){
                swal({
                    title: "Falta agregar productos",
                    text: "Debes escoger un producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }


            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var costo = $(this).find('#costo_unitario #costo_prod').val();
                var medida_venta = $(this).find('#medida_venta #med').val();
                productos.push({idproducto:producto,stock_total:stock_total,costo:costo});
            });

            var json_prod = JSON.stringify(productos);

            $.post(currentLocation+'crear_guia_compra',{id_orden_comprah:id_orden_comprah,idproveedor:idproveedor,serie:serie, flete_costo:flete_costo, flete_trans:flete_trans, numeracion:numeracion, igv:igv,subtotal:subtotal,descuento:descuento,medida_venta:medida_venta,total:total,comentarios:comentarios,f_emision:f_emision,productos:json_prod,paga:paga,vuelto:vuelto},function(data){
                console.log(data);
                var mensaje;
                var obj = JSON.parse(data, function (key, value) {
                    if (key == "created" && value ==200) {
                        mensaje = value;
                    } 
                });
                if(mensaje === 200){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok!",
                        text: "Se guardo correctamente!.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.open(currentLocation+"listado_guia_compra");
                        window.location.reload(); 
                    });
                    return;
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar la guia de compra, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){});
                    return;
                }
            });
        });

   </script>
@stop