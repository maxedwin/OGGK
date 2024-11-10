@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-eye-open "></i> <span class="text-semibold">Editar Proveedor</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/list_proveedores"></i>Listado de Proveedores</a></li>
    <li class="active">Editar Proveedor</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <div class="from-group pull-right" id="submit-control">
            <button class="btn btn-success btn-lg" id="btn_guardar" data-idprod="9">
                <i class="glyphicon glyphicon-save "></i>
                Editar
            </button>
        </div>

    </li>
@stop

<!-- CONTENIDO DE LA PAGINA -->
@section('contenido')
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
                 
                        <div class="from-group" id="dni_group">
                            <label for="ruc_dni">RUC/DNI:</label>
                            <input type="text" class="form-control" id="ruc_dni" value="{{ $proveedor->ruc_dni }}" disabled>
                            <input type="hidden" class="form-control" id="idproveedor" value="{{ $proveedor->idproveedor }}">
                        </div>

                        <div class="from-group" id="rs_group">
                            <label for="razon_social">Razon Social:</label>
                            <input type="text" class="form-control" id="razon_social" value="{{ $proveedor->razon_social }}" disabled>
                        </div>

                        <div class="from-group" id="direccion_group">
                            <label for="direccion">Direccion:</label>
                            <input type="text" class="form-control" id="direccion" value="{{ $proveedor->direccion }}">
                        </div>

                        <div class="row">
                        <div class="col-md-4">
                            <div class="from-group" id="dist_group">
                                <label for="distrito">Distrito:</label>
                                <select class="form-control" id="distrito">
                                    <option value="{{ $proveedor->distrito }}">{{ $proveedor->distrito }}</option>
                                    @foreach ($distritos as $distrito)    
                                        <option value="{{ $distrito->distrito_name}}">{{$distrito->distrito_name}}</option>
                                    @endforeach                            
                                </select>
                                <!-- <input type="text" class="form-control" id="distrito" value="{{ $proveedor->distrito }}"> -->
                            </div>

                            <div class="from-group" id="dist_group">
                                <label for="provincia">Provincia:</label>
                                <select class="form-control" id="provincia">
                                    <option value="{{ $proveedor->provincia }}">{{ $proveedor->provincia }}</option>
                                    @foreach ($provincias as $provincia)    
                                        <option value="{{ $provincia->provincia_name}}">{{$provincia->provincia_name}}</option>
                                    @endforeach                            
                                </select>
                                <!-- <input type="text" class="form-control" id="distrito" value="{{ $proveedor->distrito }}"> -->
                            </div>
                        
                            <div class="from-group" id="dist_group">
                                <label for="departamento">Departamento:</label>
                                <select class="form-control" id="departamento">
                                    <option value="{{ $proveedor->departamento }}">{{ $proveedor->departamento }}</option>
                                    @foreach ($departamentos as $departamento)    
                                        <option value="{{ $departamento->departamento_name}}">{{$departamento->departamento_name}}</option>
                                    @endforeach                            
                                </select>
                                <!-- <input type="text" class="form-control" id="distrito" value="{{ $proveedor->distrito }}"> -->
                            </div>
                        </div>
                        </div>

                        <div class="from-group" id="contnomb_group">
                            <label for="contacto_nombre">Nombre de Contacto:</label>
                            <input type="text" class="form-control" id="contacto_nombre" value="{{ $proveedor->contacto_nombre }}">
                        </div>

                        <div class="from-group" id="contelf_group">
                            <label for="contacto_telefono">Teléfono de Contacto:</label>
                            <input type="text" class="form-control" id="contacto_telefono" value="{{ $proveedor->contacto_telefono }}">
                        </div>

                        <div class="from-group" id="contemail_group">
                            <label for="contacto_email">Correo de Contacto:</label>
                            <input type="text" class="form-control" id="contacto_email" value="{{ $proveedor->contacto_email }}">
                        </div>
                        
                        <div class="from-group" id="diascred_group">
                            <label for="dias_credito">Días de Crédito:</label>
                            <input type="text" class="form-control" id="dias_credito" value="{{ $proveedor->dias_credito }}">
                        </div>

                        <div class="from-group" id="diascred_group">
                                <label for="tipo_pago">Tipo de Pago:</label>
                                <select class="form-control" id="tipo_pago" >
                                        <option value="99">Falta Actualizar</option>         
                                        <option <?php if ($proveedor->tipo_pago == 0 ) echo 'selected' ; ?> value="0">Contado</option>
                                        <option <?php if ($proveedor->tipo_pago == 1 ) echo 'selected' ; ?> value="1">Transferencia</option>
                                        <option <?php if ($proveedor->tipo_pago == 2 ) echo 'selected' ; ?> value="2">Cheque</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="tipo_pago"> -->
                        </div>

                        <div class="from-group" id="diascred_group">
                                <label for="moneda">Moneda:</label>
                                <select class="form-control" id="moneda">
                                        <option value="0">--</option>
                                        <option <?php if ($proveedor->moneda == 1 ) echo 'selected' ; ?> value="1">Soles</option>
                                        <option <?php if ($proveedor->moneda == 2 ) echo 'selected' ; ?> value="2">Dolares</option>
                                        <option <?php if ($proveedor->moneda == 3 ) echo 'selected' ; ?> value="3">Euros</option>
                                </select>
                                <!-- <input type="text" class="form-control" id="moneda"> -->
                            </div>

                        <div class="from-group" id="tipemp_group">
                            <label for="tipo_emp">Tipo de Empresa:</label>
                                <select class="form-control" id="tipo_emp">
                                    @foreach ($tipos_emp as $tipo_emp)
                                        <option value="{{ $proveedor->tipo_emp }}"> {{ $tipo_emp->tipoemp_nombre }} </option>
                                    @endforeach
                                        <option value="0"> -- </option>
                                    @foreach ($tipos_emp2 as $tipo_emp2)
                                        <option value="{{ $tipo_emp2->id_tipoemp }}">{{$tipo_emp2->tipoemp_nombre}}</option>
                                    @endforeach
                                </select>
                        </div>




                </div>
            </div>
        </div>
    </div>

</div>

    <script rel="script" type="text/javascript">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        $('#distrito').select2();
        $('#provincia').select2();
        $('#departamento').select2();

        $('#btn_guardar').click(function(event){
            $('#btn_guardar').prop( "disabled", true );
            var idproveedor = $('#idproveedor').val();
            var ruc_dni = $('#ruc_dni').val();
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
                    text: "Debes agregar el RUC/DNI del proveedor",
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
                    text: "Debes agregar la Razon Social del proveedor",
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
                    text: "Debes agregar la Direccion del proveedor",
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
                    text: "Debes agregar el Nombre del Contacto del proveedor",
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
                    text: "Debes agregar el Telefono del Contacto del proveedor",
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
                    text: "Debes agregar el Correo del Contacto del proveedor",
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
                    text: "Debes agregar los Días de Crédito del proveedor",
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
                    text: "Debes elegir el Tipo de Empresa del proveedor",
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
            $.post(currentLocation+'store_update_prov',{idproveedor:idproveedor, ruc_dni:ruc_dni, razon_social:razon_social, direccion:direccion, distrito:distrito, provincia:provincia,departamento:departamento, contacto_nombre:contacto_nombre, contacto_telefono:contacto_telefono, contacto_email:contacto_email, tipo_pago:tipo_pago, dias_credito:dias_credito, moneda:moneda, tipo_emp:tipo_emp},function(data){
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
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar el proveedor, intentalo de nuevo luego.",
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