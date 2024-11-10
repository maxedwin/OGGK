@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Facturas / Boletas Pendientes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active"><a href="/listado_caja"></i>Listado de Facturas - Boletas</a></li>
    <li class="active"><a href="/listado_FTpendientes"></i>Listado de Facturas - Boletas Pendientes</a></li>
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
                $("#ft_pendientes_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>   


            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="ft_pendientes_table">
                <thead>
                <tr>                    
                    <th>Correlativo</th>
                    <th>Nº NubeFact</th>
                    <th>Fecha de Emisión</th>
                    <th>Fecha Entregado</th> 
                    <th>Fecha de Cobro</th> 
                    <th>Días de Retraso</th>   
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Moneda</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Por Pagar</th>
                    <th>Vendedor</th> 
                    <th>Estado</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="ft_pendientes_table">
                @foreach ($ft_pendientes as $caja)
                    <tr id="tr_detalle">

                        <td>{{ str_pad($caja->numeracion, 6, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ $caja->codigoNB}}</td>
                        <td>{{ date('d/m/Y', strtotime($caja->created_at)) }}</td>
                        <td>
                            @foreach ($caja->guias as $guia)
                                {{'GR-'.$guia->numeracion.' ('.($guia->f_entregado == '0000-00-00' ? '-' : date('d/m/Y', strtotime($guia->f_entregado))).')'}}
                            @endforeach
                        </td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $caja->f_cobro), 'd/m/Y') }}</td>    

                        <?PHP 
                            $fechahoy = new DateTime(); 
                            $cobro = new DateTime($caja->f_cobro);
                            $interval = $fechahoy->diff($cobro);
                            $dias = $interval->format('%R%a');
                        ?>
                        <?PHP  if($dias <= 0) {
                            echo '<td style=color:red;>'.$dias.'</td>';
                        }else{
                            echo '<td>'.$dias.'</td>';
                        }  ?>

                        <td>{{ $caja->razon_social}}</td>  
                        
                        <td>{{ $caja->direccion}}</td>  
                        
                        <?PHP if($caja->moneda == 1){ echo '<td>Soles</td>'; }else if($caja->moneda == 2){ echo '<td>Dólares</td>'; } else{ echo '<td>Euros</td>'; }?>
                        <td>{{ sprintf("%.2f",round($caja->total,2)) }}</td>
                        <td>{{ sprintf("%.2f",round($caja->pagado_total,2)) }}</td>
                        <td>{{ sprintf("%.2f",round($caja->total - $caja->pagado_total,2)) }} </td>
                        

                        <td> {{ $caja->name }} {{ $caja->lastname }}</td>

                        <?PHP  if($caja->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-idcajah="'.$caja->idcajah.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($caja->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-danger" data-idcajah="'.$caja->idcajah.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($caja->estado_doc == 2) {
                            echo '<td><button id="status" class="btn btn-success" data-idcajah="'.$caja->idcajah.'" data-status="3" > Cancelada </button></td>';
                        }      elseif($caja->estado_doc == 4) {
                            echo '<td><button id="status" class="btn btn-info" data-idcajah="'.$caja->idcajah.'" data-status="3" > NCP </button></td>';
                        }      elseif($caja->estado_doc == 5) {
                            echo '<td><button id="status" class="btn btn-primary" data-idcajah="'.$caja->idcajah.'" data-status="3" > NCT </button></td>';
                        }      elseif($caja->estado_doc == 6) {
                            echo '<td><button id="status" class="btn btn-warning" data-idcajah="'.$caja->idcajah.'" data-status="3" > Descuento </button></td>';
                        }      else{
                            echo '<td><button id="status" class="btn btn-secondary" data-idcajah="'.$caja->idcajah.'" data-status="0" > Anulada </button></td>';
                        }  ?>

                        <td>{{ $caja->ruc_dni}} - {{ $caja->contacto_nombre}} - {{$caja->contacto_telefono}}</td>
                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    data-idcajah        = " {{ $caja->idcajah      }} "                                    
                                    data-correlativo    = " {{ $caja->numeracion   }} "
                                    data-total          = " {{ $caja->total        }} "
                                    data-tipo           = " {{ $caja->tipo         }} "
                                    data-codigo         = " {{ $caja->codigoNB     }} "
                                    data-serie          = " {{ $sucursal->serie    }} "
                                    data-pagado         = " {{ $caja->pagado_total }}"
                                    data-dias_credito   = " {{ $caja->dias_credito }}"
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
                <h4 class="modal-title">Nuevo Pago Recibido</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>

                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Factura/Boleta</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="correlativo" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="codigoNB" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label >Total:</label>
                                        <input type="text"  id="total" class="form-control" disabled>
                                        <input type="hidden"  id="idcajah">
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
                                    <div class="input-group">
                                        <label >Se Recibió:</label>
                                        <input type="number"  id="valor_recibido" class="form-control">
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
                                <div class="form-group">
                                    <label id="dias_credito" style="float: right;"></label>
                                    <table class="table no-footer" style="overflow-x:auto;" id="guias_tabla">
                                        <thead>
                                           <tr>
                                                <th>N° Documento</th>
                                                <th>Fecha de Entrega</th>
                                                <th>Fecha de Reprogramación</th>
                                                <th>ENTREGADO</th>
                                                <!--th>Estado</th-->
                                            </tr>
                                        </thead>
                                        <tbody >
                                        </tbody>
                                    </table>
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
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>

    <script type="application/javascript" rel="script">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        table = $('#ft_pendientes_table').DataTable( {
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "columnDefs" : [{"targets":2, "type":"date-eu"},{"targets":3, "type":"date-eu"},{"targets":12, "visible":false}],
            dom: 'lBfrtip',
            buttons: [
            'excel'
            ]
         } );

        var idcajah;
        var total;
        var pagado;
        var por_pagar;


        function dateFormatStr(dateStr) {
            if (dateStr) {
                if (dateStr.length == 10 && dateStr != '0000-00-00') {
                    return dateStr.substr(8,2) +'/'+ dateStr.substr(5,2) +'/'+ dateStr.substr(0,4);
                }
            }
            return '-';
        }

        $('#ft_pendientes_table').on('click','#pagar',function(){

            idcajah=  $(this).data('idcajah');
            serie = $(this).data('serie');
            correlativo = $(this).data('correlativo');
            total = parseFloat($(this).data('total'));
            codigoNB = $(this).data('codigo');
            tipo = $(this).data('tipo');
            pagado = parseFloat($(this).data('pagado'));
            por_pagar = parseFloat(total - pagado).toFixed(2);
            dias_credito = $(this).data('dias_credito');

            if(tipo > 1)
                tipo2 = "F";
            else
                tipo2 = "B";

            $.get(currentLocation+"pr_get_guias?query="+idcajah+"", function( data ) {
                $('#valor_recibido').val('');
                data = JSON.parse(data);
                console.log(data);

                $('#idcajah').val(idcajah);
                $('#correlativo').val(tipo2 +   ' - ' + correlativo);
                $('#codigoNB').val(codigoNB);
                $('#total').val(total);
                $('#pagado').val(pagado);
                $('#por_pagar').val(por_pagar);
                $('#dias_credito').html('<b>'+dias_credito+' dia(s) de credito</b>');

                $('#guias_tabla tbody').html('');

                var rows = '';
                $.each(data, function(index, value) {

                    dateEntr = dateFormatStr(value.f_entregado);
                    if (dateEntr != '-') {
                        d1 = new Date(dateEntr);
                        d2 = new Date();
                        d2.setHours(0);d2.setMinutes(0);d2.setSeconds(0);d2.setMilliseconds(0);
                        var diffTime = Math.abs(d2 - d1);
                        var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        console.log(diffDays);
                        if (dias_credito >= diffDays) {
                            dateEntr += ' ( vence en ' + String(dias_credito - diffDays) + ' dia(s) )';
                        } else {
                            dateEntr += ' ( credito vencido )';
                        }
                    }

                    rows += '<tr>';
                    rows += '<td>GR-'+value.numeracion+'</td>';
                    rows += '<td>'+dateFormatStr(value.f_entrega)+'</td>';
                    rows += '<td>'+dateFormatStr(value.f_reprogramar)+'</td>';
                    rows += '<td><b>'+dateEntr+'</b></td>';
                    rows += '</tr>';
                });

                $('#guias_tabla tbody').html(rows);

                $('#formulario').modal('show');
                 
            });

        });

        $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            valor_recibido = parseFloat($('#valor_recibido').val());
            tipo_pago = $('#tipo_pago').val();
            por_pagar2 = parseFloat(por_pagar - valor_recibido);

            console.log('1');
            console.log(por_pagar);
            console.log('2');
            console.log(por_pagar2);
            console.log('3');
            console.log(valor_recibido>por_pagar);

            nro_operacion = $('#nro_operacion').val();
            banco = $('#banco').val();

            if(isNaN(valor_recibido)) {
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar lo que se recibió!",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }

            if(valor_recibido > por_pagar){
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Estas recibiendo de más!",
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

            arrayPost = {idcajah:idcajah, total:total, valor_recibido:valor_recibido, por_pagar2:por_pagar2, tipo_pago:tipo_pago, nro_operacion:nro_operacion, banco:banco};
            console.log(arrayPost);
            
            $.post(currentLocation+"pr_store",arrayPost,function(data){
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