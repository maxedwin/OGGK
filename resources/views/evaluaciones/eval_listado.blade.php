@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="glyphicon glyphicon-ok position-left"></i> <span class="text-semibold">Listado de Evaluaciones</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <!-- <li>Inventario</li> -->
    <li class="active"><a href="/eval_listado"></i>Listado de Evaluaciones</a></li>
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



            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="cliente_table">
                <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Razón Social (Evaluado)</th>
                    <th>Puntaje</th>
                    <th>Escala</th>
                    <th>Usuario (Evaluador)</th>
                    <th>Fecha de Evaluación</th>    
                </tr>
                </thead>
                <tbody id="cliente_table">
                @foreach ($evaluaciones as $eval)
                    <tr id="tr_detalle">
                        
                        <?PHP  if($eval->tipo_evaluacion == 0) {
                            echo '<td> Proveedor </td>';
                        }      elseif($eval->tipo_evaluacion == 1) {
                            echo '<td> Cliente </td>';
                        }
                        else{
                            echo '<td> No tiene </td>';
                        }  ?>

                        <?PHP  if($eval->tipo_evaluacion == 0) {
                            echo '<td>'.$eval->prs.'</td>';
                        }      elseif($eval->tipo_evaluacion == 1) {
                            echo '<td>'.$eval->crs.'</td>';
                        }
                        else{
                            echo '<td> No tiene </td>';
                        }  ?>

                        <td>{{ $eval->puntaje}}</td>

                        <?PHP if ($eval->puntaje >= 20 && $eval->puntaje <=30){
                            echo '<td>Aprobación Plena</td>';
                        }elseif ($eval->puntaje >= 31 && $eval->puntaje <=50){
                            echo '<td>Aprobación Simple</td>';
                        }elseif ($eval->puntaje >= 51 && $eval->puntaje <=70){
                            echo '<td>Indecisión o Indiferencia</td>';
                        }elseif ($eval->puntaje >= 71 && $eval->puntaje <=90){
                            echo '<td>Desaprobación Simple</td>';
                        }elseif ($eval->puntaje >= 91 && $eval->puntaje <=100){
                            echo '<td>Desaprobación Plena</td>';
                        }else {
                            echo '<td>Suma invalida</td>';
                        } ?>
                        
                        <td> {{ $eval->name }} {{ $eval->lastname }}</td>
                        <td>{{ date('d/m/Y', strtotime($eval->created_at)) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

<script src="//cdn.datatables.net/plug-ins/1.10.11/sorting/date-eu.js" type="text/javascript"></script>


<script type="application/javascript" rel="script">

    table = $('#cliente_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "columnDefs" : [{"targets":5, "type":"date-eu"}],
     } );

</script>

    
@stop