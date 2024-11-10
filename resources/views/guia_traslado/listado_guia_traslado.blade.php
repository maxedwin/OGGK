@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Guías de Traslado</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class=""><a href="/listado_guia_traslado"></i>Listado de Guías de Traslado</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="guia_traslado" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Guía de Traslado
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
            $(".InputCodigoNB").on('keyup', function (e) {
                console.log('asd');
                if (e.key === 'Enter' || e.keyCode === 13) {
                    handleUpdateValue( this, $(this).data('id'));
                }
            });

            $('#cliente_table tbody').on( 'keyup', 'input', function (e) {
                console.log('asd');
                if (e.key === 'Enter' || e.keyCode === 13) {
                    handleUpdateValue( this, $(this).data('id'));
                }
                //document.getElementById(this.dataset.id).focus()
                // note - call draw() to update the table's draw state with the new data
            } );
        });
    </script>   

            <?php $status_ent_gr = Helper::status_ent_gr(); ?>
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
                    <th>Nº NubeFact</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Fecha de Entrega</th>   
                    <!--th>Fecha de Entregado</th-->    
                    <th>Despachador</th> 
                    <!--th>Estado</th-->
                    <th>Cliente-extra</th>
                    <th>Acciones</th> 
                </tr>
                </thead>
                
               
            </table>


    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Guía de Traslado</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Traslado</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº NubeFact:</label>
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
                                <legend class="text-semibold">Información de la Guía de Remision</legend>
                                
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

    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Historial de Entrega Guía de Traslado</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Guía de Traslado</legend>
                                
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
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>
<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    function handleUpdateValue(element) {
        console.log(element.dataset.prev);
        $.post(currentLocation+"update_codigonb",{id_guia_remisionh:element.id, value:element.value},function(data){   
            location.reload();
            });

    }
    $( document ).ready(function() {
    var set;
    console.log( "ready!" );
    table = $('#cliente_table').DataTable({
        "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('allGT') }}",
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
                        a.href = currentLocation+"exportGT?order="+set.order[0][0]+"&dir="+set.order[0][1]+"&search="+set.search.search+"&f_inicio="+$('#f_inicio').val()+"&f_fin="+$('#f_fin').val();
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
                { "data": "f_entrega" },
                //{ "data": "f_entregado" },
                { "data": "name" },
                //{ "data": "status_ent_gr" },
                { "data": "cliente_extra" },
                { "data": "acciones" },

            ],	
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":2, "type":"date-eu"},{"targets":6, "visible":false}],
        "order": [[ 3, "desc" ], [0, "desc"]],

        dom: 'lBfrtip',
        buttons: [/*{
               "extend": 'excel',
               "text": '<b  style="color: green;">Excel </b><i class="glyphicon glyphicon-save-file" style="color: green;"></i>',
               "titleAttr": 'Excel',                               
            },*/
        ]
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
        
        /*$.get(currentLocation+"exportGT",{input:set},function(data){
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

    $('#cliente_table').on('click','#imprimir',function(events){
        var id = $(this).data('id');
        var archivo = $(this).data('archivo');
        var direccion="info_guia_remision?id="+ id;
        if(archivo != null && archivo !=''){
            direccion= "greenter/"+archivo;
        }
        console.log(set.order[0][0]);
        console.log(set.order[0][1]);
        console.log(currentLocation+direccion);
        window.open(currentLocation+direccion,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });
    });

    




    // $('#cliente_table').on('click','#status', function(event){
    //     event.preventDefault();
    //     id_guia_remisionh=  $(this).data('id_guia_remisionh');
    //     status= $(this).data('status');
    //     var jqxhr =  $.get(currentLocation+"guiaremision_state?id="+id_guia_remisionh+"&status="+status,function(status){
    //     }).done(function() {
    //         swal({
    //             title: "Cambio el estado!",
    //             text: "La Guia ha cambiado su estado.",
    //             confirmButtonColor: "#66BB6A",
    //             type: "success"
    //         },function(){
    //             window.location.reload();
    //         });
    //     }).fail(function() {
    //         swal("Error no se ha cambiado el estado de la guia", "Intentelo nuevamente luego.", "error");
    //     })}
    // );  

 

    $('#cliente_table').on('click','#actualizar',function(events){
        var id = $(this).data('id');
        $.post(currentLocation+"checkCDRGT",{id:id},function(data){
            obj = JSON.parse(data);
                console.log(obj);
            if(obj.created === 200){
                $(this).prop( "disabled", true );
                swal({
                        title: "Buenas noticias!",
                        text: obj.msg,
                        type: "success"
                    },
                    function(){
                        window.location.reload()
                });
            }else if(obj.created === 500){
                $(this).prop( "disabled", true );
                if(obj.msg=="Error no se conectó: object(Greenter\\Model\\Response\\Error)#"+/[0-9][0-9][0-9]/+" (2) {\n  [\"code\":protected]=>\n  string(5) \"00109\"\n  [\"message\":protected]=>\n  string(1) \"-\"\n}\n"){
                swal({
                        title:"Woops!",
                        text: "Este Servicio de la Sunat está temporalmente fuera de servicio, intentalo más tarde",
                        type: "error"
                    },
                    function(){
                        window.location.reload()
                });
                }
                else{
                swal({
                        title: "Error! Comprobante "+ obj.comprobante+':',
                        text: obj.msg,
                        type: "error"
                    },
                    function(){
                        window.location.reload()
                });
                }
            }else if(obj.created === 501){
                $(this).prop( "disabled", true );
                swal({
                        title: "Woops!",
                        text: obj.msg,
                        type: "error"
                    },
                    function(){
                    $(this).prop( "disabled", false );
                });
            }
        });
    });

    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');
            np = $(this).data('np');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('GT' + ' - ' + numeracion);
            $('#np').val(np);

            $('#formulario').modal('show');
    });

    $('#cliente_table').on('click','#history',function(){

            id_orden_ventah=  $(this).data('id_guia_remisionh');
            correlativo = $(this).data('correlativo');
            codigoNB = $(this).data('codigo');

            $('#id_guia_remisionh3').val(id_orden_ventah);
            $('#correlativo3').val('GT'+' - ' + correlativo);
            $('#codigoNB3').val(codigoNB);

            $.post(currentLocation+"gr_history",{id_guia_remisionh:id_orden_ventah},function(data){
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
            console.log(id_orden_ventah);

            $.post(currentLocation+"gr_estado_anulado",{id_orden_ventah:id_orden_ventah},function(data){
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

    $('#cliente_table').on('click','#observacion',function(){

            id_orden_ventah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            $('#numeracion_obs').val('GT' + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(id_orden_ventah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"gt_edit_comments",{id_reg:id_orden_ventah, comments:comments},function(data){
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