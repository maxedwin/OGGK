@extends('index')

<!-- TITULO PAGINA -->

@section('titulo')
    <h4><i class="icon-list position-left"></i> <span class="text-semibold">Listado de Catálogos Virtuales</span></h4>
@stop

<!--BREADCRUMB -->
@section('breadcrumb')
    <li><a href="/"><i class="icon-home2 position-left"></i> Página Principal </a></li>
    <li class="active">Listado de Catálogos Virtuales</li>
@stop
<!-- MENU AUXLIAR -->

@section('menu')

    <li>
        <a href="nuevo_catalogos_virtual" id="nuevo_catalogo">
            <i class="icon-box-add position-left"></i>
            Nuevo Catálogo
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
                $("#catalogos_table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>  

          
        <!--LISTA DE CATALOGOS
        <input type="text" id="input" class="form-control input-lg" id="formGroupExampleInput" placeholder="Busca por Familia o SubFamilia">-->
        <table class="table datatable-column-search-inputs dataTable table-hover dataTable no-footer"  id="catalogos_table">
            <thead>
            <tr>            
                <!--<th>Estado</th>-->
                <th>Catálogo</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody id="catalogos_virtual_table">
            @foreach ($catalogos_virtual as $catalogo_virtual)
                <tr>
                    <td>{{$catalogo_virtual->name}}</td>
                                            
                    <td>
                        <button type="button" class="btn btn-info btn-xs"
                                data-id ="{{$catalogo_virtual->id}}"
                                data-name="{{$catalogo_virtual->name}}"
                                id="editar" data-toggle="modal">
                            <i class="icon-pen6 position-center"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" data-id ={{$catalogo_virtual->id}} id="eliminar">
                            <i class="icon-cancel-square2 position-center"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


<script >

var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;


/**********BOTONES DENTRO DE LA TABLA DE CATALOGOS ****************/

$('#catalogos_table').on('click','#eliminar' ,function() {
    id=  $(this).data('id');

    swal({
            title: "¿Estás seguro?",
            text: "¡No podrás recuperar este catálogo si lo eliminas!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "No, salir",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var jqxhr =  $.post(currentLocation+"catalogos_virtual_delete",{id:id},function(data,status){

                }).done(function() {
                    swal({
                        title: "Eliminado",
                        text: "El catálogo ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar el catálogo", "Inténtelo nuevamente luego.", "error");
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

$('#catalogos_table').on('click','#editar',function(){
    edit = 1;
    id=  $(this).data('id');
    nombre = $(this).data('name');
    location.href = currentLocation+"catalogos_virtual_edit?id="+id;
    $('#id').val(id);
    $('#nombre').val(nombre);


});


</script>
    @stop