



@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.css">
<!-- Include plugin -->
<script src="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    <h4><i class="glyphicon glyphicon-user position-left"></i> <span class="text-semibold">Nuevo Cliente</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active"><a href="/list_clientes"></i>Listado de Clientes</a></li>
    <li class="active">Nuevo Cliente</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

<!--    <li>
        <button class="btn btn-info btn-lg" id="btn_clientesinruc">
            <i class="glyphicon glyphicon-user"></i>
            Cliente SIN Ruc/Dni
        </button>
    </li>
-->
    <li>    
        <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-success btn-lg" id="btn_guardar">
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
        /* The switch - the box around the slider */
        .button-cover
        {
            height: 100px;
            margin: 20px;
            background-color: #fff;
            border-radius: 4px;
        }

        .button-cover:before
        {
            counter-increment: button-counter;
            content: counter(button-counter);
            position: absolute;
            right: 0;
            bottom: 0;
            color: #d7e3e3;
            font-size: 12px;
            line-height: 1;
            padding: 5px;
        }

        .button-cover, .knobs, .layer
        {
            border-color: black;
            border-width: 2px;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .button
        {
            border-color: black;
            border-width: 2px;
            position: relative;
            top: 50%;
            width: 150px;
            height: 36px;
            margin-top: -40px;
            margin-left:45%;            
            
        }

        .button.r, .button.r .layer
        {
            border-radius: 100px;
        }

        .button.b2
        {
            border-radius: 2px;
        }

        .checkbox
        {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .knobs
        {
            z-index: 2;
        }

        .layer
        {
            width: 100%;
            background-color: #ebf7fc;
            transition: 0.3s ease all;
            z-index: 1;
        }

    

        /* Button 10 */
        #button-10 .knobs:before, #button-10 .knobs:after, #button-10 .knobs span
        {
            position: absolute;
            width: 70px;
            height: 36px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            line-height: 1;
            padding: 9px 4px 9px 4px;
            border-radius: 2px;

            transition: 0.3s ease all;
        }

        #button-10 .knobs:before
        {
            content: '';
            left: 4px;
            background-color: #03A9F4;
        }

        #button-10 .knobs:after
        {
            content: ' CLIENTE POTENCIAL';
            right: 4px;
            color: #4e4e4e;
        }

        #button-10 .knobs span
        {
            display: inline-block;
            left: 4px;
            color: #fff;
            z-index: 1;
        }

        #button-10 .checkbox:checked + .knobs span
        {
            color: #4e4e4e;
        }

        #button-10 .checkbox:checked + .knobs:before
        {
            left: 75px;
            background-color: #F44336;
        }

        #button-10 .checkbox:checked + .knobs:after
        {
            color: #fff;
        }

        #button-10 .checkbox:checked ~ .layer
        {
            background-color: #fcebeb;
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
                    <legend class="text-semibold">Información del Cliente</legend>

                    
                        <div class="button-cover col-md-10">
                            <div class="button b2" id="button-10">
                            <input type="checkbox" class="checkbox" id="switch_tipo" onclick="ToggleFields()">
                            <div class="knobs">
                                <span>CLIENTE NUEVO</span>
                            </div>
                            <div class="layer"></div>
                            </div>
                        </div>

                        <div class="col-md-12" style="text-align:center; padding:30px; margin-left: -90px;">
                            <p id="c_nuevo" style="display:block; font-weight:bold; font-size: 15px; ">Estás creando un cliente NUEVO</p>
                            <p id="c_potencial" style="display:none; font-weight:bold; font-size: 15px;  ">Estás creando un cliente POTENCIAL</p>
                        </div>
                
                    
                    
                  
                        <div class="col-md-5">                
                           
                            <div class="from-group" id="contnomb_group">
                                <label for="contacto_nombre">Nombre de Contacto:</label>
                                <input type="text" class="form-control" id="contacto_nombre"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>

                            <div class="from-group" id="contelf_group">
                                <label for="contacto_telefono">Teléfonos de Contacto:</label>
                                
                                <div style="display: flex; flex-direction: row; flex-wrap: wrap;" >

                                    <input type="text" class="form-control" id="contacto_telefono" style=""><button id="button_telefono" onclick="addPhone()" style="margin-left:-25px; color:white; background-color:#4caf50;border:0; border-radius:2px;"><i class="fa fa-plus"></i></button>
                                   <input type="text" class="form-control" id="contacto_telefono1" style=" display:none;"><button id="button_telefono1" onclick="minusPhone(this.id)" style="margin-left:-25px;   color:white; background-color:#f44336;border:0;border-radius:2px; display:none;"><i class="fa fa-times"></i></button>
                                    <input type="text" class="form-control" id="contacto_telefono2" style=" display:none;"><button id="button_telefono2" onclick="minusPhone(this.id)" style="margin-left:-25px;  color:white; background-color:#f44336;border:0;border-radius:2px; display:none;"><i class="fa fa-times"></i></button>
                                    <input type="text" class="form-control" id="contacto_telefono3" style=" display:none;"><button id="button_telefono3" onclick="minusPhone(this.id)" style="margin-left:-25px;  color:white; background-color:#f44336;border:0;border-radius:2px; display:none;"><i class="fa fa-times"></i></button>
                                    <input type="text" class="form-control" id="contacto_telefono4" style=" display:none;"><button id="button_telefono4" onclick="minusPhone(this.id)" style="margin-left:-25px;  color:white; background-color:#f44336;border:0;border-radius:2px; display:none;"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                            <div class="from-group" id="contemail_group">
                                <label for="contacto_email">Correo de Contacto:</label>
                                <input type="text"  class="form-control" id="contacto_email"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>

                            <div class="from-group" id="nc_group">
                                <label for="nombre_comercial">Nombre Comercial:</label>
                                <input type="text" class="form-control" id="nombre_comercial"  style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
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

                            <label for="ruc_dni" id="label_dni">RUC/DNI:</label>
                            <div class="from-group form-inline" id="dni_group">                                
                                <input type="number" class="form-control" name="nruc" id="nruc" placeholder="Ingrese RUC o DNI" pattern="([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])" autofocus>
                                <button type="submit" class="btn btn-success" name="btn-submit" id="btn-submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Procesando...">
                                    <i class="glyphicon glyphicon-search"></i> Verificar
                                </button>
                                <input type="hidden" class="form-control" id="idcliente">
                            </div>

                            <div class="from-group" id="rs_group">
                                <label for="razon_social">Razón Social/Nombre:</label>
                                <input type="text" class="form-control" id="razon_social" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            </div>

                          
                            
                        </div>

                        <div class="col-md-5">                   

                            <div class="row">
                            <div class="col-md-4">
                                <div class="from-group" id="direccion_group">
                                    <label for="direccion">Dirección Fiscal:</label>
                                    <input type="text" class="form-control" id="direccion"  style="width: 150%;text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                </div>

                                    <div class="from-group" id="depa_group">
                                        <label class="control-label" for="departamento">Departamento:</label>    
                                            <select id="departamento" class="form-control"  style="width: 150%;">
                                                <option value="0">--</option>
                                                
                                            </select>
                                    </div>

                                    <div class="from-group" id="prov_group">
                                        <label class="control-label" for="provincia">Provincia:</label>    
                                            <select id="provincia" class="form-control"  style="width: 150%;">
                                                
                                            </select>
                                    </div>

                                    <div class="from-group" id="dist_group">
                                        <label class="control-label" for="distrito">Distrito:</label>                        
                                            <select id="distrito" class="form-control"  style="width: 150%;">
                                              
                                            </select>
                                    </div>

                                    <?php $puesto= Auth::user()->idrol;?>
                                    <div class="from-group" id="ruta_group">
                                        <label for="contacto_ruta">Ruta:</label>
                                        <input type="number" class="form-control" id="contacto_ruta" min="1" max="99"
                                        @if(!($puesto ==1 || $puesto ==4 || $puesto ==9 || $puesto ==2) )
                                            disabled
                                        @endif
                                        >
                                    </div> 


                                    <div class="from-group" id="sec_group">
                                        <label for="sec">Secuencia:</label>                                                                          
                                        <input type="number" class="form-control" id="sec"
                                        @if(!($puesto ==1 || $puesto ==4 || $puesto ==9 || $puesto ==2) )
                                            disabled
                                        @endif
                                        >
                                    
                                    </div>

                                    <div class="from-group" id="direccionent_group">
                                    <label for="direccionent">Dirección Entrega:</label>
                                    <input type="text" class="form-control" id="direccionent"  style="width: 150%;text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    </div>

                                    

                                    <div class="from-group" id="tipolocation_group">
                                        <label for="tipo_location">Tipo de local:</label>
                                        <select id="tipo_location" class="form-control"  style="width: 150%;">
                                            <option value="fiscal">Fiscal</option>
                                            <option value="almacen">Almacen</option>
                                        </select>
                                    </div>

                                    <div class="from-group" id="tipocli_group">
                                <label for="tipo_cliente">Tipo de Cliente:</label>
                                <select id="tipo_cliente" class="form-control"  style="width: 150%;">
                                    <option value="0">--</option>
                                    <option value="SOUDAL">SOUDAL</option>
                                    <option value="ERP">ERP</option>
                                    <option value="OTROS">OTROS</option>
                                </select>
                            </div>

                            <div class="from-group" id="tipopago_group">
                                <label for="tipo_pago">Tipo de Pago:</label>
                                <select id="tipo_pago" class="form-control"  style="width: 150%;">
                                    <option value="99">--</option>
                                    <option value="0">Contado</option>
                                    <option value="1">Transferencia</option>
                                    <option value="2">Cheque</option>
                                </select>
                            </div>

                            <div class="from-group" id="diascred_group">
                                <label for="dias_credito">Días de Crédito:</label>
                                <input type="text" class="form-control" id="dias_credito"  style="width: 150%;">
                            </div>

                            

                            <div class="from-group" id="marcas_group" style="margin-top:15px;">
                                <label for="ms">Marcas:</label>
                                <select id="ms" multiple="multiple">    
                                    @foreach($marcas as $marca)                                
                                        <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                    @endforeach                                   
                                </select>
                            </div>
                                                                
                            </div>
                            </div>
                            
                        </div>

                        </fieldset>
                    </div>
                        <div id="map" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
        </div>   
             
    </div>
   

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>    



<script rel="script" type="text/javascript">
     var departamentos = {!! json_encode($departamentos) !!};
    var distritos = {!! json_encode($distritos) !!};
    var provincias = {!! json_encode($provincias) !!};

    $(function() {
        $('#ms').change(function() {
            console.log($(this).val());
        }).multipleSelect({
            width: '150%'
        });
    });



 $(document).ready(function() {


    console.log("departamentos");

    var select01 = '';	
    $.each(departamentos, function(i, obj) {
        select01 = '<option value="'+ departamentos[i].id + '">' + departamentos[i].departamento_name + '</option>';
        $('#departamento').append(select01);
    });

    //listar provincias por departamento
    $('#departamento').on('change', function() {

        //console.log($("#departamento option:selected ").text());
        
        //limpiar los selects	
        $('#provincia').find('option').remove();
        $('#distrito').find('option').remove();
        
        var iddep = this.value;
        var select02 = '';


        
        var provincias_result_array = provincias.filter(function(elm){
            return elm.id_depa== iddep;
        });	
        
        //var provincias_result = jQuery.grep(provincias, function (obj) {
         //   return obj[iddep];
        //});

            console.log(provincias_result_array[0]);

        
        //console.log( JSON.stringify(provincias_result, null, '\t') );
        //console.log( JSON.stringify(provincias_result[0][iddep], null, '\t') );
        
        //var provincias_result_array = provincias_result[0][id_depa];
        
        $.each(provincias_result_array, function(i, obj) {
            select02 = '<option value="'+ provincias_result_array[i].id + '">' + provincias_result_array[i].provincia_name + '</option>';
            $('#provincia').append(select02);			
        });
        
        //cargar los distritos
        $('#provincia').trigger('change');
        
    });

    //listar distritos por provincia
    $('#provincia').on('change', function() {
        
        //limpiar el select de distritos		
        $('#distrito').find('option').remove();
        
        var idpro = this.value;
        var select03 = '';	
        
        //var distritos_result = jQuery.grep(distritos, function (obj) {
        //    return obj[idpro];
        //});

        var distritos_result_array = distritos.filter(function(elm){
            return elm.id_provi== idpro;
        });	
        
        //console.log( JSON.stringify(distritos_result, null, '\t') );
        //console.log( JSON.stringify(distritos_result[0][idpro], null, '\t') );
        
        //var distritos_result_array = distritos_result[0][idpro];
        
        $.each(distritos_result_array, function(i, obj) {
            select03 = '<option value="'+ distritos_result_array[i].id + '">' + distritos_result_array[i].distrito_name + '</option>';
            $('#distrito').append(select03);			
        });
        
    });

    //seleccionar por defecto LIMA
    //$('#departamento option[value="150000"]').attr("selected",true);
    //$('#departamento').trigger('change');

    //seleccionar por defecto LIMA
   // $('#provincia option[value="150100"]').attr("selected",true);
   // $('#provincia').trigger('change');

    //seleccionar por defecto LA MOLINA
   // $('#distrito option[value="150114"]').attr("selected",true);



});   

</script>



<script rel="script" type="text/javascript">



    function addPhone(){
        var texto = document.getElementById("contacto_telefono");
        var button = document.getElementById("button_telefono");

        var texto1 = document.getElementById("contacto_telefono1");
        var button1 = document.getElementById("button_telefono1");

        var texto2 = document.getElementById("contacto_telefono2");
        var button2 = document.getElementById("button_telefono2");

        var texto3 = document.getElementById("contacto_telefono3");
        var button3 = document.getElementById("button_telefono3");

        var texto4 = document.getElementById("contacto_telefono4");
        var button4 = document.getElementById("button_telefono4");

        if(texto1.style.display == "none"){
            texto1.style.display = "block";
            button1.style.display = "block";
        }
        else if(texto2.style.display == "none"){
            texto2.style.display = "block";
            button2.style.display = "block";
        }
        else if(texto3.style.display == "none"){
            texto3.style.display = "block";
            button3.style.display = "block";
        }
        else if(texto4.style.display == "none"){
            texto4.style.display = "block";
            button4.style.display = "block";
            button.style.display = "none";
        }

    }

    function minusPhone(id){    

        
        var idtexto = "contacto_telefono"+ id.substr(-1);
        var texto1 = document.getElementById(idtexto);
        var button1 = document.getElementById(id);
        
        var button = document.getElementById("button_telefono");

        texto1.value='';
            texto1.style.display = "none";
            button1.style.display = "none";
            button.style.display = "block";
    
    }




    function ToggleFields() {
    // Get the checkbox
    var checkBox = document.getElementById("switch_tipo");
    // Get the output text
    var c_nuevo = document.getElementById("c_nuevo");
    var c_potencial = document.getElementById("c_potencial");
    


    var dni=document.getElementById("dni_group");
    var label_dni=document.getElementById("label_dni");
    var ruta = document.getElementById("ruta_group");
    var sec = document.getElementById("sec_group");     
    var razon_social =document.getElementById("rs_group");
    //var nombre_comercial =document.getElementById("nc_group");
    var direccionent =document.getElementById("direccionent_group");
    var  dias_credito=document.getElementById("diascred_group");
    var tipo_cliente =document.getElementById("tipocli_group");
    var tipo_location =document.getElementById("tipolocation_group");
    var tipo_pago =document.getElementById("tipopago_group");
    var marcas = document.getElementById("marcas_group");




    if (checkBox.checked == true){
        c_nuevo.style.display = "none";
        c_potencial.style.display = "block";
        
        dni.style.display = "none";
        label_dni.style.display = "none";
        razon_social.style.display = "none";
        //nombre_comercial.style.display = "none";        
        direccionent.style.display = "none";
        dias_credito.style.display = "none";
        tipo_pago.style.display = "none";
        tipo_cliente.style.display = "none";
        tipo_location.style.display = "none";
        ruta.style.display = "none";
        sec.style.display = "none";

        //marcas.style.display = "none";



    } else {
        c_nuevo.style.display = "block";
        c_potencial.style.display = "none";


        dni.style.display = "block";
        label_dni.style.display = "block";
        razon_social.style.display = "block";
        //nombre_comercial.style.display = "block";        
        direccionent.style.display = "block";
        dias_credito.style.display = "block";
        tipo_pago.style.display = "block";
        tipo_cliente.style.display = "block";
        tipo_location.style.display = "block";
        ruta.style.display = "block";
        sec.style.display = "block";
        //marcas.style.display = "block";


    }
    }


    var map = L.map('map', {
        center: [-16.39889, -71.535],
        zoom: 13
    });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([-16.39889,-71.535]).addTo(map);
    var selectedLatLng = {lat: 0, lng: 0}
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        selectedLatLng.lat = e.latlng.lat;
        selectedLatLng.lng = e.latlng.lng;
    });


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
                console.log("search ruc....");
                console.log(data);
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
                                console.log("search dni....");
                                console.log(data);
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
            

    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    $('#distrito').select2();
    $('#provincia').select2();
    $('#departamento').select2();

    var clienteSINRUC;
    $('#btn_clientesinruc').click(function(event){
        $.get( "{{route('clienteSINRUC')}}", function(data){
            console.log(data);
            $('#ruc_dni').val(data);
        });
    });

    $('#btn_guardar').click(function(event){
        $('#btn_guardar').prop( "disabled", true );
        var idcliente = $('#idcliente').val();
        console.log(idcliente);
        var ruc_dni = $('#nruc').val();
        var razon_social = $('#razon_social').val();
        var nombre_comercial = $('#nombre_comercial').val();
        
        var direccion = $('#direccion').val();
        var direccionent = $('#direccionent').val();
       // var distrito = $('#distrito').val();
        //var provincia = $('#provincia').val();
        //var departamento = $('#departamento').val();

        var distrito = $("#distrito option:selected ").text();
        var provincia = $("#provincia option:selected ").text();
        var departamento = $("#departamento option:selected ").text();


        var contacto_nombre = $('#contacto_nombre').val();
        var contacto_telefono = $('#contacto_telefono').val();
        var contacto_telefono1 = $('#contacto_telefono1').val();
        var contacto_telefono2 = $('#contacto_telefono2').val();
        var contacto_telefono3 = $('#contacto_telefono3').val();
        var contacto_telefono4 = $('#contacto_telefono4').val();
        var contacto_ruta = $('#contacto_ruta').val();
        var sec = $('#sec').val();  
        var contacto_email = $('#contacto_email').val();
        var dias_credito = $('#dias_credito').val();
        var tipo_pago = $('#tipo_pago').val();
        var tipo_emp = $('#tipo_emp').val();
        var tipo_cliente = $('#tipo_cliente').val();
        var location_type = $('#tipo_location').val();

        var marcas =$('#ms').val();


        if (marcas) {
            marcas=marcas.join();
        }
        else{
            marcas="vacio";
        }
        
        console.log(marcas);


        var validador=false;  // -1 campos incompletos, 0 cliente potencial,1 cliente completo
        var cliente_estado="NUEVO";
        if(document.getElementById("switch_tipo").checked ==true){
            cliente_estado= "POTENCIAL";
            validador=true;
        }
        /*if(bool_click == false){
                swal({
                    title: "Upss!",
                    text: "Debes verificar RUC/DNI del Cliente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
        }*/
       


        if(validador){
            if(nombre_comercial.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el Nombre Comercial del Cliente",
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
                        text: "Debes agregar la Direccion del Cliente",
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
                        text: "Debes agregar el Nombre del Contacto del Cliente",
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
                        text: "Debes agregar el Telefono del Contacto del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(distrito == 0  || distrito==null || distrito==''){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Distrito del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(provincia== 0 || provincia==null || provincia=='' ){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir la Provincia del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(departamento == 0 || departamento==null || departamento=='' ){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Departamento del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

            

        }
        
        else{
            
            var puesto = {!!json_encode($puesto)!!};

            /*if(!(puesto ==1 || puesto ==4 || puesto ==9 || puesto ==2) ){
                    swal({
                        title: "Upss!",
                        text: "Solo los siguientes roles pueden crear clientes nuevos: \n GERENTE GENERAL\nCOORDINADOR COMERCIAL\nASISTENTE COMERCIAL\nADMINISTRADOR\nASESOR COMERCIAL",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }*/
                                            
            if(ruc_dni.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el RUC/DNI del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

            else if(ruc_dni.length != 8 && ruc_dni.length != 11){
                    swal({
                        title: "Upss!",
                        text: "El RUC/DNI debe tener 8 u 11 dígitos",
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
                        text: "Debes agregar la Razon Social del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            ////////////////////////////////////////////RUTAAAAAAAAAAAA///////////////////
            
           /* if(contacto_ruta.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar la Ruta",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }*/

            if(contacto_ruta.length > 2){
                    swal({
                        title: "Upss!",
                        text: "La Ruta debe ser de máximo dos dígitos",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

            /*if(sec.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar la Secuencia",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }*/


            ////////////////////////////////////////
            
            if(nombre_comercial.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el Nombre Comercial del Cliente",
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
                        text: "Debes agregar la Direccion del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

            
            if(direccionent.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar la Direccion de Entrega del Cliente",
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
                        text: "Debes agregar el Nombre del Contacto del Cliente",
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
                        text: "Debes agregar el Telefono del Contacto del Cliente",
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
                        text: "Debes agregar el Correo del Contacto del Cliente",
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
                        text: "Debes agregar los Días de Crédito del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(tipo_cliente == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Tipo de Cliente",
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
                        text: "Debes elegir el Tipo de Empresa del Cliente",
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
                        text: "Debes elegir el Tipo de Pago del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error" 
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(distrito == 0  || distrito==null || distrito==''){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Distrito del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(provincia== 0 || provincia==null || provincia=='' ){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir la Provincia del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
            if(departamento == 0 || departamento==null || departamento=='' ){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Departamento del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
        }

        $.post(currentLocation+'guardar_cliente',{idcliente:idcliente, ruc_dni:ruc_dni, razon_social:razon_social, direccion:direccion, direccionent:direccionent, distrito:distrito,provincia:provincia,departamento:departamento, contacto_ruta:contacto_ruta, sec:sec,  contacto_nombre:contacto_nombre, contacto_telefono:contacto_telefono, contacto_telefono1:contacto_telefono1, contacto_telefono2:contacto_telefono2,contacto_telefono3:contacto_telefono3,contacto_telefono4:contacto_telefono4,contacto_email:contacto_email, dias_credito:dias_credito, tipo_emp:tipo_emp, tipo_pago:tipo_pago,tipo_cliente:tipo_cliente, nombre_comercial:nombre_comercial,cliente_estado:cliente_estado, location_type: location_type, lat: selectedLatLng.lat, lng: selectedLatLng.lng, marcas:marcas },function(data){
           
           
            obj = JSON.parse(data);
            console.log(obj);
            console.log(obj.mensaje);
            if(obj.mensaje === 200){
                setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                swal({
                    title: "Ok!",
                    text: "Se guardo el cliente nuevo correctamente!.",
                    confirmButtonColor: "#66BB6A",
                    type: "success"
                },function(){
                    window.location.replace(currentLocation+'list_clientes');
                    /*window.close();
                    window.opener.location.reload();*/
                });
                return;
            }
            else if(obj.mensaje === 201){
                setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                swal({
                    title: "Ok!",
                    text: "Se guardo el cliente potencial correctamente!.",
                    confirmButtonColor: "#66BB6A",
                    type: "success"
                },function(){
                    window.location.replace(currentLocation+'list_potenciales');
                    /*window.close();
                    window.opener.location.reload();*/
                });
                return;
            }
            
            else if(obj.mensaje === 999){
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
                    text: "No se puede guardar el cliente, intentalo de nuevo luego.",
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