@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Comunicaciones de Baja</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_caja"></i>Comunicaciones de Baja</a></li>
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
                    <th>Correlativo</th>
                    <th>Fecha</th>
                    <th>Documento</th>
                    <th>Motivo</th>
                    <th>Ticket</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($bajas as $caja)
                    <tr id="tr_detalle">
                        <td>{{ $caja->correlativoG }}</td>
                        <td>{{ date('d/m/Y', strtotime($caja->created_at)) }}</td>
                        <td>{{ $caja->documento }}</td>
                        <td>{{ $caja->motivo }}</td>
                        <td>
                            {{ $caja->ticket }} 
                            <?php if (!is_null($caja->xml_file) and $caja->xml_file != '') { ?>
                                (<a title="{{$caja->descriptionG}}" href="{{url('greenter',$caja->xml_file)}}" download>XML</a>)
                            <?php } ?>
                            <?php if (!is_null($caja->cdr_file) and $caja->cdr_file != '') { ?>
                                (<a title="{{$caja->descriptionG}}" href="{{url('greenter',$caja->cdr_file)}}" download>CDR</a>)
                            <?php } ?>
                            <?php if (!is_null($caja->pdf_file) and $caja->pdf_file != '') { ?>
                                (<a title="{{$caja->descriptionG}}" href="{{url('greenter',$caja->pdf_file)}}" download>PDF</a>)
                            <?php } ?>
                        </td>                        
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

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        dom: 'lBfrtip',
        buttons: [
            'excel'
        ]
     } );
</script>

@stop