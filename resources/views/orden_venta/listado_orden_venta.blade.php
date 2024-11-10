@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Ordenes de Venta</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_orden_venta"></i>Listado de Ordenes de Venta</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="orden_venta" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Orden de Venta
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
            <div class="form-group form-inline" >
                <div class="input-group ">
                    <label for="f_inicio">Desde:</label>
                    <input type="date" class="form-control" id="f_inicio">
                </div>
                <div class="input-group ">
                    <label for="f_fin">Hasta:</label>
                    <input type="date" class="form-control" id="f_fin">
                </div> 
                <div class="pull-right">
                    <a class="btn btn-secondary" style="margin:1em 2em;border-color:black;"  id='exportar'><b  style="color: green;">Excel </b><i class="glyphicon glyphicon-save-file" style="color: green;"></i></a>
                </div>
            </div >  

            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <th>Correlativo</th>
                    <th>Nota de Pedido</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Base Imponible</th>
                    <th>IGV</th>
                    <th>Total</th>
                    <th>Fecha de Entrega</th>    
                    <th>Fecha de Cobro</th>   
                    <th>Vendedor</th> 
                    <th>Estado</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th> 
                </tr>
                </thead>
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
                <h4 class="modal-title">Anular Orden de Venta (Nota de Pedido)</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Orden de Venta</legend>
                                
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
                                <button type="button" class="btn btn-info" id="guardar_cambios">Anular</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>

    <div id="formularioObs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Modificar Observación</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Orden de Venta</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion_obs" class="form-control" disabled> 
                                    </div>
                                    <div class="input-group">
                                        <label>Observación:</label>
                                        <textarea class="form-control" id="observacion_obs"></textarea>
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios_obs">Guardar</button>
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

<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    function change_vendedor(element, value){
        $.post(currentLocation+"orden_update_vendedor",{id_orden_ventah:element, value:value},function(data){   
            location.reload();
        });

    }
    $( document ).ready(function() {
    var set;
    console.log( "ready!" );
    table = $('#cliente_table').DataTable( {
        "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('allOV') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":
                        function(dtParms){
                            dtParms.f_inicio = $('#f_inicio').val();
                            dtParms.f_fin = $('#f_fin').val();
                            
                            return dtParms
                        },
                        _token: "{{csrf_token()}}"
                    },
                   "stateSave": true,
                    "stateSaveParams": function (settings, data) { 
                        // Here the response
                        set= data;
                        var a = document.getElementById('exportar');
                        a.href = currentLocation+"exportOV?order="+set.order[0][0]+"&dir="+set.order[0][1]+"&search="+set.search.search+"&f_inicio="+$('#f_inicio').val()+"&f_fin="+$('#f_fin').val();
                        //console.log(settings);
                        //return settings.recordsFiltered;
                    },
                   "lengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
    ],
            "columns": [
                 { "data": "numeracion" },
                { "data": "codigoNB" },
                { "data": "created_at" },
                { "data": "razon_social" },
                { "data": "subtotal" },
                { "data": "igv" },
                { "data": "total" },
                { "data": "f_entrega" },
                { "data": "f_cobro" },
                { "data": "name" },
                { "data": "status_doc" },
                { "data": "cliente_extra" },
                { "data": "acciones" },

            ],	
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"orderable": false, "targets": 1},{"targets":2, "type":"date-eu"},{"targets":5, "type":"date-eu"},{"targets":6, "type":"date-eu"},{"targets":11, "visible":false}],
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
     $('#f_inicio').on('change',function(){ 
        table.draw();
        console.log($(this).val());
        console.log($('#f_fin').val())
     });

     $('#f_fin').on('change',function(){ 
        table.draw();
        console.log($(this).val())
     });

     $('#exportar').on('click',function(){        
        //or grab it by tagname etc
        console.log(set.order[0][0]);
        console.log(set.order[0][1]);
        console.log(set.search.search);
        console.log($('#f_inicio').val());
        console.log($('#f_fin').val());
        
        /*$.get(currentLocation+"exportGR",{input:set},function(data){
            //var blob = new Blob([data], {type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"});
            const url = window.URL.createObjectURL(new Blob([data]))
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'SolucionesOGGK_Guias_Remision');
            document.body.appendChild(link);
            link.click();
            //saveAs(data, 'SolucionesOGGK_Guias_Remision' + '.xlsx');
            //obj = JSON.parse(data);
            //console.log(obj);
        });*/


    });
});






    /*$('#cliente_table thead th').each( function () {
        var title = $(this).text();
        if (title=='Nota de Pedido') {
            $(this).html( '<input type="text" placeholder="Buscar '+title+'" />' );
        }
    } );*/

    

    $('#cliente_table').on('click','#imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_orden_venta?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');
            np = $(this).data('np');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('OV' + ' - ' + numeracion);
            $('#np').val(np);

            $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_orden_ventah);

            $.post(currentLocation+"ov_estado",{id_orden_ventah:id_orden_ventah},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se anuló correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else if(obj.mensaje === 500){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Cuidado!",
                            text: "Ya está anulada!",
                            type: "error"
                        },
                        function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                }else if(obj.mensaje === 999){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Cuidado!",
                            text: "Asegurate de anular todas las guias de remision y facturas!",
                            type: "error"
                        },
                        function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                }else{
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

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(id_orden_ventah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"ov_edit_comments",{id_reg:id_orden_ventah, comments:comments},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formularioObs').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se actualizó las observaciones",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "No se puede actualizar las observaciones, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios_obs').prop( "disabled", false );
                    });
                    return;
                }   
            });

    });



</script>

    
@stop