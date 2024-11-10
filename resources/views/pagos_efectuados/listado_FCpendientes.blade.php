@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-usd position-left"></i> <span class="text-semibold">Listado de Facturas de Compra Pendientes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_FCpendientes"></i>Facturas de Compra Pendientes</a></li>
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
                $("#fc_pendientes_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>   

            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="fc_pendientes_table">
                <thead>
                <tr>                    
                    <th>Nº Factura de Compra</th>
                    <th>Fecha de Emisión</th>
                    <th>Fecha de Vencimiento</th>   
                    <th>Proveedor</th>
                    <th>Moneda</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Por Pagar</th>
                    <th>Estado</th>
                    <th>Proveedor-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="fc_pendientes_table">
                @foreach ($fc_pendientes as $caja)
                    <tr id="tr_detalle">

                        <td>{{ $caja->serie }}-{{ str_pad($caja->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $caja->f_emision), 'd/m/Y') }}</td> 
                        <td>{{ date_format(date_create_from_format('Y-m-d', $caja->f_vencimiento), 'd/m/Y') }}</td>    
                        <td>{{ $caja->razon_social}}</td>
                        <?PHP if($caja->moneda == 1){ echo '<td>Soles</td>'; }else if($caja->moneda == 2){ echo '<td>Dolares</td>'; } else{ echo '<td>Euros</td>'; }?>
                        <td>{{ $caja->total }}</td>
                        <td>{{ sprintf("%.2f",round($caja->pagado_total,2)) }}</td>
                        <td>{{ $caja->total - sprintf("%.2f",round($caja->pagado_total,2)) }} </td>
                        
                        <?PHP  if($caja->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-id_factura_comprah="'.$caja->id_factura_comprah.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($caja->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-danger" data-id_factura_comprah="'.$caja->id_factura_comprah.'" data-status="2" > Pendiente </button></td>';
                        }      elseif($caja->estado_doc == 2) {
                            echo '<td><button id="status" class="btn btn-success" data-id_factura_comprah="'.$caja->id_factura_comprah.'" data-status="3" > Cancelada </button></td>';
                        }      else{
                            echo '<td><button id="status" class="btn btn-secondary" data-id_factura_comprah="'.$caja->id_factura_comprah.'" data-status="0" > Anulada </button></td>';
                        }  ?>

                        <td>{{ $caja->ruc_dni}} - {{ $caja->contacto_nombre}} - {{$caja->contacto_telefono}}</td>
                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    data-id_factura_comprah        = " {{ $caja->id_factura_comprah     }} "                                    
                                    data-correlativo    = " {{ $caja->numeracion   }} "
                                    data-total          = " {{ $caja->total        }} "
                                    data-serie          = " {{ $caja->serie        }} "
                                    data-pagado         = " {{ $caja->pagado_total }}"
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
                <h4 class="modal-title">Nuevo Pago Efectuado</h4>
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
                                        <label>Nº Documento:</label>
                                        <input type="text" id="correlativo" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group form-inline">
                                    <div class="input-group ">
                                        <label >Total:</label>
                                        <input type="text"  id="total" class="form-control" disabled>
                                        <input type="hidden"  id="id_factura_comprah">
                                    </div>

                                    <div class="input-group">
                                        <label >Pagado:</label>
                                        <input type="text"  id="pagado" class="form-control" disabled>
                                    </div>

                                    <div class="input-group">
                                        <label >Por pagar:</label>
                                        <input type="text"  id="por_pagar" class="form-control" disabled>
                                    </div>
                                </div>

                                <div class="form-group form-inline">
                                    <div class="input-group" >
                                        <label >Se Efectuó:</label>
                                        <input type="number"  id="valor_efectuado" class="form-control">
                                    </div> 

                                    <div class="input-group">
                                        <label >Tipo de Pago:</label>
                                        <select class="form-control" id="tipo_pago">
                                                <option value="99">--</option>
                                                <option value="0">Contado</option>
                                                <option value="1">Transferencia</option>
                                                <option value="2">Cheque</option>
                                        </select>
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

    
    <script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>

    <script type="application/javascript" rel="script">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        table = $('#fc_pendientes_table').DataTable( {
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "columnDefs" : [{"targets":1, "type":"date-eu"},{"targets":2, "type":"date-eu"},{"targets":9, "visible":false}],
         } );

        var id_factura_comprah;
        var total;
        var pagado;
        var por_pagar;
        $('#fc_pendientes_table').on('click','#pagar',function(){

            $('#valor_efectuado').val('');
            id_factura_comprah=  $(this).data('id_factura_comprah');
            serie = $(this).data('serie');
            correlativo = $(this).data('correlativo');
            total = parseFloat($(this).data('total'));
            pagado = parseFloat($(this).data('pagado'));
            por_pagar = parseFloat(total - pagado).toFixed(2);

            $('#id_factura_comprah').val(id_factura_comprah);
            $('#correlativo').val(serie + ' - ' + correlativo);
            $('#total').val(total);
            $('#pagado').val(pagado);
            $('#por_pagar').val(por_pagar);

            $('#formulario').modal('show');

        });

        $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            valor_efectuado = parseFloat($('#valor_efectuado').val());
            console.log(valor_efectuado);
            tipo_pago = $('#tipo_pago').val();
            por_pagar2 = parseFloat(por_pagar - valor_efectuado);

            nro_operacion = $('#nro_operacion').val();
            banco = $('#banco').val();

            if(isNaN(valor_efectuado)) {
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar lo que se efectuó!",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }

            if(valor_efectuado > por_pagar){
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Estas efectuando de más!",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }

            if(tipo_pago == 99){
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar Tipo de Pago",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }

            arrayPost = {id_factura_comprah:id_factura_comprah, total:total, valor_efectuado:valor_efectuado, por_pagar2:por_pagar2, tipo_pago:tipo_pago, nro_operacion:nro_operacion, banco:banco};
            console.log(arrayPost);
            
            $.post(currentLocation+"pe_store",arrayPost,function(data){
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