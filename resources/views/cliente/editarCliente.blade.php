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

    <h4>@if(!empty($cliente->ruc_dni))<i class="glyphicon glyphicon-eye-open"></i>@endif <span class="text-semibold">@if(!empty($cliente->ruc_dni)) Ver - Editar Cliente @else  Promover Cliente @endif</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active"><a href="/list_clientes"></i>Listado de Clientes</a></li>
    <li class="active">@if(!empty($cliente->ruc_dni)) Ver - Editar Cliente @else  Promover Cliente @endif</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-success btn-lg" id="btn_guardar">
               
                @if(!empty($cliente->ruc_dni))
                <i class="glyphicon glyphicon-save "></i>
                Editar
                @else
                <i class="glyphicon glyphicon-open "></i>
                Promover
                @endif
            </button>
        </div>
        @if(!empty($cliente->ruc_dni))
         <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-light btn-lg" id="btn_ultimos">
                <i class="glyphicon glyphicon-eye-open"></i>
                Ultimos Dos Pedidos
            </button>
        </div>
        @endif

    </li>
@stop

<!-- CONTENIDO DE LA PAGINA -->
@section('contenido')

<style type="text/css">
        .hide-loader{
            display:none;
        }
</style>
    <?PHP
    header("Access-Control-Allow-Origin:*");
    ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ URL::to('/') }}">


    <div class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                 
                        <div class="from-group form-inline" id="dni_group">
                            <label for="ruc_dni">RUC/DNI:</label>
                            @if(!empty($cliente->ruc_dni))
                                <input type="text" class="form-control" id="ruc_dni" value="{{ $cliente->ruc_dni }}" disabled>
                                <input type="hidden" class="form-control" id="idcliente" value="{{ $cliente->idcliente }}">
                            @else
                            <input type="number" class="form-control"  id="ruc_dni" placeholder="Ingrese RUC o DNI" pattern="([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])" autofocus>
                                <button type="submit" class="btn btn-success" name="btn-submit" id="btn-submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Procesando...">
                                    <i class="glyphicon glyphicon-search"></i> Verificar
                                </button>                            
                            <input type="hidden" class="form-control" id="idcliente" value="{{ $cliente->idpotencial }}">
                            @endif
                        </div>

                        <div class="from-group" id="rs_group">
                            <label for="razon_social">Razon Social:</label>
                            <input type="text"  onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="razon_social" value="{{ $cliente->razon_social }}">
                        </div>

                        <div class="from-group" id="nc_group">
                            <label for="nombre_comercial">Nombre Comercial:</label>
                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="nombre_comercial" value="{{ $cliente->nombre_comercial }}">
                        </div>

                        <div class="from-group" id="contnomb_group">
                            <label for="contacto_nombre">Nombre de Contacto:</label>
                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="contacto_nombre" value="{{ $cliente->contacto_nombre }}">
                        </div>

                        <div class="from-group" id="contelf_group">
                                <label for="contacto_telefono">Teléfonos de Contacto:</label>
                                
                                <div style="display: flex; flex-direction: row; flex-wrap: wrap;" >

                                    <input type="text" class="form-control" id="contacto_telefono" value="{{ $cliente->contacto_telefono}}" style=""><button id="button_telefono" onclick="addPhone()" style="margin-left:-25px; color:white; background-color:#4caf50;border:0; border-radius:2px;
                                    @if($cliente->contacto_telefono2 != "" && $cliente->contacto_telefono3 != "" && $cliente->contacto_telefono4 != "" && $cliente->contacto_telefono5 != "")
                                    display:none;
                                    @endif
                                    "><i class="fa fa-plus"></i></button>
                                   
                                   <input type="text" class="form-control" id="contacto_telefono1" value="{{ $cliente->contacto_telefono2}}" 
                                    @if($cliente->contacto_telefono2 != "")
                                   style=" display:block;">
                                   @else
                                    style=" display:none;">
                                   @endif
                                   <button id="button_telefono1" onclick="minusPhone(this.id)" style="margin-left:-25px;   color:white; background-color:#f44336;border:0;border-radius:2px; 
                                   @if($cliente->contacto_telefono2 !="")
                                   display:block;">
                                   @else
                                   display:none;">
                                   @endif
                                   <i class="fa fa-times"></i></button>
                                    <input type="text" class="form-control" id="contacto_telefono2" value="{{ $cliente->contacto_telefono3}}" 
                                     @if($cliente->contacto_telefono3 != "")
                                    style=" display:block;">
                                    @else
                                        style=" display:none;">
                                    @endif
                                    <button id="button_telefono2" onclick="minusPhone(this.id)" style="margin-left:-25px;  color:white; background-color:#f44336;border:0;border-radius:2px; 
                                    @if($cliente->contacto_telefono3 !="")
                                    display:block;">
                                    @else
                                    display:none;">
                                    @endif
                                    <i class="fa fa-times"></i></button>
                                    <input type="text" class="form-control" id="contacto_telefono3" value="{{ $cliente->contacto_telefono4}}"  
                                     @if($cliente->contacto_telefono4 != "")
                                    style=" display:block;">
                                    @else
                                        style=" display:none;">
                                    @endif
                                    <button id="button_telefono3" onclick="minusPhone(this.id)" style="margin-left:-25px;  color:white; background-color:#f44336;border:0;border-radius:2px; 
                                    @if($cliente->contacto_telefono4 !="")
                                    display:block;">
                                    @else
                                    display:none;">
                                    @endif
                                    <i class="fa fa-times"></i></button>
                                    <input type="text" class="form-control" id="contacto_telefono4" value="{{ $cliente->contacto_telefono5}}" 
                                     @if($cliente->contacto_telefono5 != "")
                                    style=" display:block;">
                                    @else
                                        style=" display:none;">
                                    @endif
                                    <button id="button_telefono4" onclick="minusPhone(this.id)" style="margin-left:-25px;  color:white; background-color:#f44336;border:0;border-radius:2px; 
                                    @if($cliente->contacto_telefono5 !="")
                                    display:block;">
                                    @else
                                    display:none;">
                                    @endif
                                    <i class="fa fa-times"></i></button>
                                </div>
                            </div>

                        <div class="from-group" id="contemail_group">
                            <label for="contacto_email">Correo de Contacto:</label>
                            <input type="text" class="form-control" id="contacto_email" value="{{ $cliente->contacto_email }}">
                        </div>
                        
                        <div class="from-group" id="tipemp_group">
                            <label for="tipo_emp">Tipo de Empresa:</label>
                                <select class="form-control" id="tipo_emp">
                                    @foreach ($tipos_emp as $tipo_emp)
                                        <option value="{{ $cliente->tipo_emp }}"> {{ $tipo_emp->tipoemp_nombre }} </option>
                                    @endforeach
                                        <option value="0"> -- </option>
                                    @foreach ($tipos_emp2 as $tipo_emp2)
                                        <option value="{{ $tipo_emp2->id_tipoemp }}">{{$tipo_emp2->tipoemp_nombre}}</option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="from-group" id="tipopago_group">
                                <label for="tipo_cliente">Tipo de Cliente:</label>
                                <select id="tipo_cliente" class="form-control"  style="width: 80%;">
                                    <option <?php if ($cliente->tipo_cliente == 0) echo 'selected' ; ?> value="0">Falta Actualizar</option>                  
                                    <option <?php if ($cliente->tipo_cliente == 'SOUDAL' ) echo 'selected' ; ?> value="SOUDAL">SOUDAL</option>
                                    <option <?php if ($cliente->tipo_cliente == 'ERP' ) echo 'selected' ; ?> value="ERP">ERP</option>
                                    <option <?php if ($cliente->tipo_cliente == 'OTROS' ) echo 'selected' ; ?> value="OTROS">OTROS</option>
                                </select>
                        </div>

                        <div class="from-group" id="tipopago_group">
                                <label for="tipo_pago">Tipo de Pago:</label>
                                <select id="tipo_pago" class="form-control"  style="width: 80%;">
                                    <option value="99">Falta Actualizar</option>                                    
                                    <option <?php if ($cliente->tipo_pago == 0 ) echo 'selected' ; ?> value="0">Contado</option>
                                    <option <?php if ($cliente->tipo_pago == 1 ) echo 'selected' ; ?> value="1">Transferencia</option>
                                    <option <?php if ($cliente->tipo_pago == 2 ) echo 'selected' ; ?> value="2">Cheque</option>
                                </select>
                        </div>
                        
                        <div class="from-group" id="diascred_group">
                            <label for="dias_credito">Días de Crédito:</label>
                            <input type="number" class="form-control" id="dias_credito" value="{{ $cliente->dias_credito }}">
                        </div>

                        <div class="from-group" id="direccion_group">
                            <label for="direccion">Dirección Fiscal:</label>
                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="direccion" value="{{ $cliente->direccion }}">
                        </div>

                        <div class="from-group" id="direccion_group">
                            <label for="direccion">Dirección Entrega:</label>
                            <input type="text" onkeyup="this.value = this.value.toUpperCase()" class="form-control" id="direccion_ent" value="{{ $cliente->direccion_ent }}">
                        </div>

                        <div class="from-group" id="tipolocation_group">
                                        <label for="tipo_location">Tipo de local:</label>
                                        <select id="tipo_location" class="form-control"  >
                                            <!--<option value="unica">Unica</option>-->
                                            
                                            <option value="FISCAL">FISCAL</option>
                                            <option value="ALMACEN"
                                            @if($clienteubicacion->location_type=="ALMACEN" || $clienteubicacion->location_type=="almacen")
                                                selected
                                            @endif                                            
                                            >ALMACEN</option>
                                        </select>
                        </div>

                        <div class="row">
                        <div class="col-md-4">
                           

                           
                            <div class="from-group" id="dist_group">
                                <label for="departamento">Departamento:</label>
                                <select class="form-control" id="departamento">
                                   <!-- <option value="{{ $cliente->departamento }}">{{ $cliente->departamento }}</option> -->
                                                               
                                </select>
                                <!-- <input type="text" class="form-control" id="distrito" value="{{ $cliente->distrito }}"> -->
                            </div>

                            <div class="from-group" id="dist_group">
                                <label for="provincia">Provincia:</label>
                                <select class="form-control" id="provincia">
                                    <!--<option value="{{ $cliente->provincia }}">{{ $cliente->provincia }}</option> -->
                                                               
                                </select>
                                <!-- <input type="text" class="form-control" id="distrito" value="{{ $cliente->distrito }}"> -->
                            </div>

                            <div class="from-group" id="dist_group">
                                <label for="distrito">Distrito: </label>
                                <select class="form-control" id="distrito">
                                    <!--<option value="{{ $cliente->distrito }}">{{ $cliente->distrito }}</option>  -->
                                          
                                </select>
                                <!-- <input type="text" class="form-control" id="distrito" value="{{ $cliente->distrito }}"> -->
                            </div>
                            <?php $puesto= Auth::user()->idrol;?>
                            <div class="from-group" id="ruta_group">
                                        <label for="contacto_ruta">Ruta:</label>
                                        <input type="number" class="form-control" id="contacto_ruta" min="1" max="99" value="{{ $cliente->ruta }}"
                                        @if(!($puesto ==1 || $puesto ==4 || $puesto ==9 || $puesto ==2) )
                                            disabled
                                        @endif
                                        >
                                        
                            </div> 


                            <div class="from-group" id="sec_group">
                                <label for="sec">Secuencia:</label>                                                                          
                                    <input type="number" class="form-control" id="sec" value="{{ $cliente->secuencia }}"
                                    @if(!($puesto ==1 || $puesto ==4 || $puesto ==9 || $puesto ==2) )
                                            disabled
                                        @endif
                                    >
                            
                            </div>


                            <div class="from-group" id="marcas_group">
                                <label >Marcas:</label>
                                <select id="ms" multiple="multiple">
                                <?php $marcasantes=array(); $vali=0;?>
                                @foreach($marcas as $marca)                                
                                        <option value="{{$marca->id}}"
                                            <?php foreach($selectedmarcas as $selected){
                                                if($vali==0){
                                                    array_push($marcasantes, strval($selected->idmarca));
                                                }
                                                if($marca->id==$selected->idmarca)
                                                    echo "selected";
                                                
                                            }
                                            $vali=$vali+1;
                                            ?>
                                           
                                        
                                        >{{$marca->nombre}}</option>
                                    @endforeach 
                                </select>
                            </div>


                        </div>
                        
                        </div>

                        <div class="from-group" id="tipemp_group">
                            <label for="idvendedor">Vendedor:</label>
                                <select class="form-control" id="idvendedor">
                                        <option value="0"> -- </option>
                                    @foreach ($usuarios as $user)
                                        <option value="{{ $user->id }}"
                                            @if($cliente->idvendedor== $user->id)
                                                selected
                                            @endif
                                        >{{$user->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                            
                        <div id="map" style="width: 100%; height: 500px;"></div>
                        

                        

                </div>
            </div>
        </div>
    </div>

</div>

<div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Últimos Dos Pedidos</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>

                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de los Dos Últimos Pedidos</legend>
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Razón Social:</label>
                                        <input type="text" id="razon_social_modal" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Última Fecha de Visita/Llamada:</label>
                                        <input type="text" id="fecha_visit" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Última Fecha de Pedido:</label>
                                        <input type="text" id="fecha_pedido" class="form-control" disabled> 
                                    </div>
                                </div>

                                <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                                    <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                    </tr>
                                    </thead>
                                    <tbody id="cliente_table">
                                    @foreach ($productos as $prod)
                                        <tr id="tr_detalle">
                                            <td>{{ $prod->barcode }}</td>
                                            <td>{{ $prod->nombre}}</td>
                                            <td>{{ $prod->cantidad}}</td>
                                            <td>{{ $prod->precio}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </fieldset>
                      
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" id="submit-control">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
    
    </div>
    </div>
    </div>

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


        var latituds=-16.39889;
        var longituds= -71.535;
        
        if({!! json_encode($clienteubicacion->latitud) !!} && {!! json_encode($clienteubicacion->longitud) !!} ){
            latituds= {!! json_encode($clienteubicacion->latitud) !!};
            longituds={!! json_encode($clienteubicacion->longitud) !!};
        }

        console.log(latituds);
        console.log(longituds);

        var map = L.map('map', {
            center: [latituds, longituds],
            zoom: 13
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker([latituds,longituds]).addTo(map);
        var selectedLatLng = {lat: 0, lng: 0}
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            selectedLatLng.lat = e.latlng.lat;
            selectedLatLng.lng = e.latlng.lng;
        });




        var departamentos = {!! json_encode($departamentos) !!};
        var distritos = {!! json_encode($distritos) !!};
        var provincias = {!! json_encode($provincias) !!};

        $(function() {
            $('#ms').change(function() {
                console.log($(this).val());
            }).multipleSelect({
                width: '90%'
            });
        });

        $(document).ready(function() {


console.log(departamentos);

var select01 = '';	
var depa_cliente= {!! json_encode($cliente->departamento)!!};
var dist_cliente={!! json_encode($cliente->distrito)!!};
var prov_cliente={!! json_encode($cliente->provincia)!!};
var cod_depa;
var cod_dist;
var cod_prov;

$.each(departamentos, function(i, obj) {
    select01 = '<option value="'+ departamentos[i].id + '">' + departamentos[i].departamento_name + '</option>';
    $('#departamento').append(select01);
    if(departamentos[i].departamento_name == depa_cliente){
            cod_depa= departamentos[i].id;
    }

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

        

    
    //console.log( JSON.stringify(provincias_result, null, '\t') );
    //console.log( JSON.stringify(provincias_result[0][iddep], null, '\t') );
    
    //var provincias_result_array = provincias_result[0][id_depa];
    
    $.each(provincias_result_array, function(i, obj) {
        select02 = '<option value="'+ provincias_result_array[i].id + '">' + provincias_result_array[i].provincia_name + '</option>';
        $('#provincia').append(select02);
        if( cod_depa== provincias_result_array[i].id_depa && provincias_result_array[i].provincia_name ==  prov_cliente ){
            //cod_prov= provincias_result_array[i].id;
                console.log(provincias_result_array[i].id);
                cod_prov= provincias_result_array[i].id;
        }			
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

    console.log(distritos_result_array[0]);
    
    //console.log( JSON.stringify(distritos_result, null, '\t') );
    //console.log( JSON.stringify(distritos_result[0][idpro], null, '\t') );
    
    //var distritos_result_array = distritos_result[0][idpro];
    
    $.each(distritos_result_array, function(i, obj) {
        select03 = '<option value="'+ distritos_result_array[i].id + '">' + distritos_result_array[i].distrito_name + '</option>';
        $('#distrito').append(select03);
        if(  cod_prov== distritos_result_array[i].id_provi && distritos_result_array[i].distrito_name == dist_cliente ){
            cod_dist= distritos_result_array[i].id;
        }				
    });
    console.log(cod_depa);
    console.log(cod_dist);
    console.log(cod_prov);
    
});
 //existe el dato de ubicacion del cliente
    $('#departamento option[value="'+cod_depa+'"]').attr("selected",true);
    $('#departamento').trigger('change');

    //seleccionar por defecto LIMA
     $('#provincia option[value="'+cod_prov+'"]').attr("selected",true);
     $('#provincia').trigger('change');

    //seleccionar por defecto LA MOLINA
     $('#distrito option[value="'+cod_dist+'"]').attr("selected",true);


//seleccionar por defecto LIMA




});   



        $("#ruc_dni").keyup(function(event) {
                if (event.keyCode === 13) {
                $("#btn-submit").click();
            }
        });
        
        var bool_click = false;

        $("#btn-submit").click(function(e){
            bool_click = true;
            var $this = $(this);
            
            $this.button('loading');
            
            //$("#contacto_nombre").val('');
            e.preventDefault();
                    
            $.ajax({
                data: { "nruc" : $("#ruc_dni").val() },
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

                            $("#ruc_dni").val(data['result']['ruc']);
                            $("#razon_social").val(data['result']['razon_social']);
                            if(data['result']['nombre_comercial'] != '-')
                                $("#nombre_comercial").val(data['result']['nombre_comercial']);
                            if(data['result']['direccion'])
                                $("#direccion").val(data['result']['direccion']);


                            document.getElementById('ruc_dni').disabled = true;
                            document.getElementById('razon_social').disabled = true;

                            if(typeof(data['result']['representantes_legales'][0])!='undefined')
                                $("#contacto_nombre").val(data['result']['representantes_legales'][0]['nombre']+' - '+data['result']['representantes_legales'][0]['cargo']);
                        
                        }
                            $("#error").hide();
                            $(".result").show();
                        }
                        else if ( typeof(data['message'])!='undefined' && data['err_num']==501 )
                        {
                            if ($("#ruc_dni").val().length == 8) {
                                $.ajax({
                                    data: {
                                        "nruc": $("#ruc_dni").val(),
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

                                            $("#ruc_dni").val(data['result']['dni']);
                                            document.getElementById('ruc_dni').disabled = true;
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
                               // $("#contacto_nombre").val('');
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
                  //  $("#contacto_nombre").val('');
                });        
        });








        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        $('#btn_ultimos').click(function(event){
            $('#cliente_table').dataTable().fnDestroy();
            table = $('#cliente_table').DataTable( {
                paging: false,
                searching: false,
                ordering: true
            } );

            $('#razon_social_modal').val("<?PHP echo (!empty($visit->rs) ? ($visit->rs) : ''); ?>");
            $('#fecha_visit').val("<?PHP echo (!empty($visit->fecha) ? ($visit->fecha) : ''); ?>");
            $('#fecha_pedido').val("<?PHP echo (!empty($fecha_pedido->fecha) ? ($fecha_pedido->fecha) : ''); ?>");

            $('#formulario').modal('show');

        });


        $('#distrito').select2();
        $('#provincia').select2();
        $('#departamento').select2();

        $('#dis_entrega').select2();
        $('#pro_entrega').select2();
        $('#dep_entrega').select2();

        $('#btn_guardar').click(function(event){
            $('#btn_guardar').prop( "disabled", true );
            var idcliente = $('#idcliente').val();
            var idvendedor = $('#idvendedor').val();
            var ruc_dni = $('#ruc_dni').val();
            var razon_social = $('#razon_social').val();

            var direccion = $('#direccion').val();
            var direccion_ent = $('#direccion_ent').val();
            var distrito = $("#distrito option:selected ").text();
        var provincia = $("#provincia option:selected ").text();
        var departamento = $("#departamento option:selected ").text();
            
            var contacto_nombre = $('#contacto_nombre').val();
            var marcas =$('#ms').val();

        var marcasantes ={!!json_encode($marcasantes)!!};

        console.log(marcas);
        

        if (marcasantes && marcas) {
            borrarmarcas=marcasantes.filter(x => marcas.indexOf(x) === -1);
            marcas=marcas.filter(x => marcasantes.indexOf(x) === -1);
            
        }
        else{

          
            if (!marcasantes) {
                borrarmarcas="vacio";                
            } 
            else if (!marcas ) {
                marcas="vacio";
                borrarmarcas= marcasantes;
            } 

           
        }

       

                     
        
     
    
        if(borrarmarcas.length === 0 ||marcasantes.length === 0){
            borrarmarcas="vacio";   
        }
        if( marcas.length === 0){
            marcas="vacio";
        }
        


        if(typeof borrarmarcas !='string'){
            borrarmarcas=borrarmarcas.join();
        }

        if(typeof marcas !='string'){
            marcas=marcas.join();
        }

        console.log(borrarmarcas);
        console.log(marcas);
        
        var contacto_telefono = $('#contacto_telefono').val();
        var contacto_telefono1 = $('#contacto_telefono1').val();
        var contacto_telefono2 = $('#contacto_telefono2').val();
        var contacto_telefono3 = $('#contacto_telefono3').val();
        var contacto_telefono4 = $('#contacto_telefono4').val();
        var contacto_email = $('#contacto_email').val();
        var contacto_ruta = $('#contacto_ruta').val();
        var sec = $('#sec').val();  
        var dias_credito = $('#dias_credito').val();
        var tipo_emp = $('#tipo_emp').val();
        var tipo_pago = $('#tipo_pago').val();
        var tipo_cliente = $('#tipo_cliente').val();
        var location_type = $('#tipo_location').val();
            

        var nombre_comercial = '';
            if ( $('#nombre_comercial').val() != undefined )
                nombre_comercial = $('#nombre_comercial').val();

            console.log(idcliente);

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
            if(nombre_comercial.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar el nombre comercial del Cliente",
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
                        text: "Debes agregar la direccion del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }

            if(direccion_ent.length == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes agregar la direccion de entrega del Cliente",
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
            /*if(tipo_cliente == 0){
                swal({
                    title: "Upss!",
                    text: "Debes elegir el Tipo de Cliente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#btn_guardar').prop( "disabled", false );
                });
                return;
            }*/
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
            if(distrito == 0){
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
            if(provincia == 0){
                    swal({
                        title: "Upss!",
                        text: "Debes elegir el Provincia del Cliente",
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
                        text: "Debes elegir el Departamento del Cliente",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#btn_guardar').prop( "disabled", false );
                    });
                    return;
            }
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

            console.log(idcliente);
            console.log(ruc_dni);
            console.log(selectedLatLng.lat);
            console.log(selectedLatLng.lng);
            $.post(currentLocation+'store_update_cli',{idcliente:idcliente, idvendedor:idvendedor, ruc_dni:ruc_dni, razon_social:razon_social, direccion:direccion,contacto_ruta:contacto_ruta, sec:sec, direccion_ent:direccion_ent, distrito:distrito, provincia:provincia,departamento:departamento, contacto_nombre:contacto_nombre, contacto_telefono:contacto_telefono, contacto_telefono1:contacto_telefono1, contacto_telefono2:contacto_telefono2,contacto_telefono3:contacto_telefono3,contacto_telefono4:contacto_telefono4, contacto_email:contacto_email, dias_credito:dias_credito, tipo_emp:tipo_emp,tipo_pago:tipo_pago,tipo_cliente:tipo_cliente, nombre_comercial:nombre_comercial, location_type:location_type, lat: selectedLatLng.lat, lng: selectedLatLng.lng, marcas:marcas, borrarmarcas:borrarmarcas},function(data){
                console.log(data);
                obj = JSON.parse(data);
                if(obj.mensaje === 200){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok!",
                        text: "Se guardo correctamente!.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.close();
                        window.opener.location.reload();
                    });
                    return;
                }
                else if(obj.mensaje === 201){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    swal({
                        title: "Ok!",
                        text: "Se cambio el estado del cliente correctamente!.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.close();
                        window.opener.location.reload();
                    });
                    return;
                }
                else{
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