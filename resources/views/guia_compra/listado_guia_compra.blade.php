@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-import position-left"></i> <span class="text-semibold">Listado de Guías de Compra</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Incomprario</li> -->
    <li class="active"><a href="/listado_guia_compra"></i>Guías de Compra</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="guia_compra" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Guía de Compra
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
                    <th>Serie</th>
                    <th>Correlativo</th>
                    <th>Fecha de Emisión</th>
                    <th>Proveedor</th>
                    <th>Flete Transporte</th>
                    <th>Flete Costo</th>
                    <th>Estado</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($guia_compras as $guia_compra)
                    <tr id="tr_detalle">

                        <td>{{ $guia_compra->serie}}</td>
                        <td>{{ str_pad($guia_compra->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $guia_compra->f_emision), 'd/m/Y') }}</td>                            
                        <td>{{ $guia_compra->razon_social}}</td>

                        <td>{{ $guia_compra->trans }}</td>
                        <td>{{ $guia_compra->flete_costo }}</td>                       
                        
                        <?PHP  if($guia_compra->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-id_guia_comprah="'.$guia_compra->id_guia_comprah.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($guia_compra->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-primary" data-id_guia_comprah="'.$guia_compra->id_guia_comprah.'" data-status="2" > Facturada </button></td>';
                        }      elseif($guia_compra->estado_doc == 2) {
                            echo '<td><button id="status" class="btn btn-success" data-id_guia_comprah="'.$guia_compra->id_guia_comprah.'" data-status="3" > Recibida </button></td>';
                        }      else{
                            echo '<td><button id="status" class="btn btn-secondary" data-id_guia_comprah="'.$guia_compra->id_guia_comprah.'" data-status="0" > Anulada </button></td>';
                        }  ?>                    
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
        "columnDefs" : [{"targets":2, "type":"date-eu"}],
     } );




</script>

    
@stop