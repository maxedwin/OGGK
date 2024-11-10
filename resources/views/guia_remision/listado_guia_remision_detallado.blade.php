@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-usd position-left"></i> <span class="text-semibold">Listado de Guías de Remisión</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_caja"></i>Guías de Remisión</a></li>
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

            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>                                        
                    <th>Fecha de Emision</th>
                    <th>Nº NubeFact</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Vendedor</th> 
                    <th>Estado</th> 
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($cajas as $caja)
                    <tr id="tr_detalle">
                        <td>{{ date('d/m/Y', strtotime($caja->created_at)) }}</td>
                        <td>{{ $caja->codigoNB}}</td>                        
                            
                        <td>{{ $caja->razon_social}}</td>

                        <td>{{ $caja->nombre }}</td>
                        <td>{{ $caja->cantidad }}</td>

                        <td> {{ $caja->name }} {{ $caja->lastname }}</td>
                        <?PHP  if($caja->estado_doc == 0) {
                            echo '<td> Pendiente </td>';
                        }      elseif($caja->estado_doc == 1) {
                            echo '<td> Facturada </td>';
                        }      elseif($caja->estado_doc == 2) {
                            echo '<td> Entregada </td>';
                        }else{
                            echo '<td> Anulada </td>';
                        }  ?>
                        
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
        "paging": false,
        "searching": true,
        "columnDefs" : [{"targets":0, "type":"date-eu"}],
        dom: 'lBfrtip',
        buttons: [
            'excel'
        ]
     } );

    // $('#cliente_table').on('click','#status', function(event){
    //     event.preventDefault();
    //     idcajah=  $(this).data('idcajah');
    //     status= $(this).data('status');
    //     var jqxhr =  $.get(currentLocation+"caja_state?id="+idcajah+"&status="+status,function(status){
    //     }).done(function() {
    //         swal({
    //             title: "Cambio el estado!",
    //             text: "La Factura/Boleta ha cambiado su estado.",
    //             confirmButtonColor: "#66BB6A",
    //             type: "success"
    //         },function(){
    //             window.location.reload();
    //         });
    //     }).fail(function() {
    //         swal("Error no se ha cambiado el estado de la Factura/Boleta", "Intentelo nuevamente luego.", "error");
    //     })}
    // );  

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_caja?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    var idcajah;
    $('#cliente_table').on('click','#anular',function(){

            idcajah=  $(this).data('idcajah');
            numeracion = $(this).data('numeracion');
            np = $(this).data('np');

            $('#idcajah').val(idcajah);
            $('#numeracion').val(numeracion);
            $('#np').val(np);

            $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            console.log(idcajah);

            $.post(currentLocation+"caja_estado",{idcajah:idcajah},function(data){
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

                    });
                    return;
                }   
            });

    });

</script>

    
@stop