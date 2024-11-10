@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Cotizaciones</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class=""><a href="/listado_cotizacion"></i>Listado de Cotizaciones</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="cotizacion" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Cotización
        </a>

    </li>
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
                    <th>Correlativo</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Vendedor</th> 
                    <th>Estado</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($cotizaciones as $cotizacion)
                    <tr id="tr_detalle">
                                                
                        <td>{{ str_pad($cotizacion->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ date('d/m/Y', strtotime($cotizacion->created_at)) }}</td>
                            
                        <td>{{ $cotizacion->razon_social}}</td>

                        <td>{{ number_format((float)$cotizacion->total, 2, '.', '') }}</td>

                        <td> {{ $cotizacion->name }} {{ $cotizacion->lastname }}</td>

                        <?PHP  if($cotizacion->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-idcotizacionh="'.$cotizacion->idcotizacionh.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($cotizacion->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-success" data-idcotizacionh="'.$cotizacion->idcotizacionh.'" data-status="2" > Facturada </button></td>';
                        }      else{
                            echo '<td><button id="status" class="btn btn-secondary" data-idcotizacionh="'.$cotizacion->idcotizacionh.'" data-status="0" > Anulada </button></td>';
                        }  ?>

                        <td>{{ $cotizacion->ruc_dni}} - {{ $cotizacion->contacto_nombre}} - {{$cotizacion->contacto_telefono}}</td>

                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    id="imprimir" data-id="{{$cotizacion->idcotizacionh}}">
                                <i class="glyphicon glyphicon-print position-center"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-toggle = "modal"
                                    id="observacion" data-id="{{$cotizacion->idcotizacionh}}"
                                    data-numeracion = "{{$cotizacion->numeracion }} "
                                    data-observacion = "{{$cotizacion->comentarios }} ">
                                <i class="icon-comments position-center"></i>
                            </button>
                            <?php /* AFTDB */ if($cotizacion->estado_doc == 0) { ?>
                            <button type="button" class="btn btn-danger btn-xs"
                                    data-id_orden_ventah   = "{{$cotizacion->idcotizacionh }} "
                                    data-numeracion      = "{{$cotizacion->numeracion }} "
                                    data-toggle          = "modal"
                                    id="anular"> 
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                            <?php /* AFTDB */ } ?>
                        </td> 

                    </tr>
                @endforeach
                </tbody>
            </table>


    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Cotización</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Cotización</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion" class="form-control" disabled> 
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios">Anular</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>

    <div id="formularioObs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Modificar Observación</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Cotización</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion_obs" class="form-control" disabled> 
                                    </div>
                                    <div class="input-group">
                                        <label>Observación:</label>
                                        <textarea class="form-control" id="observacion_obs"></textarea>
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios_obs">Guardar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>


<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":1, "type":"date-eu"},{"targets":6, "visible":false}],
    } );

    // $('#cliente_table').on('click','#status', function(event){
    //     event.preventDefault();
    //     idcotizacionh=  $(this).data('idcotizacionh');
    //     status= $(this).data('status');
    //     var jqxhr =  $.get(currentLocation+"ordenventa_state?id="+idcotizacionh+"&status="+status,function(status){
    //     }).done(function() {
    //         swal({
    //             title: "Cambio el estado!",
    //             text: "La Orden de Venta ha cambiado su estado.",
    //             confirmButtonColor: "#66BB6A",
    //             type: "success"
    //         },function(){
    //             window.location.reload();
    //         });
    //     }).fail(function() {
    //         swal("Error no se ha cambiado el estado de la orden de venta", "Intentelo nuevamente luego.", "error");
    //     })}
    // );  

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_cotizacion?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });


    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('CT' + ' - ' + numeracion);

            $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_orden_ventah);

            $.post(currentLocation+"ct_estado",{id_orden_ventah:id_orden_ventah},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se anuló correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
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

    });

    $('#cliente_table').on('click','#observacion',function(){

            id_orden_ventah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            $('#numeracion_obs').val('CT' + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(id_orden_ventah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"ct_edit_comments",{id_reg:id_orden_ventah, comments:comments},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formularioObs').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se actualizó las observaciones",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede actualizar las observaciones, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios_obs').prop( "disabled", false );
                    });
                    return;
                }   
            });

    });

</script>

    
@stop