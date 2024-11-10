@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Listado de Asistencia</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="{{route('lista-asistencia')}}"></i>Listado de Asistencia</a></li>
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
                    <th>Id</th>
                    <th>Nombre </th>
                    <th>Dia</th>
                    <th>Check In</th>
                    <th style="text-align:center;">Check Out</th>
                    <th>Horas</th>
                    <th>Descripción</th>
                    <th>Acciones</th>

                    <!-- <th>Acciones</th> -->
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($asistencias as $asistencia)
                    <tr id="tr_detalle">
                        <td>{{ $asistencia->id }}</td>
                        <td> {{ $asistencia->name }} {{ $asistencia->lastname }}</td>
                        <td>{{  $asistencia->register_date  }}</td>
                        <td>{{  $asistencia->check_in  }}</td>
                        <?PHP
                            if( $asistencia->check_out){
                                $check= $asistencia->check_out;
                            }
                            else{
                                $check= "&mdash;";
                        } ?>
                        <td style="text-align:center;">{{  $check  }}</td>
                        <td>{{  $asistencia->hours  }}</td>
                        <td>{{  $asistencia->descripcion  }}</td>                      
                        <td>                 
                            <button type="button" class="btn btn-primary btn-xs"
                                    data-id    ="{{$asistencia->id}}"
                                    data-check_in    ="{{$asistencia->check_in}}"
                                    data-check_out   ="{{$asistencia->check_out}}"
                                    id="change" >
                                <i class="glyphicon glyphicon-pencil position-center"></i>
                            </button>   

                        </td>

                       
                        
                    </tr>
                @endforeach
                </tbody>
            </table>

    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              
                    <div class="modal-header bg-info">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Editar Asistencia: <span id="#form-email"></span></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="panel panel-flat">
                                <div class="panel-heading"></div>

                                <div class="panel-body">

                                    <fieldset>
                                        <legend class="text-semibold">Editar Asistencia</legend>
                                        
                                        <div class="form-group form-inline">
                                            <div class="input-group col-md-5" style="margin-right: 4vw">
                                                <label >Nuevo Check In:</label>
                                                <input  id="checkin" type="time"  class="form-control"> 
                                            </div>

                                            <div class="input-group col-md-5" >
                                                <label >Nuevo Check Out:</label>
                                                <input  id="checkout"  type="time" class="form-control"> 
                                            </div>

                                             <div class="input-group col-md-12" style="margin-top: 2em;">
                                                <label>Descripción del cambio: </label><label style="font-weight: bold;font-size:15px; margin-left: 1em"> (Obligatorio)*</label>
                                                <input  id="descripcion" class="form-control"> 
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

            id=  $(this).data('id');
            incheck=  $(this).data('check_in');
            outcheck= $(this).data('check_out');

            $('#checkin').val(incheck);
            $('#checkout').val(outcheck);
            $('#descripcion').val('');

            $('#formulario3').modal('show');
        });

    $('#formulario3').on('click','#guardar_cambio',function(){
            var checkin = $('#checkin').val();
            var checkout = $('#checkout').val();   
             var descripcion = $('#descripcion').val();




            if(descripcion.length <= 0 || descripcion.trim() == ""){
                $('#formulario3').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Es obligatorio ingresar una descripcion del cambio.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }


            if(checkin.replace(':','') > checkout.replace(':','')){
                $('#formulario3').modal('hide');
                swal({
                    title: "Upss!",
                    text: "La hora de check out debe ser mayor a la de check in.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }




            $('#guardar_cambio').prop( "disabled", true );       
            
            $.post(currentLocation+"editar_asistencia",{id: id, checkin: checkin, checkout: checkout, descripcion: descripcion},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                $('#formulario3').modal('hide');
                if(obj.mensaje === 200){
                    swal({
                            title: "Bien hecho!",
                            text: "Se actualizaron los Datos correctamente",
                            type: "success"

                            
                        },
                        function(){
                            $('#guardar_cambio').prop( "disabled", false );

                            location.reload();
                            return false;

                    });

                }else{
                    swal({
                        title: "Error..!",
                        text: "No se pudieron cambiar los datos, intentalo de nuevo luego.",
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
</script>

    
@stop