@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-list position-left"></i> <span class="text-semibold">Listado de Facturas / Boletas</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/listado_caja"></i>Listado de Facturas - Boletas</a></li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="caja" target="_blank" id="nuevo_recibo">
            <i class="icon-box-add position-left"></i>
            Nueva Factura/Boleta
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

            <?php $status_cob_fc = Helper::status_cob_fc(); ?> 
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
                    <th>Nota de Pedido</th>
                    <th>Nº GuíaRemisión (NF)</th>
                    <th>Nº NubeFact</th>
                    <th>Fecha de Emision</th>
                    <th>Cliente</th>
                    <th>RUC/DNI</th>
                    <th>Base Imponible</th>
                    <th>IGV</th>
                    <th>Total</th>
                    <th>Tipo de Moneda</th>
                    <th>Vendedor</th> 
                    <th>Aceptada por SUNAT</th> 
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                
            </table>



    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Enlazar Guía/s de Remisión</h4>
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
                                        <input type="text" id="numeracion" class="form-control" disabled> 
                                    </div>

                                </div>

                                <div class="input-group">
                                    <label for="factbol_search">Buscar Guía/s de Remisión:</label>
                                    <div>
                                        <select class="selectpicker" id="guias_select" multiple="multiple" data-live-search="true" title="Busca tu/s Guía/s..."> 
                                            @foreach ($guias as $guia)
                                                <option value="{{ $guia->id_guia_remisionh }}">GR - {{$guia->numeracion}} // {{$guia->codigoNB}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_cambios">Enlazar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>

    <div id="formulario2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Factura/Boleta</h4>
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
                                        <label>Correlativo:</label>
                                        <input type="text" id="numeracion2" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="np" class="form-control" disabled> 
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_anular">Anular</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>

    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Anular Factura/Boleta</h4>
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
                                        <label>Correlativo:</label>
                                        <input type="text" id="numeracion3" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="np3" class="form-control" disabled> 
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_anular_op">Anular Operacion</button>
                                <button type="button" class="btn btn-info" id="guardar_anular_doc">Anular Documento</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>
    </div>
    </div>

    <div id="formulario4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Comunicar de Baja</h4>
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
                                        <label>Correlativo:</label>
                                        <input type="text" id="numeracion4" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Nº NubeFact:</label>
                                        <input type="text" id="np4" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>MOTIVO:</label>
                                        <input type="text" id="motivo4" class="form-control" onkeyup="this.value = this.value.toUpperCase()"> 
                                    </div>

                                </div>
                            </fieldset>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-info" id="guardar_anular_baja">Anular</button>
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
                                <legend class="text-semibold">Información de la Factura/Boleta</legend>
                                
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


<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="{{ URL::asset('/javascript/cal.js') }}" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>


<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    var vendedores = $('<select onchange="change_vendedor(this.id, this.value)">');
            vendedores.append($("<option>").attr('value',0).text('-'));
            $.get(currentLocation+"vendedores",{},function(data){
                obj = JSON.parse(data);
                $(obj.vendedores).each(function() {
                 vendedores.append($("<option>").attr('value',this.id).text(this.name +' '+this.lastname));
                });
                console.log(vendedores)
            });
    
    function handleUpdateValue(element) {
        console.log(element.dataset.prev);
        $.post(currentLocation+"caja_update_codigonb",{idcajah:element.id, value:element.value},function(data){   
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
                     "url": "{{ url('allFB') }}",
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
                        a.href = currentLocation+"exportFB?order="+set.order[0][0]+"&dir="+set.order[0][1]+"&search="+set.search.search+"&f_inicio="+$('#f_inicio').val()+"&f_fin="+$('#f_fin').val();
                        //console.log(settings);
                        //return settings.recordsFiltered;
                    },
                   "lengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
    ], "columns": [       
                 { "data": "numeracion" },
                { "data": "tipo" },
                { "data": "np" },
                { "data": "grnum" },
                { "data": "codigoNB" },
                { "data": "created_at" },
                { "data": "razon_social" },
                { "data": "ruc_dni" },
                { "data": "subtotal" },
                { "data": "igv" },
                { "data": "total" },
                { "data": "moneda" },
                { "data": "name" },
                { "data": "aceptada" },
                { "data": "status" },
                { "data": "acciones" },

            ],	
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":5, "type":"date-eu"}],
        "order": [[ 5, "desc" ], [ 0, "desc" ]],
        dom: 'lBfrtip',
        buttons: [
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var column = this;

                $( 'select', this.header() ).on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            //.search( val ? '^'+val+'$' : '', true, false )
                            .search(val)
                            .draw();
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

    function change_vendedor(element, value){
        $.post(currentLocation+"caja_update_vendedor",{idcajah:element, value:value},function(data){   
            location.reload();
        });
    }

    /*$('#cliente_table thead th').each( function () {
        var title = $(this).text();
        if (title=='Tipo') {
            $(this).html( '<select><option value="">TODOS</option><option value="Boleta">Boleta</option><option value="Factura">Factura</option></select>' );
        }
    } );*/

    $('#cliente_table').on('click','#imprimir',function(events){
        var id = $(this).data('id');
        var archivo = $(this).data('archivo');
        var direccion="info_caja?id="+ id;
        if(archivo != null && archivo !=''){
            direccion= "greenter/"+archivo;
        }
        console.log(currentLocation+direccion);
        window.open(currentLocation+direccion,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=1000,height=600");
    });

    $('#cliente_table').on('click','#actualizar',function(events){
        var id = $(this).data('id');
        $.post(currentLocation+"checkCDRFB",{id:id},function(data){
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

    var idcajah;
    $('#cliente_table').on('click','#agregar_guia',function(){

            idcajah=  $(this).data('idcajah');
            numeracion = $(this).data('numeracion');
            tipo = $(this).data('tipo');
            var id_orden_ventah = $(this).data('idordenventah');

            var serie;            
            if (tipo == 2 || tipo == '2'){
                 serie = 'FFF1';
            }else{
                 serie = 'BBB1';
            }                

            $('#idcajah').val(idcajah);
            $('#numeracion').val(serie + '-' + numeracion);

            $.get(currentLocation+"buscarGuias?query="+id_orden_ventah+"", function( data ) {
                var obj = JSON.parse(data);

                console.log(obj);                

                var string = '';

                $('#guias_select').find('option').remove();
                $('#guias_select').selectpicker('refresh');
                $.each(obj, function(index, guia) {
                    $("#guias_select").append('<option value='+guia.id_guia_remisionh+'>GR - '+guia.numeracion+' // '+guia.codigoNB+'</option>');
                });
                $("#guias_select").selectpicker("refresh");

            });

            $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
            $('#guardar_cambios').prop( "disabled", true );
            console.log(idcajah);
            var guias_select = $('#guias_select').val();

            $.post(currentLocation+"enlazar_guias",{idcajah:idcajah, guias_select:guias_select},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se enlazó correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else if(obj.mensaje === 500){
                    $('#formulario').modal('hide');
                    swal({
                        title: "Error..!",
                        text: "Ya tiene Guía/s de Remisión",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){ 
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                    return;
                }else{
                    $('#formulario').modal('hide');
                    swal({
                        title: "Error..!",
                        text: "No se puede Actualizar los documentos, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
                    return;
                }   
            });

    });


    var idcajah, anular_tipo;
    $('#cliente_table').on('click','#anular',function(){
            
            idcajah=  $(this).data('idcajah');
            numeracion2 = $(this).data('numeracion2');
            np = $(this).data('np');
            tipo = $(this).data('tipo2');
            anular_tipo = 1;

            var serie;            
            if (tipo == 2 || tipo == '2'){
                 serie = 'FFF1';
            }else{
                 serie = 'BBB1';
            }                

            $('#idcajah').val(idcajah);
            $('#numeracion2').val(serie+' - '+numeracion2);
            $('#np').val(np);

            $('#formulario2').modal('show');
    });

    $('#cliente_table').on('click','#anular2',function(){
            
            idcajah=  $(this).data('idcajah');
            numeracion2 = $(this).data('numeracion2');
            np = $(this).data('np');
            tipo = $(this).data('tipo2');
            anular_tipo = 2;

            var serie;            
            if (tipo == 2 || tipo == '2'){
                 serie = 'FFF1';
            }else{
                 serie = 'BBB1';
            }                

            $('#idcajah').val(idcajah);
            $('#numeracion2').val(serie+' - '+numeracion2);
            $('#np').val(np);

            $('#formulario2').modal('show');
    });

    $('#cliente_table').on('click','#baja',function(){
            
            idcajah=  $(this).data('idcajah');
            numeracion2 = $(this).data('numeracion2');
            np = $(this).data('np');
            tipo = $(this).data('tipo2');
            anular_tipo = 2;

            var serie;            
            if (tipo == 2 || tipo == '2'){
                 serie = 'FFF1';
            }else{
                 serie = 'BBB1';
            }                

            $('#idcajah').val(idcajah);
            $('#numeracion4').val(serie+' - '+numeracion2);
            $('#np4').val(np);

            $('#formulario4').modal('show');
    });

    $('#formulario2').on('click','#guardar_anular',function(){
        if (anular_tipo == 1) {
            console.log(idcajah);
            window.open(currentLocation+"nota_credito?id="+ idcajah,  "_blank");
        } else {
            $('#guardar_anular').prop( "disabled", true );
            $('#formulario2').modal('hide');
            $('#guardar_anular').prop( "disabled", false );

            $('#numeracion3').val($('#numeracion2').val());
            $('#np3').val($('#np').val());
            $('#formulario3').modal('show');

            /*$.post(currentLocation+"caja_estado",{idcajah:idcajah},function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    $('#formulario2').modal('hide');
                    swal({
                            title: "Bien hecho!",
                            text: "Se anuló correctamente",
                            type: "success"
                        },
                        function(){
                            window.location.reload()
                    });
                }else{
                    $('#formulario2').modal('hide');
                    swal({
                        title: "Error..!",
                        text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){
                        $('#guardar_anular').prop( "disabled", false );
                    });
                    return;
                }   
            });*/
        }

    });

    $('#formulario3').on('click','#guardar_anular_op',function(){

        $('#guardar_anular_op').prop( "disabled", true );
        $('#guardar_anular_doc').prop( "disabled", true );

        $.post(currentLocation+"caja_estado",{idcajah:idcajah, tipo_anulado:1},function(data){
            obj = JSON.parse(data);
            console.log(obj);
            console.log(obj.mensaje);
            if(obj.mensaje === 200){
                $('#formulario3').modal('hide');
                swal({
                        title: "Bien hecho!",
                        text: "Se anuló correctamente",
                        type: "success"
                    },
                    function(){
                        window.location.reload()
                });
            }else{
                $('#formulario3').modal('hide');
                swal({
                    title: "Error..!",
                    text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_anular_op').prop( "disabled", false );
                    $('#guardar_anular_doc').prop( "disabled", false );
                });
                return;
            }   
        });
    });

    $('#formulario3').on('click','#guardar_anular_doc',function(){

        $('#guardar_anular_op').prop( "disabled", true );
        $('#guardar_anular_doc').prop( "disabled", true );

        $.post(currentLocation+"caja_estado",{idcajah:idcajah, tipo_anulado:0},function(data){
            obj = JSON.parse(data);
            console.log(obj);
            console.log(obj.mensaje);
            if(obj.mensaje === 200){
                $('#formulario3').modal('hide');
                swal({
                        title: "Bien hecho!",
                        text: "Se anuló correctamente",
                        type: "success"
                    },
                    function(){
                        window.location.reload()
                });
            }else{
                $('#formulario3').modal('hide');
                swal({
                    title: "Error..!",
                    text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_anular_op').prop( "disabled", false );
                    $('#guardar_anular_doc').prop( "disabled", false );
                });
                return;
            }   
        });
    });

    $('#formulario4').on('click','#guardar_anular_baja',function(){

        $('#guardar_anular_baja').prop( "disabled", true );
        motivo = $('#motivo4').val();

        $.post(currentLocation+"de_baja",{idcajah:idcajah, motivo:motivo},function(data){
            var obj = JSON.parse(data);
            if(obj[0].created == 200){
                console.log(obj[1].id);
                setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                $('#formulario4').modal('hide');
                swal({
                    title: "Ok!",
                    text: obj[2].msg,
                    confirmButtonColor: "#66BB6A",
                    type: "success"
                },function(){
                    window.location.reload();
                });
                return;

            }else if(obj[0].created == 501){
                $('#formulario4').modal('hide');
                swal({
                    title: "Error!",
                    text: obj[0].msg,
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){ 
                    $('#guardar_anular_baja').prop( "disabled", false );
                });
                return;
            }else{
                $('#formulario4').modal('hide');
                swal({
                    title: "Error..!",
                    text: "No se puede realizar la comunicación de baja, intentalo de nuevo luego.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_anular_baja').prop( "disabled", false );
                });
                return;
            }

            /*if(obj.mensaje === 200){
                $('#formulario3').modal('hide');
                swal({
                        title: "Bien hecho!",
                        text: "Se anuló correctamente",
                        type: "success"
                    },
                    function(){
                        window.location.reload()
                });
            }else{
                $('#formulario3').modal('hide');
                swal({
                    title: "Error..!",
                    text: "No se puede Actualizar el Estado, intentalo de nuevo luego.",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    $('#guardar_anular_op').prop( "disabled", false );
                    $('#guardar_anular_doc').prop( "disabled", false );
                });
                return;
            }*/  
        });
    });

    $('#cliente_table').on('click','#observacion',function(){

            idcajah = $(this).data('id');
            numeracion = $(this).data('numeracion');
            observacion = $(this).data('observacion');

            tipo = $(this).data('tipo');

            var serie;            
            if (tipo == 2 || tipo == '2'){
                 serie = 'FFF1';
            }else{
                 serie = 'BBB1';
            }   

            $('#numeracion_obs').val(serie + ' - ' + numeracion);
            $("#observacion_obs").val(observacion);

            $('#formularioObs').modal('show');
    });

    $('#formularioObs').on('click','#guardar_cambios_obs',function(){
            $('#guardar_cambios_obs').prop( "disabled", true );
            console.log(idcajah);
            comments = $("#observacion_obs").val();

            $.post(currentLocation+"caja_edit_comments",{id_reg:idcajah, comments:comments},function(data){
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