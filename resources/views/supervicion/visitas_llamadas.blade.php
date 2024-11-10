@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="glyphicon glyphicon-earphone position-left"></i><span class="text-semibold">VISITAS / LLAMADAS</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">Visitas / LLamadas</li>
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

                    <div class="panel panel-default">
                        <div class="panel-heading text-semibold"> Visitas / LLamadas </div>
                            
                        <div class="panel-body">

                            <div class="row">                                                            
                                <div class="col-md-6"> 
                                    <div class="form-group form-inline">
                                        <div class="input-group">
                                            <label>De:</label><input id="fecha_ini6" type="date" class="form-control"> 
                                        </div>
                                        <div class="input-group ">
                                            <label>Hasta:</label><input id="fecha_fin6" type="date" class="form-control"> 
                                        </div>
                                    </div>                                                       
                                </div>
                            
                            </div>
                        
                            <div class="row"> 

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="idusuario">Usuario:</label>
                                        <div class="input-group" style="display: block;">
                                            <select class="form-control" name="idusuario" id="idusuario" style="width: 100%;">
                                                    <option value=0> -- TODOS -- </option>
                                                @foreach ($usuarios as $user)
                                                    <option value="{{$user->id}}">{{$user->name}} {{$user->lastname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">  
                                    <div class="form-group">                          
                                        <label for="tipo">Tipo:</label>
                                        <div class="input-group" style="display: block;">
                                            <select class="form-control" name="tipo" id="tipo" style="width: 100%;">
                                                    <option value=4> AMBAS </option>
                                                    <option value=0> VISITAS </option>
                                                    <option value=1> LLAMADAS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">                                                        
                                    <div class="form-group">
                                        <label for="tipo"></label>
                                        <div class="input-group ">
                                            <button id="kardex_boton" class="btn btn-info"> Mostrar </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                            
                            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="kardex_tabla">
                                <thead>
                                    <tr>
                                        <th>FECHA HORA</th>
                                        <th>USUARIO</th>
                                        <th>CLIENTE</th>
                                        <th>TIPO</th>
                                        <th>MOTIVO</th>
                                        <th>RESPUESTA</th>
                                        <th>DESDE</th>
                                        <th>COORDENADAS</th>
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
    <script src="{{ URL::asset('/javascript/helper.js') }}" type="text/javascript"></script>

    
    <script type="text/javascript">
        //$('#idalmacen').select2();

        var todayHelper = getTodayFormat();
        $('#fecha_ini6').val(todayHelper);
        $('#fecha_fin6').val(todayHelper);
        $('#idusuario').select2();
        $('#tipo').select2();


        $("#kardex_boton").click(function(){
            console.log('clickk');
            var fecha_ini = $("#fecha_ini6").val();
            var fecha_fin = $("#fecha_fin6").val(); 
            var tipo = $("#tipo").val(); 
            var idusuario = $("#idusuario").val();


            if(fecha_ini == null || fecha_fin == null || fecha_ini == '' || fecha_fin == '' ){
                swal({
                    title: "Upss!",
                    text: "Debes seleccionar un intervalo de Fechas",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            /*if(idusuario == 0){
                swal({
                    title: "Upss!",
                    text: "Debes seleccionar un Producto",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }*/

            if(tipo == 99){
                swal({
                    title: "Upss!",
                    text: "Debes seleccionar un Tipo",
                    confirmButtonColor: "#66BB6A",
                    type: "error"
                },function(){
                    //window.location.reload();
                });
                return;
            }

            
            $('#kardex_tabla').dataTable().fnDestroy();
            //$('#utilidad_tabla').dataTable().fnDestroy();

            $.ajax({
                url: "{{ route('get_visitas_llamadas') }}", 
                data:{ fecha_ini:fecha_ini, fecha_fin:fecha_fin, tipo:tipo, idusuario:idusuario},
                success : function(data) {
                    data = JSON.parse(data);
                    var o = data.visitas;
                    console.log(o);

                    var vt = [];


                    var date;
                    var date_peru;

                    for (var i = 0; i < o.length; i++) {

                        vt.push({
                            fecha : o[i].created_at,

                            usuario: o[i].name + ' ' + o[i].lastname,
                            cliente: (o[i].ruc_dni ? o[i].ruc_dni + ' - ' + o[i].razon_social : '> ' + o[i].nombre_comercial),
                            tipo: (o[i].web_app == 0 ? 'VISITA' : 'LLAMADA'),
                            motivo: o[i].motivo,
                            respuesta: o[i].respuesta,
                            desde: (o[i].is_app ? 'APP' : 'WEB'),
                            coordenadas: o[i].latitud.toString() + ', ' + o[i].longitud.toString(),
                        });
                    }

                    $('#kardex_tabla').dataTable( {
                        autoWidth: true,
                        searching: false,
                        ordering: false,
                        paging: false,
                        data : vt,
                        dom: 'lBfrtip',
                        buttons: [
                            'excel'
                        ],

                        columns: [
                            {"data" : "fecha"},
                            {"data" : "usuario"},
                            {"data" : "cliente"},
                            {"data" : "tipo"},
                            {"data" : "motivo"},
                            {"data" : "respuesta"},
                            {"data" : "desde"},
                            {"data" : "coordenadas"}
                        ],
                    });
                }       
            });
        });
        
    </script>
    

@stop
