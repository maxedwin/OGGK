@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="glyphicon glyphicon-ok position-left"></i><span class="text-semibold">Evaluación de Proveedores</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class=""><a href="/eval_listado"></i>Listado de Evaluaciones</a></li>
    <li class="active">Evaluación de Proveedores</li>
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

    <style>
        table.table1 {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 70%;
            height: 250px;
        }
        table.table1 th {
            border: 2px solid #dddddd;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        table.table1 td {border: 2px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 60%;
        }
        th {
            border: 2px solid #dddddd;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        td {
            border: 2px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        .column {
          float: left;
          width: 50%;
          padding: 10px;
        }

        .row:after {
          content: "";
          display: table;
          clear: both;
        }
    </style>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                        
                        <div class="panel-heading text-semibold"> Metodología de Evaluación </div>

                        <div class="panel-body">
                        
                            <div>Las evaluaciones realizadas a los proveedores, consideran los criterios. 
                            Estos criterios se evalúan en una escala de 1 a 5, donde el 5 es el máximo posible 
                            y 1 el menor valor posible, como ejemplo se presenta la siguiente descripción general:</div><br>

                            <div class="row">
                                <div class="column">
                                    <table class="table1">
                                        <tr>
                                            <th>Descripción</th>
                                            <th>Puntos</th>
                                        </tr>

                                        <tr>
                                            <td>Aprobación Plena del Criterio según descripción</td>
                                            <td>1</td>
                                        </tr>

                                        <tr>
                                            <td>Aprobación Simple del Criterio según descripción</td>
                                            <td>2</td>
                                        </tr>

                                        <tr>
                                            <td>Indecisión o Indiferencia del Criterio según descripción</td>
                                            <td>3</td>
                                        </tr>

                                        <tr>
                                            <td>Desaprobación Simple del Criterio según descripción</td>
                                            <td>4</td>
                                        </tr>

                                        <tr>
                                            <td>Desaprobación Plena del Criterio según descripción</td>
                                            <td>5</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="column">
                                    <table class="table1">
                                        <tr>
                                            <th>Escala de Evaluación</th>
                                            <th>Puntaje Mínimo</th>
                                            <th>Puntaje Máximo</th>
                                        </tr>

                                        <tr>
                                            <td>Aprobación Plena</td>
                                            <td>20</td>
                                            <td>30</td>
                                        </tr>

                                        <tr>
                                            <td>Aprobación Simple</td>
                                            <td>31</td>
                                            <td>50</td>
                                        </tr>

                                        <tr>
                                            <td>Indecisión o Indiferencia</td>
                                            <td>51</td>
                                            <td>70</td>
                                        </tr>

                                        <tr>
                                            <td>Desaprobación Simple</td>
                                            <td>71</td>
                                            <td>90</td>
                                        </tr>

                                        <tr>
                                            <td>Desaprobación Plena</td>
                                            <td>91</td>
                                            <td>100</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <br><br>
                            <div class="form-group form-inline">
                                <div class="input-group ">
                                <label for="idproveedor">Elije el Proveedor que será evaluado:</label>
                                <select id="idproveedor" class="form-control">
                                        <option value=0> -- </option>
                                        @foreach ($proveedores as $proveedor)
                                            <option value="{{$proveedor->idproveedor}}">{{$proveedor->razon_social}}</option>
                                        @endforeach
                                </select>
                                </div>

                                <div class="input-group ">
                                    <label for="puntaje">Puntaje:</label>
                                    <input type="text" class="form-control" id="puntaje" disabled  >
                                </div>

                                <div class="input-group pull-right">
                                    <button id="sumar" class="btn btn-info"> Sumar </button>
                                    <button id="guardar" class="btn btn-info"> Guardar </button> 
                               </div>
                            </div>

                            <br><br>
                            <div>
                                <table>
                                    <tr>
                                        <th colspan="2">CUESTIONARIO DE EVALUACIÓN DE PROVEEDORES</th>
                                    </tr>
                                    <tr>
                                        <th>Ítem de Evaluación</th>
                                        <th>Puntuación</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Aspectos de Calidad</th>
                                    </tr>
                                    <tr>
                                        <td>Programa de Aseguramiento de la Calidad</td>
                                        <td>
                                            <select id="id1">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Certificaciones en Gestión de la Calidad</td>
                                        <td>
                                            <select id="id2">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tiempo y Forma de Entrega del Producto</td>
                                        <td>
                                            <select id="id3">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Personal Entrenado</td>
                                        <td>
                                            <select id="id4">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Servicio post-venta y asistencia técnica</td>
                                        <td>
                                            <select id="id5">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tiempo de Garantía</td>
                                        <td>
                                            <select id="id6">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Aspectos de Medio Ambiente</th>
                                    </tr>
                                    <tr>
                                        <td>Implementación de  Sistema de Gestión Medioambiental</td>
                                        <td>
                                            <select id="id7">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Certificaciones en gestión Ambiental</td>
                                        <td>
                                            <select id="id8">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Envases y embalajes reciclables</td>
                                        <td>
                                            <select id="id9">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Impacto ambiental potencial del producto/servicio suministrado</td>
                                        <td>
                                            <select id="id10">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Aspectos de Seguridad y Prevención de Riesgos </th>
                                    </tr>
                                    <tr>
                                        <td>Implementación de  Sistema de Salud y Seguridad Ocupacional</td>
                                        <td>
                                            <select id="id11">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Certificaciones en Sistemas de Salud y Seguridad Ocupacional</td>
                                        <td>
                                            <select id="id12">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Plan de seguridad/coordinación de actividades</td>
                                        <td>
                                            <select id="id13">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Condiciones económicas</th>
                                    </tr>
                                    <tr>
                                        <td>Tarifa de acuerdo al mercado</td>
                                        <td>
                                            <select id="id14">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sistema de descuentos comerciales y por volumen de compra </td>
                                        <td>
                                            <select id="id15">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Forma y plazos de pago</td>
                                        <td>
                                            <select id="id16">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Periodo de validez de la oferta</td>
                                        <td>
                                            <select id="id17">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Plazos de entrega</td>
                                        <td>
                                            <select id="id18">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Recargos por aplazamiento del pago</td>
                                        <td>
                                            <select id="id19">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Presenta los documentos adjuntos requeridos</td>
                                        <td>
                                            <select id="id20">
                                            <option value=0> -- </option>
                                            <option value=1> 1 </option>
                                            <option value=2> 2 </option>
                                            <option value=3> 3 </option>
                                            <option value=4> 4 </option>                                           
                                            <option value=5> 5 </option>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>

                </div>
            </div>
        </div>
    </div>


    <script rel="script" type="text/javascript">

        var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

        function calcular_puntaje(){
            var puntaje = 0; 

            if($('#id1').val() == '0' || $('#id2').val() == '0' || $('#id3').val() == '0' || $('#id4').val() == '0' || $('#id5').val() == '0' || $('#id6').val() == '0'
             || $('#id7').val() == '0' || $('#id8').val() == '0' || $('#id9').val() == '0' || $('#id10').val() == '0' || $('#id11').val() == '0' || $('#id12').val() == '0'
              || $('#id13').val() == '0' || $('#id14').val() == '0' || $('#id15').val() == '0' || $('#id16').val() == '0' || $('#id17').val() == '0' || $('#id18').val() == '0'
               || $('#id19').val() == '0' || $('#id20').val() == '0'  ){
                swal({
                    title: "Falta alguna puntuación!",
                    text: "Revisa todas las puntuaciones",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){});
                return;
            }

            puntaje += parseInt($('#id1').val())  + parseInt($('#id2').val())  + parseInt($('#id3').val())  + parseInt($('#id4').val())  + parseInt($('#id5').val());
            puntaje += parseInt($('#id6').val())  + parseInt($('#id7').val())  + parseInt($('#id8').val())  + parseInt($('#id9').val())  + parseInt($('#id10').val());
            puntaje += parseInt($('#id11').val()) + parseInt($('#id12').val()) + parseInt($('#id13').val()) + parseInt($('#id14').val()) + parseInt($('#id15').val());
            puntaje += parseInt($('#id16').val()) + parseInt($('#id17').val()) + parseInt($('#id18').val()) + parseInt($('#id19').val()) + parseInt($('#id20').val());
            return puntaje;
        }

        $('#sumar').click(function(event){
            var tmp = calcular_puntaje();            
            console.log(tmp);
            $('#puntaje').val(tmp);
        });

        $('#guardar').click(function(event){

            var idevaluado = $('#idproveedor').val();
            var tipo_evaluacion = 0;  
            var puntaje = $('#puntaje').val();

            var tmp;
            if (puntaje >= 20 && puntaje <=30){
                tmp = "Aprobación Plena";
            }else if (puntaje >= 31 && puntaje <=50){
                tmp = "Aprobación Simple";
            }else if (puntaje >= 51 && puntaje <=70){
                tmp = "Indecisión o Indiferencia";
            }else if (puntaje >= 71 && puntaje <=90){
                tmp = "Desaprobación Simple";
            }else if (puntaje >= 91 && puntaje <=100){
                tmp = "Desaprobación Plena";
            }else {
                tmp= "Suma invalida";
            }


            if(typeof idevaluado === "undefined" || idevaluado=='0'){
                swal({
                    title: "Falta el campo Proveedor",
                    text: "Debes seleccionar un proveedor",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){});
                return;
            }

            $.post(currentLocation+'crear_evaluacion',{idevaluado:idevaluado, tipo_evaluacion:tipo_evaluacion, puntaje:puntaje},function(data){
                var mensaje;
                var obj = JSON.parse(data, function (key, value) {
                    if (key == "created") {
                        mensaje = value;
                    } 
                    if (key == "error") {
                        mensaje = value;
                    } 
                });
                if(mensaje == 200){
                    swal({
                        title: "Ok!",
                        text: "Se guardo correctamente! Su Escala de Evaluación es: " + tmp,
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload()
                    });
                }else{
                    swal({
                        title: "Error..!",
                        text: "Intentalo de nuevo luego. Revisa las puntuaciones",
                        confirmButtonColor: "#66BB6A",
                        type: "error"
                    },function(){});
                    return;
                }
            });
        });

    </script>

   


@stop
