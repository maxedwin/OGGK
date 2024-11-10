@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-cube position-left"></i> <span class="text-semibold">Listado de Servicios</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">Listado de Servicios</li>

@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="servicio_nuevo"  id="nuevo_servicio">
            <i class="icon-box-add position-left"></i>
            Nuevo Servicio
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

    <!--LISTA DE SERVICIOS -->
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" id="servicios_table">
                <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="servicios">
                @foreach ($products as $product)
                    <tr>
                        <td>{{$product->barcode}}</td>
                        <td>{{$product->nombre}}</b></td>
                        <td>{{$product->detalle}}</b></td>

                        <td>{{$product->precio}} </a></td>
                        
                        <td>
                            <button type="button" class="btn btn-info btn-xs" id="editar"
                                    data-idproducto="{{$product->idproducto}}">
                                <i class="icon-pen6 position-center"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" data-idproducto="{{$product->idproducto}}"  id="eliminar">
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        
        
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script type="text/javascript">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        $('#servicios_table').dataTable( {
            autoWidth: true,
            dom: 'lBfrtip',
            buttons: [
                'excel'
            ]
        });

        $('#servicios_table').on('click','#editar',function(){
            var idproducto=  $(this).data('idproducto');
            window.open(currentLocation+"servicio_editar?id="+idproducto, "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no, toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");

        });

        $('#servicios_table').on('click','#eliminar' ,function() {
            //limpiar();
            idproducto=  $(this).data('idproducto');
            swal({
                    title: "Estas seguro?",
                    text: "No podras recuperar este servicio si lo eliminas!",
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
                        var jqxhr =  $.post(currentLocation+"servicio_delete",{id:idproducto},function(data,status){

                        }).done(function() {
                            swal({
                                title: "Eliminado!",
                                text: "El producto ha sido eliminado.",
                                confirmButtonColor: "#66BB6A",
                                type: "success"
                            },function(){
                                window.location.reload();
                            });
                        }).fail(function() {
                            swal("Error al eliminar servicio", "Intentelo nuevamente luego.", "error");
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
    </script>

@stop