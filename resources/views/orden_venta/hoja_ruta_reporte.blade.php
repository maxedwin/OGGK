@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Reporte Hoja de Ruta</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class=""><a href=""></i>Reporte Hoja de Ruta</a></li>
@stop
<!-- MENU AUXLIAR -->


<!-- CONTENIDO DE LA PAGINA -->
@section('contenido')

<style>
    /* Removes the clear button from date inputs */
input[type="date"]::-webkit-clear-button {
    display: none;
}

/* Removes the spin button */
input[type="date"]::-webkit-inner-spin-button { 
    display: none;
}

/* Always display the drop down caret */
input[type="date"]::-webkit-calendar-picker-indicator {
    color: black;
}

/* A few custom styles for date inputs */
input[type="date"] {
    appearance: none;
    -webkit-appearance: none;
    color: black;
    font-family: "Helvetica", arial, sans-serif;
    font-size: 18px;
    border:1px solid #ecf0f1;
    background:#ecf0f1;
    padding:5px;
    display: inline-block !important;
    visibility: visible !important;
}

input[type="date"], focus {
    color: black;
    box-shadow: none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
}
</style>
    
    <div style="text-align:center">
        <label for="f_cobro"><h4 ><span class="text-semibold">Día:</span></h4></label>
        <input type="date" class="form-control" id="fecha" style="max-width:20%" value="{{$day}}">
    </div>


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
            $(".InputCodigoNB").on('keyup', function (e) {
                console.log('asd');
                if (e.key === 'Enter' || e.keyCode === 13) {
                    handleUpdateValue( this, $(this).data('id'));
                }
            });

            $('#cliente_table tbody').on( 'keyup', 'input', function (e) {
                console.log('asd');
                if (e.key === 'Enter' || e.keyCode === 13) {
                    handleUpdateValue( this, $(this).data('id'));
                }
                //document.getElementById(this.dataset.id).focus()
                // note - call draw() to update the table's draw state with the new data
            } );
        });
    </script>   

            <?php $status_ent_gr = Helper::status_ent_gr(); ?> 
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <th>Factura</th>                    
                    <th>Guía de remisión</th>  
                    <th>Empresa</th>                  
                    <th>Nota de Pedido</th>
                    <th>Telefono</th>
                    <th>Dirección</th>
                    <th>Vendedor</th>                     
                    <th>Repartidor</th>
                    <th>Monto Factura</th>
                    <th>Reprogramada</th> 
                    <th>Entrega</th>   

                    
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($guia_remisions as $guia_remision)
                    <tr id="tr_detalle">       
                        
                        <td>{{$guia_remision->facnum}}</td>
                        
                        <td>
                            {{$guia_remision->codigoNB}}
                        </td>   
                        
                        <td>{{ $guia_remision->razon_social}}</td>
                        
                        <td>{{ $guia_remision->np}}</td>  
                        
                        <td>{{ $guia_remision->contacto_telefono}}</td> 
                        
                        <td>{{ $guia_remision->dir}}</td>
                        
                        <td>{{ $guia_remision->ven_name}} {{ $guia_remision->ven_lastname}}</td>       
                        
                        <td> {{ $guia_remision->name }} {{ $guia_remision->lastname }}</td>

                        <td>{{ $guia_remision->monto}}</td>
                        
                        <td>                            
                            @if ($guia_remision->f_reprogramar != NULL) 
                                Sí
                            @else 
                                No
                            @endif                            
                        </td>
                        
                        
                        <td class="td_status">
                            <?PHP  
                            if( $guia_remision->codeG > 0 && $guia_remision->codeG < 4000 && $guia_remision->estado_doc==3 ){
                                echo '<button class="btn" style="background:#000;color:#fff">RECHAZADO</button>';
                            }
                            else{
                                if($guia_remision->status_ent == -1) {
                                    if($guia_remision->estado_doc == 0) {
                                        echo '<button id="status" class="btn btn-danger" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="1" >  Pendiente </button>';
                                    }      elseif($guia_remision->estado_doc == 1) {
                                        echo '<button id="status" class="btn btn-primary" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="2" > Facturada </button>';
                                    }      elseif($guia_remision->estado_doc == 2) {
                                        echo '<button id="status" class="btn btn-success" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="3" > Entregada </button>';
                                    }      elseif($guia_remision->estado_doc == 4) {
                                        echo '<button id="status" class="btn btn-info" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="3" > Reprogramada </button>';
                                    }      else{
                                        echo '<button id="status" class="btn btn-secondary" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="0" > Anulada </button>';
                                    }  
                                } else {
                                    echo $status_ent_gr[$guia_remision->status_ent];
                                    if ( $guia_remision->is_ncp ) {
                                        echo ' <i title="NCP" class="icon-exclamation position-center"></i>';
                                    }
                                }
                            }
                            ?>
                        </td>

                    

                        <!--<td id="td_actions">
                        @if(!($guia_remision->codeG > 0 && $guia_remision->codeG < 4000 && $guia_remision->estado_doc==3))
                            @if(($guia_remision->codeG ==0||$guia_remision->codeG >=4000 )  && (is_null($guia_remision->cdr_file)  || $guia_remision->cdr_file=='') && $guia_remision->correlativoG>0 )
                            <button type="button" class="btn btn-success btn-xs"
                                        id="actualizar" data-id="{{$guia_remision->id_guia_remisionh}}">
                                    <i class="glyphicon glyphicon-refresh position-center"></i>
                                </button>
                            @endif
                            <button type="button" class="btn btn-info btn-xs"
                                    id="imprimir" data-id="{{$guia_remision->id_guia_remisionh}}"
                                    data-archivo="{{$guia_remision->pdf_file}}">
                                <i class="glyphicon glyphicon-print position-center"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-toggle = "modal"
                                    id="observacion" data-id="{{$guia_remision->id_guia_remisionh}}"
                                    data-numeracion = "{{$guia_remision->numeracion }} "
                                    data-observacion = "{{$guia_remision->comentarios }} ">
                                <i class="icon-comments position-center"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-id_guia_remisionh   = " {{ $guia_remision->id_guia_remisionh  }} "                                    
                                    data-correlativo         = " {{ $guia_remision->numeracion        }} "
                                    data-codigo              = " {{ $guia_remision->codigoNB          }} "
                                    data-toggle              = "modal"
                                    id="history"> 
                                <i class="glyphicon glyphicon-time position-center"></i>
                            </button>
                            <?php /* AFTDB */ if($guia_remision->estado_doc == 0) { ?>
                            <button type="button" class="btn btn-danger btn-xs"
                                    data-id_orden_ventah   = " {{ $guia_remision->id_guia_remisionh }} "
                                    data-numeracion        = " {{ $guia_remision->numeracion }} "
                                    data-np                = " {{ $guia_remision->codigoNB }} "                                    
                                    data-toggle            = "modal"
                                    id="anular"> 
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                            <?php /* AFTDB */ } ?>
                            @endif
                        </td>-->
                    </tr>
                @endforeach
                </tbody>
            </table>


    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Guía de Remisión</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Remisión</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="np" class="form-control" disabled> 
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
                                <legend class="text-semibold">Información de la Guía de Remision</legend>
                                
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

    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Historial de Entrega Guía de Remisión</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Remisión</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group" style="width: 200px;">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="correlativo3" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group" style="width: 200px;">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="codigoNB3" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group gr-history">
                                    <table  class="table table-borderless">
                                        <thead>
                                            <tr class="bg-danger-700">
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gr_history">
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
    
    </div>
    </div>
    </div>


<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    $('#fecha').on('change',function($event){
        console.log($(this).val());
        var fecha=$(this).val();
        window.location.href= currentLocation +"hoja_ruta_reporte?day="+fecha;
    });


    function handleUpdateValue(element) {
        console.log(element.dataset.prev);
        $.post(currentLocation+"update_codigonb",{id_guia_remisionh:element.id, value:element.value},function(data){   
            location.reload();
            });

    }

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":3, "type":"date-eu"},{"targets":5, "type":"date-eu"}],
        "order": [[ 3, "desc" ], [0, "desc"]],
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            'excel'
        ]
     } );

    // $('#cliente_table').on('click','#status', function(event){
    //     event.preventDefault();
    //     id_guia_remisionh=  $(this).data('id_guia_remisionh');
    //     status= $(this).data('status');
    //     var jqxhr =  $.get(currentLocation+"guiaremision_state?id="+id_guia_remisionh+"&status="+status,function(status){
    //     }).done(function() {
    //         swal({
    //             title: "Cambio el estado!",
    //             text: "La Guia ha cambiado su estado.",
    //             confirmButtonColor: "#66BB6A",
    //             type: "success"
    //         },function(){
    //             window.location.reload();
    //         });
    //     }).fail(function() {
    //         swal("Error no se ha cambiado el estado de la guia", "Intentelo nuevamente luego.", "error");
    //     })}
    // );  

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        var archivo = $(this).data('archivo');
        var direccion="info_guia_remision?id="+ id;
        if(archivo != null && archivo !=''){
            direccion= "greenter/"+archivo;
        }
        console.log(currentLocation+direccion);
        window.open(currentLocation+direccion,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    $('#cliente_table').on('click','#tr_detalle #td_actions #actualizar',function(events){
        var id = $(this).data('id');
        $.post(currentLocation+"checkCDRGR",{id:id},function(data){
            obj = JSON.parse(data);
                console.log(obj);
            if(obj.created === 200){
                $(this).prop( "disabled", true );
                swal({
                        title: "Buenas noticias!",
                        text: obj.msg,
                        type: "success"
                    },
                    function(){
                        window.location.reload()
                });
            }else if(obj.created === 500){
                $(this).prop( "disabled", true );
                if(obj.msg=="Error no se conectó: object(Greenter\\Model\\Response\\Error)#"+/[0-9][0-9][0-9]/+" (2) {\n  [\"code\":protected]=>\n  string(5) \"00109\"\n  [\"message\":protected]=>\n  string(1) \"-\"\n}\n"){
                swal({
                        title:"Woops!",
                        text: "Este Servicio de la Sunat está temporalmente fuera de servicio, intentalo más tarde",
                        type: "error"
                    },
                    function(){
                        window.location.reload()
                });
                }
                else{
                swal({
                        title: "Error! Comprobante "+ obj.comprobante+':',
                        text: obj.msg,
                        type: "error"
                    },
                    function(){
                        window.location.reload()
                });
                }
            }else if(obj.created === 501){
                $(this).prop( "disabled", true );
                swal({
                        title: "Woops!",
                        text: obj.msg,
                        type: "error"
                    },
                    function(){
                    $(this).prop( "disabled", false );
                });
            }
        });
    });

    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');
            np = $(this).data('np');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('GR' + ' - ' + numeracion);
            $('#np').val(np);

            $('#formulario').modal('show');
    });

    $('#cliente_table').on('click','#history',function(){

            id_orden_ventah=  $(this).data('id_guia_remisionh');
            correlativo = $(this).data('correlativo');
            codigoNB = $(this).data('codigo');

            $('#id_guia_remisionh3').val(id_orden_ventah);
            $('#correlativo3').val('GR'+' - ' + correlativo);
            $('#codigoNB3').val(codigoNB);

            $.post(currentLocation+"gr_history",{id_guia_remisionh:id_orden_ventah},function(data){
                obj = JSON.parse(data);
                console.log(obj);

                $('#gr_history').html('');

                $.each(obj, function(index, value) {
                    var string = '<tr id="tr_item">';
                    string += '<td id="id_producto">'+value.barcode+' '+value.nombre+'</td>';
                    string += '<td id="cantidad_producto">'+value.cantidad+'</td>';
                    string += '<td id="fecha_entregado">'+value.f_emision+'</td>';
                    string += '</tr>';
                    $('#gr_history').append(string);
                });

                $('#formulario3').modal('show');
            });

        });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_orden_ventah);

            $.post(currentLocation+"gr_estado_anulado",{id_orden_ventah:id_orden_ventah},function(data){
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

    });

    $('#cliente_table').on('click','#observacion',function(){

            id_orden_ventah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            $('#numeracion_obs').val('GR' + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(id_orden_ventah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"gr_edit_comments",{id_reg:id_orden_ventah, comments:comments},function(data){
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