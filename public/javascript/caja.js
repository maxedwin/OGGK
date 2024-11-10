var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var Lproductos = [];
var precio_total = 0.00;

var clientes = [];
var cliente;
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
        $.get(currentLocation+"buscarProducto?query="+query+"", function( data ) {
            var productos = jQuery.parseJSON( data );
            var string = '';
            Lproductos = [];
            var card = '<div class="row" style="padding: 0 20px; ">';
            var k = 0 ;
            $.each(productos, function(key,value){
                if(parseInt(value.cantidad) > 0){
                    Lproductos.push(value);

                    if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria , color:value.color }) == null){
                        Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria, color:value.color });
                    }

                    string += '<tr>';
                    string += '<td>'+value.barcode+'</td>';
                    string += '<td>'+value.nombre+'</td>';
                    string += '<td>S/.'+ parseFloat(value.precio).toFixed(2)+'</td>';
                    string += '<td>'+value.cantidad+'</td>';
                    string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

                    var imagen = jQuery.parseJSON( value.imagenes );
                    var img ='' ;
                    if(imagen['array'][0].match('null')) {
                        img = 'servicios.png';
                    }else{
                        img = imagen['array'][0];
                    }
                    card += '<div class="col-sm-12 col-md-6 col-lg-3">' +
                        '                <div class="card doctor" id="'+value.idproducto+'" >' +
                        '                    <div class="col-md-12 col-xs-12 text-center">' +
                        '                        <img  class="img-responsive" src="productos/'+ img  +'">' +
                        '                    </div>' +
                        '                    <div class="col-md-12 col-xs-12  text-center"  >' +
                        '                        <h2>'+value.nombre +'</h2>' +
                        '                        <p><b>Precio: S/</b> '+ parseFloat(value.precio).toFixed(2)+'</p>' +
                        '                        <p><b>Stock u.</b>'+value.cantidad+'</p>' +
                        '                    </div>' +
                        '                </div>' +
                        '        </div>';

                    k++;
                    if(k > 3){
                        card += "</div><div class='row' style='padding: 0 20px; '>";
                        k = 0;
                    }
                }
            });
            $('#lista').html('').append(string);
            $('#grid_productos').html('').append(card);
            var lista_breadcrumb = '';
            $.each(Lcategorias,function(key,value){
                lista_breadcrumb += ' <button type="button" id="btn_categoria" style="background-color: '+ value.color +'" data-id='+value.descripcion+' class="btn btn-lg bg-info-600">'+value.descripcion+'</button>';
            });
            $('#lista_breadcrumb').html('').append(lista_breadcrumb);
        });
    }
});

$('#buscar_producto').click(function(){
    var query = $('#busqueda_query').val();
    var Lcategorias = [];
    $.get(currentLocation+"buscarProducto?query="+query+"", function( data ) {
        var productos = jQuery.parseJSON( data );
        var string = '';
        Lproductos = [];
        var card = '<div class="row" style="padding: 0 20px; ">';
        var k = 0 ;
        $.each(productos, function(key,value){
            if(parseInt(value.cantidad) > 0){
                Lproductos.push(value);

                if(_.findWhere(Lcategorias, {idcategoria: value.idcategoria, descripcion : value.categoria, color :value.color }) == null){
                    Lcategorias.push({idcategoria: value.idcategoria, descripcion : value.categoria, color:value.color });
                }

                string += '<tr>';
                string += '<td>'+value.barcode+'</td>';
                string += '<td>'+value.nombre+'</td>';
                string += '<td>S/.'+ parseFloat(value.precio).toFixed(2)+'</td>';
                string += '<td>'+value.cantidad+'</td>';
                string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

                var imagen = jQuery.parseJSON( value.imagenes );
                var img ='' ;
                if(imagen['array'][0].match('null')) {
                    img = 'servicios.png';
                }else{
                    img = imagen['array'][0];
                }
                card += '<div class="col-sm-12 col-md-6 col-lg-3">' +
                    '                <div class="card doctor" id="'+value.idproducto+'" >' +
                    '                    <div class="col-md-12 col-xs-12 text-center">' +
                    '                        <img  class="img-responsive" src="productos/'+ img  +'">' +
                    '                    </div>' +
                    '                    <div class="col-md-12 col-xs-12  text-center"  >' +
                    '                        <h2>'+value.nombre +'</h2>' +
                    '                        <p><b>Precio: S/</b> '+ parseFloat(value.precio).toFixed(2)+'</p>' +
                    '                        <p><b>Stock u.</b>'+value.cantidad+'</p>' +
                    '                    </div>' +
                    '                </div>' +
                    '        </div>';

                k++;
                if(k > 3){
                    card += "</div><div class='row' style='padding: 0 20px; '>";
                    k = 0;
                }
            }
        });
        $('#lista').html('').append(string);
        $('#grid_productos').html('').append(card);
        var lista_breadcrumb = '';
        $.each(Lcategorias,function(key,value){
            lista_breadcrumb += ' <button type="button" id="btn_categoria" data-id='+value.descripcion+' style="background-color: '+ value.color +'"  class="btn btn-lg bg-info-600">'+value.descripcion+'</button>';
        });
        $('#lista_breadcrumb').html('').append(lista_breadcrumb);
    });
});

$('#lista_breadcrumb').on('click', '#btn_categoria', function(){
    var cat = $(this).data('id');
    var cat_prod = _.where(Lproductos,{categoria: String(cat)});
    var string = '';
    var card = '<div class="row" style="padding: 0 20px; ">';
    var k = 0
    $.each(cat_prod, function(key,value){
        string += '<tr id='+value.idproducto +'>';
        string += '<td>'+value.barcode+'</td>';
        string += '<td>'+value.nombre+'</td>';
        string += '<td>S/.'+ parseFloat(value.precio).toFixed(2)+'</td>';
        string += '<td>'+value.cantidad+'</td>';
        string += '<td><button class="btn btn-info" id="btn_agregar" data-idprod ="'+ value.idproducto +'">Agregar</button></td></tr>';

        var imagen = jQuery.parseJSON( value.imagenes );
        var img ='' ;
        if(imagen['array'][0].match('null')) {
            img = 'servicios.png';
        }else{
            img = imagen['array'][0];
        }
        card += '<div class="col-sm-12 col-md-6 col-lg-3">' +
            '                <div class="card doctor" id="'+value.idproducto+'" >' +
            '                    <div class="col-md-12 col-xs-12 text-center">' +
            '                        <img  class="img-responsive" src="productos/'+ img  +'">' +
            '                    </div>' +
            '                    <div class="col-md-12 col-xs-12  text-center"  >' +
            '                        <h2>'+value.nombre +'</h2>' +
            '                        <p><b>Precio: S/</b> '+ parseFloat(value.precio).toFixed(2)+'</p>' +
            '                        <p><b>Stock u.</b>'+value.cantidad+'</p>' +
            '                    </div>' +
            '                </div>' +
            '        </div>';

        k++;
        if(k > 3){
            card += "</div><div class='row' style='padding: 0 20px; '>";
            k = 0;
        }

    });
    $('#lista').html('').append(string);
    $('#grid_productos').html('').append(card);
});

$('#lista').on('click','#btn_agregar',function(event){
    var idproducto =  $(this).data('idprod');
    var producto = _.find(Lproductos,{idproducto : idproducto });
    //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

    var string = '<tr id="tr_item" >';
    string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'">'+producto.nombre+'</div></td>';
    string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ parseFloat(producto.precio).toFixed(2) +'"></td>';
    string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0"  ></td> ';
    string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="0" disabled></td> ';
    string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

    string += '</tr> ';

    $('#lista_carrito').append(string);

});

$('#grid_productos').on('click','.row  .col-lg-3 .card', function(event){
    var idproducto = $(this).attr('id');
    var producto = _.find(Lproductos,{idproducto : parseInt(idproducto) });
    //carrito.push({idproducto: producto.idproducto, nombre: producto.nombre, cantidad:producto.cantidad, precio: producto.precio, descuento: 0.0, total_precio: 0.0});

    var string = '<tr id="tr_item">';
    string += '<td id="nombre_producto"><div id="ver" data-tipo="'+producto.tipo+'" data-idproducto="'+idproducto+'">'+producto.nombre+'</div></td>';
    string += '<td id="precio_unitario"><input class="form-control" type="text" id="precio_prod" value="'+ parseFloat(producto.precio).toFixed(2) +'"></td>';
    string += '<td id="detalle_cantidad"><input class="form-control" id="input_cantidad" data-idprod="'+ producto.idproducto +'" type="number" step="1" value="0" ></td> ';
    string += '<td id="detalle_total"><input class="form-control" id="input_precio" type="number" step="0.1" value="0"disabled ></td> ';
    string += '<td><button class="btn btn-danger btn-xs" id="eliminar" data-idprod="'+ producto.idproducto +'" ><i class="glyphicon glyphicon-remove"></i></button></td> ';

    string += '</tr> ';

    $('#lista_carrito').append(string);


});

/********************CALCULAR TOTALES***********************************/

function calcular_totales(){
    var total_detalle = 0;
    $("#lista_carrito #tr_item").each(function(){
        var importe = $(this).find('#detalle_total #input_precio');
        total_detalle += parseFloat(importe.val());
    });
    if ($('#con_igv').is(':checked')) {
        precio_total = (total_detalle);
        $('#total').val(precio_total ) ;

    }else{
        var igv = $('#igv').val();
        precio_total = (total_detalle + parseFloat(igv));
        $('#total').val(precio_total ) ;
    }
    calcular_descuento();
}
function calcular_igv(){
    var total_detalle = 0;
    $("#lista_carrito #tr_item").each(function(){
        var importe = $(this).find('#detalle_total #input_precio');
        total_detalle += parseFloat(importe.val());
    });
    var igv = total_detalle * 0.18;
    $('#igv').val(igv.toFixed(2));
}

$('#con_igv').on('change',function(event){
    var total_detalle = 0;
    $("#lista_carrito #tr_item").each(function(){
        var importe = $(this).find('#detalle_total #input_precio');
        total_detalle += parseFloat(importe.val());
    });
    if ($('#con_igv').is(':checked')) {
        precio_total = (total_detalle);
        $('#total').val(precio_total ) ;
    }else{
        var igv = $('#igv').val();
        precio_total = (total_detalle + parseFloat(igv));
        $('#total').val(precio_total ) ;
    }
    calcular_descuento()
})


function calcular_descuento(){
    var descuento = $('#descuento').val();
    $('#descuento').val( parseFloat(descuento).toFixed(2) );
    var precio = precio_total - descuento;
    $('#total').val(precio.toFixed(2));

    var paga = $('#paga').val();
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

$('#lista_carrito').on('click','#ver', function(event){
    var idproducto = $(this).data('idproducto');
    var tipo = $(this).data('tipo');

    if(tipo === 1){
        window.open(currentLocation+"producto_editar?id="+idproducto, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
    }else{
        window.open(currentLocation+"servicio_editar?id="+idproducto, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");

    }

});

$('#eliminar_all').click(function(event){
    $('#lista_carrito').html('');
    $('#total').val('0.00');
    $('#descuento').val('0.00');
    $('#igv').val('0.00');
    $('#paga').val('0.00');
    $('#vuelto').val('0.00');

});

/*******CANTIDAD Y DESCUENTOS INPUT CHANGE****************/

$('#lista_carrito').on('change','#tr_item #input_cantidad', function(event){
    var precio = parseFloat( ($(this).parent().parent().find( "#precio_unitario #precio_prod" )).val());
    var total = $(this).parent().parent().find( "#detalle_total #input_precio" );
    var cantidad = parseFloat( $(this).val());
    var tprecio = (precio * cantidad).toFixed(2);
    total.val(tprecio);
    calcular_totales();
    calcular_igv();
});

$('#lista_carrito').on('change','#tr_item #precio_prod', function(event){
    var cantidad = parseFloat( ($(this).parent().parent().find( "#detalle_cantidad #input_cantidad" )).val());
    var total = $(this).parent().parent().find( "#detalle_total #input_precio" );
    var precio = parseFloat( $(this).val());
    $(this).val(precio.toFixed(2));
    var tprecio = (precio * cantidad).toFixed(2);
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
    if(query.length >  4){
        $.get(currentLocation+"buscarCliente?query="+query+"", function( data ) {
            $('#search_results').html('');
            var obj = JSON.parse(data);
            $.each(obj, function(index, value) {
                if(_.findWhere(clientes,{idcliente:value.idcliente}) == null){
                    clientes.push(value); }

                $('#search_results')
                    .removeClass('hide')
                    .append("<div id='item_to_add' class='list-group-item' name='"
                        +value.idcliente+"' >"+value.nombres +' ' + value.apellidos+'</div>');

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
    $('#cliente_search').val(cliente.nombres+' '+cliente.apellidos);
    $('#search_results').addClass('hide').html('');

});

/*******************************************NUEVO CLIENTE**********************/
$('#btn_nuevoCliente').click(function(event){
    window.open("nuevo_cliente", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
});



/********************************DOCUMENTOS*****************/
$('#documento').on('change',function(event){
    var doc = $(this).val();
    if(doc === '2'){
        swal({
            title: "Tipo: FACTURA",
            text: "Necesitas que el cliente tenga RUC registrado.",
            confirmButtonColor: "#66BB6A",
            type: "warning"
        },function(){
            //window.location.reload();
        });
    }

});

/****************************************************GUARDAR BOLETA****/

$('#btn_guardar').click(function(event){
    var doc = $('#documento').val();


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
            text: "Debes registar el ruc de "+ cliente.nombres+ ' '+ cliente.apellidos,
            confirmButtonColor: "#66BB6A",
            type: "error"
        },function(){
            //window.location.reload();
        });
        return;
    }

    var producto = [];
    $("#lista_carrito #tr_item").each(function(){
        var cantidad = $(this).find('#detalle_cantidad #input_cantidad').val();
        var idpro = $(this).find('#detalle_cantidad #input_cantidad').data('idprod');
        var nombre = $(this).find('#nombre_producto').html();
        var precio = $(this).find('#detalle_total #input_precio').val();
        producto.push({idproducto:idpro, nombre:nombre, cantidad:cantidad,precio:precio});
    });


    if(producto.length == 0){
        swal({
            title: "Falta agregar productos o servicios",
            text: "Debes escoger un producto o servicio.",
            confirmButtonColor: "#66BB6A",
            type: "error"
        },function(){
            //window.location.reload();
        });
        return;
    }

    var igv = $('#igv').val();
    var descuento = $('#descuento').val();
    var total = $('#total').val();
    var detalle = $('#comentarios').val();


    $('#boleta_modal').modal();

});

/***********************PAGA CON O VUELTO********/

$('#total').change(function(event){
    var paga = $('#paga').val();
    var total = $(this).val();
    var vuelto = paga - total;
    if( paga){
        $('#vuelto').val(vuelto.toFixed(2));
    }

});
$('#paga').change(function(event){
    var paga = $(this).val();
    if(paga.length < 1)paga = '0';
    $(this).val( parseFloat(paga).toFixed(2));
    var total = $('#total').val();
    var vuelto = paga - total;
    if(paga.length !== 0){
        $('#vuelto').val(vuelto.toFixed(2));
    }
});