@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="icon-home4 position-left"></i><span class="text-semibold">Página Principal</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">Panel de Control</li>
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

    @foreach($pending_guias as $guia)
    <div class="modal fade" id="{{$guia->numeracion}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">{{$guia->razon_social}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    </tr>
                    </thead>
                @foreach($guia->productos as $producto)
                <tr>
                <td>{{$producto->nombre}}</td>
                <td>{{$producto->cantidad}}</td>
                </tr>
                @endforeach
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
    @endforeach



            <div class="row">
                <div class="col-md-6"> 
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Entregas pendientes </div>

                        <div class="d-flex flex-wrap justify-content-around card-body">
                            <table class="table" id="entregas">
                              <thead>
                                <tr>
                                  <th scope="col">Correlativo</th>
                                  <th scope="col">N guía de Remision</th>
                                  <th scope="col">Razon Social</th>
                                  <th scope="col">Fecha de entrega</th>
                                  <th scope="col">Fecha reprogramada</th>
                                  <th scope="col">Productos</th>


                                </tr>
                              </thead>
                              <tbody>
                                @foreach($pending_guias as $guia)
                                <tr>
                                  <th scope="row">{{str_pad($guia->numeracion, 6, "0", STR_PAD_LEFT) }}</th>
                                  <td>{{$guia->codigoNB}}</td>
                                  <td>{{$guia->razon_social}}</td>
                                  <td>{{$guia->f_entrega}}</td>
                                  @if($guia->f_reprogramar) 
                                  <td>{{$guia->f_reprogramar}}</td>
                                  @else
                                  <td>-</td>
                                  @endif
                                  <td>
                                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#{{$guia->numeracion}}">
                                          Productos
                                        </button>

                                  </td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                        </div>
                    </div>         
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Facturas pendientes </div>
                        <div class="d-flex flex-wrap justify-content-around card-body">
                            <table class="table" id="facts">
                              <thead>
                                <tr>
                                  <th scope="col">Correlativo</th>
                                  <th scope="col">N NubeFact</th>
                                  <th scope="col">Razon Social</th>
                                  <th scope="col">Monto Total</th>
                                  <th scope="col">Pagos Recibidos</th>
                                  <th scope="col">Fecha de cobro</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($pending_facts as $fact)
                                <tr>
                                  <th scope="row">{{str_pad($fact->numeracion, 6, "0", STR_PAD_LEFT) }}</th>
                                  <td>{{$fact->codigoNB}}</td>
                                  <td>{{$fact->razon_social}}</td>
                                  <td>{{$fact->total}}</td>
                                  @if($fact->pagos_recibidos) 
                                  <td>{{$fact->pagos_recibidos}}</td>
                                  @else
                                  <td>0</td>
                                  @endif
                                  <td>{{$fact->f_cobro}}</td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                        </div>
                    </div>      
                    <div class="d-flex flex-wrap justify-content-around">
                        @include('modal')
                        @foreach($notas as $nota)
                        <div class="card border-primary mb-3" style="max-width: 15rem; margin-top: 10px;margin-left: 1px;">
                            <div class="card-header">
                                <b style="font-size: 12px; text-transform: uppercase;">{{$nota->titulo}}</b>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{$nota->texto}}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{URL::action('HomeController@delete', $nota->id)}}">
                                <button type="button" class="btn btn-light btn-xs" style="float:right;">
                                <i class="glyphicon glyphicon-trash position-center"></i>
                                </button>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Ventas Totales x Mes </div>
                            <div class="panel-body">
                                <canvas id="ventasxmes" height="280" width="600"></canvas>
                            </div>
                    </div>

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
            </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

    <script>
        $.extend( true, $.fn.dataTable.defaults, {
            "searching": false,
            "ordering": false
        } );

        $(document).ready(function () {
          $('#entregas').DataTable({
            "pageLength": 5,
            "bInfo" : false ,
            "bLengthChange" : false
          });
          $('#facts').DataTable({
            "pageLength": 5,
            "bInfo" : false ,
            "bLengthChange" : false
          });
        });

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

                var graphColors = [];
                var graphOutlines = [];
                var hoverColor = [];

                var internalDataLength = jsObj.viewer.length;
                i = 0;
                while (i <= internalDataLength) {
                    var randomR = 0;
                    var randomG = (Math.floor((Math.random() * 100) + 1))+100;
                    var randomB = 255;

                    var randomR2 = 255;
                    var randomG2 = (Math.floor((Math.random() * 150) + 1))+100;
                    var randomB2 = 0;

                    console.log(randomR);
                    console.log(randomG);
                    console.log(randomB);
                  
                    var graphBackground = "rgb(" 
                            + randomR + ", " 
                            + randomG + ", " 
                            + randomB + ")";
                    var graphBackground2 = "rgb(" 
                            + randomR2 + ", " 
                            + randomG2 + ", " 
                            + randomB2 + ")";

                    graphColors.push(graphBackground);
                    graphColors.push(graphBackground2);
                    
                    var graphOutline = "rgb(" 
                            + (randomR - 80) + ", " 
                            + (randomG - 80) + ", " 
                            + (randomB - 80) + ")";
                    var graphOutline2 = "rgb(" 
                            + (randomR2 - 80) + ", " 
                            + (randomG2 - 80) + ", " 
                            + (randomB2 - 80) + ")";
                    graphOutlines.push(graphOutline);
                    graphOutlines.push(graphOutline2);
                    
                    var hoverColors = "rgb(" 
                            + (randomR + 25) + ", " 
                            + (randomG + 25) + ", " 
                            + (randomB + 25) + ")";
                    var hoverColors2 = "rgb(" 
                            + (randomR2 + 25) + ", " 
                            + (randomG2 + 25) + ", " 
                            + (randomB2 + 25) + ")";
                    hoverColor.push(hoverColors);
                    hoverColor.push(hoverColors2);
                    
                    i++;
                };

                var ctx = document.getElementById("canvas").getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: jsObj.days,
                        datasets: [{
                            label: 'Ventas Totales x Fecha',
                            data: jsObj.viewer,
                            backgroundColor: graphColors,
                            hoverBackgroundColor: hoverColor,
                            borderColor: graphOutlines
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


        var myChart2;
        $.get( "{{route('ventasxmes')}}", function(response){
            var jsObj = JSON.parse(response);
            console.log(jsObj);

                var graphColors = [];
                var graphOutlines = [];
                var hoverColor = [];

                var internalDataLength = jsObj.viewer.length;
                i = 0;
                while (i <= internalDataLength) {
                    var randomR = 0;
                    var randomG = (Math.floor((Math.random() * 100) + 1))+100;
                    var randomB = 255;

                    var randomR2 = 255;
                    var randomG2 = (Math.floor((Math.random() * 150) + 1))+100;
                    var randomB2 = 0;

                    console.log(randomR);
                    console.log(randomG);
                    console.log(randomB);
                  
                    var graphBackground = "rgb(" 
                            + randomR + ", " 
                            + randomG + ", " 
                            + randomB + ")";
                    var graphBackground2 = "rgb(" 
                            + randomR2 + ", " 
                            + randomG2 + ", " 
                            + randomB2 + ")";

                    graphColors.push(graphBackground);
                    graphColors.push(graphBackground2);
                    
                    var graphOutline = "rgb(" 
                            + (randomR - 80) + ", " 
                            + (randomG - 80) + ", " 
                            + (randomB - 80) + ")";
                    var graphOutline2 = "rgb(" 
                            + (randomR2 - 80) + ", " 
                            + (randomG2 - 80) + ", " 
                            + (randomB2 - 80) + ")";
                    graphOutlines.push(graphOutline);
                    graphOutlines.push(graphOutline2);
                    
                    var hoverColors = "rgb(" 
                            + (randomR + 25) + ", " 
                            + (randomG + 25) + ", " 
                            + (randomB + 25) + ")";
                    var hoverColors2 = "rgb(" 
                            + (randomR2 + 25) + ", " 
                            + (randomG2 + 25) + ", " 
                            + (randomB2 + 25) + ")";
                    hoverColor.push(hoverColors);
                    hoverColor.push(hoverColors2);
                    
                    i++;
                };
                

            var ctx = document.getElementById("ventasxmes").getContext('2d');
            myChart2 = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: jsObj.months,
                    datasets: [{
                        label: 'Ventas Totales x Mes',
                        data: jsObj.viewer,
                        backgroundColor: graphColors,
                        hoverBackgroundColor: hoverColor,
                        borderColor: graphOutlines,
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


@stop
