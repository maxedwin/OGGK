@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Reclamos</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_reclamos"></i>Listado de Reclamos</a></li>
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
                    <th>Correlativo</th>
                    <th>Cliente</th>
                    <th>Fecha de Emisión</th>
                    <th>Reclamo</th>
                    <th>Estado</th>
                    <th>Creador</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th> 
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($reclamos as $reclamo)
                    <tr id="tr_detalle">
                                                
                        <td>{{ $reclamo->idreclamo }}</td>
                        <td> {{ $reclamo->razon_social }} </td>
                        <td>{{ date('d/m/Y', strtotime($reclamo->fecha)) }}</td>
                            
                        <td> {{ $reclamo->reclamo }} </td>

                        <?PHP  if($reclamo->estado == 0) {
                            echo '<td><button id="status" class="btn btn-danger" >  Pendiente  </button></td>';
                        }      elseif($reclamo->estado == 1) {
                            echo '<td><button id="status" class="btn btn-primary" > En Proceso  </button></td>';
                        }      elseif($reclamo->estado == 2) {
                            echo '<td><button id="status" class="btn btn-success" > Solucionado  </button></td>';
                        }      else {
                            echo '<td><button id="status" class="btn btn-danger"  > Pendiente </button></td>';
                        }?>

                        <td> {{ $reclamo->name }} {{ $reclamo->lastname }} </td>
                        <td>{{ $reclamo->ruc_dni}} - {{ $reclamo->contacto_nombre}} - {{$reclamo->contacto_telefono}}</td>

                        <td id="td_actions">
                            <button type="button" class="btn btn-primary"
                                    data-idreclamo   = "{{ $reclamo->idreclamo }}"
                                    data-toggle      = "modal"
                                    id="en_proceso"> 
                                <i class="glyphicon glyphicon-random position-center"></i>
                            </button>                            
                            <button type="button" class="btn btn-success"
                                    data-idreclamo   = " {{$reclamo->idreclamo }}"
                                    data-toggle      = "modal"
                                    id="solucionado"> 
                                <i class="glyphicon glyphicon-ok position-center"></i>
                            </button>
                        </td> 

                    </tr>
                @endforeach
                </tbody>
            </table>


<script type="text/javascript" src="{{ asset('javascript/products.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":2, "type":"date-eu"},{"targets":6, "visible":false}],
    } );

    $('#cliente_table').on('click','#tr_detalle #td_actions #en_proceso',function(events){
        var idreclamo = $(this).data('idreclamo');

        swal({
            title: "Estas seguro de cambiar el estado?",
            text: "Se cambiará su estado a EN PROCESO!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, cambiar!",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        
        function(isConfirm){
            if (isConfirm) {
                var jqxhr =  $.get(currentLocation+"cambiar_enproceso?idreclamo="+idreclamo, function(data,status){})
                .done(function() {
                    swal({
                        title: "Estado cambiado!",
                        text: "El reclamo está en proceso.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    }
                    ,function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al cambiar el estado", "Intentelo nuevamente luego.", "error");
                });
            }
            else {
                swal({
                    title: "Cancelado",
                    text: "No se ha cambiado nada.",
                    confirmButtonColor: "#2196F3",
                            type: "error"
                });
            }
        });
    });

    $('#cliente_table').on('click','#tr_detalle #td_actions #solucionado',function(events){
        var idreclamo = $(this).data('idreclamo');

        swal({
            title: "Estas seguro de cambiar el estado?",
            text: "Se cambiará su estado a SOLUCIONADO!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, cambiar!",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        
        function(isConfirm){
            if (isConfirm) {
                var jqxhr =  $.get(currentLocation+"cambiar_solucionado?idreclamo="+idreclamo, function(data,status){})
                .done(function() {
                    swal({
                        title: "Estado cambiado!",
                        text: "El reclamo está solucionado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    }
                    ,function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al cambiar el estado", "Intentelo nuevamente luego.", "error");
                });
            }
            else {
                swal({
                    title: "Cancelado",
                    text: "No se ha cambiado nada.",
                    confirmButtonColor: "#2196F3",
                            type: "error"
                });
            }
        });
    });
</script>

    
@stop