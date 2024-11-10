@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-usd position-left"></i> <span class="text-semibold">Crear Factura / Boleta</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/listado_caja"></i>Listado de Facturas - Boletas</a></li>
    <li class="active">Crear Factura - Boleta</li>
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
                                <label for="documento">Tipo de Recibo:</label>
                                <select class="form-control input-lg" id="documento">
                                    <option value="0"> -- </option>
                                    <option value="2">Factura</option>
                                    <option value="1">Boleta</option>
                                </select>
                            </div>

                             <div class="input-group">
                                <label for="factbol_search">Buscar Orden de Venta:</label>
                                <input type="text" class="form-control"  id="factbol_search"  placeholder="Buscar una Orden de Venta">
                                <div id="search_results2" class="list-group col-md-12 hide"></div>
                            </div>

                            <div class="input-group">
                                <label for="factbol_search">Buscar Guía/s de Remisión:</label>
                                <div>
                                    <select class="selectpicker" id="guias_select" multiple="multiple" data-live-search="true" title="Busca tu/s Guía/s..."> 
                                        @foreach ($guias as $guia)
                                            <option value="{{ $guia->id_guia_remisionh }}">GR - {{$guia->numeracion}} // {{$guia->codigoNB}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="cliente_search">Buscar Cliente:</label>
                                <input type="text" class="form-control input-lg"  id="cliente_search"  placeholder="Buscar por Razon Social o RUC">
                                <div id="search_results" style="margin-top:30px;" class="list-group col-md-12 hide"></div>
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

                        </div>

                        
                        <div class="form-group form-inline">

                            <div class="input-group ">
                                <label for="fechaNB">Fecha de Emision:</label>
                                <input type="date" class="form-control" id="fechaNB">
                            </div> 
                            <div class="input-group ">
                                <label for="f_entrega">Fecha de Entrega:</label>
                                <input type="date" class="form-control" id="f_entrega">
                            </div>
                            <div class="input-group ">
                                <label for="f_cobro">Fecha de Cobro:</label>
                                <input type="date" class="form-control" id="f_cobro">
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
                                <label for="moneda">Moneda:</label>
                                <select class="form-control" id="moneda">
                                        <option value="1">Soles</option>
                                        <option value="2">Dolares</option>
                                        <option value="3">Euros</option>
                                </select>
                            </div> 
                            <div class="input-group">
                                <label for="tipo_cambio">Tipo de cambio:</label>
                                <input type="text" class="form-control input-lg"  id="tipo_cambio"  placeholder="Ingrese el tipo de cambio" disabled = "true">
                            </div>  

                        </div>    

                        <div class="form-group form-inline">
                            
                            <div class="input-group ">
                                <label for="is_invoice">¿Generar Factura/Boleta Electrónica?</label>
                                <select class="form-control input-lg" id="is_invoice">
                                    <option value="0">SI</option>
                                    <option value="1">No</option>
                                </select>
                            </div>

                            <div id="codigoNB-group" class="input-group hidden">
                                <label for="codigoNB">Código:</label>
                                <input type="text" class="form-control" id="codigoNB">
                            </div> 

                            <div class="input-group ">
                                <label for="forma_pago">Forma de Pago</label>
                                <select class="form-control input-lg" id="forma_pago">
                                    <option value="0">Contado</option>
                                    <option value="1">Credito</option>
                                </select>
                            </div>
                        
                        </div>

                        <div id="cuotas_line">
                        </div>

                    </div>
                </div>
            </div>



        <!--div class="col-md-9">
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
                                    <tr class="bg-success-700">
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
                                <tr class="bg-success-700">
                                    <th>Producto</th>
                                    <th>Unidad Venta</th>
                                    <th>Cantidad Venta</th>
                                    <th>Precio Venta (con IGV)</th>
                                    <th>Precio Total</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody id="lista_carrito" >
                            </tbody>
                            <tfoot style="text-align: right">
                                <tr>
                                    <td class="bg-success-300" colspan="3">
                                        Sub-Total 
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="total_detalle" type="number" value="0.00" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-success-400" colspan="3">
                                        Descuento
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="descuento" type="number" value="0.00" step="0.1" >
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-success-600" colspan="3">
                                       (0.18%)  IGV<br>
                                    </td>
                                    <td colspan="2">
                                        <!-- <div class="alert alert-warning" role="alert"> -->
                                            <input class="form-control pull-left" id="igv" type="number" value="0.00" step="0.1" disabled>
                                            <!-- <br>
                                            <label class=""><input  class="pull-left" type="checkbox" id="con_igv" checked>Precios incluyen IGV</label> -->
                                        <!-- </div> -->
                                    </td>
                                </tr>
                                <tr id="envio_contenedor">
                                    <td class="bg-success-400" colspan="3">
                                        Envio
                                    </td>
                                    <td colspan="2">
                                      <input class="form-control" id="envio" type="number" value="0.00" step="0.1" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-success-800" colspan="3">
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
                                    <th>ENVIO</th>
                                    <td id="bol_envio"></td>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

   <script>

        $('#multi').selectpicker();     

        window.onload = function () {
            calc.init();
            /*fetch('https://api.cambio.today/v1/quotes/USD/PEN/json?quantity=1&key=7616|Z5b_HdC_Qg3CGUTzD6HBT2_LnFT80qqf')
              .then(response => response.json())
              .then(json => console.log(json))
            fetch('https://api.cambio.today/v1/quotes/EUR/PEN/json?quantity=1&key=7616|Z5b_HdC_Qg3CGUTzD6HBT2_LnFT80qqf')
              .then(response => response.json())
              .then(json => console.log(json))*/
        };
        var $USD = 0;
        var $EUR = 0;
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
        var todayHelper = getTodayFormat();
        var Lproductos = [];
        var Lotes = [];
        var precio_total = 0.00;

        var clientes = [];
        var cliente;

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
                //$('#codigoNB-group').removeClass('hidden');
                console.log(true);
            }
        });

        $('#forma_pago').on('change', function() {
            console.log(this.value);
            if (this.value == '0') {
                $('#cuotas_line').html('');
            } else {
                var $html = '<div class="form-group form-inline cuota_line_row">';
                $html += '<div class="input-group "><label>Importe/Monto</label><input type="number" class="form-control cuota_monto"></div>';
                $html += '<div class="input-group "><label>Fecha de Cuota</label><input type="date" class="form-control cuota_fecha"></div>';
                $html += '<div class="input-group "><label>Agregar</label><button onclick="addCuota()" class="form-control btn btn-primary"><i class="glyphicon glyphicon-plus"></i></button></div>';
                $html += '</div>';
                $('#cuotas_line').html($html);
            }
        });

        function addCuota() {
            var $html = '<div class="form-group form-inline cuota_line_row">';
            $html += '<div class="input-group "><label>Importe/Monto</label><input type="number" class="form-control cuota_monto"></div>';
            $html += '<div class="input-group "><label>Fecha de Cuota</label><input type="date" class="form-control cuota_fecha"></div>';
            $html += '<div class="input-group "><label>Remover</label><button onclick="removeCuota(this)" class="form-control btn btn-danger"><i class="glyphicon glyphicon-remove"></i></button></div>';
            $html += '</div>';
            $('#cuotas_line').append($html);
        }

        function removeCuota($this) {
            $($this).parent().parent().remove();
        }


        $('#busqueda_query').on('keydown', function(event) {
            if (event.which == 13 || event.keyCode == 13) {
                var query = $('#busqueda_query').val();
                var almacen = $('#almacen').val();
                var Lcategorias = [];

                var data2 = {query:query, almacen:almacen};

                $.get(currentLocation+"buscarProductoCoti?query="+query+"", function( data ) {
                    var productos = jQuery.parseJSON( data );
                    var string = '';
                    Lproductos = [];
                    var card = '<div class="row" style="padding: 0 20px; ">';
                    var k = 0 ;
                    $.each(productos, function(key,value){
                        //if(parseInt(value.stockT) > 0){
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
                        //}
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

        function changeMoneda($val) {
          if( $val != '1' ){
            $("#tipo_cambio").prop("disabled", false);
            if( $val == '2' ){
                $("#tipo_cambio").val($USD);
            } else if( $val == '3' ){
                $("#tipo_cambio").val($EUR);
            }
          }
          else{
            $("#tipo_cambio").prop("disabled", true);
            $("#tipo_cambio").val('');
          }
        }

        $("#moneda").change(function() {
            var selectOption = this.value;
            if (($USD == 0 && $EUR == 0) && (selectOption == '2' || selectOption == '3')) {
                $.get(currentLocation+"exchange_pen", function( data ) {
                    data = jQuery.parseJSON( data );
                    $USD = data.USDExchange;
                    $EUR = data.EURExchange;
                    changeMoneda(selectOption);
                });
            } else {
                changeMoneda(selectOption);
            }
        });

        $('#buscar_producto').click(function(){
            var query = $('#busqueda_query').val();
            var almacen = $('#almacen').val();
            var Lcategorias = [];
            
            var data2 = {query:query, almacen:almacen};

            $.get(currentLocation+"buscarProductoCoti?query="+query+"", function( data ) {
                var productos = jQuery.parseJSON( data );
                var string = '';
                Lproductos = [];
                var card = '<div class="row" style="padding: 0 20px; ">';
                var k = 0 ;
                $.each(productos, function(key,value){
                    //if(parseInt(value.stockT) > 0){
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

                    //}
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

            /*$.get( "{{route('buscarLote')}}" ,data2,function(data){
                var lotes = jQuery.parseJSON( data );
            
                    console.log("yaaa1");
                    console.log(lotes);*/
                    
                    //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

                    var string = '<tr id="tr_item" >';
                    string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                    string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                    string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';

                    /*string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                    $.each(lotes, function(key,value){
                        string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                    });
                    string +='</select></td>';*/

                    string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ parseFloat(producto.precio).toFixed(2) +'"></td>';
                    string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="0" disabled></td> ';
                    string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                    string += '</tr> ';     

                    $('#lista_carrito').append(string);

            //}); 

        });

        $('#grid_productos').on('click','.row  .col-lg-3 .card', function(event){
            var idproducto = $(this).attr('id');
            var producto = _.find(Lproductos,{idproducto : parseInt(idproducto) });
            
            var almacen = $('#almacen').val();
            var data2 = {query:producto.idproducto, almacen:almacen};

            /*$.get( "{{route('buscarLote')}}" ,data2,function(data){
                var lotes = jQuery.parseJSON( data );

                console.log("yaaa2");
                console.log(lotes.idlote);*/

                //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

                var string = '<tr id="tr_item">';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'">'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0" ></td> ';
                    
                /*string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                $.each(lotes, function(key,value){
                    string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                });
                string +='</select></td>';*/

                string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ parseFloat(producto.precio).toFixed(2) +'"></td>';            
                string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="0"disabled ></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';

                $('#lista_carrito').append(string);

            //});

        });

        /********************CALCULAR TOTALES***********************************/

        function calcular_totales(){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_precio');
                total_detalle += parseFloat(importe.val());
            });
            // if ($('#con_igv').is(':checked')) {
            //     precio_total = (total_detalle);
            //     $('#total').val(precio_total ) ;

            // }else{
                var subtotal = (total_detalle/1.18);
                var igv = $('#igv').val();
                var envio = $('#envio').val();
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
            var total_sin_envio = total_detalle -  $('#envio').val();
            var subtotal = (total_sin_envio/1.18);
            var igv = total_sin_envio-subtotal;
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
            //    precio_total = (total_detalle);
            //    $('#total').val(precio_total ) ;
            //}else{
                var igv = $('#igv').val();
                precio_total = (total_detalle + parseFloat(igv));
                $('#total').val(precio_total ) ;
            //}
            calcular_descuento()
        })


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
                    $('#envio').val('0.00');                   

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



        /**************************BUSCAR CLIENTE*********/
        $('#cliente_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                cliente = temp;
            }
        });

        $('#cliente_search').keyup(function(event) {
            var query = $(this).val();
            var query2 = 0;
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

         /**************************BUSCAR guias**
        var idclientefinal;
        $('#guias_select').on('change',function(event){
            var guias_select = [];
            $('#lista_carrito').html('');
            $.each($(".selectpicker option:selected"), function(){            
                guias_select.push($(this).val());
            });
            console.log('guiass')
            console.log(guias_select);
            console.log(guias_select[0]);

            var query = guias_select[0];

            var almacen = $('#almacen').val();

            $.get(currentLocation+"buscarGRTodo?query="+query+"", function( data ) {
                var obj = JSON.parse(data);

                console.log('todo');
                console.log(obj);
                idclientefinal = obj.idcliente;
                $('#cliente_search').val(obj.razon_social);

                $('#f_entrega').val(obj.f_entrega);
                $('#f_cobro').val(obj.f_cobro);
                $('#idvendedor').val(obj.idvendedor);
                $('#moneda').val(obj.moneda);

                $('#total_detalle').val(obj.subtotal);
                $('#igv').val(obj.igv);
                $('#total').val(obj.total);


                $.each(obj.detalle, function(index, producto) {
                    
                    var data2 = {query:producto.idproducto, almacen:almacen};
                    $.get( "{{route('buscarLote')}}" ,data2,function(lotes_data){
                    
                        var string = '<tr id="tr_item" >';
                        string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                        string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                        string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+'" ></td> ';
                                            
                        var lotes = jQuery.parseJSON( lotes_data );
                        console.log(lotes);
                    
                        string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                        $.each(lotes, function(key,value){
                            string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                        });
                        string +='</select></td>';

                        string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ producto.precio_unit +'"></td>';
                        string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="'+producto.precio_total+'" disabled></td> ';
                        string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                        string += '</tr> ';     

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
        });*******/

          /**************************BUSCAR orden de ventas*********/
        var orden_ventas = [];
        var orden_venta;
        
        $('#factbol_search').change(function(event){
            if( $(this).val().length == 0){
                var temp;
                orden_venta = temp;
            }
        });

        $('#factbol_search').keyup(function(event) {
            var query = $(this).val();
            if(query.length >  0){
                $.get(currentLocation+"buscarOV?query="+query+"", function( data ) {
                    $('#search_results2').html('');
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(orden_ventas,{id_orden_ventah:value.id_orden_ventah}) == null){
                            orden_ventas.push(value); }

                        var recibo;

                        if(value.is_digital==0){
                            recibo='OV';
                        }else if(value.is_digital==1){
                            recibo='D0';
                        }else{}

                        $('#search_results2')
                            .removeClass('hide')
                            .append("<div id='item_to_add2' class='list-group-item' name='"
                                +value.id_orden_ventah+"' >"+ recibo + ' - ' + value.numeracion +' // '+ value.codigoNB +'</div>');

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

        var idclientefinal;
        $( "#search_results2" ).on( "click","#item_to_add2", function() {
            $('#lista_carrito').html('');
            var id_orden_ventah = $(this).attr('name');
            orden_venta = _.findWhere(orden_ventas, {id_orden_ventah: parseInt(id_orden_ventah)});

            $('#search_prod2').val('');

            var recibo;

            if(orden_venta.is_digital==0){
                recibo='OV';
            }else if(orden_venta.is_digital==1){
                recibo='D0';
            }else{}

            $('#factbol_search').val(recibo+' - '+orden_venta.numeracion);
            $('#search_results2').addClass('hide').html('');

            var query = orden_venta.id_orden_ventah;

            $.get(currentLocation+"buscarOVTodoGuia?query="+query+"", function( data ) {
                var obj = JSON.parse(data);

                console.log('todo');
                console.log(obj);
                idclientefinal = obj.idcliente;
                $('#cliente_search').val(obj.razon_social);

                $('#f_entrega').val(obj.f_entrega); 
                $('#f_cobro').val(obj.f_cobro);
                $('#idvendedor').val(obj.idvendedor);
                 $('#moneda').val(obj.moneda);
                changeMoneda(obj.moneda);


                $('#total_detalle').val(obj.subtotal);
                $('#igv').val(obj.igv);
                $('#total').val(obj.total);
                $('#descuento').val(0);
                if(obj.tipo_envio){
                    $('#envio').val((obj.tipo_envio-1)*10);
                    $('#envio_contenedor').removeClass('hidden');
                    document.getElementById('descuento').disabled = true;
                    //$('#input_cantidad').disabled = true;

                } 
                else{
                    $('#envio').val(0);
                    $('#envio_contenedor').addClass('hidden');
                    document.getElementById('descuento').disabled = false;
                    //$('#input_cantidad').disabled = false;
                }

                var string = '';

                var subT = 0;

                $.each(obj.detalle, function(index, producto) {

                        if (obj.is_igv) {
                            producto.precio_unit = (producto.precio_unit/1.18);
                            producto.precio_total = producto.precio_unit * producto.cantidad;
                            subT += producto.precio_total;
                            producto.precio_unit = producto.precio_unit.toFixed(2);
                            producto.precio_total = producto.precio_total.toFixed(2);
                        }
                        final='"></td>';
                        if(obj.tipo_envio){
                            final='" disabled></td>';
                        }
                        string += '<tr id="tr_item" >';
                        string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                        string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                        string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad+final;
                        string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ producto.precio_unit +final;
                        string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="'+producto.precio_total+'" disabled></td> ';
                        string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'"><i class="glyphicon glyphicon-remove"></i></button></td> ';

                        string += '</tr> ';     
                });          

                $('#lista_carrito').append(string);

                if (obj.is_igv) {
                    $('#total_detalle').val(subT.toFixed(2));
                    $('#igv').val((obj.total-subT).toFixed(2));
                }

                string = '';
                $('#guias_select').find('option').remove();
                $('#guias_select').selectpicker('refresh');
                $.each(obj.guias, function(index, guia) {
                    $("#guias_select").append('<option value='+guia.id_guia_remisionh+'>GR - '+guia.numeracion+' // '+guia.codigoNB+'</option>');
                });
                $("#guias_select").selectpicker("refresh");

                $.get(currentLocation+"buscarClienteOV?query="+idclientefinal+"", function( data ) {
                    var obj = JSON.parse(data);
                    $.each(obj, function(index, value) {
                        if(_.findWhere(clientes,{idcliente:value.idcliente}) == null){
                            clientes.push(value); }                   
                    });
                });

            }); 

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
            var documento = doc;

            if (idclientefinal != null)
                cliente = _.findWhere(clientes, {idcliente: parseInt(idclientefinal)});
            
            if(typeof orden_venta === "undefined"){
                swal({
                    title: "Falta enlazar Orden de Venta",
                    text: "Debes seleccionar una Orden de Venta.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

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


            var f_entrega = $('#f_entrega').val();
            var f_cobro = $('#f_cobro').val();
            var idvendedor = $('#idvendedor').val();            
            var guias_select = $('#guias_select').val();
            console.log('tipoo');
            console.log(typeof guias_select);
            var codigoNB = $('#codigoNB').val();
            var fechaNB = $('#fechaNB').val();
            var tipo_cambio = $('#tipo_cambio').val();
            var is_invoice = $('#is_invoice').val();

              if(documento == 0 || documento == '0'){
                swal({
                    title: "Upss!",
                    text: "Debes seleccionar Tipo de Recibo",
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

            if(f_entrega.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Entrega",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(f_cobro.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Cobro",
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

            /*if((is_invoice == '1' || is_invoice == 1) && codigoNB.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Codigo",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }*/

            if( guias_select === null ){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Guía/s de Remisión correspondiente/s",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            var isEmptyCuota = false;
            $(".cuota_line_row").each(function(){
                var cuota_monto = $(this).find('.cuota_monto').val();
                var cuota_fecha = $(this).find('.cuota_fecha').val();
                if (cuota_monto == '' || cuota_fecha == '') {
                    isEmptyCuota = true;
                }
            });

            if(isEmptyCuota){
                swal({
                    title: "Cuotas de credito incompleto",
                    text: "Debes completar los campos de cuotas de credito",
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
                var idlote = $(this).find('#lote #input_lote').val();
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
            $('#bol_envio').html('').append($('#envio').val());
            $('#bol_igv').html('').append($('#igv').val());
            $('#bol_total_detalle').html('').append($('#total_detalle').val());
            $('#bol_descuento').html('').append($('#descuento').val());
            $('#bol_pago').html('').append($('#paga').val());
            $('#bol_vuelto').html('').append($('#vuelto').val());
            $('#bol_f_entrega').html('').append($('#f_entrega').val());
            $('#bol_f_cobro').html('').append($('#f_cobro').val());
            $('#bol_idvendedor').html('').append($('#idvendedor').val());

            var string = '';

            $("#lista_carrito #tr_item").each(function(){
                string += '<tr>';
                string += '<td>'+$(this).find('#nombre_producto #ver').html()+'</td>';
                string += '<td>'+$(this).find('#medida_venta #med').html()+'</td>';
                string += '<td>'+$(this).find('#detalle_cantidad #input_cantidad').val()+'</td>';
                string += '<td>'+$(this).find('#precio_unitario #precio_prod').val()+'</td>';
                string += '<td>'+$(this).find('#detalle_total #input_precio').val()+'</td>';
                string += '</tr>';
            });
            console.log(string);
            $('#bol_detalle').html('').append(string);
            $('#bol_recibo').html('').append($('#documento :selected').html());
            $('#boleta_modal').modal();

        });
        $('#btn_confirmar').click(function(event){
            $('#btn_confirmar').prop( "disabled", true );

            var idcliente = cliente.idcliente;
            var id_orden_ventah = orden_venta.id_orden_ventah;
            var igv = $('#igv').val();
            var subtotal = $('#total_detalle').val();
            var descuento = $('#descuento').val();
            var total = $('#total').val();
            var envio = $('#envio').val();
            var medida_venta = $('#medida_venta').val();
            var comentarios = $('#comentarios').val();
            var f_entrega = $('#f_entrega').val();
            var f_cobro = $('#f_cobro').val();
            var idvendedor = $('#idvendedor').val();
            var guias_select = $('#guias_select').val();
            var productos = [];
            var paga = 0.00;//$('#paga').val();
            var vuelto = 0.00;//$('#vuelto').val();
            var tipo = $('#documento').val();
            var tipo_cambio = $('#tipo_cambio').val();
            var moneda = $('#moneda').val();
            var codigoNB = $('#codigoNB').val();
            var fechaNB = $('#fechaNB').val();
            var is_invoice = $('#is_invoice').val();
            var forma_pago = $('#forma_pago').val();

            console.log('final');
            console.log(guias_select);

            // var codigoNB;
            // if ($('#codigoNB').val() == null){
            //     codigoNB = '00';
            // }else{
            //     codigoNB = $('#codigoNB').val();
            // }


            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var precio = $(this).find('#precio_unitario #precio_prod').val();
                //var idlote = $(this).find('#lote #input_lote').val();
                var medida_venta = $(this).find('#medida_venta #med').val();
                productos.push({idproducto:producto,stock_total:stock_total,precio:precio});

                /*console.log('aquiii');
                console.log(producto.idlote);

                if(idlote == 0 || idlote == null ){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "Falta agregar Lote",
                        text: "Debes escoger un lote",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        //window.location.reload();
                    });
                    return;
                }*/
            });

            var cuotas = [];
            $(".cuota_line_row").each(function(){
                var cuota_monto = $(this).find('.cuota_monto').val();
                var cuota_fecha = $(this).find('.cuota_fecha').val();
                cuotas.push({monto:cuota_monto,fecha:cuota_fecha});
            });

            var json_cuotas = JSON.stringify(cuotas);
            var json_prod = JSON.stringify(productos);

            $.post(currentLocation+'crear_caja',{idcliente:idcliente,id_orden_ventah:id_orden_ventah,igv:igv,subtotal:subtotal,descuento:descuento,medida_venta:medida_venta,total:total,comentarios:comentarios,f_entrega:f_entrega,f_cobro:f_cobro,idvendedor:idvendedor,productos:json_prod,paga:paga,vuelto:vuelto,tipo:tipo, tipo_cambio:tipo_cambio, guias_select:guias_select,moneda:moneda, codigoNB:codigoNB,fechaNB:fechaNB,is_invoice:is_invoice,forma_pago:forma_pago,cuotas:json_cuotas,envio:envio},function(data){
                var obj = JSON.parse(data);
                if(obj[0].created == 200){
                    console.log(obj[1].id);
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    if(parseInt(obj[4].code) > 0 && parseInt(obj[4].code) < 4000){
                        $('#boleta_modal').modal('hide');
                        swal({
                            title: "Lo sentimos!",
                            text:"SUNAT rechazó el comprobante por tener errores, tendrá que corregirlos y enviarlo de nuevo. "+ obj[2].msg,
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){                           
                            window.location.reload();
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
                                window.open(currentLocation+"info_caja?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        window.location.reload();
                        });
                        return;

                    }
                    else if (obj[2].msg == '') {
                        if(obj[3].pdf !=null && obj[3].pdf !="")
                        window.open(currentLocation+"greenter/"+ obj[3].pdf,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        else
                        window.open(currentLocation+"info_caja?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        window.location.reload();
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
                                window.open(currentLocation+"info_caja?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        window.location.reload();
                        });
                        return;
                    }

                }else if(obj[0].created == 500){
                    $('#boleta_modal').modal('hide');
                    swal({
                        title: "Error!",
                        text: "Codigo de NubeFact repetido.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#btn_confirmar').prop( "disabled", false );
                    });
                    return;
                }else if(obj[0].created == 502){
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
                        text: "No se puede guardar la Factura/Boleta, intentalo de nuevo luego.",
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
