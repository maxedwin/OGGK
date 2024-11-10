@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Listado de Empleados</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_usuarios"></i>Listado de Empleados</a></li>
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

    <script>
        $(document).ready(function(){
            $("#input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#cliente_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>   


            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <th>Puesto</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>

                    <!-- <th>Acciones</th> -->
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($usuarios as $usuario)
                    <tr id="tr_detalle">
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->name }} {{ $usuario->lastname }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->dni }}</td>
                        <td>{{ $usuario->telefono }}</td>
                        <?PHP  if($usuario->activated == 0) {
                            echo '<td><span class="label label-default">Inactivo</span></td>';
                        }      else {
                            echo '<td><span class="label label-primary">Activo</span></td>';
                        }?>
                        <td>
                        <?PHP  if($usuario->activated == 0) {
                            echo '<button type="button" class="btn btn-default btn-lg" id="habilitar" data-idusuario="'.$usuario->id.'" data-idnombre="'.$usuario->name.'">
                                      <span class="glyphicon glyphicon-plus-sign " aria-hidden="true"></span> Reactivar
                                    </button> ';
                        }      else {
                            echo '<button type="button" class="btn btn-default" id="inhabilitar" data-idusuario="'.$usuario->id.'" data-idnombre="'.$usuario->name.'">
                                      <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Inhabilitar
                                    </button> ';
                        }?>
                            <button type="button" class="btn btn-primary btn-xs"
                                    data-iduser    ="{{$usuario->id}}"
                                    data-email    ="{{$usuario->email}}"
                                    id="change" >
                                <i class="glyphicon glyphicon-lock position-center"></i>
                            </button>   

                        </td>

                        <!-- <td>{{ dump($usuario) }}</td> -->
                        
                    </tr>
                @endforeach
                </tbody>
            </table>

    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Cambiar Contraseña: <span id="#form-email"></span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="panel panel-flat">
                                <div class="panel-heading"></div>

                                <div class="panel-body">

                                    <fieldset>
                                        <legend class="text-semibold">Cambiar Contraseña</legend>
                                        
                                        <div class="form-group form-inline">
                                            <div class="input-group">
                                                <label>Nueva Contraseña</label>
                                                <input type="password" id="new_pass" class="form-control"> 
                                            </div>

                                            <div class="input-group">
                                                <label>Confirmar Contraseña</label>
                                                <input type="password" id="confirm_pass" class="form-control"> 
                                            </div>
                                        </div>
                                    </fieldset>
                              
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer" id="submit-control">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info" id="guardar_cambio">Guardar Cambios</button>
                    </div>
            
            </div>
        </div>
    </div>



<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
    var iduser;

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "ordering": false
    } );

    $('#cliente_table').on('click','#change',function(){

            iduser=  $(this).data('iduser');
            email = $(this).data('email');

            $('#new_pass').val('');
            $('#confirm_pass').val('');

            $('#form-email').html(email);
            $('#formulario3').modal('show');
        });

    $('#formulario3').on('click','#guardar_cambio',function(){
            var new_pass = $('#new_pass').val();
            var confirm_pass = $('#confirm_pass').val();

            if(new_pass.length < 6){
                $('#formulario3').modal('hide');
                swal({
                    title: "Upss!",
                    text: "La Contraseña debe tener al menos 6 caracteres",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(new_pass != confirm_pass){
                $('#formulario3').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Las Contraseñas no son iguales",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            $('#guardar_cambio').prop( "disabled", true );       
            
            $.post(currentLocation+"cambiar_pass",{idusuario: iduser, new_pass: new_pass},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                $('#formulario3').modal('hide');
                if(obj.mensaje === 200){
                    swal({
                            title: "Bien hecho!",
                            text: "Se actualizo la Contraseña correctamente",
                            type: "success"
                        },
                        function(){
                            $('#guardar_cambio').prop( "disabled", false );
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede cambiar la Contraseña, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambio').prop( "disabled", false );
                    });
                    return;
                }   
            }).fail(function(xhr, status, error) {
                $('#formulario3').modal('hide');
                swal({
                    title: "Error..!",
                    text: "No se puede realizar la acción.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambio').prop( "disabled", false );
                });
                return;
            });

        });

    $('#cliente_table').on('click','#inhabilitar',function(event){
            event.preventDefault();
            let idnombre = $(this).data('idnombre');
            if( confirm( idnombre + ' ya no tendrá acceso al sistema, pero sus datos y documentos seguirán en nuestra base de datos.') ){
                let idusuario = $(this).data('idusuario');
                console.log(idusuario);

                $.post(currentLocation+"disable_user",{idusuario:idusuario},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se inhabilitó correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else if(obj.mensaje === 500){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Cuidado!",
                            text: "Ya está anulada!",
                            type: "error"
                        },
                        function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                    return;
                }   
            });

            }
    });

    $('#cliente_table').on('click','#habilitar',function(event){
            event.preventDefault();
            let idnombre = $(this).data('idnombre');
            if( confirm( idnombre + ' tendrá acceso al sistema') ){
                let idusuario = $(this).data('idusuario');
                console.log(idusuario);

                $.post(currentLocation+"enable_user",{idusuario:idusuario},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se habilitó correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else if(obj.mensaje === 500){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Cuidado!",
                            text: "Ya está anulada!",
                            type: "error"
                        },
                        function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                    return;
                }   
            });

            }
    });


</script>

    
@stop