@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Fichas de Recepción</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')

    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Incomprario</li> -->
    <li class="active"><a href="/listado_ficha_recepcion"></i>Listado de Fichas de Recepción</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="ficha_recepcion" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Ficha de Recepción
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
        });
    </script>   

<div class="form-group form-inline" >
                <div class="input-group ">
                    <label for="f_inicio">Desde:</label>
                    <input type="date" class="form-control" id="f_inicio">
                </div>
                <div class="input-group ">
                    <label for="f_fin">Hasta:</label>
                    <input type="date" class="form-control" id="f_fin">
                </div> 
                <!--div class="pull-right">
                    <a class="btn btn-secondary" style="margin:1em 2em;border-color:black;"  id='exportar'><b  style="color: green;">Excel </b><i class="glyphicon glyphicon-save-file" style="color: green;"></i></a>
                </div-->
            </div > 
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <th>Correlativo</th>
                    <th>Orden de Compra</th>
                    <th>Nº Guia de Compra</th>
                    <th>Fecha de Recepción</th>
                    <th>Proveedor</th>
                    <th>Flete Transporte</th>
                    <th>Flete Costo</th>
                    <th>Estado</th>
                    <th>Proveedor-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                
            </table>

    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Ficha de Recepción</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información de la Ficha de Recepción</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>Nº Documento:</label>
                                        <input type="text" id="numeracion" class="form-control" disabled> 
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
                                <legend class="text-semibold">Información de la Ficha de Recepción</legend>
                                
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

<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
    $( document ).ready(function() {
    var set;
    console.log( "ready!" );
    table = $('#cliente_table').DataTable({
        "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('allFR') }}",
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
                        //var a = document.getElementById('exportar');
                        //a.href = currentLocation+"exportFR?order="+set.order[0][0]+"&dir="+set.order[0][1]+"&search="+set.search.search+"&f_inicio="+$('#f_inicio').val()+"&f_fin="+$('#f_fin').val();
                        //console.log(settings);
                        //return settings.recordsFiltered;
                    },
                   "lengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
    ],
            "columns": [
                 { "data": "numeracion" },
                { "data": "oc" },
                { "data": "gc" },
                { "data": "f_recepcion" },
                { "data": "razon_social" },
                { "data": "flete" },
                { "data": "flete_costo" },
                { "data": "estado_doc" },
                { "data": "cliente_extra" },
                { "data": "acciones" },

            ],	
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":3, "type":"date-eu"},{"targets":8, "visible":false}],
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

     /*$('#exportar').on('click',function(){        
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


     $('#cliente_table').on('click','#imprimir',function(events){
        var id = $(this).data('id');
        window.open(currentLocation+"info_ficha_recepcion?id="+ id,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
    });


    var id_orden_ventah;
    $('#cliente_table').on('click','#anular',function(){

            id_orden_ventah=  $(this).data('id_orden_ventah');
            numeracion = $(this).data('numeracion');

            $('#id_orden_ventah').val(id_orden_ventah);
            $('#numeracion').val('FR' + ' - ' + numeracion);

            $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(id_orden_ventah);

            $.post(currentLocation+"fr_estado",{id_orden_ventah:id_orden_ventah},function(data){
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
                        $('#guardar_cambios').prop( "disabled", false);
                    });
                    return;
                }   
            });

    });

    $('#cliente_table').on('click','#observacion',function(){

            id_orden_ventah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            $('#numeracion_obs').val('FR' + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(id_orden_ventah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"fr_edit_comments",{id_reg:id_orden_ventah, comments:comments},function(data){
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