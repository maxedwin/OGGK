@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Listado de Clientes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/list_clientes"></i>Listado de Clientes</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="nuevo_cliente" target="_blank" id="nuevo_cliente">
            <i class="icon-box-add position-left"></i>
            Nuevo Cliente
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



              <!--<div class="form-inline">
                <input type="text" id="input" class="form-control" id="formGroupExampleInput" placeholder="Busca tu Cliente">
                <button id="buscar" class="btn btn-info">Buscar</button>
            </div>

          LISTA DE CLIENTES 
            <div class="text-right" >
            </div>-->
            <p>{{ $count }} resultados</p>
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <!-- <th>Estado</th> -->
                    <th>RUC/DNI</th>
                    <th>Razon Social/Nombre</th>
                    <th>Contacto Telefono</th>
                    <th>Direccion</th>
                    <th>Fecha entregado</th>
                    <th>Fecha pagado</th>
                    <th>Dias de retraso</th>
                    <th>Monto</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($clientes as $cliente)
                    <tr id="tr_detalle">
                        <!-- <?PHP  //if($cliente->estado_entidad == 1) --> <!--{-->
                            //echo '<td><button id="status" class="btn btn-success" data-idcliente="'.$cliente->idcliente.'" data-status="0">A</button></td>';
                        //else{
                           // echo '<td><button id="status" class="btn btn-secondary" data-idcliente="'.$cliente->idcliente.'" data-status="1">I</button></td>';
                        //}  ?> -->

                        <td>{{ $cliente->ruc_dni }}</td>
                        <td>{{ $cliente->razon_social }}</td>
                        <td>{{ $cliente->contacto_telefono }}</td>
                        <td>{{ $cliente->direccion }}</td>
                        <td>{{ $cliente->f_entregado }}</td>
                        @if($cliente->f_pagado) 
                        <td>{{$cliente->f_pagado}}</td>
                        @else
                        <td>No pagado</td>
                        @endif
                        <td>{{ $cliente->retraso }}</td>
                        <td>{{ $cliente->total }}</td>

                        
                        
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

        $.extend( true, $.fn.dataTable.defaults, {
            "searching": true,
            "ordering": false
        } );

        $(document).ready(function () {
          $('#cliente_table').DataTable({
            "pageLength": 15,
            "bInfo" : false ,
            "bLengthChange" : false
          });
        });

        // $(document).ready(function(){
        //     $("#input").on("keyup", function() {
        //         var value = $(this).val().toLowerCase();
        //         $("#cliente_table tr").filter(function() {
        //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //         });
        //     });
        // });

        $("#input").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#buscar").click();
            }
        });

    </script>
    
@stop