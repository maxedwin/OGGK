@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Crear Documento</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
<li><a href="/"><i class="icon-home2 position-left"></i> Home</a></li>
<li>Documentos</li>
<li class="active">Crear documento</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

@stop

@section('contenido')
<style>
    #result_dni{
        position: absolute;width:350px;background: white;z-index: 2;cursor:pointer;border: 1px solid #DDD;border-top: 5px solid #DDD;display: none;
    }
    #result_ruc{
        position: absolute;width:350px;background: white;z-index: 2;cursor:pointer;border: 1px solid #DDD;border-top: 5px solid #DDD;display: none;
    }
    .result_desc{
        overflow-y:scroll;max-height:200px;position: absolute;width:98%;background: white;z-index: 2;cursor:pointer;border: 1px solid #DDD;border-top: 5px solid #DDD;display: none;
    }
    .documento_footer{
        margin-top: 20px;
        float: left;
        width: 100%;
    }
    .lab1{
        height: 36px;
        padding-top: 7px;
        font-size: 16px;
    }


</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{ URL::to('/') }}">
<form id="data_documento" method="POST" name="data_documento" action="print_proforma" target="_blank">

<div class="col-md-4">
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading">
            <h5 class="panel-title">Cliente<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <div id="container_dni">
                    <input name="dni" id="dni" type="text" class="form-control" placeholder="DNI" autocomplete="off">
                    <div id="result_dni" >
                    </div>
                </div>
                <br>
                <input name="nombres" id="nombres" type="text" class="form-control" placeholder="Nombres"><br>
                <input name="direccion" id="direccion" type="text" class="form-control" placeholder="Direccion">
            </div>
            <div class="col-md-6">
                <div>
                    <input name="ruc" id="ruc" type="text" class="form-control" placeholder="RUC">
                    <div id="result_ruc" >
                    </div>
                </div>
                <br>
                <input name="apellidos" id="apellidos" type="text" class="form-control" placeholder="Apellidos"><br>
                <input name="telefono" id="telefono" type="text" class="form-control" placeholder="Telefono">
            </div>

        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading">
            <h5 class="panel-title">Orden de reparacion<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
        </div>
        <div class="panel-body">
            <input name="orden_servicio" type="text" class="form-control" placeholder="Orden de servicio"><br>
            <input name="fecha" type="text" class="form-control" placeholder="Fecha"><br>
            <input name="referencia" type="text" class="form-control" placeholder="Referencia">
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-primary panel-bordered">
        <div class="panel-heading">
            <h5 class="panel-title">Vehiculo<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
        </div>
        <div class="panel-body">
            <input name="modelo" type="text" class="form-control" placeholder="Modelo"><br>
            <input name="marca" type="text" class="form-control" placeholder="Marca"><br>
            <input name="placa" type="text" class="form-control" placeholder="Placa">
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="documento_head">
                <div class="col-md-1">Cantidad</div>
                <div class="col-md-10">Descripcion</div>
                <div class="col-md-1">Precio</div>
            </div>
            @for ($i = 0; $i < 20; $i++)
                <div class="documento_body">
                    <div class="content_descripcion_item">
                        <div class="col-md-1"><input type="text" name="cantidad[]" class="form-control cantidad" value=''></div>
                        <div class="col-md-10"><input name="descripcion[]" type="text" class="form-control descripcion" autocomplete="off">
                            <div class="result_desc">
                            </div>
                        </div>
                        <div class="col-md-1"><input type="text" name="precio[]" class="form-control precio" value=''></div>
                    </div>
                </div>
            @endfor

            <div class="documento_footer" >
                    <div class="col-md-4"><div class="col-md-4 label label-danger label-block lab1">A cuenta</div> <div class="col-md-8"><input name="acuenta" type="text" id="acuenta" class="form-control " ></div></div>
                    <div class="col-md-4"><div class="col-md-4 label label-danger label-block lab1">Saldo</div><div class="col-md-8"><input type="text" name="saldo" id="saldo" class="form-control " ></div></div>
                    <div class="col-md-4"><div class="col-md-4 label label-danger label-block lab1">Total</div> <div class="col-md-8"><input type="text" name="total" id="total" class="form-control " ></div></div>
            </div>

        </div>
    </div>
</div>
</form>
<div class="col-md-12">
    <div id="print_actions" class="panel panel-body border-top-primary text-center">
        <div class="btn btn-success btn-xlg"><i class="icon-printer2 position-left"></i> Orden de Servicio</div>
        <div id="print_proforma" class="btn btn-warning btn-xlg"><i class="icon-printer2 position-left"></i> Proforma </div>
    </div>
</div>


<script type="text/javascript">
    var App = {base_url:"<?php echo substr(URL::to('/'), 0, -6); ; ?>", site_url:"<?php echo URL::to('/')."/"; ?>"};
    $('#dni').keyup(function(){
        if (this.value.length > 7) {
            input=$( "#dni" ).val();
            $('#result_dni').show();
            $.post( App.site_url+"get_cliente_dni",{ dni:input },function( data ) {
                obj = JSON.parse(data);
                results=obj;
                if(results.length>0){
                    $('#result_dni').html('');
                    $.each( results, function( key, value ) {
                        $('#result_dni').append('<div class="list-group-item">'+value.nombres+' '+ value.apellidos+'</div>');
                    });
                    $('.list-group-item').hover(function() { $( this ).toggleClass( "active" );}, function() { $( this ).removeClass( "active" );});
                }else{
                    $('#result_dni').html('');
                    $('#result_dni').hide();
                }

            });
        }
    });

    $( "#result_dni" ).on( "click",".list-group-item", function(event){
        $.post( App.site_url+"get_cliente_dni",{ dni:input },function( data ) {
            obj = JSON.parse(data);
            results=obj;
            if(results.length>0){
                $('#nombres').val(results[0].nombres);
                $('#apellidos').val(results[0].apellidos);
                $('#direccion').val(results[0].direccion);
                $('#telefono').val(results[0].telefono);
                $('#ruc').val(results[0].ruc);
            }else{
                $('#result_dni').html('');
                $('#result_dni').hide();
            }
        });
        $('#result_dni').html('');
        $('#result_dni').hide();
    });

    $('#ruc').keyup(function(){
        if (this.value.length > 10) {
            input=$( "#ruc" ).val();
            $('#result_ruc').show();
            $.post( App.site_url+"get_cliente_ruc",{ ruc:input },function( data ) {
                obj = JSON.parse(data);
                results=obj;
                if(results.length>0){
                    $('#result_ruc').html('');
                    $.each( results, function( key, value ) {
                        $('#result_ruc').append('<div class="list-group-item">'+value.nombres+' '+ value.apellidos+'</div>');
                    });
                    $('.list-group-item').hover(function() { $( this ).toggleClass( "active" );}, function() { $( this ).removeClass( "active" );});
                }else{
                    $('#result_ruc').html('');
                    $('#result_ruc').hide();
                }

            });
        }
    });

    $( "#result_ruc" ).on( "click",".list-group-item", function(event){
        $.post( App.site_url+"get_cliente_ruc",{ ruc:input },function( data ) {
            obj = JSON.parse(data);
            results=obj;
            if(results.length>0){
                $('#nombres').val(results[0].nombres);
                $('#apellidos').val(results[0].apellidos);
                $('#direccion').val(results[0].direccion);
                $('#telefono').val(results[0].telefono);
                $('#dni').val(results[0].dni);
            }else{
                $('#result_ruc').html('');
                $('#result_ruc').hide();
            }
        });
        $('#result_ruc').html('');
        $('#result_ruc').hide();
    });

    $('.descripcion').keyup(function(){
        $('.result_desc').hide();
        if (this.value.length > 3) {
            input=$(this).val();
            result_container=$(this).next();
            result_container.show();
            $.post( App.site_url+"get_descripcion",{ query:input },function( data ) {
                obj = JSON.parse(data);
                results=obj;
                if(results.length>0){
                    result_container.html('');
                    $.each( results, function( key, value ) {
                        result_container.append('<div class="list-group-item" name="'+value.nombre+'" datasrc="'+value.precio+'">'+value.nombre+'</div>');
                    });
                    $('.list-group-item').hover(function() { $( this ).toggleClass( "active" );}, function() { $( this ).removeClass( "active" );});
                }else{
                    result_container.html('');
                    result_container.hide();
                }
            });
        }
    });

    $( ".result_desc" ).on( "click",".list-group-item", function(event){
        result_container=$(this).parent().parent();
        descripcion=$(this).attr('name');
        precio=$(this).attr('datasrc');
        precio_container=result_container.next().children();
        cantidad_container=result_container.prev().children();
        descripcion_container=$(this).parent().prev();
        descripcion_container.val(descripcion);
        precio_container.val(precio);
        cantidad_container.val(1);
        result_desc=$(this).parent();
        result_desc.html('');
        result_desc.hide();
        getTotals();
    });

    $('.precio').keyup(function(){
        getTotals();
    });
    $('.cantidad').keyup(function(){
        getTotals();
    });

    function getTotals(){
        var total=0;
        $(".precio").each(function() {
            cantidad=$(this).parent().prev().prev().children().val();
            variable =$(this).val();
            if(cantidad == ''){
                cantidad=0;
            }
            if(variable == ''){
                variable=0;
            }
            total+=(parseFloat(variable)*parseInt(cantidad));
        });
        $('#total').val(parseFloat(total).toFixed(2));
    }

    $( "#print_actions" ).on( "click","#print_proforma", function(event){

        printDocument();
    });
    function printDocument(){
        $("#data_documento").submit();
    }
</script>
@stop
