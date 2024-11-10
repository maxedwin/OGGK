@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Ordenes de Venta por Confirmar</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active">Ordenes de Venta por Confirmar</li>
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
        $('#cliente_table tbody').on( 'keyup', 'input', function (e) {
                console.log('asd');
                if (e.key === 'Enter' || e.keyCode === 13) {
                    handleUpdateValue( this, $(this).data('id'));
                }
                //document.getElementById(this.dataset.id).focus()
                // note - call draw() to update the table's draw state with the new data
            } );

            var vendedores = $('<select onchange="change_vendedor(this.id, this.value)">');
            vendedores.append($("<option>").attr('value',0).text('-'));
            $.get(currentLocation+"vendedores",{},function(data){
                obj = JSON.parse(data);
                $(obj.vendedores).each(function() {
                 vendedores.append($("<option>").attr('value',this.id).text(this.name +' '+this.lastname));
                });
                console.log(vendedores)
            });

            $('#cliente_table tbody').on( 'dblclick', 'td.vendedor', function (e) {
                let vendedors = vendedores;
                vendedors.val(this.id);
                vendedors.attr("id",$(this).data('fact'));
                console.log($(this).data('fact'));
                var cell = table.cell( this );
                cell.data(vendedors[0].outerHTML).draw();
            } );
        });
    </script>   


    <!--<div class="panel panel-flat">
        

        <div class="panel-body">
        <input type="text" id="input" class="form-control input-lg" id="formGroupExampleInput" placeholder="Busca tu Orden de Venta">
            
            orden_ventas->links()-->

            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <th>Correlativo</th>
                    <th>Nota de Pedido</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Fecha de Entrega</th>    
                    <th>Fecha de Cobro</th>   
                    <th>Vendedor</th> 
                    <th>Estado</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th> 
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($orden_ventas as $orden_venta)
                    <tr id="tr_detalle">
                                                
                        <td>{{ str_pad($orden_venta->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ $orden_venta->codigoNB}}</td>
                        <td>{{ str_limit($orden_venta->created_at, $limit = 10, $end='') }}</td>
                        <td>{{ $orden_venta->razon_social}}</td>

                        <td>{{ number_format((float)$orden_venta->total, 2, '.', '') }}</td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $orden_venta->f_entrega), 'd/m/Y') }}</td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $orden_venta->f_cobro), 'd/m/Y') }}</td>

                <td class="vendedor" id="{{ $orden_venta->idvendedor }}" data-fact="{{ $orden_venta->id_orden_ventah }}"> {{ $orden_venta->name }} {{ $orden_venta->lastname }}</td>


                        <?PHP  if($orden_venta->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="1" >  Pendiente  </button></td>';
                        }      elseif($orden_venta->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-success" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="2" > Facturada  </button></td>';
                        }      elseif($orden_venta->estado_doc == 3) {
                            echo '<td><button id="status" class="btn btn-primary" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="2" > Parcial  </button></td>';
                        }      elseif($orden_venta->estado_doc == 9) {
                            echo '<td><button id="status" class="btn btn-info" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="9" > Por Confirmar  </button></td>';
                        }      else {
                            echo '<td><button id="status" class="btn btn-secondary" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="0" > Anulada </button></td>';
                        }?>

                        <td>{{ $orden_venta->ruc_dni}} - {{ $orden_venta->contacto_nombre}} - {{$orden_venta->contacto_telefono}}</td>
                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    id="imprimir" data-id="{{$orden_venta->id_orden_ventah}}">
                                <i class="glyphicon glyphicon-print position-center"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-xs"
                                    id="confirmTo" data-id="{{$orden_venta->id_orden_ventah}}"
                                    data-numeracion        = " {{ $orden_venta->numeracion }} ">
                                <i class="glyphicon glyphicon-ok position-center"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs"
                                    id="denyTo" data-id="{{$orden_venta->id_orden_ventah}}"
                                    data-numeracion        = " {{ $orden_venta->numeracion }} ">
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>

                        </td> 

                    </tr>
                @endforeach
                </tbody>
            </table>

<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    function change_vendedor(element, value){
        $.post(currentLocation+"orden_update_vendedor",{id_orden_ventah:element, value:value},function(data){   
            location.reload();
        });
    }

    $('#cliente_table thead th').each( function () {
        var title = $(this).text();
        if (title=='Nota de Pedido') {
            $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
        }
    } );

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"orderable": false, "targets": 1},{"targets":2, "type":"date-eu"},{"targets":5, "type":"date-eu"},{"targets":6, "type":"date-eu"},{"targets":9, "visible":false}],
        dom: 'lBfrtip',
        buttons: [
            'excel'
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            });
        }
     } );

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_orden_venta?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    $('#cliente_table').on('click','#tr_detalle #td_actions #confirmTo',function(events){
        var id_orden_ventah = $(this).data('id');
        var numeracion = $(this).data('numeracion');

        swal({
            title: "Estas seguro de confirmar la Orden de Venta" +' OV' + ' - ' + numeracion+"?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, Confirmar!",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        
        function(isConfirm){
                    if (isConfirm) {
                        var jqxhr =  $.post(currentLocation+'confirm_ov',{id_orden_ventah:id_orden_ventah}, function(data,status){})
                        .done(function() {
                            swal({
                                title: "Orden de Venta Confirmada",
                                text: "La Orden de Venta fue Confirmada",
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            }
                            ,function(){
                                window.location.reload();
                            });
                        }).fail(function() {
                                    swal("No se pudo realizar la confirmacion","", "error");
                                });
                    }
                    else {
                        swal({
                            title: "Cancelado",
                            text: "No se ha realizado ningun cambio.",
                            confirmButtonColor: "#2196F3",
                                    type: "error"
                        });
                    }
                });
    });

    $('#cliente_table').on('click','#tr_detalle #td_actions #denyTo',function(events){
        var id_orden_ventah = $(this).data('id');
        var numeracion = $(this).data('numeracion');

        swal({
            title: "Estas seguro de anular la Orden de Venta" +' OV' + ' - ' + numeracion+"?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, Confirmar!",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        
        function(isConfirm){
                    if (isConfirm) {
                        var jqxhr =  $.post(currentLocation+'deny_ov',{id_orden_ventah:id_orden_ventah}, function(data,status){})
                        .done(function() {
                            swal({
                                title: "Orden de Venta Anulada",
                                text: "La Orden de Venta fue Anulada",
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            }
                            ,function(){
                                window.location.reload();
                            });
                        }).fail(function() {
                                    swal("No se pudo realizar la anulación","", "error");
                                });
                    }
                    else {
                        swal({
                            title: "Cancelado",
                            text: "No se ha realizado ningun cambio.",
                            confirmButtonColor: "#2196F3",
                                    type: "error"
                        });
                    }
                });
    });


</script>

    
@stop