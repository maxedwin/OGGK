@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-shopping-cart position-left"></i> <span class="text-semibold">Listado de Proveedores</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/list_proveedores"></i>Listado de Proveedores</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="nuevo_proveedor" id="nuevo_producto">
            <i class="icon-box-add position-left"></i>
            Nuevo Proveedor
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
                <input type="text" id="input" class="form-control" id="formGroupExampleInput" placeholder="Busca tu Proveedor">
                <button id="buscar" class="btn btn-info">Buscar</button>
            </div>
            LISTA DE Proveedores -->

            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="proveedor_table">
                <thead>
                <tr>
                    <!--<th>Estado</th>-->
                    <th>RUC/DNI</th>
                    <th>Razon Social</th>
                    <th>Direccion</th>
                    <th>Distrito</th>
                    <th>Contacto Nombre</th>
                    <th>Contacto Telefono</th>
                    <th>Dias de Crédito</th>    
                    <th>Tipo de Pago</th>    
                    <th>Moneda</th>    
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="proveedor_table">
                @foreach ($proveedores as $proveedor)
                    <tr id="tr_detalle">
                        <!--<?PHP  //if($proveedor->est_ent == 1) {
                            //echo '<td><button id="status" class="btn btn-success" data-idproveedor="'.$proveedor->idproveedor.'" data-status="0">A</button></td>';
                        //}//else{
                            //echo '<td><button id="status" class="btn btn-secondary" data-idproveedor="'.$proveedor->idproveedor.'" data-status="1">I</button></td>';
                        //}  ?>-->
                        <td>{{ $proveedor->ruc_dni }}</td>
                        <td>{{ $proveedor->razon_social }}</td>
                        <td>{{ $proveedor->direccion }}</td>
                        <td>{{ $proveedor->distrito }}</td>
                        <td>{{ $proveedor->contacto_nombre }}</td>
                        <td>{{ $proveedor->contacto_telefono }}</td>
                        <td>{{ $proveedor->dias_credito }}</td>
                        
                        <?PHP  if($proveedor->tipo_pago == 0) {
                            echo '<td>Contado</td>';
                        }elseif($proveedor->tipo_pago == 1) {
                            echo '<td>Transferencia</td>';
                        }elseif($proveedor->tipo_pago == 2) {
                            echo '<td>Cheque</td>';
                        }else{
                            echo '<td>No registrado</td>';
                        }  ?>

                        <?PHP  if($proveedor->moneda == 1) {
                            echo '<td>Soles</td>';
                        }elseif($proveedor->moneda == 2) {
                            echo '<td>Dolares</td>';
                        }elseif($proveedor->moneda == 3) {
                            echo '<td>Euros</td>';
                        }else{
                            echo '<td>No tiene</td>';
                        }  ?>
                        
                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    id="editar" data-idproveedor="{{$proveedor->idproveedor}}">
                                <i class="icon-pen6 position-center"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" id="eliminar"
                                    data-idproveedor="{{$proveedor->idproveedor}}">
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>



<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    table = $('#proveedor_table').DataTable( {
        "autoWidth": true,
    } );

    $('#proveedor_table').on('click','#status', function(event){
        event.preventDefault();
        idproveedor=  $(this).data('idproveedor');
        status= $(this).data('status');
        var jqxhr =  $.get(currentLocation+"proveedor_state?id="+idproveedor+"&status="+status,function(status){
        }).done(function() {
            swal({
                title: "Cambio el estado!",
                text: "El Proveedor ha cambiado su estado.",
                confirmButtonColor: "#66BB6A",
                type: "success"
            },function(){
                window.location.reload();
            });
        }).fail(function() {
            swal("Error no se ha cambiado el estado del proveedor", "Intentelo nuevamente luego.", "error");
        })}
    );  

    $('#proveedor_table').on('click','#tr_detalle #td_actions #editar',function(events){
        var idproveedor = $(this).data('idproveedor');
        window.open(currentLocation+"editar_proveedor?idproveedor="+idproveedor, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
    });
    
    $('#proveedor_table').on('click','#tr_detalle #td_actions #eliminar',function(events){
        var idproveedor = $(this).data('idproveedor');

        swal({
            title: "Estas seguro?",
            text: "No podras recuperar este proveedor si lo eliminas!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        
        function(isConfirm){
            if (isConfirm) {
                var jqxhr =  $.get(currentLocation+"eliminar_proveedor?idproveedor="+idproveedor, function(data,status){})
                .done(function() {
                    swal({
                        title: "Eliminado!",
                        text: "El proveedor ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    }
                    ,function(){
                        window.location.reload();
                    });
                }).fail(function() {
                            swal("Error al eliminar al proveedor", "Intentelo nuevamente luego.", "error");
                        });
            }
            else {
                swal({
                    title: "Cancelado",
                    text: "No se ha eliminado nada :)",
                    confirmButtonColor: "#2196F3",
                            type: "error"
                        });
                    }
                });
        });

        $(document).ready(function(){
            $("#input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#proveedor_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        $("#input").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#buscar").click();
            }
        });

        
        $("#buscar").on('click',function(){
            var query = $("#input").val();
            var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
            console.log('click');
            window.location.href = currentLocation+"list_proveedores"+"?query="+query;
        });

    </script>

    
@stop