@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-import position-left"></i> <span class="text-semibold">Crear Orden de Compra</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/listado_orden_compra"></i>Listado de Ordenes de Compra</a></li>
    <li class="active"> Crear Orden de Compra </li>
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
                                <button class="btn btn-info btn-lg" id="btn_guardar">
                                    <i class="glyphicon glyphicon-save"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group form-inline">
                            <div class="input-group ">
                                <label for="f_emision">Fecha de Emisión:</label>
                                <input type="date" class="form-control" id="f_emision">
                            </div>
                            <div class="input-group ">
                            <label for="moneda">Moneda:</label>
                            <select class="form-control" id="moneda">
                                    <option value="0">--</option>
                                    <option value="1">Soles</option>
                                    <option value="2">Dolares</option>
                                    <option value="3">Euros</option>
                            </select>
                            </div>
                            <div class="input-group" style="width: 40%;">
                                <label for="lugar_entrega">Lugar de Entrega:</label>
                                <input type="text" class="form-control input-lg"  id="lugar_entrega" value="{{$sucursal->direccion}}">
                            </div>
                            
                        </div>
                        <div class="form-group form-inline">
                            <div class="input-group " style="width: 60%;">
                                <label for="observaciones">Observaciones:</label>
                                <textarea id="observaciones" class="form-control"></textarea>
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
                                    <tr class="bg-warning-700">
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Costo x Unidad</th>
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
                                <tr class="bg-warning-700">
                                    <th>Producto</th>
                                    <th>Unidad Compra</th>
                                    <th>Cantidad Compra</th>
                                    <th>Costo Compra</th>
                                    <th>Costo Total</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody id="lista_carrito" >
                            </tbody>
                            <tfoot style="text-align: right">
                                <tr>
                                    <td class="bg-warning-300" colspan="3">
                                        Sub-Total 
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="total_detalle" type="number" value="0.00" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-warning-400" colspan="3">
                                        Descuento 
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="descuento" type="number" value="0.00" step="0.1" >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-warning-600" colspan="3">
                                       (0.18%)  IGV <br>
                                    </td>
                                    <td colspan="2">
                                        <!-- <div class="alert alert-warning" role="alert"> -->
                                            <input class="form-control pull-left" id="igv" type="number" value="0.00" step="0.1" disabled>
                                            <!-- <br>
                                            <label class=""><input  class="pull-left" type="checkbox" id="con_igv" checked>costos incluyen IGV</label> -->
                                        <!-- </div> -->
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-warning-800" colspan="3">
                                        Total 
                                    </td>
                                    <td colspan="2">
                                        <input class="form-control" id="total"  type="number" value="0.00" step="0.1" disabled>
                                    </td>
                                </tr>
                            </tfoot>
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
                            <span id="bol_proveedorDireccion"></span><br>
                            <b>LUGAR DE ENTREGA:</b>
                            <span id="bol_lugarEntrega"></span>
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
                                        <th>COSTO UNIT.</th>                                        
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

                            <div class="col-md-12" id="submit-control">
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

        var cotis = [];
        var coti;

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
                            string += '<td>'+ parseFloat(value.costo_sin_igv).toFixed(2)+'</td>';
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
                        string += '<td>'+ parseFloat(value.costo_sin_igv).toFixed(2)+'</td>';
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
                string += '<td>'+ parseFloat(value.costo_sin_igv).toFixed(2)+'</td>';
                string += '<td>'+value.stockT+'</td>';
                string += '<td>'+value.medida_venta+'</td>';
                string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

            });
            $('#lista').html('').append(string);
            $('#grid_productos').html('').append(card);
        });

        function checkIdProducSelect($idproducto) {
            var isSelect = true;
            $("#lista_carrito #tr_item").each(function(){
                var prod_detail = $(this).find('#nombre_producto #ver');
                if ($idproducto == prod_detail.data('idproducto')) {
                    swal({
                        title: "Producto ya seleccionado",
                        text: "El producto " + prod_detail.html() + " ya se encuentra seleccionado",
                        confirmButtonColor: "#fde602",
                        type: "warning"
                    },function(){
                        //window.location.reload();
                    });
                    isSelect = false;;
                }
            });
            return isSelect;
        }

        $('#lista').on('click','#btn_agregar',function(event){
            var idproducto =  $(this).data('idprod');
            var producto = _.find(Lproductos,{idproducto : idproducto });
            if (checkIdProducSelect(idproducto)) {

                //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, costo: producto.costo, descuento: 0.0, total_costo: 0.0});

                var string = '<tr id="tr_item" >';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-costound="'+producto.costo_und+'" data-costo1a9="'+producto.costo_1a9+'" data-costo10a19="'+producto.costo_10a19+'" data-costo20a24="'+producto.costo_20a24+'" data-costo25a29="'+producto.costo_25a29+'" data-costo30="'+producto.costo_30+'" >'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';
                string += '<td id="costo_unitario"><input class="form-control" type="text" id="costo_prod" value="'+ producto.costo_sin_igv +'"></td>';
                string += '<td id="detalle_total"><input class="form-control" id="input_costo" type="number" step="0.1" value="0" disabled></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';                 

                $('#lista_carrito').append(string);
            }

        });

        $('#grid_productos').on('click','.row  .col-lg-3 .card', function(event){
            var idproducto = $(this).attr('id');
            var producto = _.find(Lproductos,{idproducto : parseInt(idproducto) });
            if (checkIdProducSelect(idproducto)) {
                
                //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, costo: producto.costo, descuento: 0.0, total_costo: 0.0});

                var string = '<tr id="tr_item">';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-costound="'+producto.costo_und+'" data-costo1a9="'+producto.costo_1a9+'" data-costo10a19="'+producto.costo_10a19+'" data-costo20a24="'+producto.costo_20a24+'" data-costo25a29="'+producto.costo_25a29+'" data-costo30="'+producto.costo_30+'">'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0" ></td> ';
                string += '<td id="costo_unitario"><input class="form-control" type="text" id="costo_prod" value="'+ producto.costo_sin_igv +'"></td>';
                string += '<td id="detalle_total"><input class="form-control" id="input_costo" type="number" step="0.1" value="0"disabled ></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';

                $('#lista_carrito').append(string);
            }


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
            var tcosto = (costo * cantidad).toFixed(4);
            total.val(tcosto);
            calcular_totales();
            calcular_igv();
        });

        $('#lista_carrito').on('change','#tr_item #costo_prod', function(event){
            var cantidad = parseFloat( ($(this).parent().parent().find( "#detalle_cantidad #input_cantidad" )).val());
            var total = $(this).parent().parent().find( "#detalle_total #input_costo" );
            var costo = parseFloat( $(this).val());
            $(this).val(costo.toFixed(4));
            var tcosto = (costo * cantidad).toFixed(4);
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

        /**************************BUSCAR CLIENTE*********/
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
            $('#search_prod').val('');
            $('#proveedor_search').val(proveedor.ruc_dni+' '+proveedor.razon_social);
            $('#search_results').addClass('hide').html('');

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

        $('#btn_guardar').click(function(event){

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

            var f_emision = $('#f_emision').val();
            var moneda = $('#moneda').val();
            var lugar_entrega = $('#lugar_entrega').val();

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

            if(moneda == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Moneda",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(lugar_entrega == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Lugar de Entrega",
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


            $('#bol_proveedor').html('').append(proveedor.razon_social );
            $('#bol_proveedorDireccion').html('').append(proveedor.direccion+', '+proveedor.distrito+', '+proveedor.provincia+', '+proveedor.departamento );
            // $('#bol_proveedorDni').html('').append(proveedor.dni);
            $('#bol_proveedorRUC').html('').append(proveedor.ruc_dni);
            $('#bol_proveedorTELF').html('').append(proveedor.contacto_telefono);
            $('#bol_lugarEntrega').html('').append(lugar_entrega);

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();

            if(dd<10) {
                dd = '0'+dd
            }

            if(mm<10) {
                mm = '0'+mm
            }

            today = mm + '/' + dd + '/' + yyyy;

            $('#bol_fecha').html('').append(today);
            $('#bol_empresa').html('').append('{{ $usuario->empresa }}');
            $('#bol_sucursal').html('').append('{{ $usuario->direccion  }}');
            $('#bol_empresaRuc').html('').append('{{$usuario->ruc}}');
            $('#bol_empresaTelefono').html('').append('{{ $usuario->telefono }}');

            var igv = $('#igv').val();
            var subtotal = $('#total_detalle').val();
            var descuento = $('#descuento').val();
            var total = $('#total').val();

            if(!$.isNumeric(igv) || !$.isNumeric(subtotal) || !$.isNumeric(descuento) || !$.isNumeric(total)){
                swal({
                    title: "Cantidad / Costo de compra de productos nulos",
                    text: "Debes escribir una cantidad de compra y un costo de compra en los productos seleccionados",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            $('#bol_total').html('').append($('#total').val());
            $('#bol_igv').html('').append($('#igv').val());
            $('#bol_total_detalle').html('').append($('#total_detalle').val());
            $('#bol_descuento').html('').append($('#descuento').val());
            $('#bol_pago').html('').append($('#paga').val());
            $('#bol_vuelto').html('').append($('#vuelto').val());

            var string = '';

            $("#lista_carrito #tr_item").each(function(){
                string += '<tr>';
                string += '<td>'+$(this).find('#nombre_producto #ver').html()+'</td>';
                string += '<td>'+$(this).find('#medida_venta #med').html()+'</td>';
                string += '<td>'+$(this).find('#detalle_cantidad #input_cantidad').val()+'</td>';
                string += '<td>'+$(this).find('#costo_unitario #costo_prod').val()+'</td>';
                string += '<td>'+$(this).find('#detalle_total #input_costo').val()+'</td>';
                string += '</tr>';
            });
            console.log(string);
            $('#bol_detalle').html('').append(string);
            
            $('#boleta_modal').modal();

        });

        $('#btn_confirmar').click(function(event){
            $('#btn_confirmar').prop( "disabled", true );
            var idproveedor = proveedor.idproveedor;
            var igv = $('#igv').val();
            var subtotal = $('#total_detalle').val();
            var descuento = $('#descuento').val();
            var total = $('#total').val();
            var medida_venta = $('#medida_venta').val();
            var comentarios = $('#comentarios').val();
            var f_emision = $('#f_emision').val();
            var moneda = $('#moneda').val();
            var lugar_entrega = $('#lugar_entrega').val();
            var observaciones = $('#observaciones').val();
            var productos = [];
            var paga = 0.00;//$('#paga').val();
            var vuelto = 0.00;//$('#vuelto').val();
            

            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var costo = $(this).find('#costo_unitario #costo_prod').val();
                var medida_venta = $(this).find('#medida_venta #med').val();
                productos.push({idproducto:producto,stock_total:stock_total,costo:costo});
            });

            var json_prod = JSON.stringify(productos);

            $.post(currentLocation+'crear_orden_compra',{idproveedor:idproveedor, igv:igv,subtotal:subtotal,descuento:descuento,medida_venta:medida_venta,total:total,comentarios:comentarios,f_emision:f_emision,productos:json_prod,paga:paga,vuelto:vuelto,moneda:moneda,lugar_entrega:lugar_entrega, observaciones:observaciones},function(data){
                console.log(data);
                var mensaje;
                var id;
                var obj = JSON.parse(data, function (key, value) {
                    if (key == "created" && value ==200) {
                        mensaje = value;
                        id = "id";
                        console.log("acaa");
                        console.log(obj);
                        console.log(data);
                    }if (key == "id") {
                        id = value;
                        console.log("acaa");
                        console.log(id);
                    }  

                });
                
                if(mensaje === 200){
                        setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                        window.open(currentLocation+"info_orden_compra?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
                        window.open(currentLocation+"listado_orden_compra");
                        window.location.reload(); 
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar la orden de compra, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_confirmar').prop( "disabled", false );
                    });
                    return;
                }
            });
        });

   </script>
@stop