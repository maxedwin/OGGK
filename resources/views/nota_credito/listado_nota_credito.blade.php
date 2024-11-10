@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Notas de Crédito / Devoluciones</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class=""><a href="/listado_nota_credito"></i>Listado de Notas de Crédito - Devoluciones</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="nota_credito" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Nota de Crédito/Devolución
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
                    <th>Tipo</th>
                    <th>Nº Factura/Boleta (NF)</th>
                    <th>Nº NubeFact</th>
                    <th>Fecha de Emisión</th>
                    <th>Fecha de Devolución</th>    
                    <th>Cliente</th>
                    <th>RUC/DNI</th>
                    <th>Base Imponible</th>
                    <th>IGV</th>
                    <th>Total</th>
                    <th>Tipo de Moneda</th>
                    <th>Vendedor</th> 
                    <th>Estado</th>
                    <th>Cliente-extra</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                
            </table>

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
                                <legend class="text-semibold">Información de la Nota de Crédito</legend>
                                
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
                     "url": "{{ url('allNC') }}",
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
                        a.href = currentLocation+"exportNC?order="+set.order[0][0]+"&dir="+set.order[0][1]+"&search="+set.search.search+"&f_inicio="+$('#f_inicio').val()+"&f_fin="+$('#f_fin').val();
                        //console.log(settings);
                        //return settings.recordsFiltered;
                    },
                   "lengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
    ],
            "columns": [
                 { "data": "numeracion" },
                { "data": "tipo" },
                { "data": "fact" },
                { "data": "codigoNB" },
                { "data": "created_at" },
                { "data": "f_devolucion" },
                { "data": "cliente" },
                { "data": "ruc_dni" },
                { "data": "subtotal" },
                { "data": "igv" },
                { "data": "total" },
                { "data": "moneda" },
                { "data": "name" },
                { "data": "estado_doc" },
                { "data": "cliente_extra" },
                { "data": "acciones" },
               

            ],	
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":4, "type":"date-eu"},{"targets":5, "type":"date-eu"},{"targets":14, "visible":false}],
        "order": [[ 4, "desc" ], [0, "desc"]],
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
        
        });


    });


    function handleUpdateValue(element) {
        console.log(element.dataset.prev);
        $.post(currentLocation+"nota_update_codigonb",{id_nota_creditoh:element.id, value:element.value},function(data){   
            location.reload();
        });
    }

    function change_vendedor(element, value){
        console.log(element);
        $.post(currentLocation+"nota_update_vendedor",{id_nota_creditoh:element, value:value},function(data){   
            location.reload();
        });
    }
    // $('#cliente_table').on('click','#status', function(event){
    //     event.preventDefault();
    //     id_nota_creditoh=  $(this).data('id_nota_creditoh');
    //     status= $(this).data('status');
    //     var jqxhr =  $.get(currentLocation+"notacredito_state?id="+id_nota_creditoh+"&status="+status,function(status){
    //     }).done(function() {
    //         swal({
    //             title: "Cambio el estado!",
    //             text: "La Devolución ha cambiado su estado.",
    //             confirmButtonColor: "#66BB6A",
    //             type: "success"
    //         },function(){
    //             window.location.reload();
    //         });
    //     }).fail(function() {
    //         swal("Error no se ha cambiado el estado de la devolucion", "Intentelo nuevamente luego.", "error");
    //     })}
    // );  

    $('#cliente_table').on('click', '#imprimir',function(events){
        var id = $(this).data('id');
        var archivo = $(this).data('archivo');
        var direccion="info_nota_credito?id="+ id;
        if(archivo != null && archivo !=''){
            direccion= "greenter/"+archivo;
        }
        console.log(currentLocation+direccion);
        window.open(currentLocation+direccion,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });
    $('#cliente_table').on('click', '#actualizar',function(events){
        var id = $(this).data('id');
        $.post(currentLocation+"checkCDRNC",{id:id},function(data){
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
                if(obj.msg=="Error no se conectó: object(Greenter\\Model\\Response\\Error)#560 (2) {\n  [\"code\":protected]=>\n  string(5) \"00109\"\n  [\"message\":protected]=>\n  string(1) \"-\"\n}\n"){
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
    $('#cliente_table').on('click','#observacion',function(){

            id_orden_ventah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            $('#numeracion_obs').val('NC' + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(id_orden_ventah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"nota_edit_comments",{id_reg:id_orden_ventah, comments:comments},function(data){
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