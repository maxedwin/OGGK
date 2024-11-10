@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-send position-left"></i> <span class="text-semibold">Crear Guía de Remisión</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/listado_guia_remision"></i>Listado de Guías de Remisión</a></li>
    <li class="active">Crear Guía de Remisión </li>
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
                                <label for="idmotivo">Motivo de Traslado:</label>
                                <select id="idmotivo" class="form-control">
                                        <option value=0> -- </option>
                                        <option value=1 selected="selected">VENTA</option>
                                        <option value=2>DEVOLUCIÓN</option>
                                        <option value=3>REGALO / MUESTRA</option>
                                        <option value=4>MERMA</option>
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
                            </div>
                            
                            <div class="input-group">
                                <label for="factbol_search">Buscar Orden de Venta:</label>
                                <input type="text" class="form-control input-lg"  id="factbol_search"  placeholder="Buscar una Orden de Venta" readonly="readonly">
                                <div id="orden_venta_search" class="list-group col-md-12 hide"></div>
                            </div>

                            <div class="input-group">
                                <label for="cliente_search">Buscar Cliente:</label>
                                <input type="text" class="form-control input-lg"  id="cliente_search"  placeholder="Buscar por Razon Social o RUC" readonly="readonly">
                                <div id="search_results" style="margin-top:30px;" class="list-group col-md-12 hide"></div>
                            </div>
                            
                            <div class="input-group pull-right">
                                <!-- <button type="button" class="btn btn-default btn-lg" id="btn_nuevoCliente">
                                    <i class="glyphicon glyphicon-user"></i>
                                    Agregar Cliente
                                </button> -->
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
                                <label for="fechaNB">Fecha de Emisión:</label>
                                <input type="date" class="form-control" id="fechaNB" min="2021-07-07">
                            </div>
                            <div class="input-group ">
                                <label for="f_entrega">Fecha de Entrega:</label>
                                <input type="date" class="form-control" id="f_entrega">
                            </div>
                            <div class="input-group hide">
                                <label for="f_cobro">Fecha de Cobro:</label>
                                <input type="date" class="form-control" id="f_cobro">
                            </div>
                            <div class="input-group ">
                                <label for="idvendedor">Vendedor:</label>
                                <select id="idvendedor" class="form-control">
                                        <option value=0> -- </option>
                                    @foreach ($vendedores as $vendedor)
                                        <?php if ($vendedor->id == $iduser) { ?>
                                            <option value="{{ $vendedor->id}}" selected>{{$vendedor->name}}</option>
                                        <?php } else { ?>
                                            <option value="{{ $vendedor->id}}">{{$vendedor->name}}</option>
                                        <?php } ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group ">
                                <label for="iddespachador">Despachador:</label>
                                <select id="iddespachador" class="form-control">
                                        <option value=0> -- </option>
                                    @foreach ($despachadores as $despachador)
                                        <option value="{{ $despachador->id}}">{{$despachador->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group ">
                                <label for="idtransporte">Transporte:</label>
                                <select id="idtransporte" class="form-control">
                                        <option value=0> -- </option>
                                    @foreach ($transportes as $transporte)
                                        <option value="{{ $transporte->idtransporte}}">{{$transporte->nombre_trans.' ('.$transporte->placa.')'}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group ">
                                <label for="comentarios">Razón/Comentarios:</label>
                                <textarea onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="comentarios" placeholder="Sólo si NO es venta" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group form-inline">
                            <div class="input-group ">
                                <label for="is_invoice">¿Generar Guía de Remisión Electrónica?</label>
                                <select class="form-control input-lg" id="is_invoice">
                                    <option value="0">SI</option>
                                    <option value="1">No</option>
                                </select>
                            </div>

                            <div id="codigoNB-group" class="input-group hidden">
                                <label for="codigoNB">Código:</label>
                                <input type="text" class="form-control" id="codigoNB" rows="2">
                            </div>
                        </div>

                        <div id="guia_electronia-group" class="form-group form-inline">
                            <legend>DATOS DE TRASLADO</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="envio_cod_traslado">Motivo de Traslado:</label>
                                        <select class="form-control input-lg" id="envio_cod_traslado">
                                            <option value="01">VENTA</option>
                                            <option value="02">VENTA SUJETA A CONFIRMACION DEL COMPRADOR</option>
                                            <option value="03">COMPRA</option>
                                            <option value="04">TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA</option>
                                            <option value="05">TRASLADO EMISOR ITINERANTE CP</option>
                                            <option value="06">IMPORTACION</option>
                                            <option value="07">EXPORTACION</option>
                                            <option value="08">TRASLADO A ZONA PRIMARIA</option>
                                            <option value="09">OTROS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="envio_mod_traslado">Tipo de Transporte:</label>
                                        <select class="form-control input-lg" id="envio_mod_traslado">
                                            <option value="02">TRANSPORTE PRIVADO</option>
                                            <option value="01">TRANSPORTE PÚBLICO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="envio_fec_traslado">Fecha de Traslado:</label>
                                        <input type="date" class="form-control" id="envio_fec_traslado">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="envio_peso_total">Peso bruto total (KGM):</label>
                                        <input type="number" class="form-control" id="envio_peso_total">
                                    </div>
                                </div>
                            </div>

                            <legend>DATOS DE TRANSPORTISTA</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="transporte_tipo_doc">Tipo de doc. de Transportista:</label>
                                        <select class="form-control input-lg" id="transporte_tipo_doc">
                                            <option value="6">RUC - REGISTRO ÚNICO DE CONTRIBUYENTE</option>
                                            <option value="1">DNI - DOC. NACIONAL DE IDENTIDAD</option>
                                            <option value="-">VARIOS - VENTAS MENORES A S/.700.00 Y OTROS</option>
                                            <option value="4">CARNET DE EXTRANJERÍA</option>
                                            <option value="7">PASAPORTE</option>
                                            <option value="0">NO DOMICILIADO, SIN RUC (EXPORTACIÓN)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="transporte_num_doc">Transportista doc. numero:</label>
                                        <input type="text" class="form-control" id="transporte_num_doc" value="20600819667">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="transporte_denominacion">Transportista denominación:</label>
                                        <input type="text" class="form-control" id="transporte_denominacion" value="SOLUCIONES OGGK SAC">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="transporte_placa">Transportista placa numero:</label>
                                        <select id="transporte_placa" class="form-control">
                                            <option value="VAJ-712"> VAJ-712 </option>
                                            <option value="VAB-843"> VAB-843 </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <legend>DATOS DE TRANSPORTISTA</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="transporte_chofer_tipo_doc">Tipo de doc. del Conductor:</label>
                                        <select class="form-control input-lg" id="transporte_chofer_tipo_doc">
                                            <option value="1">DNI - DOC. NACIONAL DE IDENTIDAD</option>
                                            <option value="-">VARIOS - VENTAS MENORES A S/.700.00 Y OTROS</option>
                                            <option value="4">CARNET DE EXTRANJERÍA</option>
                                            <option value="7">PASAPORTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group ">
                                        <label for="transporte_chofer_num_doc">Conductor documento numero:</label>
                                        <select id="transporte_chofer_num_doc" class="form-control">
                                            <option value="71723693 - VALENCIA PORTUGAL HUGO FERNANDO"> 71723693 - VALENCIA PORTUGAL HUGO FERNANDO </option>
                                            <option value="41164272 - GUTIERREZ DEL CARPIO OLIVER GIOVANNI"> 41164272 - GUTIERREZ DEL CARPIO OLIVER GIOVANNI </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <legend>PUNTO DE PARTIDA</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="partida_ubigeo">UBIGEO dirección de partida (distrito):</label>
                                        <select class="form-control input-lg" id="partida_ubigeo">
                                            <option value=""> (Buscar Ubigeo) </option>
                                            @foreach ($ubigeo as $ubig)
                                                <option value="{{ $ubig->id}}">{{'('.$ubig->id.') - '.$ubig->distrito_name.' - '.$ubig->provincia_name.' - '.$ubig->departamento_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group " style="width: 100%;">
                                        <label for="partida_direccion">Dirección del punto de partida:</label>
                                        <input type="text" class="form-control" id="partida_direccion" value="PASAJE LA RONDA NRO. 107 - CAYMA - AREQUIPA - AREQUIPA" onkeyup="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                            </div>

                            <legend>PUNTO DE LLEGADA</legend>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group ">
                                        <label for="llegada_ubigeo">UBIGEO dirección de llegada (distrito):</label>
                                        <select class="form-control input-lg" id="llegada_ubigeo">
                                            <option value=""> (Buscar Ubigeo) </option>
                                            @foreach ($ubigeo as $ubig)
                                                <option value="{{ $ubig->id}}">{{'('.$ubig->id.') - '.$ubig->distrito_name.' - '.$ubig->provincia_name.' - '.$ubig->departamento_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group " style="width: 100%;">
                                        <label for="llegada_direccion">Dirección del punto de llegada:</label>
                                        <input type="text" class="form-control" id="llegada_direccion" onkeyup="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                            </div>

                            <legend>OBSERVACIONES</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group " style="width: 100%;">
                                        <label for="observacion">Observaciones:</label>
                                        <textarea class="form-control" id="observacion" onkeyup="this.value = this.value.toUpperCase()"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>  

                    </div>
                </div>
            </div>



        <div class="col-md-12" id="search_area_products">
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
                                        <th>Peso x Unidad</th>
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
                                <tr class="bg-danger-700">
                                    <th>Producto</th>
                                    <th>Unidad Venta</th>
                                    <th>Cantidad Venta</th>
                                    <th>Lote</th>
                                    <th>Peso x Unidad</th>
                                    <th>Peso Total</th>
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
                            <b>PESO TOTAL:</b>
                            <span id="bol_peso"></span>
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
                                        <th>PESO UNIT.</th>                                        
                                        <th>PESO TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody id="bol_detalle">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="row">

                            <div class="col-md-12">
                                <b>Transportista:</b>
                                <span id="bol_despachador"></span>
                                <b>Vehículo:</b>
                                <span id="bol_transporte"></span>
                            </div>

                            <div class="col-md-12" id="submit-control">
                                <button class="btn btn-info btn-lg" id="btn_confirmar" >
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

        var orden_ventas = [];
        var orden_venta;
        var usuario = '<?php echo Auth::user()->name; ?>';

        $('#grid_productos').hide();

        var f_min = new Date();
        f_min.setDate(f_min.getDate()-3)
        $('#fechaNB').val(todayHelper).attr({"min" : getDateFormat(f_min)});
        //$('#f_entrega').val(todayHelper);
        $('#envio_fec_traslado').val(todayHelper);

        /*$('#comentarios').wysihtml5({
            parserRules:  wysihtml5ParserRules,
            stylesheets: ["assets/css/components.css"],
            "image": false,
            "link": false,
            "font-styles": false,
            "emphasis": false
        });*/

        $('#partida_ubigeo').select2().val('040103').trigger('change');
        var $ubigeoLlegada = $('#llegada_ubigeo').select2();

        $('#is_invoice').on('change', function() {
            if (this.value == '0') {
                $('#codigoNB-group').addClass('hidden');
                $('#guia_electronia-group').removeClass('hidden');
            } else {
                //$('#codigoNB-group').removeClass('hidden');
                $('#guia_electronia-group').addClass('hidden');
                console.log(true);
            }
        });

        $('#busqueda_query').on('keydown', function(event) {
            if (event.which == 13 || event.keyCode == 13) {
                var query = $('#busqueda_query').val();
                var almacen = $('#almacen').val();
                var Lcategorias = [];

                var data2 = {query:query, almacen:almacen};

                $.get( "{{route('buscarProductoCaja')}}" ,data2,function(data){
                    var productos = jQuery.parseJSON( data );
                    var string = '';
                    Lproductos = [];
                    var card = '<div class="row" style="padding: 0 20px; ">';
                    var k = 0 ;
                    $.each(productos, function(key,value){
                        if(parseInt(value.stockT) > 0){
                            Lproductos.push(value);

                            if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria  }) == null){
                                Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria });
                            }

                            string += '<tr>';
                            string += '<td>'+value.barcode+'</td>';
                            string += '<td>'+value.nombre+'</td>';
                            string += '<td>'+value.stockT+'</td>';
                            string += '<td>'+value.medida_venta+'</td>';
                            string += '<td>'+ parseFloat(value.peso_unidad)+' '+ value.peso_unidad_und  +'</td>';
                            string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';
                        }
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

            if(almacen == 0){
                swal({
                    title: "Upss!",
                    text: "Debes seleccionar un Almacen",
                    confirmButtonColor: "#66BB6A",
                    type: "warning"
                },function(){
                    //window.location.reload();
                });
                return;
            }

             var data2 = {query:query, almacen:almacen};

            $.get( "{{route('buscarProductoCaja')}}" ,data2,function(data){
                var productos = jQuery.parseJSON( data );
                var string = '';
                Lproductos = [];
                var card = '<div class="row" style="padding: 0 20px; ">';
                var k = 0 ;
                $.each(productos, function(key,value){
                     if(parseInt(value.stockT) > 0){
                        Lproductos.push(value);

                        if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria }) == null){
                            Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria });
                        }

                        string += '<tr>';
                        string += '<td>'+value.barcode+'</td>';
                        string += '<td>'+value.nombre+'</td>';
                        string += '<td>'+value.stockT+'</td>';
                        string += '<td>'+value.medida_venta+'</td>';
                        string += '<td>'+ parseFloat(value.peso_unidad)+' '+ value.peso_unidad_und  +'</td>';
                        string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';
                    }

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
                string += '<td>'+ parseFloat(value.peso_unidad)+' '+ value.peso_unidad_und  +'</td>';
                string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

            });
            $('#lista').html('').append(string);
            $('#grid_productos').html('').append(card);
        });

        $('#lista').on('click','#btn_agregar',function(event){
            var idproducto =  $(this).data('idprod');
            var producto = _.find(Lproductos,{idproducto : idproducto });
            //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

            var almacen = $('#almacen').val();
            var data2 = {query:producto.idproducto, almacen:almacen};

            $.get( "{{route('buscarLote')}}" ,data2,function(data){
                var lotes = jQuery.parseJSON( data );

                var string = '<tr id="tr_item" >';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'" >'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';

                string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                    $.each(lotes, function(key,value){
                        string += '<option value="' + value.idlote+ '">' + value.codigo + ' (' + value.stock_lote.toString() + ') FV: ' + value.f_venc + '</option>'
                    });
                string +='</select></td>';
                
                string += '<td id="peso_venta"><div id="peso1" data-idpeso="'+ producto.peso_unidad +'" data-idund="'+ producto.peso_unidad_und +'">'+ parseFloat(producto.peso_unidad)+' '+ producto.peso_unidad_und +'</div></td>';
                string += '<td id="peso_total"><input class="form-control" id="peso2" type="number" step="0.1" value="0" disabled></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';                 

                $('#lista_carrito').append(string);

                calculateTotalPeso();

            }); 

        });

        $('#grid_productos').on('click','.row  .col-lg-3 .card', function(event){
            var idproducto = $(this).attr('id');
            var producto = _.find(Lproductos,{idproducto : parseInt(idproducto) });
            //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});
            var almacen = $('#almacen').val();
            var data2 = {query:producto.idproducto, almacen:almacen};

            $.get( "{{route('buscarLote')}}" ,data2,function(data){
                var lotes = jQuery.parseJSON( data );

                var string = '<tr id="tr_item">';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'">'+producto.nombre+'</div></td>';
                string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0" ></td> ';

                string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                $.each(lotes, function(key,value){
                    string += '<option value="' + value.idlote+ '">' + value.codigo + ' (' + value.stock_lote.toString() + ') FV: ' + value.f_venc + '</option>'
                });
                string +='</select></td>';

                string += '<td id="peso_venta"><div id="peso1" data-idpeso="'+ producto.peso_unidad +'" data-idund="'+ producto.peso_unidad_und +'">'+ parseFloat(producto.peso_unidad)+' '+ producto.peso_unidad_und +'</div></td>';
                string += '<td id="peso_total"><input class="form-control" id="peso2" type="number" step="0.1" value="0" disabled></td> ';
                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';

                $('#lista_carrito').append(string);

                calculateTotalPeso();
            }); 

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
                var igv = $('#igv').val();
                precio_total = (total_detalle + parseFloat(igv));
                $('#total').val(precio_total ) ;
           // }
            calcular_descuento();
        }

        function calcular_igv(){
            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#detalle_total #input_precio');
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
                var importe = $(this).find('#detalle_total #input_precio');
                total_detalle += parseFloat(importe.val());
            });
            // if ($('#con_igv').is(':checked')) {
            //     precio_total = (total_detalle);
            //     $('#total').val(precio_total ) ;
            // }else{
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
            calculateTotalPeso();
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
            
            var peso = parseFloat( ($(this).parent().parent().find( "#peso_venta #peso1" )).data('idpeso'));
            
            var total = $(this).parent().parent().find( "#peso_total #peso2" );       
            var tprecio = (peso * cantidad);
            total.val(tprecio);
            
            calcular_totales();
            calcular_igv();
            calculateTotalPeso();
        });

        $('#lista_carrito').on('change','#tr_item #precio_prod', function(event){
            var cantidad = parseFloat( ($(this).parent().parent().find( "#detalle_cantidad #input_cantidad" )).val());
            var total = $(this).parent().parent().find( "#peso_total #peso2" );
            var peso = parseFloat( ($(this).parent().parent().find( "#peso_venta #peso1" )).data('idpeso'));
            
            var tprecio = (peso * cantidad);
            total.val(tprecio);
            calcular_totales();
            calcular_igv();
            calculateTotalPeso();
        });


        $('#btnlista').click(function(event){
            $('#lista_productos').show();
            $('#grid_productos').hide();

        });

        $('#btngrid').click(function(event){
            $('#lista_productos').hide();
            $('#grid_productos').show();
        });

         /**************************BUSCAR orden de ventas*********/

        /*$('#idmotivo').change(function(event) {
            if ($(this).val() == 1) {
                $('#search_area_products').hide();
            } else {
                $('#search_area_products').show();
            }
        });*/


        $('#factbol_search').change(function(event){
            if( $(this).val().length == 0){
                var temp_ov;
                orden_venta = temp_ov;
            }
        });

        $('#factbol_search').keyup(function(event) {
            var query_ov = $(this).val();
            if(query_ov.length >  0){
                $.get(currentLocation+"buscarOVPDF?query="+query_ov+"", function( data ) {
                    $('#orden_venta_search').html('');
                    var obj_ov = JSON.parse(data);
                    $.each(obj_ov, function(index, value) {
                        if(_.findWhere(orden_ventas,{id_orden_ventah:value.id_orden_ventah}) == null){
                            orden_ventas.push(value); }

                        var recibo;

                        if(value.is_digital==0){
                            recibo='OV';
                        }else if(value.is_digital==1){
                            recibo='D0';
                        }else{}

                        $('#orden_venta_search')
                            .removeClass('hide')
                            .append("<div id='orden_venta_items' class='list-group-item' ordenventa='"
                                +value.id_orden_ventah+"' >"+ recibo + ' - ' + value.numeracion +' // '+ value.codigoNB +'</div>');

                    });
                });
            }else{
                $('#orden_venta_search').addClass('hide').html('');

            }
        });

        $('#orden_venta_items').hover(function() {
                $( this ).toggleClass( "active" );
            }, function() {
                $( this ).removeClass( "active" );
            }
        );

        var idclientefinal;
        $( "#orden_venta_search" ).on( "click","#orden_venta_items", function() {
            $('#lista_carrito').html('');
            var id_orden_ventah = $(this).attr('ordenventa');
            orden_venta = _.findWhere(orden_ventas, {id_orden_ventah: parseInt(id_orden_ventah)});

            $('#search_prod2').val('');
            $('#idmotivo').val(1);
            //$('#search_area_products').hide();
            
            var recibo;

            if(orden_venta.is_digital==0){
                recibo='OV';
            }else if(orden_venta.is_digital==1){
                recibo='D0';
            }else{}
            
            $('#factbol_search').val(recibo+' - '+orden_venta.numeracion);
            $('#orden_venta_search').addClass('hide').html('');

            var query = orden_venta.id_orden_ventah;
            var almacen = $('#almacen').val();

            $.get(currentLocation+"buscarOVTodo?query="+query+"", function( data ) {
                var obj = JSON.parse(data);

                console.log('todo');
                console.log(obj);
                idclientefinal = obj.idcliente;
                $('#cliente_search').val(obj.razon_social);
                $('#llegada_direccion').val(obj.direccion_entrega);
                if (obj.ubigeo != '') {
                    $ubigeoLlegada.val(obj.ubigeo).trigger('change');
                } else {
                    $ubigeoLlegada.val('040103').trigger('change');
                }
                
                //$ubigeoLlegada.select2("open");
                //$('.select2-search.select2-search--dropdown input').val(obj.distrito.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toUpperCase());

                

                $('#f_entrega').val(obj.f_entrega); 
                $('#f_cobro').val(obj.f_cobro);
                $('#idvendedor').val(obj.idvendedor);

                if (obj.comentarios) {
                    $('#observacion').val('ASESOR: '+ $("#idvendedor option:selected").text() + ', NP: ' +obj.codigoNB+'\n'+obj.comentarios);
                    $('#comentarios').val('ASESOR: '+$("#idvendedor option:selected").text()+', NP: '+obj.codigoNB+'\n'+obj.comentarios);
                } else {
                    $('#observacion').val('ASESOR: '+$("#idvendedor option:selected").text()+', NP: '+obj.codigoNB);
                    $('#comentarios').val('ASESOR: '+ $("#idvendedor option:selected").text()+', NP: '+obj.codigoNB);
                }


                $('#total_detalle').val(obj.subtotal);
                $('#igv').val(obj.igv);
                $('#total').val(obj.total);
                

                //var string = '';
                var peso_total_total = 0;

                $.each(obj.detalle, function(index, producto) {

                    
                    var data2 = {query:producto.idproducto, almacen:almacen};
                    console.log(data2);
                    $.get( "{{route('buscarLote')}}" ,data2,function(lotes_data){

                        if (producto.cantidad_fal > 0 && producto.tipo == 1) {
                            var string = '<tr id="tr_item" >';
                            string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+producto.idproducto+'" data-caja="'+producto.cantidad_caja+'" data-preciound="'+producto.precio_und+'" data-precio1a9="'+producto.precio_1a9+'" data-precio10a19="'+producto.precio_10a19+'" data-precio20a24="'+producto.precio_20a24+'" data-precio25a29="'+producto.precio_25a29+'" data-precio30="'+producto.precio_30+'">'+producto.nombre+'</div></td>';
                            string += '<td id="medida_venta"><div id="med">'+producto.medida_venta+'</div></td>';
                            string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="'+producto.cantidad_fal+'"  ></td> ';

                            var lotes = jQuery.parseJSON( lotes_data );
                            console.log(lotes);
                        
                            string += '<td id="lote"><select class="form-control" style="width: 200px" name="input_lote" id="input_lote" >';
                            $.each(lotes, function(key,value){
                                string += '<option value="' + value.idlote+ '">' + value.codigo + ' (' + value.stock_lote.toString() + ') FV: ' + value.f_venc + '</option>'
                            });
                            string +='</select></td>';

                            string += '<td id="peso_venta"><div id="peso1" data-idpeso="'+ producto.peso_unidad +'" data-idund="'+ producto.peso_unidad_und +'">'+ parseFloat(producto.peso_unidad)+' '+ producto.peso_unidad_und +'</div></td>';
                            var pesofinal = parseFloat(producto.peso_unidad)*parseFloat(producto.cantidad);
                            string += '<td id="peso_total"><input class="form-control" id="peso2" type="number" step="0.1" value="'+pesofinal+'" disabled></td> ';
                            string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                            string += '</tr> ';  

                            $('#lista_carrito').append(string);

                            calculateTotalPeso();
                        }
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

        $('#almacen').change(function(){
            if (parseInt($(this).val()) != 0) {
                console.log("almacennnn");
                $('#factbol_search').attr('readonly', false);
                $('#cliente_search').attr('readonly', false);
            } else {
                idclientefinal = null;
                orden_venta = null;
                $('#factbol_search').val('');
                $('#factbol_search').attr('readonly', true);
                $('#cliente_search').val('');
                $('#cliente_search').attr('readonly', true);
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

        var peso;
        $('#btn_guardar').click(function(event){

            if (idclientefinal != null)
                cliente = _.findWhere(clientes, {idcliente: parseInt(idclientefinal)});
            
            console.log('cliente');
            console.log(cliente);


            /*if(typeof orden_venta === "undefined"){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Orden de Venta correspondiente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }*/

            var total_detalle = 0;
            $("#lista_carrito #tr_item").each(function(){
                var importe = $(this).find('#peso_total #peso2');
                total_detalle += parseFloat(importe.val());
            });

            peso = total_detalle/1000;
            console.log('acaaa peso');
            console.log(parseFloat(total_detalle)/1000);

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
            var iddespachador = $('#iddespachador').val();
            var idtransporte = $('#idtransporte').val();
            var codigoNB = $('#codigoNB').val();
            var fechaNB = $('#fechaNB').val();
            var idmotivo = $('#idmotivo').val();
            var almacen = $('#almacen').val();
            var is_invoice = $('#is_invoice').val();
            
            if(idmotivo == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Motivo de Traslado",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
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
                    //window.location.reload();
                });
                return;
            }

             if(idmotivo == 1 && typeof orden_venta === "undefined" ){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Orden de Venta!",
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

            /*f(f_cobro.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Cobro",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }*/

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

            if(iddespachador == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Despachador",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(idtransporte == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Transporte",
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
            if (is_invoice == '0' || is_invoice == 0) {
                var allRequired = true;
                var title = '';
                $("#guia_electronia-group .form-control").each(function(){
                    if (allRequired && (this.value == '')) {
                        allRequired = false;
                        title = $(this).parent().find('label').text().slice(0, -1);
                    }
                });
                if (!allRequired) {
                    swal({
                        title: "Upss!",
                        text: "Debes agregar "+title,
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        //window.location.reload();
                    });
                    return;
                }
            }

            var producto = [];
            var tmp_lote;
            $("#lista_carrito #tr_item").each(function(){
                var nombre = $(this).find('#nombre_producto #ver').html();
                if( $(this).find('#lote #input_lote').html() == '' ){
                    tmp_lote=1;
                }
                producto.push({nombre:nombre});
            });

            if(tmp_lote == 1){
                swal({
                    title: "Upss!",
                    text: "Debes escoger un Lote o Verificar Almacen",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    tmp_lote=0;
                });
                return;
            }


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
            $('#bol_peso').html('').append(peso + " kg");

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
            $('#bol_f_entrega').html('').append($('#f_entrega').val());
            $('#bol_f_cobro').html('').append($('#f_cobro').val());
            $('#bol_idvendedor').html('').append($('#idvendedor').val());

            var despachadores = {!! json_encode($despachadores) !!} ;
            var despa = $('#iddespachador').val();
                        
            for (var i = 0; i < despachadores.length; i++) {
                if(despachadores[i].id == despa) {
                    console.log(despachadores[i].name);
                    console.log(despachadores[i].lastname);
                    $('#bol_despachador').html('').append(despachadores[i].name+' '+despachadores[i].lastname );
                    break;
                }
            }

            var transportes = {!! json_encode($transportes) !!} ;
            var trans = $('#idtransporte').val();

            for (var i = 0; i < transportes.length; i++) {
                if(transportes[i].idtransporte == trans) {
                    $('#bol_transporte').html('').append(transportes[i].nombre_trans+' '+transportes[i].marca+' '+transportes[i].placa );
                    break;
                }
            }      

            var string = '';

            $("#lista_carrito #tr_item").each(function(){
                string += '<tr>';
                string += '<td>'+$(this).find('#nombre_producto #ver').html()+'</td>';
                string += '<td>'+$(this).find('#medida_venta #med').html()+'</td>';
                string += '<td>'+$(this).find('#detalle_cantidad #input_cantidad').val()+'</td>';
                string += '<td>'+$(this).find('#peso_venta #peso1').data('idpeso')+' '+$(this).find('#peso_venta #peso1').data('idund')+'</td>';
                string += '<td>'+$(this).find('#peso_total #peso2').val()+'</td>';
                string += '</tr>';
            });
            console.log(string);
            $('#bol_detalle').html('').append(string);
            
            $('#boleta_modal').modal();

        });
        $('#btn_confirmar').click(function(event){
            $('#btn_confirmar').prop( "disabled", true );

            var idcliente = cliente.idcliente;
            var id_orden_ventah;


            if(typeof orden_venta === "undefined")
                id_orden_ventah = '';
            else
                id_orden_ventah = orden_venta.id_orden_ventah;


            var igv = $('#igv').val();
            var subtotal = $('#total_detalle').val();
            var descuento = $('#descuento').val();
            var total = $('#total').val();
            var medida_venta = $('#medida_venta').val();
            var comentarios = $('#comentarios').val();
            var f_entrega = $('#f_entrega').val();
            var f_cobro = $('#f_cobro').val();
            var idvendedor = $('#idvendedor').val();
            var iddespachador = $('#iddespachador').val();
            var idtransporte = $('#idtransporte').val();
            var productos = [];
            var paga = 0.00;//$('#paga').val();
            var vuelto = 0.00;//$('#vuelto').val();
            var peso_total = peso;
            var codigoNB = $('#codigoNB').val();
            var fechaNB = $('#fechaNB').val();
            var idmotivo = $('#idmotivo').val();
            var almacen = $('#almacen').val();
            var is_invoice = $('#is_invoice').val();
            
            var transporte_tipo_doc = $('#transporte_tipo_doc').val();
            var transporte_num_doc = $('#transporte_num_doc').val();
            var transporte_denominacion = $('#transporte_denominacion').val();
            var transporte_placa = $('#transporte_placa').val();
            var transporte_chofer_tipo_doc = $('#transporte_chofer_tipo_doc').val();
            var transporte_chofer_num_doc = $('#transporte_chofer_num_doc').val();
            var envio_cod_traslado = $('#envio_cod_traslado').val();
            var envio_desc_traslado = $("#envio_cod_traslado option:selected").text();
            var envio_mod_traslado = $('#envio_mod_traslado').val();
            var envio_fec_traslado = $('#envio_fec_traslado').val();
            var envio_peso_total = $('#envio_peso_total').val();
            var partida_ubigeo = $('#partida_ubigeo').val();
            var partida_direccion = $('#partida_direccion').val();
            var llegada_ubigeo = $('#llegada_ubigeo').val();
            var llegada_direccion = $('#llegada_direccion').val();
            var observacion = $('#observacion').val();
            
            // var codigoNB;
            // if ($('#codigoNB').val() == null){
            //     codigoNB = '00';
            // }else{
            //     codigoNB = $('#codigoNB').val();
            // }

            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_cantidad #input_cantidad').val();
                var peso = $(this).find('#peso_venta #peso1').data('idpeso');
                var peso_und = $(this).find('#peso_venta #peso1').data('idund');
                var medida_venta = $(this).find('#medida_venta #med').val();
                var idlote = $(this).find('#lote #input_lote').val();
                productos.push({idproducto:producto,stock_total:stock_total,peso:peso, peso_und:peso_und, idlote:idlote});
            });

            var json_prod = JSON.stringify(productos);

            $.post(currentLocation+'generar_pdf',{idcliente:idcliente,id_orden_ventah:id_orden_ventah,igv:igv,subtotal:subtotal,descuento:descuento,medida_venta:medida_venta,total:total,comentarios:comentarios,f_entrega:f_entrega,f_cobro:f_cobro,idvendedor:idvendedor,iddespachador:iddespachador, idtransporte:idtransporte,productos:json_prod,paga:paga,vuelto:vuelto, codigoNB:codigoNB, fechaNB:fechaNB, peso_total:peso_total, almacen:almacen, idmotivo:idmotivo, is_invoice:is_invoice, transporte_tipo_doc:transporte_tipo_doc, transporte_num_doc:transporte_num_doc, transporte_denominacion:transporte_denominacion, transporte_placa:transporte_placa, transporte_chofer_tipo_doc:transporte_chofer_tipo_doc, transporte_chofer_num_doc:transporte_chofer_num_doc, envio_cod_traslado:envio_cod_traslado, envio_desc_traslado:envio_desc_traslado, envio_mod_traslado:envio_mod_traslado, envio_fec_traslado:envio_fec_traslado, envio_peso_total:envio_peso_total, partida_ubigeo:partida_ubigeo, partida_direccion:partida_direccion, llegada_ubigeo:llegada_ubigeo, llegada_direccion:llegada_direccion, observacion:observacion},function(data){
                console.log("entraaaaaaaa");
                var obj = JSON.parse(data);
                console.log(obj[0].cdrStatus);
                if(obj[0].created == 200){
                    console.log(obj[1].id);
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    if (obj[2].msg == '') {
                        window.open(currentLocation+"info_guia_remision?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                        window.location.reload(); 
                    } else {
                        $('#boleta_modal').modal('hide');
                        swal({
                            title: "Ok!",
                            text: obj[2].msg,
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){
                            window.open(currentLocation+"info_guia_remision?id="+ obj[1].id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
                            window.location.reload();
                        });
                        return;
                    }
                }
            });



        });

        function convertKg(value, und) {
            und = String(und).trim();
            if (und == 'KG') {
                return value;
            } else if (und == 'G') {
                return value * 0.001;
            } else if (und == 'MG') {
                return value * 0.000001;
            }
            return 0;
        }

        function calculateTotalPeso() {

            var peso_total_total = 0.0;
            $("#lista_carrito #tr_item").each(function(){
                var value = parseFloat($(this).find('#peso_venta #peso1').data('idpeso')) * parseFloat($(this).find('#detalle_cantidad #input_cantidad').val());
                var und = $(this).find('#peso_venta #peso1').data('idund');
                peso_total_total += convertKg(value, und);
            });

            $('#envio_peso_total').val(peso_total_total);
        }


   </script>
@stop