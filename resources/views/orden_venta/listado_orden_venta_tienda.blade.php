@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Ordenes de Venta - Tienda</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_orden_venta_tienda"></i>Listado de Ordenes de Venta - Tienda</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="orden_venta" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Orden de Venta
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
                    <th>Nota de Pedido</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Comprobante</th>
                    <th>RUC/DNI</th>
                    <th>Estado</th>
                    <th>Acciones</th> 
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($orden_ventas as $orden_venta)
                    <tr id="tr_detalle">
                                                
                        <td>{{ str_pad($orden_venta->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td> {{ $orden_venta->codigoNB }} </td>
                        <td>{{ date('d/m/Y', strtotime($orden_venta->created_at)) }}</td>
                            
                        <td> {{ $orden_venta->name }} </td>

                        <td>{{ $orden_venta->total }}</td>
                        
                        <td>{{ $orden_venta->document_type }}</td>
                        <td>{{ $orden_venta->ruc_dni }}</td>

                        <?PHP  if($orden_venta->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="1" >  Pendiente  </button></td>';
                        }      elseif($orden_venta->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-success" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="2" > Facturada  </button></td>';
                        }      elseif($orden_venta->estado_doc == 3) {
                            echo '<td><button id="status" class="btn btn-primary" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="2" > Parcial  </button></td>';
                        }      else {
                            echo '<td><button id="status" class="btn btn-secondary" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="0" > Anulada </button></td>';
                        }?>

                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    id="imprimir" data-id="{{$orden_venta->id_orden_ventah}}">
                                <i class="glyphicon glyphicon-print position-center"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs"
                                    data-id_orden_ventah   = " {{ $orden_venta->id_orden_ventah }} "
                                    data-numeracion        = " {{ $orden_venta->numeracion }} "
                                    data-np                = " {{ $orden_venta->codigoNB }} "                                    
                                    data-toggle            = "modal"
                                    id="anular"> 
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                        </td> 

                    </tr>
                @endforeach
                </tbody>
            </table>


    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Orden de Venta (Nota de Pedido)</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Orden de Venta</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº Nota de Pedido:</label>
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
        "columnDefs" : [{"targets":2, "type":"date-eu"}],
     } );

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_orden_venta_tienda?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');
            np = $(this).data('np');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('OV' + ' - ' + numeracion);
            $('#np').val(np);

            $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_orden_ventah);

            $.post(currentLocation+"ov_estado",{id_orden_ventah:id_orden_ventah},function(data){
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
                            window.location.reload();
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



</script>

    
@stop