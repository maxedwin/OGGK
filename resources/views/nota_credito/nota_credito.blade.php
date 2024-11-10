@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-remove-circle position-left"></i> <span class="text-semibold">Crear Nota de Crédito / Devoluciones</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/listado_nota_credito"></i>Listado de Notas de Crédito - Devoluciones</a></li>
    <li class="active">Crear Nota de Crédito - Devoluciones</li>
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

                            <div class="input-group ">
                                <label for="documento">Tipo de Devolución:</label>
                                <select class="form-control input-lg" id="documento">
                                    <option value="1">Nota de Crédito</option>
                                    <!--option value="2">Devolución</option-->
                                </select>
                            </div>

                            <div class="input-group ">
                                <label for="tipo_descuento">Por Descuento?:</label>
                                <select class="form-control input-lg" id="tipo_descuento">
                                    <!--option value="0"> -- </option-->
                                    <option value="1">SI</option>
                                    <option value="2">NO</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label for="factbol_search">Buscar Factura / Boleta:</label>
                                <input type="text" class="form-control input-lg"  id="factbol_search"  placeholder="Buscar una Factura o Boleta" value="{{$idcaja != 0 ? $idcaja : ''}}">
                                <div id="prueba" class="list-group col-md-12 hide"></div>
                            </div>

                            <div class="input-group pull-right">
                                <button type="button" class="btn btn-default btn-lg" id="eliminar_all" >
                                    <i class="glyphicon glyphicon-trash"></i>
                                    Limpiar
                                </button>
                                <button class="btn btn-info btn-lg" id="btn_guardar" data-idprod="9">
                                    <i class="glyphicon glyphicon-save"></i>
                                    Guardar
                                </button>
                            </div>
                            
                            <div class="input-group">
                                <label for="cliente_search">Buscar Cliente:</label>
                                <input type="text" class="form-control input-lg"  id="cliente_search"  placeholder="Buscar por Razon Social o RUC">
                                <div id="search_results" style="margin-top:30px;" class="list-group col-md-12 hide"></div>
                            </div>
                        
                        </div>
                        
                        <div class="form-group form-inline">
                            <div class="input-group ">
                                <label for="fechaNB">Fecha de Emision:</label>
                                <input type="date" class="form-control" id="fechaNB">
                            </div> 
                            <div class="input-group ">
                                <label for="f_devolucion">Fecha de Devolución:</label>
                                <input type="date" class="form-control" id="f_devolucion">
                            </div>
                            <div class="input-group ">
                                <label for="idvendedor">Vendedor:</label>
                                <select id="idvendedor" class="form-control">
                                        <option value=0> -- </option>
                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{ $vendedor->id}}">{{$vendedor->name}}</option>
                                    @endforeach
                                </select>
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

                            <div class="input-group ">
                            <label for="moneda">Moneda:</label>
                            <select class="form-control" id="moneda">
                                    <option value="1">Soles</option>
                                    <option value="2">Dolares</option>
                                    <option value="3">Euros</option>
                            </select>
                            </div>    
                        </div>    

                        <div class="form-group form-inline">

                            <div class="input-group ">
                                <label for="cod_motivo">Tipo Nota de Crédito:</label>
                                <select class="form-control input-lg" id="cod_motivo">
                                    <option value="01">ANULACÍON DE LA OPERACIÓN</option>
                                    <option value="02">ANULACÍON POR ERROR EN EL RUC</option>
                                    <option value="03">CORRECCIÓN POR ERROR EN LA DESCRIPCIÓN</option>
                                    <option value="04">DESCUENTO GLOBAL</option>
                                    <option value="05">DESCUENTO POR ÍTEM</option>
                                    <option value="06">DEVOLUCIÓN TOTAL</option>
                                    <option value="07">DEVOLUCIÓN POR ÍTEM</option>
                                    <option value="08">BONIFICACIÓN</option>
                                    <option value="09">DISMINUCIÓN EN EL VALOR</option>
                                    <option value="10">OTROS CONCEPTOS</option>
                                    <option value="11">AJUSTES AFECTOS AL IVAP</option>
                                    <option value="12">AJUSTES DE OPERACIONES DE EXPORTACIÓN</option>
                                    <option value="13">AJUSTES - MONTOS Y/O FECHAS DE PAGO</option>
                                </select>
                            </div>

                            <div class="input-group hidden">
                                <label for="is_invoice">¿Generar Nota Cred. Electrónica?</label>
                                <select class="form-control" id="is_invoice">
                                    <option value="0">SI</option>
                                    <option value="1">No</option>
                                </select>
                            </div>

                            <div id="codigoNB-group" class="input-group hidden">
                                <label for="codigoNB">Código:</label>
                                <input type="text" class="form-control" id="codigoNB">
                            </div>
                            
                            <!--div class="input-group ">
                                <label for="razon">Razón:</label>
                                <textarea onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="razon" rows="2"></textarea>
                            </div-->
                            
                        </div>    

                    </div>
                </div>
            </div>



        <!--div class="col-md-12">
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
                                        <th>Precio x Unidad</th>
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
            </div-->

            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <table  class="table table-borderless">
                            <thead>
                                <tr class="bg-primary-700">
                                    <th>Producto</th>
                                    <th>Unidad Venta</th>
                                    <th>Cantidad Venta</th>
                                    <th>Lote</th>
                                    <th>Valor Venta</th>
                                    <th>Valor Total</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody id="lista_carrito" >
                            </tbody>
                            <tfoot style="text-align: right">
                                <tr>
                                    <td class="bg-primary-300" colspan="3">
                                        Sub-Total 
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="total_detalle" type="number" value="0.00" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary-400" colspan="3">
                                        Descuento 
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="descuento" type="number" value="0.00" step="0.1" >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary-600" colspan="3">
                                       (0.18%)  IGV <br>
                                    </td>
                                    <td colspan="2">
                                        <!-- <div class="alert alert-warning" role="alert"> -->
                                            <input class="form-control pull-left" id="igv" type="number" value="0.00" step="0.1" disabled>
                                            <!-- <br>
                                            <label class=""><input  class="pull-left" type="checkbox" id="con_igv" checked>Precios incluyen IGV</label> -->
                                        <!-- </div> -->
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-primary-800" colspan="3">
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
                            <b>RECIBO:</b>
                            <span id="bol_recibo"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-sm-6" id="datos_cliente">
                            <!-- <h5>CLIENTE:</h5> -->
                            <b>CLIENTE:</b>
                            <span id="bol_cliente"></span><br>
                            <!-- <b>DNI:</b>
                            <span id="bol_clienteDni"></span><br> -->
                            <b>RUC:</b>
                            <span id="bol_clienteRUC"></span><br>
                            <b>TELEFONO:</b>
                            <span id="bol_clienteTELF"></span><br>
                            <b>DIRECCION:</b>
                            <span id="bol_clienteDireccion"></span>
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
                                        <th>VALOR UNIT.</th>                                        
                                        <th>VALOR TOTAL</th>
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

<script src="{{ URL::asset('/javascript/helper.js') }}" type="text/javascript"></script>
<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>

   <script>
        window.onload = function () {
            calc.init();
        };
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
        var todayHelper = getTodayFormat();
        var Lproductos = [];
        var precio_total = 0.00;

        var clientes = [];
        var cliente;

        var cajas = [];
        var caja;

        var f_min = new Date();
        f_min.setDate(f_min.getDate()-3)
        $('#fechaNB').val(todayHelper).attr({"min" : getDateFormat(f_min)});

        $('#grid_productos').hide();

        $('#comentarios').wysihtml5({
            parserRules:  wysihtml5ParserRules,
            stylesheets: ["assets/css/components.css"],
            "image": false,
            "link": false,
            "font-styles": false,
            "emphasis": false
        });

        $('#is_invoice').on('change', function() {
            if (this.value == '0') {
                $('#codigoNB-group').addClass('hidden');
            } else {
                $('#codigoNB-group').removeClass('hidden');
            }
        });


        $('#busqueda_query').on('keydown', function(event) {
            if (event.which == 13 || event.keyCode == 13) {
                var query = $('#busqueda_query').val();
                var almacen = $('#almacen').val();
                var data2 = {query:query, almacen:almacen};

                $.get( "{{route('buscarProductoNC')}}" ,data2,function(data){
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
                            string += '<td>'+ parseFloat(value.precio).toFixed(2)+'</td>';
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
            var almacen = $('#almacen').val();
            var Lcategorias = [];
            var data2 = {query:query, almacen:almacen};

                $.get( "{{route('buscarProductoNC')}}" ,data2,function(data){
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
                        string += '<td>'+ parseFloat(value.precio).toFixed(2)+'</td>';
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
                string += '<td>'+ parseFloat(value.precio).toFixed(2)+'</td>';
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

            var almacen = $('#almacen').val();
            var data2 = {query:producto.idproducto, almacen:almacen};

            $.get( "{{route('buscarLoteNC')}}" ,data2,function(data){
                var lotes = jQuery.parseJSON( data );
            
                    console.log("yaaa1");
                    console.log(lotes);
            //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

                var string = '<tr id="tr_item" >';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';
                
                string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                        $.each(lotes, function(key,value){
                            string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                        });
                string +='</select></td>';


                string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ parseFloat(producto.precio).toFixed(2) +'"></td>';
                string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="0" disabled></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';                 

                $('#lista_carrito').append(string);

            });

        });

        $('#grid_productos').on('click','.row  .col-lg-3 .card', function(event){
            var idproducto = $(this).attr('id');
            var producto = _.find(Lproductos,{idproducto : parseInt(idproducto) });

            var almacen = $('#almacen').val();
            var data2 = {query:producto.idproducto, almacen:almacen};

            $.get( "{{route('buscarLoteNC')}}" ,data2,function(data){
                var lotes = jQuery.parseJSON( data );
            
                    console.log("yaaa1");
                    console.log(lotes);
            //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

                var string = '<tr id="tr_item">';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'">'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0" ></td> ';
                
                string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                        $.each(lotes, function(key,value){
                            string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                        });
                string +='</select></td>';

                string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ parseFloat(producto.precio).toFixed(2) +'"></td>';
                string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="0"disabled ></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';

                $('#lista_carrito').append(string);

            });


        });

        /********************CALCULAR TOTALES***********************************/

        function calcular_totales(){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_precio');
                total_detalle += parseFloat(importe.val());
            });
            //if ($('#con_igv').is(':checked')) {
                //precio_total = (total_detalle);
                //$('#total').val(precio_total ) ;

            //}else{
                var subtotal = (total_detalle/1.18);
                var igv = $('#igv').val();
                precio_total = (subtotal + parseFloat(igv));
                $('#total').val(precio_total.toFixed(2)) ;
            //}
            calcular_descuento();
        }

        function calcular_igv(){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_precio');
                total_detalle += parseFloat(importe.val());
            });
            var subtotal = (total_detalle/1.18);
            var igv = total_detalle-subtotal;
            $('#total_detalle').val(subtotal.toFixed(2));
            $('#igv').val(igv.toFixed(2));

            calcular_totales();
        }

        $('#con_igv').on('change',function(event){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_precio');
                total_detalle += parseFloat(importe.val());
            });
            //if ($('#con_igv').is(':checked')) {
                //precio_total = (total_detalle);
                //$('#total').val(precio_total ) ;
            //}else{
                var igv = $('#igv').val();
                precio_total = (total_detalle + parseFloat(igv));
                $('#total').val(precio_total ) ;
            //}
            calcular_descuento()
        });

        $('#tipo_descuento').on('change',function(event){
            console.log($('#tipo_descuento').val());
            //var dis = ($('#tipo_descuento').val() == '2' ? true : false);
            //$('.precio_prod').prop( "disabled", dis );
        });

        function calcular_descuento(){
            var descuento = $('#descuento').val();
            $('#descuento').val( parseFloat(descuento).toFixed(2) );
            var precio = precio_total - descuento;
            $('#total').val(precio.toFixed(2));

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
            var precio = precio_total - descuento;
            $('#total').val(precio.toFixed(2));
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
            var cantidad = parseFloat( $(this).val() );
            var cantidad_caja = $(this).parent().parent().find( "#nombre_producto #ver" ).data('caja');
            
            var precio_und    = $(this).parent().parent().find( "#nombre_producto #ver" ).data('preciound');
            var precio_1a9    = $(this).parent().parent().find( "#nombre_producto #ver" ).data('precio1a9');
            var precio_10a19  = $(this).parent().parent().find( "#nombre_producto #ver" ).data('precio10a19');
            var precio_20a24  = $(this).parent().parent().find( "#nombre_producto #ver" ).data('precio20a24');
            var precio_25a29  = $(this).parent().parent().find( "#nombre_producto #ver" ).data('precio25a29');
            var precio_30     = $(this).parent().parent().find( "#nombre_producto #ver" ).data('precio30');

            var precio = 0.00; //$(this).parent().parent().find( "#precio_unitario #precio_prod" ).val();

            console.log(cantidad/cantidad_caja);

            if( cantidad/cantidad_caja < 1){
                precio = precio_und;  
            }
            else if( cantidad/cantidad_caja > 1  && cantidad/cantidad_caja < 10){
                precio = precio_1a9;  
            }
            else if( cantidad/cantidad_caja > 10 && cantidad/cantidad_caja < 20){
                precio = precio_10a19;  
            }
            else if( cantidad/cantidad_caja > 20 && cantidad/cantidad_caja < 25){
                precio = precio_20a24;  
            }
            else if( cantidad/cantidad_caja > 25 && cantidad/cantidad_caja < 30){
                precio = precio_25a29;  
            }
            else {
                precio = precio_30;  
            }
            if (precio === null) {
                precio = parseFloat($(this).parent().parent().find('#precio_unitario #precio_prod').val());
            }
            $(this).parent().parent().find('#precio_unitario #precio_prod').val(precio);
            
            var total = $(this).parent().parent().find( "#detalle_total #input_precio" );       
            var tprecio = (precio * cantidad).toFixed(4);
            total.val(tprecio);
            
            calcular_totales();
            calcular_igv();
        });

        $('#lista_carrito').on('change','#tr_item #precio_prod', function(event){
            var cantidad = parseFloat( ($(this).parent().parent().find( "#detalle_cantidad #input_cantidad" )).val());
            var total = $(this).parent().parent().find( "#detalle_total #input_precio" );
            var precio = parseFloat( $(this).val());
            $(this).val(precio.toFixed(4));
            var tprecio = (precio * cantidad).toFixed(4);
            total.val(tprecio);
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

         /**************************BUSCAR cajas*********/
         $('#factbol_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                caja = temp;
            }
        });

        $('#factbol_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  0){
                $.get(currentLocation+"buscarFTNum?query="+query+"", function( data ) {
                    $('#prueba').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(cajas,{idcajah:value.idcajah}) == null){
                            cajas.push(value); }
                        
                        var recibo;

                        if(value.tipo==2){
                            recibo='FT';
                        }else if(value.tipo==1){
                            recibo='BT';
                        }else{}

                        $('#prueba')
                            .removeClass('hide')
                            .append("<div id='item_to_add2' class='list-group-item' name='"
                                +value.idcajah+"' >"+ recibo + ' - ' + value.numeracion+'</div>');

                    });
                });
            }else{
                $('#prueba').addClass('hide').html('');

            }
        });

        $('#item_to_add2').hover(function() {
                $( this ).toggleClass( "active" );
            }, function() {
                $( this ).removeClass( "active" );
            }
        );

        var idclientefinal;
        $( "#prueba" ).on( "click","#item_to_add2", function() {
            $('#lista_carrito').html('');
            var idcajah = $(this).attr('name');
            caja = _.findWhere(cajas, {idcajah: parseInt(idcajah)});
            $('#search_prod2').val('');
            
            var recibo;

            if(caja.tipo==2){
                recibo='FT';
            }else if(caja.tipo==1){
                recibo='BT';
            }else{}

            $('#factbol_search').val(recibo+' - '+caja.numeracion);
            $('#prueba').addClass('hide').html('');

            var query = caja.idcajah;

            $.get(currentLocation+"buscarFTTodo?query="+query+"", function( data ) {
                var obj = JSON.parse(data);

                console.log('todo');
                console.log(obj);
                idclientefinal = obj.idcliente;
                var almacen = obj.idalmacen;

                $('#cliente_search').val(obj.razon_social);

                $('#f_entrega').val(obj.f_entrega);
                $('#f_cobro').val(obj.f_cobro);
                $('#idvendedor').val(obj.idvendedor);
                $('#moneda').val(obj.moneda);
                $('#almacen').val(obj.idalmacen);


                $('#total_detalle').val(obj.subtotal);
                $('#igv').val(obj.igv);
                $('#total').val(obj.total);

                var dis = ($('#tipo_descuento').val() == '2' ? 'disabled' : '');

                $.each(obj.detalle, function(index, producto) {
                    
                    var data2 = {query:producto.idlote};
                    $.get( "{{route('buscarLoteSimple')}}" ,data2,function(lotes_data){
                        var obj_lote = JSON.parse(lotes_data);
                        console.log(obj_lote);

                        if (obj_lote) {
                            var string = '<tr id="tr_item" >';
                            string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                            string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                            string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+'" ></td> ';
                                                
                            string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                                string += '<option value="' + obj_lote.idlote+ '">' + obj_lote.codigo + '</option>'
                            string +='</select></td>';

                            string += '<td id="precio_unitario"><input class="form-control precio_prod" type="text" id="precio_prod" value="'+ producto.precio_unit +'"></td>';
                            string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="'+producto.precio_total+'" disabled></td> ';
                            string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'"><i class="glyphicon glyphicon-remove"></i></button></td> ';

                            string += '</tr> ';
                        } else if (producto.tipo == 2) {
                            var string = '<tr id="tr_item" >';
                            string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                            string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                            string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+'" ></td> ';
                                                
                            string += '<td id="lote"><input name="input_lote" id="input_lote" type="hidden" value="0"></td>';

                            string += '<td id="precio_unitario"><input class="form-control precio_prod" type="text" id="precio_prod" value="'+ producto.precio_unit +'"></td>';
                            string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="'+producto.precio_total+'" disabled></td> ';
                            string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'"><i class="glyphicon glyphicon-remove"></i></button></td> ';

                            string += '</tr> ';
                        }

                        $('#lista_carrito').append(string);
                    });
                    
                });               

                $.get(currentLocation+"buscarClienteOV?query="+idclientefinal+"", function( data ) {
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(clientes,{idcliente:value.idcliente}) == null){
                            clientes.push(value); }                   
                    });
                });

            }); 

        });

        $( document ).ready(function() {
            var idcajah = $('#factbol_search').val();
            if (idcajah != '') {

                var query = idcajah;

                $.get(currentLocation+"buscarFTTodo?query="+query+"", function( data ) {
                    var obj = JSON.parse(data);

                    cajas.push(obj);
                    caja = obj;

                    var recibo;

                    if(obj.tipo==2){
                        recibo='FT';
                    }else if(obj.tipo==1){
                        recibo='BT';
                    }else{}

                    $('#factbol_search').val(recibo+' - '+obj.numeracion);

                    console.log('todo');
                    console.log(obj);
                    idclientefinal = obj.idcliente;
                    var almacen = obj.idalmacen;

                    $('#cliente_search').val(obj.razon_social);

                    $('#f_entrega').val(obj.f_entrega);
                    $('#f_cobro').val(obj.f_cobro);
                    $('#idvendedor').val(obj.idvendedor);
                    $('#moneda').val(obj.moneda);
                    $('#almacen').val(obj.idalmacen);


                    $('#total_detalle').val(obj.subtotal);
                    $('#igv').val(obj.igv);
                    $('#total').val(obj.total);

                    var dis = ($('#tipo_descuento').val() == '2' ? 'disabled' : '');

                    $.each(obj.detalle, function(index, producto) {
                        
                        var data2 = {query:producto.idlote};
                        $.get( "{{route('buscarLoteSimple')}}" ,data2,function(lotes_data){
                            var obj_lote = JSON.parse(lotes_data);
                            console.log(obj_lote);

                            if (obj_lote) {
                                var string = '<tr id="tr_item" >';
                                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+'" ></td> ';
                                                    
                                string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                                    string += '<option value="' + obj_lote.idlote+ '">' + obj_lote.codigo + '</option>'
                                string +='</select></td>';

                                string += '<td id="precio_unitario"><input class="form-control precio_prod" type="text" id="precio_prod" value="'+ producto.precio_unit +'"></td>';
                                string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="'+producto.precio_total+'" disabled></td> ';
                                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'"><i class="glyphicon glyphicon-remove"></i></button></td> ';

                                string += '</tr> ';
                            } else if (producto.tipo == 2) {
                                var string = '<tr id="tr_item" >';
                                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+'" ></td> ';
                                                    
                                string += '<td id="lote"><input name="input_lote" id="input_lote" type="hidden" value="0"></td>';

                                string += '<td id="precio_unitario"><input class="form-control precio_prod" type="text" id="precio_prod" value="'+ producto.precio_unit +'"></td>';
                                string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="'+producto.precio_total+'" disabled></td> ';
                                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'"><i class="glyphicon glyphicon-remove"></i></button></td> ';

                                string += '</tr> ';
                            }

                            $('#lista_carrito').append(string);
                        });
                        
                    });               

                    $.get(currentLocation+"buscarClienteOV?query="+idclientefinal+"", function( data ) {
                        var obj = JSON.parse(data);
                        $.each(obj, function(index, value) {
                            if(_.findWhere(clientes,{idcliente:value.idcliente}) == null){
                                clientes.push(value); }                   
                        });
                    });

                });
            }
        });


        /**************************BUSCAR CLIENTE*********/
        $('#cliente_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                cliente = temp;
            }
        });

        $('#cliente_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  3){
                $.get(currentLocation+"buscarCliente?query="+query+"", function( data ) {
                    $('#search_results').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(clientes,{idcliente:value.idcliente}) == null){
                            clientes.push(value); }

                        $('#search_results')
                            .removeClass('hide')
                            .append("<div id='item_to_add' class='list-group-item' name='"
                                +value.idcliente+"' >"+value.ruc_dni +' ' + value.razon_social+'</div>');

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
            var idcliente = $(this).attr('name');
            cliente = _.findWhere(clientes, {idcliente: parseInt(idcliente)});
            $('#search_prod').val('');
            $('#cliente_search').val(cliente.ruc_dni+' '+cliente.razon_social);
            $('#search_results').addClass('hide').html('');

        });

        /*******************************************NUEVO CLIENTE**********************/
        $('#btn_nuevoCliente').click(function(event){
            window.open("nuevo_cliente", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
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
            var doc = $('#documento').val();

            if (idclientefinal != null)
                cliente = _.findWhere(clientes, {idcliente: parseInt(idclientefinal)});

            if(typeof cliente === "undefined"){
                swal({
                    title: "Falta el campo Cliente",
                    text: "Debes seleccionar un cliente.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if( cliente.ruc === null && doc === '2' ){
                swal({
                    title: "Falta el RUC del Cliente",
                    text: "Debes registar el ruc de "+ cliente.ruc_dni+ ' '+ cliente.razon_social,
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            var f_devolucion = $('#f_devolucion').val();
            var idvendedor = $('#idvendedor').val();
            var idalmacen = $('#almacen').val();
            //var razon = $('#razon').val();
            var cod_motivo = $("#cod_motivo").val();
            var desc_motivo = $("#cod_motivo option:selected").text();
            var razon = desc_motivo;
            var codigoNB = $('#codigoNB').val();
            var fechaNB = $('#fechaNB').val();
            var is_invoice = $('#is_invoice').val();

            console.log(razon);

            if(typeof caja === "undefined"){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Factura/Boleta correspondiente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(fechaNB.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Emision",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(f_devolucion.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Devolución",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(idvendedor == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Vendedor",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

              if($('#tipo_descuento').val() !=1 && idalmacen == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Almacen",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if((is_invoice == '1' || is_invoice == 1) && codigoNB.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Codigo",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(razon.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar alguna Razón y/o Comentario",
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




            $('#bol_cliente').html('').append(cliente.razon_social );
            $('#bol_clienteDireccion').html('').append(cliente.direccion+', '+cliente.distrito+', '+cliente.provincia+', '+cliente.departamento );
            // $('#bol_clienteDni').html('').append(cliente.dni);
            $('#bol_clienteRUC').html('').append(cliente.ruc_dni);
            $('#bol_clienteTELF').html('').append(cliente.contacto_telefono);

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

            $('#bol_total').html('').append($('#total').val());
            $('#bol_igv').html('').append($('#igv').val());
            $('#bol_total_detalle').html('').append($('#total_detalle').val());
            $('#bol_descuento').html('').append($('#descuento').val());
            $('#bol_pago').html('').append($('#paga').val());
            $('#bol_vuelto').html('').append($('#vuelto').val());
            $('#bol_f_devolucion').html('').append($('#f_devolucion').val());
            $('#bol_f_cobro').html('').append($('#f_cobro').val());
            $('#bol_idvendedor').html('').append($('#idvendedor').val());

            var string = '';
            var totalcmp=0;
            var total = $('#total').val();

            $("#lista_carrito #tr_item").each(function(){
                string += '<tr>';
                string += '<td>'+$(this).find('#nombre_producto #ver').html()+'</td>';
                string += '<td>'+$(this).find('#medida_venta #med').html()+'</td>';
                string += '<td>'+$(this).find('#detalle_cantidad #input_cantidad').val()+'</td>';
                //string += '<td>'+$(this).find('#lote #input_lote').val()+'</td>';
                string += '<td>'+$(this).find('#precio_unitario #precio_prod').val()+'</td>';
                string += '<td>'+$(this).find('#detalle_total #input_precio').val()+'</td>';
                string += '</tr>';
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var precio = $(this).find('#precio_unitario #precio_prod').val();
                totalcmp+= (stock_total*precio);


            });
            console.log(totalcmp.toFixed(2));
            console.log('yapues');
            console.log(parseFloat(total).toFixed(2));
            console.log(string);
            if( totalcmp.toFixed(2) != parseFloat(total).toFixed(2) ){
                swal({
                    title: "Los totales no coiniciden",
                    text: "Debe coincidir el total de los productos con el total de la NC",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            $('#bol_detalle').html('').append(string);
            $('#bol_recibo').html('').append($('#documento :selected').html());
            $('#boleta_modal').modal();

        });

        $('#btn_confirmar').click(function(event){
            $('#btn_confirmar').prop( "disabled", true );

            var idcliente = cliente.idcliente;
            var idcajah = caja.idcajah;
            var igv = $('#igv').val();
            var almacen = $('#almacen').val();           
            var subtotal = $('#total_detalle').val();
            var descuento = $('#descuento').val();
            var total = $('#total').val();
            var medida_venta = $('#medida_venta').val();
            var comentarios = $('#comentarios').val();
            var f_devolucion = $('#f_devolucion').val();
            var f_cobro = $('#f_cobro').val();
            var idvendedor = $('#idvendedor').val();
            var productos = [];
            var paga = 0.00;//$('#paga').val();
            var vuelto = 0.00;//$('#vuelto').val();
            var tipo = $('#documento').val();            
            var moneda = $('#moneda').val();
            var codigoNB = $('#codigoNB').val();
            var fechaNB = $('#fechaNB').val();
            var tipo_descuento = $('#tipo_descuento').val();
            var is_invoice = $('#is_invoice').val();
            var cod_motivo = $('#cod_motivo') .val();
            var desc_motivo = $("#cod_motivo option:selected").text();
            var razon = desc_motivo;
            
            // var codigoNB;
            // if ($('#codigoNB').val() == null){
            //     codigoNB = '00';
            // }else{
            //     codigoNB = $('#codigoNB').val();
            // }

            //var semi_total=0;
            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var idlote = $(this).find('#lote #input_lote').val();
                var precio = $(this).find('#precio_unitario #precio_prod').val();
                var medida_venta = $(this).find('#medida_venta #med').val();
                productos.push({idproducto:producto,stock_total:stock_total,idlote:idlote,precio:precio});
                //semi_total += (stock_total*precio);
            });
 
            //console.log(productos);
            //return;
            var json_prod = JSON.stringify(productos);

            $.post(currentLocation+'crear_nota_credito',{idcliente:idcliente,almacen:almacen,razon:razon,idcajah:idcajah,igv:igv,subtotal:subtotal,descuento:descuento,medida_venta:medida_venta,total:total,comentarios:comentarios,f_devolucion:f_devolucion,f_cobro:f_cobro,idvendedor:idvendedor,productos:json_prod,paga:paga,vuelto:vuelto,tipo:tipo,moneda:moneda, codigoNB:codigoNB, tipo_descuento:tipo_descuento, fechaNB:fechaNB, is_invoice:is_invoice, cod_motivo:cod_motivo, desc_motivo: desc_motivo},function(data){
                var obj = JSON.parse(data);
                var mensaje = obj[0].created;
                /*var id;
                var obj = JSON.parse(data, function (key, value) {
                    if (key == "created") {
                        mensaje = value;
                    } 
                    if (key == "id") {
                        id = value;
                    } 
                });*/
                console.log(mensaje);
                if(mensaje === 200){
                    var id = obj[1].id;
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    if(parseInt(obj[4].code) > 0 && parseInt(obj[4].code) < 4000){
                        $('#boleta_modal').modal('hide');
                        swal({
                            title: "Lo sentimos!",
                            text:"SUNAT rechazó el comprobante por tener errores, tendrá que corregirlos y enviarlo de nuevo. "+ obj[2].msg,
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){                           
                            window.location.href=currentLocation+"nota_credito";
                        });
                        return;
                    }
                    else if(obj[5].reenviar=="si" ){
                        $('#boleta_modal').modal('hide');
                        swal({
                            title: "El comprobante se creó y envió a SUNAT",
                            text:"Estamos esperando el CDR de SUNAT. Presione el botón de recuperar documento más tarde.",
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){                           
                            if(obj[3].pdf !=null && obj[3].pdf !="")
                                window.open(currentLocation+"greenter/"+ obj[3].pdf,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                            else
                                window.open(currentLocation+"info_nota_credito?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        window.location.href=currentLocation+"nota_credito";
                        });
                        return;

                    }
                    else if (obj[2].msg == '') {
                        if(obj[3].pdf !=null && obj[3].pdf !="")
                        window.open(currentLocation+"greenter/"+ obj[3].pdf,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        else
                        window.open(currentLocation+"info_nota_credito?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        window.location.href=currentLocation+"nota_credito";
                    } else {
                        $('#boleta_modal').modal('hide');
                        swal({
                            title: "Ok!",
                            text: obj[2].msg,
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){
                            if(obj[3].pdf !=null && obj[3].pdf !="")
                                window.open(currentLocation+"greenter/"+ obj[3].pdf,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                            else
                                window.open(currentLocation+"info_nota_credito?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                            window.location.href=currentLocation+"nota_credito";
                        });
                        return;
                    }
                }else if(mensaje === 999){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "NO!",
                        text: "Código de NubeFact repetido! Revisa por favor.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#btn_confirmar').prop( "disabled", false);
                    });
                    return;
                }else if(mensaje === 997){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "NO!",
                        text: "La cantidad de los productos superan al valor original de la Factura/Boleta.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#btn_confirmar').prop( "disabled", false);
                    });
                    return;
                }else if(mensaje === 996){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "NO!",
                        text: "Se selecciono Por Descuento: NO, las cantidades de productos deberian ser totales",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#btn_confirmar').prop( "disabled", false);
                    });
                    return;
                }else if(mensaje === 995){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "NO!",
                        text: "Se selecciono Por Descuento: SI, las cantidades de productos deberian ser parciales",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#btn_confirmar').prop( "disabled", false);
                    });
                    return;
                }else if(obj[0].created == 501){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "Error!",
                        text: obj[0].msg,
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#btn_confirmar').prop( "disabled", false );
                    });
                    return;
                }else{
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar la nota de credito/devolucion, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_confirmar').prop( "disabled", false);
                    });
                    return;
                }
                
            });



        });





   </script>
@stop