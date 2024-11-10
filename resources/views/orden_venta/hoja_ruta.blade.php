@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Administrar Hoja de Ruta</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/hoja_ruta_admin"></i>Administrar Hoja de Ruta</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <button type="button" class="btn btn-xs"                                      
            id="nuevo_recibo"> 
            <i class="glyphicon glyphicon-plus position-center"></i>  Agregar a la Hoja de Ruta
        </button>        

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
        $('#cliente_table tbody').on( 'keyup', 'input', function (e) {
                console.log('asd');
                if (e.key === 'Enter' || e.keyCode === 13) {
                    handleUpdateValue( this, $(this).data('id'));
                }
                //document.getElementById(this.dataset.id).focus()
                // note - call draw() to update the table's draw state with the new data
            } );

            var vendedores = $('<select onchange="change_vendedor(this.id, this.value)">');
            vendedores.append($("<option>").attr('value',0).text('-'));
            $.get(currentLocation+"vendedores",{},function(data){
                obj = JSON.parse(data);
                $(obj.vendedores).each(function() {
                 vendedores.append($("<option>").attr('value',this.id).text(this.name +' '+this.lastname));
                });
                console.log(vendedores)
            });

            $('#cliente_table tbody').on( 'dblclick', 'td.vendedor', function (e) {
                let vendedors = vendedores;
                vendedors.val(this.id);
                vendedors.attr("id",$(this).data('fact'));
                console.log($(this).data('fact'));
                var cell = table.cell( this );
                cell.data(vendedors[0].outerHTML).draw();
            } );
        });
    </script>   


    <!--<div class="panel panel-flat">
        

        <div class="panel-body">
        <input type="text" id="input" class="form-control input-lg" id="formGroupExampleInput" placeholder="Busca tu Orden de Venta">
            
            orden_ventas->links()-->
            <?php $status_doc_ov = Helper::status_doc_ov(); ?> 
            <?php $status_ent_ov = Helper::status_ent_ov(); ?> 
            <?php $status_cob_ov = Helper::status_cob_ov(); ?> 
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                <th>Correlativo</th>                    
                    <th>Nota de Pedido</th>
                    <th>Nº NubeFact</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Fecha de Entrega</th>   
                    <th>Fecha de Entregado</th>    
                    <th>Despachador</th> 
                    
                    <th>Acciones</th>
                    <th>Cliente-extra</th>   
                </tr>
                </thead>
                <tbody id="cliente_table">
                <?php 
                    $muestras=[];
                ?>
                @foreach ($guia_remisions as $guia_remision)
                    @if(!$guia_remision->hoja_ruta)
                        @if($guia_remision->status_ent ==1 || $guia_remision->status_ent ==2)
                            <?php 
                                array_push($muestras,$guia_remision);
                            ?>
                        @endif
                    @else
                    <tr id="tr_detalle">
                                                
                        <td>{{ str_pad($guia_remision->numeracion, 6, "0", STR_PAD_LEFT) }}
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
                        <td ondblclick="this.innerHTML=`<input
                                                                value='{{$guia_remision->codigoNB}}'
                                                                class='InputCodigoNB'
                                                                onfocusout='handleUpdateValue(this);'
                                                                id='{{$guia_remision->id_guia_remisionh}}'
                                                                data-prev='{{$guia_remision->codigoNB}}'
                                                        />`;document.getElementById('{{$guia_remision->id_guia_remisionh}}').focus();" 
                            id="{{ $guia_remision->codigoNB}}">
                            {{ $guia_remision->codigoNB }}
                        </td>    
                        <td>{{ date('d/m/Y', strtotime($guia_remision->created_at)) }}</td>
                        <td>{{ $guia_remision->razon_social}}</td>

                        <td>{{ date_format(date_create_from_format('Y-m-d', $guia_remision->f_entrega), 'd/m/Y') }}</td>
                        <td>{{ date_format(date_create_from_format('Y-m-d', $guia_remision->f_entregado), 'd/m/Y') }}</td>

                        <td> {{ $guia_remision->name }} {{ $guia_remision->lastname }}</td>

                        
                        <td id="td_actions">                                        
                        <button type="button" class="btn btn-danger btn-xs"
                                    data-id_orden_ventah   = " {{ $guia_remision->id_guia_remisionh }} "
                                    data-numeracion        = " {{ $guia_remision->numeracion }} "
                                    data-np                = " {{ $guia_remision->codigoNB }} "                                    
                                    data-toggle            = "modal"
                                    id="anular"> 
                                <i class="icon-cancel-square2 position-center"></i>
                            </button>
                        </td> 
                        <td></td>

                    </tr>
                    @endif
                @endforeach
                </tbody>
                <!--tfoot>
                    <tr>
                        <th>Correlativo</th>
                        <th>Nota de Pedido</th>
                        <th>Fecha de Emisión</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha de Entrega</th>    
                        <th>Fecha de Cobro</th>   
                        <th>Vendedor</th> 
                        <th>Estado</th>
                        <th>Cliente-extra</th>
                        <th>Acciones</th> 
                    </tr>
                </tfoot-->
            </table>


    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Quitar de la Hoja de Ruta</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Remisión</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº Nota de Pedido:</label>
                                        <input type="text" id="np" class="form-control" disabled> 
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios">Quitar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>

    <div id="formularioHojaRuta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Añadir a la Hoja de Ruta</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Guias de Remisión con fecha de entrega hoy:</legend>
                                
                                <div class="form-group form-inline" style="text-align:center;">
                                <div class="input-group">
                                    <label for="ov_select">Buscar Guía de Remisión:</label>
                                    <div>
                                        <select class="selectpicker" id="ov_select" multiple="multiple" data-live-search="true" title="Busca tu Orden de Venta..."> 
                                            @foreach ($muestras as $guia_remision)
                                                <option value="{{ $guia_remision->id_guia_remisionh }}">GR - {{$guia_remision->numeracion}} // {{$guia_remision->codigoNB}} (NP:{{$guia_remision->np}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>                                    

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios_hr">Añadir</button>
                            </div>

                        </div>
                    </div>
                </div>
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
    <script src="{{ URL::asset('/javascript/helper.js') }}" type="text/javascript"></script>
<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script type="application/javascript" rel="script">
$('#multi').selectpicker(); 
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
    var muestras = {!!json_encode($muestras)!!}
    console.log(muestras);

    function change_vendedor(element, value){
        $.post(currentLocation+"orden_update_vendedor",{id_orden_ventah:element, value:value},function(data){   
            location.reload();
        });
    }

    $('#cliente_table thead th').each( function () {
        var title = $(this).text();
        if (title=='Nota de Pedido') {
            $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
        }
    } );

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"orderable": false, "targets": 1},{"targets":2, "type":"date-eu"},{"targets":5, "type":"date-eu"},{"targets":6, "type":"date-eu"},{"targets":9, "visible":false}],
        "order": [[ 2, "desc" ], [0, "desc"]],
        dom: 'lBfrtip',
        buttons: [
            
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            });
        }
     } );

    $('#cliente_table').on('click','#tr_detalle #td_actions #imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_orden_venta?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');
            np = $(this).data('np');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('GR' + ' - ' + numeracion);
            $('#np').val(np);

            $('#formulario').modal('show');
    });

    $('#nuevo_recibo').on('click',function(){
        $('#formularioHojaRuta').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_orden_ventah);

            $.post(currentLocation+"quitar_hoja_ruta",{id_orden_ventah:id_orden_ventah},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se quitó correctamente de la hoja de ruta",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede quitar, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                    return;
                }   
            });

    });

    $('#cliente_table').on('click','#mark_total',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');

            swal({
              title: "Esta seguro de marcar la Orden de Venta #" + ('000000' + numeracion).slice(-6) + " como completado?",
              text: "Esta acción actualizara los stocks y las cantidades de los productos en el detalle de la Orden de Venta",
              type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#2196F3',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                closeOnConfirm: false
            }, function(isConfirm) {
              if (isConfirm) {
                $.post(currentLocation+"ov_complete_order",{id_orden_ventah:id_orden_ventah},function(data){
                    obj = JSON.parse(data);
                    console.log(obj);
                    console.log(obj.mensaje);
                    if(obj.mensaje === 200){
                        swal({
                          title: 'Acción Completada',
                          text: 'Se completo la Orden de Venta',
                          type: "success"
                        }, function() {
                            window.location.reload();
                        });
                    }else{
                        swal({
                            title: "Error..!",
                            text: "No se puede completar la Orden de Venta, acción denegada.",
                            confirmButtonColor: "#66BB6A",
                            type: "error"
                        },function(){

                        });
                        return;
                    }   
                });
              } else {

              }
            });

    });

    $('#cliente_table').on('click','#observacion',function(){

            id_orden_ventah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            $('#numeracion_obs').val('OV' + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioHojaRuta').on('click','#guardar_cambios_hr',function(){
            $('#guardar_cambios_hr').prop( "disabled", true );
            var ov_select = $('#ov_select').val();

            $.post(currentLocation+"ov_add_hr",{ ov_select:ov_select},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formularioHojaRuta').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se actualizó la Hoja de Rutas",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede actualizar la hoja de rutas, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios_hr').prop( "disabled", false );
                    });
                    return;
                }   
            });

    });



</script>

    
@stop