@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-grid position-left"></i> <span class="text-semibold">Listado de Banners</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li class="active">Listado de Banners</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="#" id="nuevo_banner">
            <i class="icon-box-add position-left"></i>
            Nuevo Banner
        </a>

    </li>
@stop

<!-- CONTENIDO DE LA PAGINA -->
@section('contenido')
<?PHP
    header("Access-Control-Allow-Origin:*");
 ?>
<style type="text/css">
    .colorpicker-basic { z-index: 9999; }
    .sp-container { z-index: 9999 !important;}
    .sp-preview {z-index: 9999 !important;}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{ URL::to('/') }}">

<script>
        $(document).ready(function(){
            $("#input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#banners_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
</script>  



        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="banner_table">
            <thead>
            <tr>
                <th>Alias</th>
                <th>Imagen</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="banners_table">
            @foreach ($slides as $slide)
                <tr>
                  
                    <td>{{$slide->alias}}</td>
                    <td>
                        <img src="{{url('images/large/',$slide->image)}}" width="120" height="50" alt="">
                    </td>
                    <td>
                        <?PHP  if($slide->active == 0) {
                            echo '<span class="label label-default">Inactivo</span>';
                        }      else {
                            echo '<span class="label label-primary">Activo</span>';
                        }?>
                    </td>
                    <td>
                        <?PHP  if($slide->active == 0) { ?>
                            <button type="button" class="btn btn-info btn-xs" data-idslide ={{$slide->id}}
                            data-active={{$slide->active}} id="active">
                                <i class="glyphicon glyphicon-check position-center"></i> Activar
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-default btn-xs" data-idslide ={{$slide->id}} data-active={{$slide->active}} id="active">
                                <i class="glyphicon glyphicon-ban-circle position-center"></i> Desactivar
                            </button>
                        <?php } ?>

                        <button type="button" class="btn btn-danger btn-xs" data-idslide ={{$slide->id}} id="eliminar">
                            <i class="icon-cancel-square2 position-center"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        
<div id="formulario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('slide_store') }}" method="POST" enctype="multipart/form-data">

                <div class="modal-header bg-info">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h6 class="modal-title">Nuevo Banner</h6>
                </div>

                <div class="modal-body">
                    <div class="form-horizontal">
                        <div class="panel panel-flat">
                            <div class="panel-heading">

                            </div>

                            <div class="panel-body">
                                <fieldset>
                                    <legend class="text-semibold">Información del Banner</legend>

                                    <div id="nombregroup" class="form-group">
                                        <label class="col-lg-3 control-label">Nombre:</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="alias" id="alias" class="form-control" required="required">
                                        </div>
                                    </div>

                                    <div id="direcciongroup" class="form-group">
                                        <label class="col-lg-3 control-label">Imágen:</label>
                                        <div class="col-lg-9">
                                            <input type="file" name="image" id="image" accept="image/*" required="required"/>
                                        </div>
                                    </div>
                                </fieldset>

                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-info" id="guardar_cambios">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript" rel="script">
    var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

    table = $('#banner_table').DataTable( {
        "autoWidth": true
    } );

    $('#nuevo_banner').on('click',function(){
        
        $('#alias').val('');
        $('#image').val('');

        $('#formulario').modal('show');

    });

    $('#banners_table').on('click','#eliminar' ,function() {
    
        idslide=  $(this).data('idslide');

        swal({
                title: "Estas seguro?",
                text: "No podras recuperar este banner si lo eliminas!",
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
                    var jqxhr =  $.post(currentLocation+"slide_delete",{idslide:idslide},function(data,status){

                    }).done(function() {
                        swal({
                            title: "Eliminado!",
                            text: "El banner ha sido eliminado.",
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){
                            window.location.reload();
                        });
                    }).fail(function() {
                        swal("Error al eliminar banner", "Intentelo nuevamente luego.", "error");
                    });
                }
                else {
                    swal({
                        title: "Cancelado",
                        text: "No se ha eliminado nada :)",
                        confirmButtonColor: "#2196F3",
                        type: "error"
                    });
                }
            });
    });

    $('#banners_table').on('click','#active' ,function() {
    
        idslide=  $(this).data('idslide');
        activeValue = $(this).data('active');

        var jqxhr =  $.post(currentLocation+"slide_active",{idslide:idslide},function(data,status){

        }).done(function() {

            textActive = (activeValue == 0 ? "Activado" : "Desactivado")

            swal({
                title: textActive,
                text: "El banner ha sido "+textActive+".",
                confirmButtonColor: "#66BB6A",
                type: "success"
            },function(){
                window.location.reload();
            });
        }).fail(function() {
            swal("Error al activar/desactivar banner", "Intentelo nuevamente luego.", "error");
        });
    });

</script>
@stop