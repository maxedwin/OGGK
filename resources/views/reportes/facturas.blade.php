@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Facturas / Boletas</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_caja"></i>Listado de Facturas - Boletas</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="caja" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Factura/Boleta
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
    </script>   


  
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>                    
                    <th>Fecha de emision</th>
                    <th>Razon Social</th>
                    <th>Codigo NubeFact</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Precio Total</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($cajah as $fact)
                <tr>
                    <td>{{  str_limit($fact->created_at, $limit = 10, $end='') }}</td>
                    <td>{{ $fact->razon_social }}</td>
                    <td>{{ $fact->codigoNB }}</td>
                    <td>{{ $fact->producto }}</td>
                    <td>{{ $fact->cantidad }}</td>
                    <td>{{ $fact->precio_unit }}</td>
                    <td>{{ $fact->precio_total }}</td>


                </tr>
                @endforeach
                </tbody>
            </table>






<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>


<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
    
    function handleUpdateValue(element) {
        console.log(element.dataset.prev);
        $.post(currentLocation+"caja_update_codigonb",{idcajah:element.id, value:element.value},function(data){   
            location.reload();
        });
    }

    function change_vendedor(element, value){
        $.post(currentLocation+"caja_update_vendedor",{idcajah:element, value:value},function(data){   
            location.reload();
        });
    }

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":1, "type":"date-eu"}],
        dom: 'lBfrtip',
        buttons: [
            'excel'
        ]
     } );

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_caja?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    var idcajah;
    $('#cliente_table').on('click','#agregar_guia',function(){

            idcajah=  $(this).data('idcajah');
            numeracion = $(this).data('numeracion');
            tipo = $(this).data('tipo');

            var serie;            
            if (tipo == 2 || tipo == '2'){
                 serie = 'FFF1';
            }else{
                 serie = 'BBB1';
            }                

            $('#idcajah').val(idcajah);
            $('#numeracion').val(serie + '-' + numeracion);

            $('#formulario').modal('show');
    });

</script>

    
@stop