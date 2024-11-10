@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Inventario / Salidas</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
    <li>Inventario</li>
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
                                    <label for="f_emision">Fecha de Emisión:</label>
                                    <input type="date" class="form-control" id="f_emision">
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
                                    <label for="idvendedor">Persona que recibe:</label>
                                    <select id="idvendedor" class="form-control">
                                            <option value=0> -- </option>
                                        @foreach ($vendedores as $vendedor)
                                            <option value="{{ $vendedor->id}}">{{$vendedor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group ">
                                <label for="movimiento">Tipo de Movimiento:</label>
                                <select class="form-control" id="movimiento">
                                        <option value="0">--</option>
                                        <option value="1">Movimiento</option>
                                        <option value="2">Muestra</option>
                                        <option value="3">Regalo</option>
                                        <option value="4">Prestamo</option>
                                        <option value="5">Cambio por fallo</option>
                                </select>
                                </div>   

                                <div class="input-group ">
                                    <label for="razon">Razón/Comentario:</label>
                                    <textarea class="form-control" id="razon" rows="2"></textarea>
                                </div>

                                <div class="input-group pull-right">
                                    <button type="button" class="btn btn-default btn-lg" id="eliminar_all" >
                                        <i class="glyphicon glyphicon-trash"></i>
                                        Limpiar
                                    </button>
                                    <div id="submit-control">
                                        <button class="btn btn-info btn-lg" id="btn_guardar" data-idprod="9">
                                            <i class="glyphicon glyphicon-save"></i>
                                            Guardar
                                        </button>
                                    </div>
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
                                <input type="text" class="form-control input-lg" style="width: 400px;" id="busqueda_query"  placeholder="Buscar por codigo de barras, nombre o categoria">
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
                                <tr class="bg-danger-700">
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Precio Unitario</th>
                                    <th>En Stock</th>
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



            <div class="col-md-11">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        
                        <table  class="table table-borderless">
                            <thead>
                            <tr class="bg-danger-700">
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Lote</th>
                                <th>Accion</th>
                            </tr>
                            </thead>
                            <tbody id="lista_carrito" >
                            </tbody>
                            <tfoot style="text-align: right">
                            </tfoot>

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
        $('#grid_productos').hide();




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
                        if(parseInt(value.tipo) === 1 ){
                            Lproductos.push(value);

                            if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria }) == null){
                                Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria });
                            }

                            string += '<tr>';
                            string += '<td>'+value.barcode+'</td>';
                            string += '<td>'+value.nombre+'</td>';
                            string += '<td>'+ parseFloat(value.precio).toFixed(2)+'</td>';
                            string += '<td>'+value.stockT+'</td>';
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
            var data2 = {query:query, almacen:almacen};

            $.get( "{{route('buscarProductoCaja')}}" ,data2,function(data){
                var productos = jQuery.parseJSON( data );
                var string = '';
                Lproductos = [];
                var card = '<div class="row" style="padding: 0 20px; ">';
                var k = 0 ;
                $.each(productos, function(key,value){
                    if(parseInt(value.tipo) === 1 ){
                        Lproductos.push(value);

                        if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria }) == null){
                            Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria});
                        }

                        string += '<tr>';
                        string += '<td>'+value.barcode+'</td>';
                        string += '<td>'+value.nombre+'</td>';
                        string += '<td>'+ parseFloat(value.precio).toFixed(2)+'</td>';
                        string += '<td>'+value.stockT+'</td>';
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
            var k = 0
            $.each(cat_prod, function(key,value){
                string += '<tr id='+value.idproducto +'>';
                string += '<td>'+value.barcode+'</td>';
                string += '<td>'+value.nombre+'</td>';
                string += '<td>'+ parseFloat(value.precio).toFixed(2)+'</td>';
                string += '<td>'+value.stockT+'</td>';
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

            $.get( "{{route('buscarLote')}}" ,data2,function(data){
            
                var lotes = jQuery.parseJSON( data );
            
                //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, stock_total:producto.stock_total, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

                var string = '<tr id="tr_item" >';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'">'+producto.nombre+'</div></td>';
                string += '<td id="detalle_stock_total"><input class="form-control" id="input_stock_total" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';

                string += '<td id="lote"><select class="form-control" name="input_lote" id="input_lote" >';
                    $.each(lotes, function(key,value){
                        string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                    });
                string +='</select></td>';
                
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

            $.get( "{{route('buscarLote')}}" ,data2,function(data){
            
                var lotes = jQuery.parseJSON( data );
            
                //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, stock_total:producto.stock_total, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

                var string = '<tr id="tr_item">';
                string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'">'+producto.nombre+'</div></td>';
                string += '<td id="detalle_stock_total"><input class="form-control" id="input_stock_total" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';

                string += '<td id="lote"><select class="form-control" name="input_lote" id="input_lote" >';
                    $.each(lotes, function(key,value){
                        string += '<option value="' + value.idlote+ '">' + value.codigo + '</option>'
                    });
                string +='</select></td>';

                string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

                string += '</tr> ';

                $('#lista_carrito').append(string);

            }); 
        });


        /*********ELIMINAR Y LIMPIAR DE BOLETA ****************/

        $('#lista_carrito').on('click','#eliminar', function(event){
            $(this).closest('tr').remove();
         });



        $('#eliminar_all').click(function(event){
            $('#lista_carrito').html('');

        });

        /*******CANTIDAD Y DESCUENTOS INPUT CHANGE****************/

        $('#lista_carrito').on('change','#tr_item #input_stock_total', function(event){

        });

        $('#btnlista').click(function(event){
            $('#lista_productos').show();
            $('#grid_productos').hide();

        });
//        FUNCIONDE BOTONES CAMBIO DE ESTILOS DIV PRODUCTOS
        $('#btngrid').click(function(event){
            $('#lista_productos').hide();
            $('#grid_productos').show();
        });

        $('#item_to_add').hover(function() {
                $( this ).toggleClass( "active" );
            }, function() {
                $( this ).removeClass( "active" );
            }
        );

        $( "#search_results" ).on( "click","#item_to_add", function() {
            $('#search_prod').val('');
            $('#search_results').addClass('hide').html('');

        });

        /****************************************************GUARDAR INVENTARIO****/

        $('#btn_guardar').click(function(event){

            var almacen = $('#almacen').val()
            var razon = $('#razon').val()
            var quien = $('#idvendedor').val()
            var f_emision = $('#f_emision').val()
            var tipo_mov = $('#movimiento').val()

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

            if(quien == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Persona que recibe",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(tipo_mov == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar Tipo de Movimiento",
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
                    text: "Debes agregar una Razón y/o Comentario",
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



            var productos = [];
            var a = true;
            $("#lista_carrito #tr_item").each(function(){
                var producto = $(this).find('#nombre_producto #ver').data('idproducto');
                var stock_total = $(this).find('#detalle_stock_total #input_stock_total').val();
                var stock = $(this).find('#detalle_total #input_stock').val();
                var idlote = $(this).find('#lote #input_lote').val();

                productos.push({idproducto:producto,stock_total:stock_total, idlote:idlote});
            });

            var json_prod = JSON.stringify(productos);

            $.post(currentLocation+'crear_salida',{productos:json_prod, almacen:almacen, razon:razon, quien:quien, f_emision:f_emision, tipo_mov:tipo_mov},function(data){

                var obj = JSON.parse(data);    
                console.log(data);
                console.log(obj);
                var mensaje;
                var obj = JSON.parse(data, function (key, value) {
                    if (key == "created" && value ==200) {
                        mensaje = value;
                    } 
                    if (key == "deleted" && value ==999) {
                        mensaje = value;
                    } 
                });
                if(mensaje === 200){
                        setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                        swal({ 
                            title: "Bien hecho!",
                            text: "Se guardo correctamente",
                            type: "success"
                        },
                        function(){
                            console.log('ok button');
                            window.location.reload();
                        });

                }else if(mensaje === 999){
                    swal({
                        title: "NO!",
                        text: "Estas sacando más de lo que hay en el Lote! Revisa por favor.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){  });
                    return;
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar la salida, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){});
                    return;
                }
            });

        });





    </script>
@stop