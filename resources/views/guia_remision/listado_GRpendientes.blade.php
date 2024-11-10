@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Guías de Remisión Pendientes</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class=""><a href="/listado_guia_remision"></i>Listado de Guías de Remisión</a></li>
    <li class=""><a href="/listado_GRpendientes"></i>Listado de Guías de Remisión Pendientes</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')
    <li>
        <a href="guia_remision" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Guía de Remisión
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
                $("#gr_pendientes_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>   

        

            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="gr_pendientes_table">
                <thead>
                <tr>            
                    <th>Correlativo</th>                            
                    <th>Nota de Pedido</th>                            
                    <th>Nº GuiaRemisión (NF)</th>
                    <th>Nº Factura/Boleta (NF)</th>
                    <th>Fecha de Emisión</th>
                    <th>Fecha de Entrega</th>  
                    <th>Fecha de Reprogramación</th>   
                    <th>Cliente</th>
                    <th>Vendedor</th> 
                    <th>Estado</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="gr_pendientes_table">
                @foreach ($gr_pendientes as $guia_remision)
                    <tr id="tr_detalle">
                        <td>
                            {{ str_pad($guia_remision->numeracion, 6, "0", STR_PAD_LEFT) }}
                            <?php if (!is_null($guia_remision->xml_file) and $guia_remision->xml_file != '') { ?>
                                (<a title="{{$guia_remision->descriptionG}}" href="{{url('greenter',$guia_remision->xml_file)}}" download>XML</a>)
                            <?php } ?>
                            <?php if (!is_null($guia_remision->cdr_file) and $guia_remision->cdr_file != '') { ?>
                                (<a title="{{$guia_remision->descriptionG}}" href="{{url('greenter',$guia_remision->cdr_file)}}" download>CDR</a>)
                            <?php } ?>
                            <?php if (!is_null($guia_remision->pdf_file) and $guia_remision->pdf_file != '') { ?>
                                (<a title="{{$guia_remision->descriptionG}}" href="{{url('greenter',$guia_remision->pdf_file)}}" download>PDF</a>)
                            <?php } ?>
                        </td>
                        <td>{{ $guia_remision->np}}</td>
                        <td>{{ $guia_remision->codigoNB }}</td>
                        <td>{{ $guia_remision->caja }}</td>
                        <td>{{ date('d/m/Y', strtotime($guia_remision->created_at)) }}</td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $guia_remision->f_entrega), 'd/m/Y') }}</td>    
                        <td>{{ date_format(date_create_from_format('Y-m-d', $guia_remision->f_reprogramar), 'd/m/Y') }}</td>    
                        <td>{{ $guia_remision->razon_social}}</td>

                        <td> {{ $guia_remision->name }} {{ $guia_remision->lastname }}</td>

                         <?PHP  if($guia_remision->estado_doc == 0) {
                            echo '<td><button id="status" class="btn btn-danger" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($guia_remision->estado_doc == 1) {
                            echo '<td><button id="status" class="btn btn-primary" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="2" > Facturada </button></td>';
                        }      elseif($guia_remision->estado_doc == 2) {
                            echo '<td><button id="status" class="btn btn-success" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="3" > Entregada </button></td>';
                        }      elseif($guia_remision->estado_doc == 4) {
                            echo '<td><button id="status" class="btn btn-info" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="3" > Reprogramada </button></td>';
                        }      else{
                            echo '<td><button id="status" class="btn btn-secondary" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="0" > Anulada </button></td>';
                        }  ?>

                        <td>{{ $guia_remision->ruc_dni}} - {{ $guia_remision->contacto_nombre}} - {{$guia_remision->contacto_telefono}}</td>
                        <td id="td_actions">
                            <button type="button" class="btn btn-info btn-xs"
                                    id="imprimir" data-id="{{$guia_remision->id_guia_remisionh}}">
                                <i class="glyphicon glyphicon-print position-center"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-id_guia_remisionh   = " {{ $guia_remision->id_guia_remisionh  }} "                                    
                                    data-correlativo         = " {{ $guia_remision->numeracion        }} "
                                    data-codigo              = " {{ $guia_remision->codigoNB          }} "
                                    data-estado              = " {{ $guia_remision->estado_doc        }} "
                                    data-serie               = " {{ $sucursal->serie }} "
                                    data-toggle              = "modal"
                                    id="pagar"> 
                                <i class="glyphicon glyphicon-plus position-center"></i>
                            </button>
                            <button type="button" class="btn btn-info btn-xs"
                                    data-id_guia_remisionh   = " {{ $guia_remision->id_guia_remisionh  }} "                                    
                                    data-correlativo         = " {{ $guia_remision->numeracion        }} "
                                    data-codigo              = " {{ $guia_remision->codigoNB          }} "
                                    data-toggle              = "modal"
                                    id="history"> 
                                <i class="glyphicon glyphicon-time position-center"></i>
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
                <h4 class="modal-title">Actualizar Estado Guía de Remisión</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Remisión</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group" style="width: 200px;">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="correlativo" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group" style="width: 200px;">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="codigoNB" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group hide">
                                        <label >Estado:</label>
                                        <input type="text"  id="estado" class="form-control" disabled>
                                        <input type="hidden"  id="estado">
                                    </div>

                                    <div class="input-group" style="width: 200px;">
                                        <label>Fecha Reprogramación:</label>
                                        <input type="date" id="f_reprogramar" class="form-control"> 
                                    </div>
                                </div>

                                <div class="form-group gr-detail">
                                    <table  class="table table-borderless">
                                        <thead>
                                            <tr class="bg-danger-700">
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Cantidad Entregada</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gr_detail">
                                            <!--tr id="tr_item" >
                                                <td>prod sad </td>
                                                <td>20</td>
                                                <td><input class="form-control" id="peso2" type="number" value="20"></td>
                                            </tr>
                                            <tr id="tr_item" >
                                                <td>prod sad </td>
                                                <td>20</td>
                                                <td><input class="form-control" id="peso2" type="number" value="20"></td>
                                            </tr-->
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios">Entregada</button>
                                <button type="button" class="btn btn-primary" id="guardar_cambios_repro">Reprogramada</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
    
    </div>
    </div>
    </div>

    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Historial de Entrega Guía de Remisión</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Remisión</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group" style="width: 200px;">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="correlativo3" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group" style="width: 200px;">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="codigoNB3" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group gr-history">
                                    <table  class="table table-borderless">
                                        <thead>
                                            <tr class="bg-danger-700">
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gr_history">
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
    
    </div>
    </div>
    </div>

<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
    <script type="application/javascript" rel="script">
        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        table = $('#gr_pendientes_table').DataTable( {
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "columnDefs" : [{"targets":4, "type":"date-eu"},{"targets":5, "type":"date-eu"},{"targets":6, "type":"date-eu"},{"targets":10, "visible":false}],
            "order": [[ 4, "desc" ], [0, "desc"]],
         } );

        $('#gr_pendientes_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
            var id = $(this).data('id');
            window.open(currentLocation+"info_guia_remision?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
        });

        var id_guia_remisionh;
        /*$('#gr_pendientes_table').on('click','#pagar',function(){

            id_guia_remisionh=  $(this).data('id_guia_remisionh');
            serie = $(this).data('serie');
            correlativo = $(this).data('correlativo');
            codigoNB = $(this).data('codigo');
            estado = $(this).data('estado');

            $('#id_guia_remisionh').val(id_guia_remisionh);
            $('#correlativo').val('GR'+' - ' + correlativo);
            $('#codigoNB').val(codigoNB);

            var tmp;
            if(estado == 0)
                tmp = 'Pendiente';
            else if (estado == 1)
                tmp = 'Facturada';
            else 
                tmp = 'Anulada';

            $('#estado').val(tmp);

            $('#formulario').modal('show');

        });*/

        $('#gr_pendientes_table').on('click','#history',function(){

            id_guia_remisionh=  $(this).data('id_guia_remisionh');
            correlativo = $(this).data('correlativo');
            codigoNB = $(this).data('codigo');

            $('#id_guia_remisionh3').val(id_guia_remisionh);
            $('#correlativo3').val('GR'+' - ' + correlativo);
            $('#codigoNB3').val(codigoNB);

            $.post(currentLocation+"gr_history",{id_guia_remisionh:id_guia_remisionh},function(data){
                obj = JSON.parse(data);
                console.log(obj);

                $('#gr_history').html('');

                $.each(obj, function(index, value) {
                    var string = '<tr id="tr_item">';
                    string += '<td id="id_producto">'+value.barcode+' '+value.nombre+'</td>';
                    string += '<td id="cantidad_producto">'+value.cantidad+'</td>';
                    string += '<td id="fecha_entregado">'+value.f_emision+'</td>';
                    string += '</tr>';
                    $('#gr_history').append(string);
                });

                $('#formulario3').modal('show');
            });

        });

        $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_guia_remisionh);

            var productos = [];
            var isCorrect = true;
            var isTotal = 1;
            $("#gr_detail #tr_item").each(function(){
                var idproducto = $(this).find('#id_producto').data('idprod');
                var id_guia_remisiond = $(this).find('#id_producto').data('id_guia_remisiond');
                var cantidad_entregada = parseFloat($(this).find('#cantidad_entregado_producto #cantidad_entregada').val());
                var cantidad = parseInt($(this).find('#cantidad_producto').text());
                if (cantidad_entregada != parseInt(cantidad_entregada) || cantidad_entregada > cantidad || cantidad_entregada < 0) {
                    isCorrect = false;
                }
                if (parseInt(cantidad_entregada) < cantidad) {
                    isTotal = 0;
                }
                productos.push({id:id_guia_remisiond,idproducto:idproducto,cantidad_entregada:parseInt(cantidad_entregada)});
            });

            if (!isCorrect) {
                $('#formulario').modal('hide');
                swal({
                    title: "Error..!",
                    text: "Valores invalidos en la cantidad de entrega.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios').prop( "disabled", false );
                });
                return;
            }

            console.log(productos);
            var json_prod = JSON.stringify(productos);
            console.log(json_prod);
            console.log(isTotal);
            //return;

            $.post(currentLocation+"gr_estado",{id_guia_remisionh:id_guia_remisionh, productos:json_prod, is_total:isTotal},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
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
                    $('#formulario').modal('hide');
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

        $('#gr_pendientes_table').on('click','#pagar',function(){

            id_guia_remisionh=  $(this).data('id_guia_remisionh');
            serie = $(this).data('serie');
            correlativo = $(this).data('correlativo');
            codigoNB = $(this).data('codigo');
            estado = $(this).data('estado');

            $('#id_guia_remisionh').val(id_guia_remisionh);
            $('#correlativo').val('GR'+' - ' + correlativo);
            $('#codigoNB').val(codigoNB);

            $.post(currentLocation+"gr_detail",{id_guia_remisionh:id_guia_remisionh},function(data){
                obj = JSON.parse(data);
                console.log(obj);

                $('#gr_detail').html('');

                $.each(obj, function(index, value) {
                    var string = '<tr id="tr_item">';
                    string += '<td id="id_producto" data-id_guia_remisiond="'+value.id_guia_remisiond+'" data-idprod="'+value.idproducto+'">'+value.barcode+' '+value.nombre+'</td>';
                    string += '<td id="cantidad_producto">'+(value.cantidad-value.cantidad_ent)+'</td>';
                    string += '<td id="cantidad_entregado_producto"><input id="cantidad_entregada" class="form-control" type="number" value="'+(value.cantidad-value.cantidad_ent)+'"></td>';
                    string += '</tr>';
                    $('#gr_detail').append(string);
                });

                $('#formulario').modal('show');
            });

        });

        $('#formulario').on('click','#guardar_cambios_repro',function(){
            $('#guardar_cambios_repro').prop( "disabled", true );
            console.log(id_guia_remisionh);

            var f_reprogramar = $('#f_reprogramar').val();

            if(f_reprogramar.length == 0){
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar Fecha de Reprogramación",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_cambios_repro').prop( "disabled", false );
                });
                return;
            }

            $.post(currentLocation+"gr_estado_reprogramar",{id_guia_remisionh:id_guia_remisionh,f_reprogramar:f_reprogramar},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
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
                    $('#formulario').modal('hide');
                    swal({
                        title: "Error..!",
                        text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios_repro').prop( "disabled", false );
                    });
                    return;
                }   
            });

        });

    </script>

    
@stop