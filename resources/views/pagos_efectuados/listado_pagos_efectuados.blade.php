@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Pagos Efectuados</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_pagos_efectuados"></i>Listado de Pagos Efectuados</a></li>
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
                    <th>Nº de Pago</th>
                    <th>Proveedor </th>
                    <th>Nº Factura de Compra</th>
                    <th>Fecha de Pago</th>
                    <th>Tipo de Pago</th>
                    <th>Se Efectuó</th>
                    <th>Nº de Operación/Cheque</th>
                    <th>Banco</th>
                    <th>Proveedor-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($prs as $pr)
                    <tr id="tr_detalle">
                        
                        <td>{{ $pr->id_pago_efectuado}}</td>
                        <td>{{ $pr->razon_social }} </td>
                        <td>{{ $pr->serie }}-{{ str_pad($pr->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ date('d/m/Y', strtotime($pr->created_at)) }}</td>
                         <?PHP  if($pr->tipo_pago == 0) {
                            echo '<td>Contado</td>';
                        }elseif ($pr->tipo_pago == 1){
                            echo '<td>Transferencia</td>';
                        }else{
                            echo '<td>Cheque</td>';
                        }  ?>
                        <td><?PHP   if($pr->moneda == 1){ echo 'S/ '; }
                                    else if($pr->moneda == 2){ echo '$'; } 
                                    else{ echo '€'; }?>
                            {{ $pr->pagado }} 
                        </td>

                        <td>{{ $pr->nro_operacion }} </td>
                        <td>{{ $pr->banco }} </td>

                        <td>{{ $pr->ruc_dni}} - {{ $pr->contacto_nombre}} - {{$pr->contacto_telefono}}</td>
                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    data-idpr           = " {{ $pr->id_pago_efectuado  }} "    
                                    data-idcajah        = " {{ $pr->id_factura_comprah }} "
                                    data-serie          = " {{ $pr->serie            }} "                                    
                                    data-correlativo    = " {{ $pr->numeracion         }} "
                                    data-toggle         = "modal"
                                    id="pagar"> 
                                <i class="glyphicon glyphicon-plus position-center"></i>
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
                <h4 class="modal-title">Pago Efectuado</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>

                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Factura de Compra</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Pago Efectuado:</label>
                                        <input type="text" id="idpr" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="correlativo" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label >Nº de Operación / Cheque:</label>
                                        <input type="text"  id="nro_operacion" class="form-control">
                                    </div>                                
                                
                                    <div class="input-group">
                                        <label >Banco / Caja:</label>
                                        <select class="form-control" id="banco">
                                                <option value=0> -- </option>
                                            @foreach ($bancos as $banco)
                                                <option value="{{ $banco->idbanco}}">{{$banco->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>                               
                                </div> 
                            </fieldset>
                      
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer" id="submit-control">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>
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

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":3, "type":"date-eu"},{"targets":8, "visible":false}],
     } );

     var idpr;
        $('#cliente_table').on('click','#pagar',function(){

            $('#valor_recibido').val('');
            idpr=  $(this).data('idpr');
            serie = $(this).data('serie');
            correlativo = $(this).data('correlativo');

            $('#idpr').val(idpr);
            $('#correlativo').val(serie +   ' - ' + correlativo);

            $('#formulario').modal('show');

        });

        $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );

            nro_operacion = $('#nro_operacion').val();
            banco = $('#banco').val();

            arrayPost = {idpr:idpr, nro_operacion:nro_operacion, banco:banco};
            console.log(arrayPost);
            
            $.post(currentLocation+"pe_update",arrayPost,function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se guardo correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede guardar el Pago, intentalo de nuevo luego.",
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