@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Evaluacion de Precios - Marcas</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li><a href="marcas">Evaluacion de Precios - Marcas</a></li>
    <li class="active">Productos - Marca</li>
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
            });
        });
    </script>   


            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="producto_table">
                <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Producto</th>
                    <th>Valor de Compra (sin IGV)</th>
                    <th>Rango 1<br>(11+)</th>
                    <th>Rango 2<br>(6 - 10)</th>
                    <th>Rango 3<br>(0 - 5)</th>
                    <th>Cantidad x Caja</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="productos_table">
                @foreach ($productos as $prod)
                    <tr id="tr_detalle">
                        <td>{{ $prod->barcode }}</td>
                        <td>{{ $prod->nombre }}</td>
                        <td>{{ number_format((float)$prod->costo_sin_igv, 2, '.', '') }}</td>
                        <td>{{ number_format((float)$prod->precio_rango_0, 2, '.', '') }}</td>
                        <td>{{ number_format((float)$prod->precio_rango_1, 2, '.', '') }}</td>
                        <td>{{ number_format((float)$prod->precio_rango_2, 2, '.', '') }}</td>
                        <td>{{ $prod->cantidad_caja }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-xs"
                                data-idproducto ="{{$prod->idproducto}}"
                                data-nombre="{{$prod->nombre}}"
                                data-costo_sin_igv="{{$prod->costo_sin_igv}}"
                                data-precio_rango_0="{{$prod->precio_rango_0}}"
                                data-precio_rango_1="{{$prod->precio_rango_1}}"
                                data-precio_rango_2="{{$prod->precio_rango_2}}"
                                data-cantidad_caja="{{$prod->cantidad_caja}}"
                                id="editar" data-toggle="modal">
                                <i class="icon-pen6 position-center"></i>
                            </button>
                        </td>
                        
                    </tr>
                @endforeach
                </tbody>
            </table>

    <div id="formulario" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h6 class="modal-title">Editar Costo/Precios Producto</h6>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="panel panel-flat">
                            <div class="panel-heading">

                            </div>

                            <div class="panel-body">
                                <fieldset>
                                    <legend class="text-semibold">Información de Producto</legend>
                                    <div class="row">
                                        <div id="costo_sin_igvgroup" class="col-md-6">
                                            <label class="control-label">Valor de Compra (sin IGV):</label>
                                            <input type="number" id="costo_sin_igv" class="form-control" step="0.01" min="0">
                                            <input type="hidden"  id="idproducto">
                                        </div>
                                        <div id="precio_rango_0group" class="col-md-6">
                                            <label class="control-label">Precio Rango 1 (11+):</label>
                                            <input type="number" id="precio_rango_0" class="form-control" step="0.01" min="0">
                                        </div>
                                        <div id="precio_rango_1group" class="col-md-6">
                                            <label class="control-label">Precio Rango 2 (6 - 10):</label>
                                            <input type="number" id="precio_rango_1" class="form-control" step="0.01" min="0">
                                        </div>
                                        <div id="precio_rango_2group" class="col-md-6">
                                            <label class="control-label">Precio Rango 3 (0 - 5):</label>
                                            <input type="number" id="precio_rango_2" class="form-control" step="0.01" min="0">
                                        </div>
                                        <div id="cantidad_cajagroup" class="col-md-6">
                                            <label class="control-label">Cantidad x Caja:</label>
                                            <input type="number" id="cantidad_caja" class="form-control" min="0">
                                        </div>
                                    </div>

                                </fieldset>

                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    table = $('#producto_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "ordering": false
    } );

    var names = ['idproducto', 'costo_sin_igv', 'precio_rango_0', 'precio_rango_1', 'precio_rango_2', 'cantidad_caja'];

    function limpiar(){
        $.each(names, function(index, value) {
            $('#'+value+'group').removeClass("has-error");
        });
    }

    function validar_datos(){

        var is = true;
        $.each(names, function(index, value) {
            var elem =  $('#'+value).val();
            if(elem  === undefined || elem === ''){
                is = false;
                $('#'+value+'group').addClass("has-error");
            } else {
                $('#'+value+'group').removeClass("has-error");
            }
        });

        return is;
    }

    $('#productos_table').on('click','#editar',function(){
        limpiar();
        $that = this;

        $.each(names, function(index, value) {
            var elem = $($that).data(value);
            $('#'+value).val(elem);
        });

        $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
        $('#guardar_cambios').prop( "disabled", true );

        if(validar_datos()){
            
            arrayPost = {};
            $.each(names, function(index, value) {
                arrayPost[value] = $('#'+value).val();
            });

            console.log(arrayPost);

            var jqxhr =  $.post(currentLocation+"guardar_precios_producto",arrayPost,function(data,status){

            }).done(function() {
                $('#formulario').modal('hide');

                swal({
                        title: "Bien hecho!",
                        text: "Se guardo correctamente",
                        type: "success"
                    },
                    function(){
                        window.location.reload()
                    });
                ;
            }).fail(function() {
              
              $('#formulario').modal('hide');
                swal({
                      title:"Error al guardar", 
                      text: "Intentelo nuevamente luego.",
                      type: "error"
                    },
                    function(){
                        $('#guardar_cambios').prop( "disabled", false );
                    });
            });

        } else {
            $('#guardar_cambios').prop( "disabled", false );
        }

    });

</script>

@stop