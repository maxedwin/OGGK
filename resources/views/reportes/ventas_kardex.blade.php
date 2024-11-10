@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <!-- <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Página Principal</span></h4> -->
    <h4><i class="glyphicon glyphicon-signal position-left"></i><span class="text-semibold">KARDEX</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">KARDEX</li>
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
                        <div class="panel-heading text-semibold"> KARDEX </div>
                            
                        <div class="container">

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
                            
                                <div class="col-md-6">                                                        
                                    
                                </div>
                            </div>
                        
                            <div class="row">                                                            
                                <div class="col-md-6">
                                    <div class="form-group">   
                                        <label for="idalmacen">Almacén:</label>
                                        <div class="input-group ">                                    
                                            <select class="form-control" name="idalmacen" id="idalmacen" style="width:400px" >
                                                    <option value="0">--</option>
                                                @foreach ($almacenes as $almacen)
                                                    <option value="{{$almacen->idalmacen}}">{{$almacen->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">  
                                    <div class="form-group">                          
                                        <label for="tipo">Tipo:</label>
                                        <div class="input-group">
                                            <select class="form-control" name="tipo" id="tipo" style="width:400px">
                                                    <option value=99> -- </option>
                                                    <option value=1> ENTRADAS </option>
                                                    <option value=0> SALIDAS</option>
                                                    <option value=4> AMBAS </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="idproducto">Producto:</label>
                                        <div class="input-group">
                                            <select class="form-control" name="idproducto" id="idproducto" style="width:400px">
                                                    <option value=0> -- </option>
                                                @foreach ($productos as $producto)
                                                    <option value="{{$producto->idproducto}}">{{$producto->barcode}} // {{$producto->nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
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
                                        <th>FECHA</th>
                                        <th>DOC. SUSTENTO</th>
                                        <th>DOC. REFERECIA</th>
                                        <th>CODIGO</th>
                                        <th>NOMBRE</th>
                                        <th>ENTRADAS</th>
                                        <th>SALIDAS</th>
                                        <th>SALDO</th>
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

    
    <script type="text/javascript">
        $('#idalmacen').select2();
        $('#idproducto').select2();
        $('#idproveedor').select2();
        $('#tipo').select2();
        $('#unidad_medida').select2();


        $("#kardex_boton").click(function(){
            console.log('clickk');
            var fecha_ini = $("#fecha_ini6").val();
            var fecha_fin = $("#fecha_fin6").val(); 
            var tipo = $("#tipo").val(); 
            var idproducto = $("#idproducto").val(); 
            var idproveedor = $("#idproveedor").val(); 
            var idalmacen = $("#idalmacen").val(); 
            var unidad_medida = $("#unidad_medida").val(); 


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

            /*if(idproducto == 0){
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
                url: "{{ route('kardex') }}", 
                data:{ fecha_ini:fecha_ini, fecha_fin:fecha_fin, tipo:tipo, idproducto:idproducto, idproveedor:idproveedor, idalmacen:idalmacen},
                success : function(data) {
                    var o = JSON.parse(data);
                    console.log(o);
                    $('#kardex_tabla').dataTable( {
                        autoWidth: true,
                        searching: false,
                        ordering: false,
                        paging: false,
                        data : o.viewer,
                        dom: 'lBfrtip',
                        buttons: [
                            'excel'
                        ],

                        columns: [
                            {"data" : "fecha"},
                            {"data" : "doc_sustento"},
                            {"data" : "doc_referencia"},
                            {"data" : "barcode"},
                            {"data" : "nombre"},
                            {"data" : "entradas", render: $.fn.dataTable.render.number( ',', '.', 3) , className: "text-right"  },
                            {"data" : "salidas", render: $.fn.dataTable.render.number( ',', '.', 3) , className: "text-right" },
                            {"data" : "saldo" , render: $.fn.dataTable.render.number( ',', '.', 3) , className: "text-right" },
                        ],
                    });
                }       
            });
        });
        
    </script>
    

@stop
