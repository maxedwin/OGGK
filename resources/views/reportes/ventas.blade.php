@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="glyphicon glyphicon-signal position-left"></i><span class="text-semibold">Reportes de Ventas</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">Reportes de Ventas</li>
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


    <div class="container">
        
        <div class="row">
            <div class="col-md-6"> 
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Ventas Totales x Fecha </div>
                            <div class="panel-body">
                                De:     <input id="fecha_ini" type="date" > 
                                Hasta:  <input id="fecha_fin" type="date" > 
                                <button id="ventasxdia" class="btn btn-info"> Graficar </button>
                                <canvas id="canvas" height="280" width="600"></canvas>
                            </div>
                    </div>
            </div>

            <div class="col-md-6"> 
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Ventas Totales x Mes </div>
                            <div class="panel-body">
                                <canvas id="ventasxmes" height="280" width="600"></canvas>
                            </div>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">                     
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Cotizaciones x Ordenes de Venta</div>
                            <div class="panel-body">
                                <canvas id="canvas2" height="280" width="600"></canvas>
                            </div>
                    </div>
            </div>

            <div class="col-md-6"> 
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Ventas Totales x Vendedor </div>
                            <div class="panel-body">
                                De:     <input id="fecha_ini2" type="date" > 
                                Hasta:  <input id="fecha_fin2" type="date" > 
                                <button id="ventasxvendedor" class="btn btn-info"> Graficar </button>
                                <canvas id="canvas3" height="280" width="600"></canvas>
                            </div>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">         
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Ranking Clientes (10) </div>
                            <div class="panel-body">
                                De:     <input id="fecha_ini3" type="date" > 
                                Hasta:  <input id="fecha_fin3" type="date" > 
                                <button id="rankingclientes" class="btn btn-info"> Graficar </button>
                                <canvas id="canvas4" height="280" width="600"></canvas>
                            </div>
                    </div>
            </div>

            <div class="col-md-6">    
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Ventas Totales x Cliente x Mes</div>
                        <div class="panel-body">
                            Cliente:
                                <select id="idcliente" style="width: 250px;">
                                        <option value=0> -- </option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{$cliente->idcliente}}">{{$cliente->razon_social}}</option>
                                    @endforeach
                                </select>
                            <button id="ventasxclientexmes" class="btn btn-info"> Graficar </button>
                            <canvas id="canvas5" height="280" width="600"></canvas>
                        </div>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">    
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Visitas / Llamadas </div>
                            <div class="panel-body">
                                <div class="form-group form-inline">
                                <div class="input-group ">
                                    <label>De:</label><input id="fecha_ini4" type="date" class="form-control"> 
                                </div>
                            
                                <div class="input-group ">
                                    <label>Hasta:</label><input id="fecha_fin4" type="date" class="form-control"> 
                                </div>
                                <div class="input-group ">
                                <label>Tipo:</label>
                                <select class="form-control" id="idvisita">
                                        <option value=0> Visita </option>
                                        <option value=1> Llamada </option>
                                </select>
                                </div>
                                <button id="visitasxvendedor" class="btn btn-info"> Graficar </button>
                                <canvas id="canvas6" height="280" width="600"></canvas>
                                </div>
                            </div>
                    </div>
            </div>
                    
            <div class="col-md-6">    
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Visitas / Llamadas Detalladas </div>
                            
                            <div class="form-group form-inline">
                                <div class="input-group ">
                                    <label>De:</label><input id="fecha_ini5" type="date" class="form-control"> 
                                </div>
                            
                                <div class="input-group ">
                                    <label>Hasta:</label><input id="fecha_fin5" type="date" class="form-control"> 
                                </div>
                                
                                <div class="input-group ">
                                <label>Tipo:</label>
                                <select class="form-control" id="idvisita2">
                                        <option value=0> Visita </option>
                                        <option value=1> Llamada </option>
                                </select>
                                </div>

                                <div class="input-group ">
                                    <label>Vendedor:</label>
                                    <select id="idvendedor" class="form-control">
                                            <option value=0> -- </option>
                                        @foreach ($vendedores as $vendedor)
                                            <option value="{{$vendedor->id}}">{{$vendedor->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button id="visitasdetalladasxvendedorbutton" class="btn btn-info"> Mostrar </button>
                            </div>
                            
                            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="visitasdetalladasxvendedor">
                                <thead>
                                   <tr>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Motivo</th>
                                        <th>Respuesta</th>
                                        <th>Nota de Pedido</th>                                        
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
            </div>
        </div>

    </div>

        <div class="panel panel-flat hide">
            <div class="panel-heading text-semibold"> Ordenes de Venta Detallado </div>
                <div class="panel-body">
                    <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="ordenesventas">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Día</th>
                                <th>Vendedor</th>
                                <th>Distrito</th>
                                <th>NP</th>
                                <th>RUC/DNI</th>
                                <th>Razón Social</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Moneda</th>
                                <th>Precio Unit.</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="venci_table">
                            @foreach ($ordenesventas as $ov)
                                <tr id="tr_detalle">                                            
                                    <td>{{ date('d/m/Y', strtotime($ov->fecha))       }} </td>
                                    <td>{{ $ov->dia         }} </td>
                                    <td>{{ $ov->vendedor    }} </td>
                                    <td>{{ $ov->distrito    }} </td>
                                    <td>{{ $ov->np          }} </td>
                                    <td>{{ $ov->ruc_dni     }} </td>
                                    <td>{{ $ov->razon_social}} </td>
                                    <td>{{ $ov->producto    }} </td>
                                    <td>{{ $ov->cantidad    }} </td>
                                    <?PHP if     ($ov->moneda == 1) {
                                        echo '<td> Soles   </td>';
                                        } elseif ($ov->moneda == 2) {
                                        echo '<td> Dólares </td>';
                                        } else   {
                                        echo '<td> Euros   </td>';
                                    }?>
                                    <td>{{ $ov->precio_unit }} </td>
                                    <?PHP  if($ov->estado_doc == 0) {
                                        echo '<td> Pendiente </td>';
                                        }  elseif($ov->estado_doc == 1) {
                                        echo '<td> Facturada </td>';
                                        }  else {
                                        echo '<td> Anulada   </td>';
                                        }?>                                        
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>     

        <div class="panel panel-default hide">
                        <div class="panel-heading text-semibold"> Reporte de Utilidad </div>
                            
                            <div class="form-group form-inline">
                                <div class="input-group ">
                                    <label>De:</label><input id="fecha_ini5" type="date" class="form-control"> 
                                </div>
                            
                                <div class="input-group ">
                                    <label>Hasta:</label><input id="fecha_fin5" type="date" class="form-control"> 
                                </div>
                                
                                <div class="input-group">
                                    <label>Tipo de cambio:</label>
                                    <input type="number" class="form-control" id="tipo_cambio">
                                </div>

                                <div class="input-group ">
                                    <button id="utilidad_boton" class="btn btn-info"> Mostrar </button>
                                </div>
                            </div>
                            
                            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="utilidad_tabla">
                                <thead>
                                   <tr>
                                        <!--<th>Nº Interno</th>-->
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad Vendida</th>
                                        <th>Costo</th>
                                        <th>Precio Promedio</th>
                                        <th>Promedio Total</th>
                                        <th>Utilidad</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <!--<td></td>-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                </tbody>
                            </table>
                    </div>

                    <div class="panel panel-default hide">
                        <div class="panel-heading text-semibold"> Kardex </div>
                            
                            <div class="form-group form-inline">
                                <div class="input-group ">
                                    <label>De:</label><input id="fecha_ini6" type="date" class="form-control"> 
                                </div>
                            
                                <div class="input-group ">
                                    <label>Hasta:</label><input id="fecha_fin6" type="date" class="form-control"> 
                                </div>
                                
                                <div class="input-group hide">
                                    <label>Tipo de cambio:</label>
                                    <input type="number" class="form-control" id="tipo_cambio">
                                </div>

                                <div class="input-group ">
                                    <button id="kardex_boton" class="btn btn-info"> Mostrar </button>
                                </div>
                            </div>
                            
                            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="kardex_tabla">
                                <thead>
                                   <tr>
                                        <th>Fecha</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Cantidad Usada (M2)</th>
                                        <th>Cantidad Usada (CAJA)</th>
                                        <th>Stock Total (M2)</th>
                                        <th>Stock Total (CAJA)</th>
                                        <th>Tipo de Movimiento</th>
                                        <th>Documento Usado</th>
                                        <th>Almacen Usado (Salida/Entrada)</th>
                                        <th>Almacen Origen (Mov. Stock)</th>
                                        <th>Almacen Destino (Mov. Stock)</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                </tbody>
                            </table>
                    </div>        


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    
    <script type="text/javascript">
        $('#ordenesventas').dataTable( {
            autoWidth: true,
            searching: false,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel'
            ]
        });
    </script>

    <script>
        var myChart;
        $("#ventasxdia").click(function(){
            var fecha_ini = $("#fecha_ini").val();
            var fecha_fin = $("#fecha_fin").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin};

            $.get( "{{route('ventasxdia')}}" ,data,function(response){
                if(myChart!=null){
                    myChart.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);

                var ctx = document.getElementById("canvas").getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: jsObj.days,
                        datasets: [{
                            label: 'Ventas Totales x Fecha',
                            data: jsObj.viewer,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });  

            });
        });
    </script>

    <script>
        var myChart2;
        $.get( "{{route('ventasxmes')}}", function(response){
            var jsObj = JSON.parse(response);
            console.log(jsObj);

            var ctx = document.getElementById("ventasxmes").getContext('2d');
            myChart2 = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: jsObj.months,
                    datasets: [{
                        label: 'Ventas Totales x Mes',
                        data: jsObj.viewer,
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });  
        });
    </script>

    <script>
        var myChart3;
        $.get( "{{route('cotisxventas')}}", function(response){
            var jsObj = JSON.parse(response);
            console.log(jsObj);

            var ctx = document.getElementById("canvas2").getContext('2d');
            myChart3 = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: jsObj.months,
                    datasets: [{
                        label: 'Cotizaciones',
                        backgroundColor: "#3e95cd",
                        data: jsObj.cotis,

                    },{
                        label: 'Ordenes de Venta',
                        data: jsObj.viewer,
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });  
        });
    </script>

    <script>
        var myChart4;
        $("#ventasxvendedor").click(function(){
            var fecha_ini = $("#fecha_ini2").val();
            var fecha_fin = $("#fecha_fin2").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin};

            $.get( "{{route('ventasxvendedor')}}" ,data,function(response){
                if(myChart4!=null){
                    myChart4.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);

                var ctx = document.getElementById("canvas3").getContext('2d');
                myChart4 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: jsObj.vendedor,
                        datasets: [{
                            label: 'Ventas Totales x Vendedor',
                            data: jsObj.viewer,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });  

            });
        });
    </script>

    <script>
        var myChart5;
        $("#rankingclientes").click(function(){
            var fecha_ini = $("#fecha_ini3").val();
            var fecha_fin = $("#fecha_fin3").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin};

            $.get( "{{route('rankingclientes')}}" ,data,function(response){
                if(myChart5!=null){
                    myChart5.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);
            
                var ctx = document.getElementById("canvas4").getContext('2d');
                myChart5 = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: jsObj.cliente,
                        datasets: [{
                            label: 'Ranking Clientes',
                            data: jsObj.viewer,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });  
            });
        });
    </script>

    <script>
        $('#idcliente').select2();

        var myChart6;
        $("#ventasxclientexmes").click(function(){

            var cliente = $("#idcliente").val();
            var data = {cliente:cliente};
            
            $.get( "{{route('ventasxclientexmes')}}", data, function(response){
                if(myChart6!=null){
                    myChart6.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log('este es');
                console.log(jsObj);

                var ctx = document.getElementById("canvas5").getContext('2d');
                myChart6 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: jsObj.months,
                        datasets: [{
                            label: 'Ventas Totales x Cliente x Mes',
                            data: jsObj.viewer,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });  
            });
        });
    </script>

    <script>
        var myChart7;
        $("#visitasxvendedor").click(function(){
            var fecha_ini = $("#fecha_ini4").val();
            var fecha_fin = $("#fecha_fin4").val();
            var idvisita = $("#idvisita").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin, tipo:idvisita};

            $.get( "{{route('visitasxvendedor')}}" ,data,function(response){
                if(myChart7!=null){
                    myChart7.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);
            
                var ctx = document.getElementById("canvas6").getContext('2d');
                myChart7 = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: jsObj.vendedor,
                        datasets: [{
                            label: 'Visitas x Vendedor',
                            data: jsObj.viewer,
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });  
            });
        });
    </script>


    <script>
        $("#visitasdetalladasxvendedorbutton").click(function(){
            console.log('clickk');
            var fecha_ini = $("#fecha_ini5").val();
            var fecha_fin = $("#fecha_fin5").val(); 
            var vendedor = $("#idvendedor").val(); 
            var idvisita = $("#idvisita2").val();
            
            $('#visitasdetalladasxvendedor').dataTable().fnDestroy();

            $.ajax({
                url: "{{ route('visitasdetalladasxvendedor') }}", 
                data:{ fecha_ini:fecha_ini, fecha_fin:fecha_fin, vendedor:vendedor, tipo:idvisita},
                success : function(data) {
                    var o = JSON.parse(data);
                    console.log(o);
                    $('#visitasdetalladasxvendedor').dataTable( {
                        autoWidth: true,
                        searching: false,
                        data : o.viewer,
                        columns: [
                            {"data" : "rs"},
                            {"data" : "fecha"},
                            {"data" : "motivo"},
                            {"data" : "respuesta"},
                            {"data" : "orden_venta"}
                        ],
                    });
                }       
            });
        });

        $("#utilidad_boton").click(function(){
            console.log('clickk');
            var fecha_ini = $("#fecha_ini5").val();
            var fecha_fin = $("#fecha_fin5").val(); 
            var tipo_cambio = $("#tipo_cambio").val(); 
            
            $('#utilidad_tabla').dataTable().fnDestroy();
            //$('#kardex_tabla').dataTable().fnDestroy();

            $.ajax({
                url: "{{ route('utilidad') }}", 
                data:{ fecha_ini:fecha_ini, fecha_fin:fecha_fin, tipo_cambio:tipo_cambio},
                success : function(data) {
                    var o = JSON.parse(data);
                    console.log(o);
                    $('#utilidad_tabla').dataTable( {
                        autoWidth: true,
                        searching: false,
                        ordering: false,
                        paging: false,
                        data : o.viewer,
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel'
                        ],
                        columns: [
                            //{"data" : "ocid"},
                            {"data" : "barcode"},
                            {"data" : "nombre"},
                            {"data" : "cacanti"},
                            {"data" : "occosto"},
                            {"data" : "caprecio"},
                            {"data" : "promedio"},
                            {"data" : "utilidad"}            
                        ],
                    });
                }       
            });
        });

        $("#kardex_boton").click(function(){
            console.log('clickk');
            var fecha_ini = $("#fecha_ini6").val();
            var fecha_fin = $("#fecha_fin6").val(); 
            //var tipo_cambio = $("#tipo_cambio").val(); 
            
            $('#kardex_tabla').dataTable().fnDestroy();
            //$('#utilidad_tabla').dataTable().fnDestroy();

            $.ajax({
                url: "{{ route('kardex') }}", 
                data:{ fecha_ini:fecha_ini, fecha_fin:fecha_fin},
                success : function(data) {
                    var o = JSON.parse(data);
                    console.log(o);
                    $('#kardex_tabla').dataTable( {
                        autoWidth: true,
                        searching: false,
                        ordering: false,
                        paging: false,
                        data : o.viewer,
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel'
                        ],
                        columns: [
                            {"data" : "fecha"},
                            {"data" : "barcode"},
                            {"data" : "nombre"},
                            {"data" : "canti_m2"},
                            {"data" : "canti_caja"},
                            {"data" : "stock_m2"},
                            {"data" : "stock_caja"},
                            {"data" : "tipo_mov"},
                            {"data" : "doc_usado"},
                            {"data" : "alm_usado"},
                            {"data" : "alm_orig"},
                            {"data" : "alm_dest"}           
                        ],
                    });
                }       
            });
        });
        
    </script>
    

@stop
