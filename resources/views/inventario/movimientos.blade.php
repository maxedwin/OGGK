@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-usd position-left"></i> <span class="text-semibold">Movimientos de Stock</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active">Movimientos</li>
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
                    <th>Fecha</th>   
                    <th>Usuario</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Entrada/Salida</th>
                    <th>Tipo</th>    
                    <th>Vendedor</th> 
                    <th>Razon</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($movs as $mov)
                    <tr id="tr_detalle">
                                                
                        <td>{{ date('d/m/Y', strtotime($mov->movi_fecha)) }}</td>                            
                        <td>{{ $mov->creador}}</td>
                        <td>{{ $mov->nombre}}</td>
                        <td>{{ $mov->cantidad }}</td>

                        <?PHP  if($mov->movi == 0) {
                            echo '<td>  Salida </td>';
                        }      elseif($mov->movi == 1) {
                            echo '<td> Entrada </td>';
                        }      else{
                            echo '<td> Otros </td>';
                        }  ?>
                        
                        <?PHP  if($mov->tipo_movimiento == 1) {
                            echo '<td>  Movimiento </td>';
                        }      elseif($mov->tipo_movimiento == 2) {
                            echo '<td> Muestra </td>';
                        }      elseif($mov->tipo_movimiento == 3) {
                            echo '<td> Regalo </td>';
                        }      elseif($mov->tipo_movimiento == 4) {
                            echo '<td> Prestamo </td>';
                        }      elseif($mov->tipo_movimiento == 5) {
                            echo '<td> Cambio por fallo </td>';
                        }      else{
                            echo '<td> Otros </td>';
                        }  ?>
                        
                        <td> {{ $mov->quien }} </td>
                        <td> {{ $mov->razon }} </td>

                    </tr>
                @endforeach
                </tbody>
            </table>

 
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":0, "type":"date-eu"}],
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


</script>

    
@stop