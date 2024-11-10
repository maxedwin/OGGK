@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="icon-pen6 position-left"></i><span class="text-semibold">Registro de Empleado</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/listado_usuarios"></i>Listado de Empleados</a></li>
    <li class="active"></i>Registro de Empleado</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')


@stop

<!-- CONTENIDO DE LA PAGINA -->

@section('contenido')

<style type="text/css">
        .hide-loader{
            display:none;
        }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h5>Registrar Empleado</h5></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('dni') ? ' has-error' : '' }} form-inline" >
                            <label class="col-md-4 control-label">DNI / RUC</label>

                            <div class="col-md-6">
                                <!--<input type="number" class="form-control" name="dni" value="{{ old('dni') }}">-->

                                <input type="number" class="form-control" name="dni" id="dni" value="{{ old('dni') }}" pattern="([0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])" autofocus>
                                <button type="submit" class="btn btn-success" name="btn-submit" id="btn-submit" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Procesando...">
                                    <i class="glyphicon glyphicon-search"></i> Verificar
                                </button>

                                @if ($errors->has('dni'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dni') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Apellido</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lastname" id="lastname" value="{{ old('lastname') }}">

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Telefono</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="telefono" value="{{ old('telefono') }}">

                                @if ($errors->has('telefono'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('telefono') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('direccion') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Direccion</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="direccion" value="{{ old('direccion') }}">

                                @if ($errors->has('direccion'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('direccion') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('f_nac') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Fecha Nacimiento</label>

                            <div class="col-md-6">
                                <input type="date" class="form-control" name="f_nac" value="{{ old('f_nac') }}">

                                @if ($errors->has('f_nac'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('f_nac') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('sexo') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Género</label>

                            <div class="col-md-6">
                                <!-- <input type="text" class="form-control" name="sexo" value="{{ old('sexo') }}"> -->
                                <select class="form-control" name="sexo" >
                                    <option>--</option>
                                    <option <?php if ( old('sexo')  == "M" ) echo 'selected' ; ?> value="M">Masculino</option>
                                    <option <?php if ( old('sexo')  == "F" ) echo 'selected' ; ?> value="F">Femenino</option>
                                </select>

                                @if ($errors->has('sexo'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sexo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('puesto') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Puesto</label>

                            <div class="col-md-6">
                                <!-- <input type="text" class="form-control" name="sexo" value="{{ old('sexo') }}"> -->
                                <select class="form-control" name="puesto" >
                                        <option value="0">--</option>
                                    @foreach ($puestos as $puesto)
                                        <option <?php if ( old('puesto')  == $puesto->idpuesto ) echo 'selected' ; ?> value="{{ $puesto->idpuesto}}">{{$puesto->nombre}}</option>
                                    @endforeach
                                </select>
            
                                @if ($errors->has('puesto'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('puesto') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('f_entrada') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Fecha Entrada</label>

                            <div class="col-md-6">
                                <input type="date" class="form-control" name="f_entrada" value="{{ old('f_entrada') }}">

                                @if ($errors->has('f_entrada'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('f_entrada') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Correo</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Contraseña</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirmar Contraseña</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-btn fa-user position-left"></i>Registrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>    


<script rel="script" type="text/javascript">

    $("#dni").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#btn-submit").click();
        }
    });
    
    var bool_click = false;

    $("#btn-submit").click(function(e){
        bool_click = true;
        var $this = $(this);
        
        $this.button('loading');
        
        e.preventDefault();
                
        $.ajax({
            data: { "nruc" : $("#dni").val() },
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

                        $("#dni").val(data['result']['ruc']);
                        $("#name").val(data['result']['razon_social']);
                        $("#lastname").val(data['result']['razon_social']);
                    
                    }
                        $("#error").hide();
                        $(".result").show();
                    }
                    else if ( typeof(data['message'])!='undefined' && data['err_num']==501 )
                    {
                        if ($("#dni").val().length == 8) {
                            $.ajax({
                                data: {
                                    "nruc": $("#dni").val(),
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

                                        $("#dni").val(data['result']['dni']);
                                        $("#name").val(data['result']['nombres']);
                                        $("#lastname").val(data['result']['apellidoPaterno']+' '+data['result']['apellidoMaterno']);
                                        /*$("#dni").val(data['result']['dni']);
                                        document.getElementById('dni').disabled = true;
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
            });        
    });

</script>

@stop
