
@extends('index')

<!-- TITULO PAGINA -->

<!--<link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"> -->

@section('titulo')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Listado de Clientes
    @if ($in ==1)
        Nuevos
    @elseif($in ==2)
        Frecuentes
    @elseif($in ==3)
        No Frecuentes
    @endif

    
    </span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/list_clientes"></i>Listado de Clientes
    @if ($in ==1)
        Nuevos
    @elseif($in ==2)
        Frecuentes
    @elseif($in ==3)
        No Frecuentes
    @endif
    

</a></li>
<link rel="stylesheet" type="text/css" href="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.css">
<!-- Include plugin -->
<script src="https://cdn.rawgit.com/wenzhixin/multiple-select/e14b36de/multiple-select.js"></script>





@stop
<!-- MENU AUXLIAR -->

@section('menu')



    <li>
        <a href="nuevo_cliente" id="nuevo_cliente">
            <i class="icon-box-add position-left"></i>
            Nuevo Cliente
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



              <!--<div class="form-inline">
                <input type="text" id="input" class="form-control" id="formGroupExampleInput" placeholder="Busca tu Cliente">
                <button id="buscar" class="btn btn-info">Buscar</button>
            </div>

          LISTA DE CLIENTES 
            <div class="text-right" >
            </div>-->

        


            <!--<div class="buttonsToHide" style="position:absolute; right:12vw;">
                Toggle column: <a class="toggle-vis" data-column="0">Name</a> - <a class="toggle-vis" data-column="1">Position</a> - <a class="toggle-vis" data-column="2">Office</a> - <a class="toggle-vis" data-column="3">Age</a> - <a class="toggle-vis" data-column="4">Start date</a> - <a class="toggle-vis" data-column="5">Salary</a>
            </div>-->
         
            <div class="form-group form-inline" >
                <div class="input-group ">
                    <label for="f_inicio">Desde:</label>
                    <input type="date" class="form-control" id="f_inicio">
                </div>
                <div class="input-group ">
                    <label for="f_fin">Hasta:</label>
                    <input type="date" class="form-control" id="f_fin">
                </div> 
                <div class="pull-right  form-group form-inline ">
                <div class="input-group buttonsToHide" id="marcas_group">
                                <label for="ms">Columnas a descargar:</label>
                                <select id="ms" multiple="multiple">                                                                  
                                        <option value="0" selected>RUC/DNI</option>
                                        <option value="1" selected>Codigo</option>
                                        <option value="2" selected>Razon Social/Nombre</option>
                                        <option value="3" selected>Nombre Comercial</option>
                                        <option value="4" selected>Contacto Telefono</option>
                                        <option value="5" selected>Direccion</option>
                                        <option value="6" selected>Distrito</option>
                                        <option value="7" selected>Provincia</option>
                                        <option value="8" selected>Vendedor</option>
                                        <option value="9" selected>Creador</option>
                                        <option value="10" selected>Creado</option>
                                        <option value="11" selected>% Datos</option>
                                        <option value="12" selected>Dias_para nueva_visita</option>                                                                        
                                </select>
            </div>
                    <div class="input-group"> 
                    <a class="btn btn-secondary" style="margin:1em 2em;border-color:black;"  id='exportar'><b  style="color: green;">Excel </b><i class="glyphicon glyphicon-save-file" style="color: green;"></i></a>
</div>
                </div>
            </div > 
            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
          
                <thead>
                <tr>
                    <!-- <th>Estado</th> -->
                    <th>RUC/DNI</th>
                    <th>Codigo</th>
                    <th>Razon Social/Nombre</th>
                    <th>Nombre Comercial</th>
                    <th>Contacto Telefono</th>
                    <th>Direccion</th>
                    <th>Distrito</th>
                    <th>Provincia</th>
                    <th>Vendedor</th>
                    <th>Creador</th>
                    <th>Creado</th>
                    <th>% Datos</th>
                    <th>Dias_para nueva_visita</th>
                    <th>Acciones</th>
                </tr>
                </thead>
               
            </table>

       

    <div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Registrar Visita/Llamada</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>

                        <div class="panel-body">
                        <div class="button-cover col-md-10">
                                <div class="button b2" id="button-10">
                                <input type="checkbox" class="checkbox" id="switch_tipo" onclick="ToggleFields()">
                                <div class="knobs">
                                    <span>VISITA</span>
                                </div>
                                <div class="layer"></div>
                                </div>
                            </div>
                            <fieldset style="z-index:100;"> 
                            <legend class="text-semibold" id="visita" >Información de la Visita</legend>
                                <legend class="text-semibold" id="llamada" style="display:none;" >Información de la LLamada</legend>
                                
                                
                                <div class="form-group form-inline">     
                                    <div class="input-group">
                                        <label>RUC / DNI:</label>
                                        <input type="text" id="ruc_dni" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Razón Social / Nombre:</label>
                                        <input type="text" id="razon_social" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group form-inline">                                                                 
                                    <div class="input-group" id="tipo_visita_group" style="display:table" >
                                        <label >Tipo:</label>
                                        <select class="form-control" id="tipo_visita"  >
                                                <option value="0">--</option>
                                                <option value="Primera Visita">Primera Visita</option>
                                                <option value="Visita Regular">Visita Regular</option>
                                                <option value="Pedido">Pedido</option>
                                                <option value="Cobranza">Cobranza</option>
                                                <option value="Entrega de Pedido">Entrega de Pedido</option>
                                                
                                        </select>
                                    </div>  
                                    
                                    <div class="input-group" id="tipo_llamada_group" style="display:none">
                                    <label >Tipo:</label>
                                        <select class="form-control" id="tipo_llamada">
                                                <option value="0">--</option>
                                                <option value="Primera Llamada">Primera Llamada</option>
                                                <option value="Llamada Regular">Llamada Regular</option>
                                                <option value="Orden de Venta">Orden de Venta</option>
                                                <option value="Primera Cobranza">Primera Cobranza</option>
                                                <option value="Cobranza Regular">Cobranza Regular</option>
                                        </select>
                                    </div>                      
                                    <div class="input-group">
                                        <label >Respuesta / Comentario:</label>
                                        <textarea type="text"  id="respuesta" class="form-control" rows="3"> </textarea>
                                    </div>                                     
                                </div> 
                            </fieldset>

                            <!--<div id="map" style="width: 100%; height: 500px;"></div>-->
                      
                        </div>
                    </div>
                </div>
            </div>

            <div id="map" style="width: 100%; height: 300px;"></div>
            <div class="modal-footer" id="submit-control">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>
            </div>
    
    </div>
    </div>
    </div>



    <div id="formulario3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Registrar Reclamo</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>

                        <div class="panel-body">

                            <fieldset>
                                <legend class="text-semibold">Información del Cliente</legend>
                                
                                <div class="form-group form-inline">
                                    <div class="input-group">
                                        <label>RUC / DNI:</label>
                                        <input type="text" id="ruc_dni_reclamo" class="form-control" disabled> 
                                    </div>

                                    <div class="input-group">
                                        <label>Razón Social / Nombre:</label>
                                        <input type="text" id="razon_social_reclamo" class="form-control" disabled> 
                                    </div>
                                </div>

                                <div class="form-group form-inline">                                                                                           
                                    <div class="input-group">
                                        <label >Reclamo / Comentario:</label>
                                        <textarea type="text"  id="reclamo" class="form-control" rows="3"> </textarea>
                                    </div>                                     
                                </div> 
                            </fieldset>
                      
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer" id="submit-control">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" id="guardar_reclamo">Guardar Cambios</button>
            </div>
    
    </div>
    </div>
    </div>

    <div id="formulario2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3">
    <div class="modal-dialog">
    <div class="modal-content">
      
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Crear Cotización / Crear Orden de Venta</h4>
            </div>

            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="panel panel-flat">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
                            <fieldset>
                                <button type="button" class="btn btn-primary" id="crear_coti">Cotización</button>
                                <button type="button" class="btn btn-warning" id="crear_orden">OrdenVenta</button>
                            </fieldset>                      
                        </div>
                    </div>
                </div>
            </div>
    
    </div>
    </div>
    </div>


    <style type="text/css">
        .hide-loader{
            display:none;
        }
        /* The switch - the box around the slider */
        .button-cover
        {
            height: 100px;
            margin: 20px;
            border-radius: 4px;
        }

        .button-cover:before
        {
            counter-increment: button-counter;
            content: counter(button-counter);
            position: absolute;
            right: 0;
            bottom: 0;
            color: #d7e3e3;
            font-size: 12px;
            line-height: 1;
            padding: 5px;
        }

        .button-cover, .knobs, .layer
        {
            border-color: black;
            border-width: 2px;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .button
        {
            border-color: black;
            border-width: 2px;
            position: relative;
            top: 50%;
            width: 150px;
            height: 36px;
            margin-top: -100px;
            margin-left:45%;            
            
        }

        .button.r, .button.r .layer
        {
            border-radius: 100px;
        }

        .button.b2
        {
            border-radius: 2px;
        }

        .checkbox
        {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
            opacity: 0;
            cursor: pointer;
            z-index: 3;
        }

        .knobs
        {
            z-index: 2;
        }

        .layer
        {
            width: 100%;
            background-color: #ebf7fc;
            transition: 0.3s ease all;
            z-index: 1;
        }

    

        /* Button 10 */
        #button-10 .knobs:before, #button-10 .knobs:after, #button-10 .knobs span
        {
            position: absolute;
            width: 70px;
            height: 36px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            line-height: 1;
            padding: 9px 4px 9px 4px;
            border-radius: 2px;

            transition: 0.3s ease all;
        }

        #button-10 .knobs:before
        {
            content: '';
            left: 4px;
            background-color: #03A9F4;
        }

        #button-10 .knobs:after
        {
            content: 'LLAMADA';
            right: 4px;
            color: #4e4e4e;
        }

        #button-10 .knobs span
        {
            display: inline-block;
            left: 4px;
            color: #fff;
            z-index: 1;
        }

        #button-10 .checkbox:checked + .knobs span
        {
            color: #4e4e4e;
        }

        #button-10 .checkbox:checked + .knobs:before
        {
            left: 75px;
            background-color: #F44336;
        }

        #button-10 .checkbox:checked + .knobs:after
        {
            color: #fff;
        }

        #button-10 .checkbox:checked ~ .layer
        {
            background-color: #fcebeb;
        }


    </style>

<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.print.min.js"></script>

    


<script type="application/javascript" rel="script">

function ToggleFields() {
    
    // Get the checkbox
    var checkBox = document.getElementById("switch_tipo");
    // Get the output text
    var visita = document.getElementById("visita");
    var llamada = document.getElementById("llamada");  


    var tipo_visita=document.getElementById("tipo_visita_group");
    var tipo_llamada=document.getElementById("tipo_llamada_group");
    



    if (checkBox.checked == true){
        visita.style.display = "none";
        llamada.style.display = "table";
        
        tipo_visita.style.display = "none";
        tipo_llamada.style.display = "table";

        

        //marcas.style.display = "none";



    } else {
        llamada.style.display = "none";
        visita.style.display = "table";

        
        tipo_llamada.style.display = "none";
        tipo_visita.style.display = "table";
        //marcas.style.display = "block";


    }
    }



var cols=[];
$(function() {
        $('#ms').change(function() {
            //console.log($(this).val());
            var colsexcel = ($('#ms').val())
            if(colsexcel){
                cols=colsexcel.map(function(item) {
                return parseInt(item, 10);
                });
            }
            else{
                cols=[];
            }
            //console.log(cols);
            for(var i=0; i < 13; i++ ){
                var clase= i.toString();
                var elements = document.getElementsByClassName(clase);
                if(cols.includes(i)){
                    for( var e=0; e <elements.length; e++){
                        if( elements[e].classList.contains("notForPrint"))                    
                            elements[e].classList.remove("notForPrint");
                    }
                }
                else{
                    for( var e=0; e <elements.length; e++){  
                        if( !elements[e].classList.contains("notForPrint"))                                     
                            elements[e].classList.add("notForPrint");
                    }
                }
            }

        }).multipleSelect({
            width: '100%'
        });
    });

    


   // $(".dt-buttons").before( $( "#columnas_excel" ) );
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
    $( document ).ready(function() {

    var set;
    console.log( "ready!" );
    table = $('#cliente_table').DataTable( {
        "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ url('allCliente') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":
                        function(dtParms){
                            dtParms.f_inicio = $('#f_inicio').val();
                            dtParms.f_fin = $('#f_fin').val();
                            <?php
                                if(!$in){
                                    $in=0;  
                                } 
                            ?>
                            dtParms.i ={{$in}};
                            
                            return dtParms;
                        },
                        _token: "{{csrf_token()}}"
                    },
                    "rowCallback": function( row, data, index ) {
                        console.log(data.dias_visita)
                        if ( parseInt(data.dias_visita) == 0) {
                                $("td:eq(0)", row).css("background-color",'#87ceff');
                        }
                        else if( parseInt(data.dias_visita) < 0){
                            $("td:eq(0)", row).css("background-color",'#fd7940');
                            data.dias_visita=0;
                        }
                        else{
                            $("td:eq(0)", row).css("background-color",'#aa9d87');

                        }
                        
                    },
                   "stateSave": true,
                    "stateSaveParams": function (settings, data) { 
                        // Here the response
                        set= data;
                        var a = document.getElementById('exportar');
                        var colsexcel = ($('#ms').val())
                        if(colsexcel){
                            cols=colsexcel.map(function(item) {
                            return parseInt(item, 10);
                            });
                        }
                        else{
                            cols=[];
                        }
                        var json_cols = JSON.stringify(cols);
                        console.log(json_cols);

                        a.href = currentLocation+"exportCliente?order="+set.order[0][0]+"&dir="+set.order[0][1]+"&search="+set.search.search+"&f_inicio="+$('#f_inicio').val()+"&f_fin="+$('#f_fin').val()+"&i="+{{$in}}+"&cols="+json_cols;
                        //console.log(settings);
                        //return settings.recordsFiltered;
                    },
                   "lengthMenu": [
        [10, 25, 50, 100],
        [10, 25, 50, 100]
    ],
            "columns": [
                { "data": "ruc_dni" },
                 { "data": "codigo" },
                { "data": "razon_social" },
                { "data": "nombre_comercial" },
                { "data": "contacto_telefono" },
                { "data": "direccion" },
                { "data": "distrito" },
                { "data": "provincia" },
                { "data": "vendedor" },
                { "data": "name" },
                { "data": "created_at" },
                { "data": "porcentaje" },
                { "data": "dias_visita" },
                { "data": "acciones" },

            ],	
        "autoWidth": true,
        "ordering": true,
        "paging": true,
        "searching": true,
        "order": [[11, "asc" ]],
        dom: 'lBfrtip',
        buttons: [           
                            
        ]
        
            
        
    } );
    table.search('');            
    table.ajax.reload(null, true);
    $(window).bind('beforeunload', function(){
        if(table.page.info().page >0){
        }
    });
   

   /* ocultar=document.querySelectorAll(".buttonsToHide");

    for(i=0; i<ocultar.length; ++i) {
            ocultar[i].style.display = 'none';
    }

    for(i=0; i<ocultar.length; ++i) {
            ocultar[i].style.display = 'block';
    }*/

    //('.buttonsToHide').addClass('hidden');
    $('#f_inicio').on('change',function(){ 
        table.draw();
        console.log($(this).val());
        console.log($('#f_fin').val())
     });

     $('#f_fin').on('change',function(){ 
        table.draw();
        console.log($(this).val())
     });

     $('#ms').on('change', function(){
        table.draw();

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

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );

    $('#cliente_table').on('click','#status', function(event){
        event.preventDefault();
        idcliente=  $(this).data('idcliente');
        status= $(this).data('status');
        var jqxhr =  $.get(currentLocation+"cliente_state?id="+idcliente+"&status="+status,function(status){
        }).done(function() {
            swal({
                title: "Cambio el estado!",
                text: "El Cliente ha cambiado su estado.",
                confirmButtonColor: "#66BB6A",
                type: "success"
            },function(){
                window.location.reload();
            });
        }).fail(function() {
            swal("Error no se ha cambiado el estado del cliente", "Intentelo nuevamente luego.", "error");
        })}
    );  

    $('#cliente_table').on('click','#editar',function(events){
        var idcliente = $(this).data('idcliente');
        window.open(currentLocation+"editar_cliente?idcliente="+idcliente, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");
    });
    
    $('#cliente_table').on('click','#eliminar',function(events){
        var idcliente = $(this).data('idcliente');

        swal({
            title: "Estas seguro?",
            text: "No podras recuperar este cliente si lo eliminas!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        
        function(isConfirm){
            if (isConfirm) {
                var jqxhr =  $.get(currentLocation+"eliminar_cliente?idcliente="+idcliente, function(data,status){})
                .done(function() {
                    swal({
                        title: "Eliminado!",
                        text: "El cliente ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    }
                    ,function(){
                        window.location.reload();
                    });
                }).fail(function() {
                            swal("Error al eliminar al cliente", "Intentelo nuevamente luego.", "error");
                        });
            }
            else {
                swal({
                    title: "Cancelado",
                    text: "No se ha eliminado nada.",
                    confirmButtonColor: "#2196F3",
                            type: "error"
                        });
                    }
                });
        });


        // $(document).ready(function(){
        //     $("#input").on("keyup", function() {
        //         var value = $(this).val().toLowerCase();
        //         $("#cliente_table tr").filter(function() {
        //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        //         });
        //     });
        // });

        $("#input").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#buscar").click();
            }
        });

        
        $("#buscar").on('click',function(){
            var query = $("#input").val();
            var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
            console.log('click');
            window.location.href = currentLocation+"list_clientes"+"?query="+query;
        });

    </script>

    <script type="text/javascript">
     var map;
        var selectedLatLng;
        var marker;

        function errorCallback(error) {
            //const info = document.getElementById('info');
            //info.innerHTML = error.message;
            //info.style.display = '';
        }

        function showPosition(position) {
                
            map = L.map('map', {
            center: [position.coords.latitude, position.coords.longitude],
            zoom: 13               
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);
            marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            selectedLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};           
        }

        if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition,errorCallback,{maximumAge:0, timeout:5000, enableHighAccuracy:true});
            } 
        else {
                
            }  

       
        var idcliente;
        var ruc_dni;
        var razon_social; 

        function a(position){
            setTimeout(function() {
                    map.invalidateSize();
                }, 1000);          
            

                if(!map){
                    showPosition(position);

                }

                map.on('click', function(e) {
                    marker.setLatLng(e.latlng);
                    selectedLatLng.lat = e.latlng.lat;
                    selectedLatLng.lng = e.latlng.lng;
                    
                });

                

                $('#formulario').modal('show');
        } 

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    swal({
                    title: "Upss!",
                    text: "Tienes que activar la Geolocalización para poder crear una visita/llamada",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                    },function(){
                    //window.location.reload();
                });
                return;
                break;
                case error.POSITION_UNAVAILABLE:
                    swal({
                    title: "Upss!",
                    text: "La información no está disponible",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                    },function(){
                    //window.location.reload();
                });
                return;
                break;
                case error.TIMEOUT:
                    swal({
                    title: "Upss!",
                    text: "El tiempo de espera se agotó, intentelo nuevamente",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                    },function(){
                    //window.location.reload();
                });
                return;
                break;
                case error.UNKNOWN_ERROR:
                    swal({
                    title: "Upss!",
                    text: "Un error desconocido ocurrió",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                    },function(){
                    //window.location.reload();
                });
                return;
                break;
            }
        }
        
        $('#cliente_table').on('click','#llamar',function(){

            idcliente=  $(this).data('idcliente');
            ruc_dni = $(this).data('ruc_dni');
            razon_social = $(this).data('razon_social');

            $('#idcliente').val(idcliente);
            $('#ruc_dni').val(ruc_dni);
            $('#razon_social').val(razon_social);
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(a,showError);

                

            }
            else{

                swal({
                    title: "Upss!",
                    text: "La Geolocalización no esta soportada por este navegador",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;

            }
        });

        $('#formulario').on('click','#guardar_cambios',function(){
            console.log(selectedLatLng.lat)
            console.log(selectedLatLng.lng)
            var checkBox = document.getElementById("switch_tipo");
            var tipo=0;
            if(checkBox.checked==true){
                tipo_llamada = $('#tipo_llamada').val();
                tipo=1;
            }
            else{
                tipo_llamada = $('#tipo_visita').val();
            }
            respuesta = $('#respuesta').val();

            if(tipo_llamada == 0){
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar Tipo",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            if(respuesta.length == 0){
                $('#formulario').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar Respuesta/Comentario!",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }           

            arrayPost = {idcliente:idcliente, tipo_llamada:tipo_llamada,tipo:tipo, respuesta:respuesta, latitud:selectedLatLng.lat, longitud:selectedLatLng.lng};
            console.log(arrayPost);
            
            $.post(currentLocation+"llamada",arrayPost,function(data){
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
                        text: "No se puede guardar el cambio, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){

                    });
                    return;
                }   
            });

        });

    </script>

    <script type="text/javascript">
        var idcliente_reclamo;
        var ruc_dni_reclamo;
        var razon_social_reclamo;
        $('#cliente_table').on('click','#reclamar',function(){

            idcliente_reclamo=  $(this).data('idcliente');
            ruc_dni_reclamo = $(this).data('ruc_dni');
            razon_social_reclamo = $(this).data('razon_social');

            $('#idcliente').val(idcliente_reclamo);
            $('#ruc_dni_reclamo').val(ruc_dni_reclamo);
            $('#razon_social_reclamo').val(razon_social_reclamo);

            $('#formulario3').modal('show');
        });

        $('#formulario3').on('click','#guardar_reclamo',function(){
            reclamo = $('#reclamo').val();

            if(reclamo.length == 0){
                $('#formulario3').modal('hide');
                swal({
                    title: "Upss!",
                    text: "Debes agregar Reclamo/Comentario!",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }           

            arrayPost = {idcliente_reclamo:idcliente_reclamo, reclamo:reclamo};
            console.log(arrayPost);
            
            $.post(currentLocation+"reclamo",arrayPost,function(data){
                obj = JSON.parse(data);
                console.log(obj);
                console.log(obj.mensaje);
                if(obj.mensaje === 200){
                    setInterval(function(){ $('#submit-control').html("Tranquil@ con los clicks!") },1);
                    $('#formulario3').modal('hide');
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
                        text: "No se puede guardar el cambio, intentalo de nuevo luego.",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){

                    });
                    return;
                }   
            });

        });

    </script>

    <script type="text/javascript">
        var idcliente;
        $('#cliente_table').on('click','#agregar',function(){
            idcliente=  $(this).data('idcliente');
            $('#formulario2').modal('show');
        });

        $('#formulario2').on('click','#crear_coti',function(){
            $('#formulario2').modal('hide');
            window.open(currentLocation+"cotizacion?idcliente="+idcliente, "_blank");
        });

        $('#formulario2').on('click','#crear_orden',function(){
            $('#formulario2').modal('hide');
            window.open(currentLocation+"orden_venta?idcliente="+idcliente, "_blank");
        });

    </script>

    
@stop