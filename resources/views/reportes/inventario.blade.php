@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="glyphicon glyphicon-signal position-left"></i><span class="text-semibold">Reportes de Inventario</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">Reportes de Inventario</li>
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
                        <div class="panel-heading text-semibold"> Cantidad Productos Vendidos x Fecha </div>
                        <div class="panel-body">
                            De:     <input id="fecha_ini" type="date" > 
                            Hasta:  <input id="fecha_fin" type="date" > 
                            <br>Categoria:  
                                <select id="idcategoria">
                                        <option value=0> -- </option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{$cat->idcategoria}}">{{$cat->descripcion}}</option>
                                    @endforeach
                                </select>
                            <button id="productosxfechas" class="btn btn-info"> Graficar </button>
                            <canvas id="canvas" height="280" width="600"></canvas>
                        </div>
                    </div>
            </div>

            <div class="col-md-6">      
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Productos x Vencerse </div>
                            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="venci_table">
                                <thead>
                                   <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Lote</th>
                                        <th>Fecha de Venc.</th>
                                    </tr>
                                </thead>
                                <tbody id="venci_table">
                                @foreach ($prods_venc as $prod)
                                    <tr id="tr_detalle">
                                        
                                        <td>{{ $prod->barcode }} </td>
                                        <td>{{ $prod->nombre  }} </td>
                                        <td>{{ $prod->lote    }} </td>
                                        <td>{{ $prod->fecha   }} </td>
                                        
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">    
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Cantidad Productos Vendidos x Vendedor </div>
                        <div class="panel-body">
                            De:     <input id="fecha_ini2" type="date" > 
                            Hasta:  <input id="fecha_fin2" type="date" > 
                            Vendedor:
                                <select id="idvendedor">
                                        <option value=0> -- </option>
                                    @foreach ($vendedores as $vendedor)
                                        <option value="{{$vendedor->id}}">{{$vendedor->name}}</option>
                                    @endforeach
                                </select>
                            <button id="productosxvendedor" class="btn btn-info"> Graficar </button>
                            <canvas id="canvas2" height="280" width="600"></canvas>
                        </div>
                    </div>
            </div>

            <div class="col-md-6">    
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Productos No Rotados </div>
                        <button id="productosnorotados" class="btn btn-info"> Mostrar </button>
                            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="norotados_table">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">    
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Cantidad Productos Vendidos x Cliente </div>
                        <div class="panel-body">
                            De:     <input id="fecha_ini3" type="date" > 
                            Hasta:  <input id="fecha_fin3" type="date" > 
                            <br>Cliente:
                                <select id="idcliente" style="width: 250px;">
                                        <option value=0> -- </option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{$cliente->idcliente}}">{{$cliente->razon_social}}</option>
                                    @endforeach
                                </select>
                            <button id="productosxcliente" class="btn btn-info"> Graficar </button>
                            <canvas id="canvas3" height="280" width="600"></canvas>
                        </div>
                    </div>
            </div>
        </div>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $('#idcategoria').select2();
        var myChart;
        $("#productosxfechas").click(function(){
            var fecha_ini = $("#fecha_ini").val();
            var fecha_fin = $("#fecha_fin").val();
            var categoria  = $("#idcategoria").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin, categoria:categoria};

            $.get( "{{route('productosxfechas')}}" ,data,function(response){
                if(myChart!=null){
                    myChart.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);

                var ctx = document.getElementById("canvas").getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: jsObj.prods,
                        datasets: [{
                            label: 'Cantidad Productos Vendidos x Fecha',
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
        table = $('#venci_table').DataTable( {
            "autoWidth": true,
            "paging": true,
            "searching": true,
            dom: 'lBfrtip',
            buttons: [
                'excel'
            ]
        });
    </script>

    <script>
        var myChart2;
        $("#productosxvendedor").click(function(){
            var fecha_ini = $("#fecha_ini2").val();
            var fecha_fin = $("#fecha_fin2").val();
            var vendedor  = $("#idvendedor").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin, vendedor:vendedor};

            $.get( "{{route('productosxvendedor')}}" ,data,function(response){
                if(myChart2!=null){
                    myChart2.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);

                var ctx = document.getElementById("canvas2").getContext('2d');
                myChart2 = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: jsObj.prods,
                        datasets: [{
                            label: 'Cantidad Productos Vendidos x Vendedor',
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
        $("#productosnorotados").click(function(){
            console.log('clickk');
            //var fecha_ini = $("#fecha_ini3").val();
            //var fecha_fin = $("#fecha_fin3").val(); 
            
           $.ajax({
                url: "{{ route('productosnorotados') }}", 
                //data:{ fecha_ini:fecha_ini, fecha_fin:fecha_fin},
                success : function(data) {
                    var o = JSON.parse(data);
                    console.log(o);
                    $('#norotados_table').dataTable( {
                        autoWidth: true,
                        searching: true,
                        data : o.viewer,
                        dom: 'lBfrtip',
                        buttons: [
                            'excel'
                        ],
                        columns: [
                            {"data" : "barcode"},
                            {"data" : "nombre"},
                            {"data" : "stock_total"}            
                        ],
                    });
                }       
            });
        });
    </script>

    <script>
        $('#idcliente').select2();
        
        var myChart3;
        $("#productosxcliente").click(function(){
            var fecha_ini = $("#fecha_ini3").val();
            var fecha_fin = $("#fecha_fin3").val();
            var cliente  = $("#idcliente").val();

            var data = {fecha_ini:fecha_ini, fecha_fin:fecha_fin, cliente:cliente};

            $.get( "{{route('productosxcliente')}}" ,data,function(response){
                if(myChart3!=null){
                    myChart3.destroy();
                }
                var jsObj = JSON.parse(response);
                console.log(jsObj);

                var ctx = document.getElementById("canvas3").getContext('2d');
                myChart3 = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: jsObj.prods,
                        datasets: [{
                            label: 'Cantidad Productos Vendidos x Cliente',
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
@stop
