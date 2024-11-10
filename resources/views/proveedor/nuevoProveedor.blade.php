@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <h4><i class="glyphicon glyphicon-user position-left"></i> <span class="text-semibold">Nuevo Proveedor</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/list_proveedores"></i>Listado de Proveedores</a></li>
    <li class="active">Nuevo Proveedor</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-success btn-lg" id="btn_guardar" >
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
    <style type="text/css">
        .hide-loader{
            display:none;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ URL::to('/') }}">
    
    <div class="content">
        
        <div class="row">
        
            <div class="col-md-12">
            
                <div class="panel panel-flat">
                    <div class="panel-body">

                    <fieldset>
                    <legend class="text-semibold">Información del Proveedor</legend>

                        <div class="col-md-5">

                            <!--<div class="from-group" id="dni_group">
                                <label for="ruc_dni">RUC/DNI:</label>
                                <input type="text" class="form-control" id="ruc_dni">
                                <input type="hidden" class="form-control" id="idproveedor">
                            </div>-->
                            <label for="ruc_dni">RUC/DNI:</label>
                            <div class="from-group form-inline" id="dni_group">                                
                                <input type="number" class="form-control" name="nruc" id="nruc" placeholder="Ingrese RUC o DNI" pattern="([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])" autofocus>
                                <button type="submit" class="btn btn-success" name="btn-submit" id="btn-submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Procesando...">
                                    <i class="glyphicon glyphicon-search"></i> Verificar
                                </button>
                                <input type="hidden" class="form-control" id="idproveedor">
                            </div>

                            <div class="from-group" id="rs_group">
                                <label for="razon_social">Razon Social:</label>
                                <input type="text" class="form-control" id="razon_social">
                            </div>

                            <div class="from-group" id="direccion_group">
                                <label for="direccion">Direccion:</label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="direccion">
                            </div>

                            <div class="row">
                            <div class="col-md-4">

                                    <div class="from-group" id="dist_group">
                                        <label class="control-label" for="distrito">Distrito:</label>                        
                                            <select id="distrito" class="form-control"  style="width: 150%;">
                                                <option value="0">--</option>
                                                @foreach ($distritos as $distrito)
                                                    <option value="{{ $distrito->distrito_name}}">{{$distrito->distrito_name}}</option>
                                                @endforeach
                                            </select>
                                    </div>

                                    <div class="from-group" id="prov_group">
                                        <label class="control-label" for="provincia">Provincia:</label>    
                                            <select id="provincia" class="form-control"  style="width: 150%;">
                                                <option value="0">--</option>
                                                @foreach ($provincias as $provincia)
                                                    <option value="{{ $provincia->provincia_name}}">{{$provincia->provincia_name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                        
                                    <div class="from-group" id="depa_group">
                                        <label class="control-label" for="departamento">Departamento:</label>    
                                            <select id="departamento" class="form-control"  style="width: 150%;">
                                                <option value="0">--</option>
                                                @foreach ($departamentos as $departamento)
                                                    <option value="{{ $departamento->departamento_name}}">{{$departamento->departamento_name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                        
                            <div class="from-group" id="contnomb_group">
                                <label for="contacto_nombre">Nombre de Contacto:</label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="contacto_nombre">
                            </div>

                            <div class="from-group" id="contelf_group">
                                <label for="contacto_telefono">Teléfono de Contacto:</label>
                                <input type="text" class="form-control" id="contacto_telefono">
                            </div>

                            <div class="from-group" id="contemail_group">
                                <label for="contacto_email">Correo de Contacto:</label>
                                <input type="text" class="form-control" id="contacto_email">
                            </div>
                            
                            <div class="from-group" id="diascred_group">
                                <label for="dias_credito">Días de Crédito:</label>
                                <input type="number" class="form-control" id="dias_credito">
                            </div>

                            <div class="from-group" id="diascred_group">
                                <label for="tipo_pago">Tipo de Pago:</label>
                                <select class="form-control" id="tipo_pago">
                                        <option value="99">--</option>
                                        <option value="0">Contado</option>
                                        <option value="1">Transferencia</option>
                                        <option value="2">Cheque</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="tipo_pago"> -->
                            </div>

                            <div class="from-group" id="diascred_group">
                                <label for="moneda">Moneda:</label>
                                <select class="form-control" id="moneda">
                                        <option value="0">--</option>
                                        <option value="1">Soles</option>
                                        <option value="2">Dolares</option>
                                        <option value="3">Euros</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="moneda"> -->
                            </div>

                            <div class="from-group" id="tipemp_group">
                                <label for="tipo_emp">Tipo de Empresa:</label>
                                    <select class="form-control" id="tipo_emp">
                                        <option value="0">--</option>
                                        @foreach ($tipos_emp as $tipo_emp)
                                            <option value="{{ $tipo_emp->id_tipoemp}}">{{$tipo_emp->tipoemp_nombre}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        </fieldset>
                    </div>
                </div>
            </div>
        </div>    
    </div>
   
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>    

<script rel="script" type="text/javascript">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    $("#nruc").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#btn-submit").click();
        }
    });
    
    var bool_click = false;

    $("#btn-submit").click(function(e){
        bool_click = true;
        var $this = $(this);
        
        $this.button('loading');
        
        $("#contacto_nombre").val('');
        e.preventDefault();
                
        $.ajax({
            data: { "nruc" : $("#nruc").val() },
            type: "POST",
            dataType: "json",
            timeout: 30000,
            url: "{{ route('buscarRuc') }}",
            }).done(function( data, textStatus, jqXHR ){
            if(data['success']!="false" && data['success']!=false)
                {
                    $("#json_code").text(JSON.stringify(data, null, '\t'));
                    if(typeof(data['result'])!='undefined')
                    {
                        $this.addClass("hide-loader");

                        $("#nruc").val(data['result']['ruc']);
                        $("#razon_social").val(data['result']['razon_social']);
                        $("#nombre_comercial").val(data['result']['nombre_comercial']);
                        $("#direccion").val(data['result']['direccion']);
                        document.getElementById('nruc').disabled = true;
                        document.getElementById('razon_social').disabled = true;

                        if(typeof(data['result']['representantes_legales'][0])!='undefined')
                            $("#contacto_nombre").val(data['result']['representantes_legales'][0]['nombre']+' - '+data['result']['representantes_legales'][0]['cargo']);
                    
                    }
                        $("#error").hide();
                        $(".result").show();
                    }
                    else if ( typeof(data['message'])!='undefined' && data['err_num']==501 )
                    {
                        if ($("#nruc").val().length == 8) {
                            $.ajax({
                                data: {
                                    "nruc": $("#nruc").val(),
                                },
                                type: "POST",
                                dataType: "json",
                                timeout: 10000,
                                url: "{{ route('buscarReniec') }}",
                            }).done(function(data, textStatus, jqXHR) {
                                if (data['success'] != false) {
                                    $("#json_code").text(JSON.stringify(data, null, '\t'));
                                    if (typeof(data['result']) != 'undefined') {

                                        $this.addClass("hide-loader");

                                        $("#nruc").val(data['result']['dni']);
                                        document.getElementById('nruc').disabled = true;
                                        $("#razon_social").val(data['result']['apellidoPaterno']+' '+data['result']['apellidoMaterno']+' '+data['result']['nombres']);
                                        document.getElementById('razon_social').disabled = true;
                                        /*$("#nruc").val(data['result']['dni']);
                                        document.getElementById('nruc').disabled = true;
                                        $("#razon_social").val(data['result']['apellidos']+' '+data['result']['nombres']);
                                        document.getElementById('razon_social').disabled = true;
                                        $("#distrito").val(data['result']['distrito']).change();
                                        $("#provincia").val(data['result']['provincia']).change();
                                        $("#departamento").val(data['result']['departamento']).change();*/
                                    }
                                    $("#error").hide();
                                    $(".result").show();
                                } else {
                                    if (typeof(data['message']) != 'undefined') {
                                        $this.button('reset');

                                        swal({
                                        title: "Revisar Por favor",
                                        text: data['message'],
                                        confirmButtonColor: "#66BB6A",
                                        type: "error"
                                        },function(){
                                            //window.location.reload();
                                        });
                                        return;
                                    }
                                }
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                $this.button('reset');

                                swal({
                                    title: "Solicitud fallida",
                                    text: textStatus + " - intentalo de nuevo",
                                    confirmButtonColor: "#66BB6A",
                                    type: "error"
                                    },function(){
                                        //window.location.reload();
                                    });
                            });
                        } else {
                            $this.button('reset');
                            swal({
                                title: "Opción",
                                text: "No se encontro el RUC, Ingrese manualmente",
                                confirmButtonColor: "#66BB6A",
                                type: "warning"
                                },function(){
                                    //window.location.reload();
                                });
                        }    
                    }
                    else
                    {
                        $this.button('reset');
                        swal({
                            title: "Revisar Por favor",
                            text: data['message'],
                            confirmButtonColor: "#66BB6A",
                            type: "error"
                            },function(){
                                //window.location.reload();
                            });
                            $("#contacto_nombre").val('');
                            return;
                    }
                    
            }).fail(function( jqXHR, textStatus, errorThrown ){
                //alert( "Solicitud fallida:" + textStatus );
                $this.button('reset');
                swal({
                        title: "Solicitud fallida",
                        text: textStatus + " - intentalo de nuevo",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                        },function(){
                            //window.location.reload();
                        });
                $("#contacto_nombre").val('');
            });        
    });

    $('#distrito').select2();
    $('#provincia').select2();
    $('#departamento').select2();

    $('#btn_guardar').click(function(event){
        $('#btn_guardar').prop( "disabled", true );
            var idproveedor = $('#idproveedor').val();
            var ruc_dni = $('#nruc').val();
            var razon_social = $('#razon_social').val();
            var direccion = $('#direccion').val();
            var distrito = $('#distrito').val();
            var provincia = $('#provincia').val();
            var departamento = $('#departamento').val();
            var contacto_nombre = $('#contacto_nombre').val();
            var contacto_telefono = $('#contacto_telefono').val();
            var contacto_email = $('#contacto_email').val();
            var tipo_pago = $('#tipo_pago').val();
            var dias_credito = $('#dias_credito').val();
            var moneda = $('#moneda').val();
            var tipo_emp = $('#tipo_emp').val();

            console.log(idproveedor);

            if(ruc_dni.length == 0){
                swal({
                    title: "Upss!",
                    text: "Debes agregar el RUC/DNI del Proveedor",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }
            if(razon_social.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar la Razon Social del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(direccion.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar la Direccion del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

            
            if(contacto_nombre.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el Nombre del Contacto del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(contacto_telefono.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el Telefono del Contacto del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(contacto_email.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el Correo del Contacto del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(dias_credito.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar los Días de Crédito del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(tipo_emp == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Tipo de Empresa del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(tipo_pago == 99){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Tipo de Pago del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

                    if(tipo_emp == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Tipo de Empresa del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(tipo_pago == 99){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Tipo de Pago del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error" 
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(distrito == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Distrito del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(provincia == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Provincia del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(departamento == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Departamento del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
              if(moneda == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Moneda del Proveedor",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

                console.log(idproveedor);
                $.post(currentLocation+'guardar_proveedor',{idproveedor:idproveedor, ruc_dni:ruc_dni, razon_social:razon_social, direccion:direccion, distrito:distrito, provincia:provincia,departamento:departamento, contacto_nombre:contacto_nombre, contacto_telefono:contacto_telefono, contacto_email:contacto_email, tipo_pago:tipo_pago, dias_credito:dias_credito, moneda:moneda, tipo_emp:tipo_emp},function(data){
                    obj = JSON.parse(data);
                    if(obj.mensaje === 200){
                        setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                        swal({
                            title: "Ok!",
                            text: "Se guardo correctamente!.",
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){
                            window.location.replace(currentLocation+'list_proveedores');
                            /*window.close();
                            window.opener.location.reload();*/
                        });
                        return;
                    }else if(obj.mensaje === 999){
                        swal({
                            title: "Error!",
                            text: "RUC/DNI repetido! Revisa por favor.",
                            confirmButtonColor: "#66BB6A",
                            type: "error"
                        },function(){ 
                            $('#btn_guardar').prop( "disabled", false );
                        });
                        return;
                    }else{
                        swal({
                            title: "Error..!",
                            text: "No se puede guardar el Proveedor, intentalo de nuevo luego.",
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