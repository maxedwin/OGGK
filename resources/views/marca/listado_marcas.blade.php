@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-users2 position-left"></i> <span class="text-semibold">Evaluacion de Precios - Marcas</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal</a></li>
    <li class="active">Evaluacion de Precios - Marcas</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')
    
    <li>
        <a href="#" id="nueva_marca">
            <i class="icon-box-add position-left"></i>
            Nueva Marca
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
            });
        });
    </script>   


            <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer" style="overflow-x:auto;" id="marca_table">
                <thead>
                <tr>
                    <th>Marca</th>
                    <th>Acciones</th>

                    <!-- <th>Acciones</th> -->
                </tr>
                </thead>
                <tbody id="marcas_table">
                @foreach ($marcas as $marca)
                    <tr id="tr_detalle">
                        <td>{{ $marca->nombre }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-xs"
                                data-idmarca ="{{$marca->id}}"
                                data-nombre="{{$marca->nombre}}"
                                id="editar" data-toggle="modal">
                                <i class="icon-pen6 position-center"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-xs"
                                data-idmarca ="{{$marca->id}}"
                                id="listado" data-toggle="modal">
                                <i class="icon-list position-center"></i>
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
                    <h6 class="modal-title">Nueva/Editar Marca</h6>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="panel panel-flat">
                            <div class="panel-heading">

                            </div>

                            <div class="panel-body">
                                <fieldset>
                                    <legend class="text-semibold">Información de Marca</legend>
                                    <div class="row">
                                        <div id="nombregroup" class="col-md-6">
                                            <label class="control-label">Marca:</label>
                                            <input type="text" id="nombre" class="form-control" placeholder="Nombre de Marca">
                                            <input type="hidden"  id="idmarca">
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
    var edit  = 0;

    table = $('#marca_table').DataTable( {
        "autoWidth": true,
        "paging": true,
        "searching": true,
        "ordering": false
    } );

    var names = ['nombre'];

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

    $('#nueva_marca').on('click',function(){
        limpiar();
        edit = 0;
        $.each(names, function(index, value) {
            $('#'+value).val('');
        });

        $('#idmarca').val('');
        $('#formulario').modal('show');
    });

    $('#marcas_table').on('click','#editar',function(){
        limpiar();
        edit = 1;
        $that = this;

        $.each(names, function(index, value) {
            var elem = $($that).data(value);
            $('#'+value).val(elem);
        });

        var idmarca = $(this).data('idmarca');
        $('#idmarca').val(idmarca);
        $('#formulario').modal('show');
    });

    $('#formulario').on('click','#guardar_cambios',function(){
        $('#guardar_cambios').prop( "disabled", true );

        if(validar_datos()){

            var idmarca = $('#idmarca').val();
            
            arrayPost = {};
            $.each(names, function(index, value) {
                arrayPost[value] = $('#'+value).val();
            });
            arrayPost.idmarca = idmarca;
            arrayPost.edit = edit;

            console.log(arrayPost);

            var jqxhr =  $.post(currentLocation+"guardar_marca",arrayPost,function(data,status){

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

    $('#marcas_table').on('click','#listado',function(){
        var idmarca = $(this).data('idmarca');
        window.location.href = currentLocation+"productos_marca?id="+idmarca;
    });

</script>

@stop