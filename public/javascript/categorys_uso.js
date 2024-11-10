var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;


/**********BOTONES DENTRO DE LA TABLA DE CATEGORIAS ****************/

$('#categorias_table').on('click','#eliminar' ,function() {
    id=  $(this).data('id');

    swal({
            title: "¿Estás seguro?",
            text: "No podrás recuperar esta categoría si la eliminas",
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
                var jqxhr =  $.post(currentLocation+"categorys_uso_delete",{id:id},function(data,status){

                }).done(function() {
                    swal({
                        title: "Eliminada",
                        text: "La categoría ha sido eliminada.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar la categoría", "Inténtelo nuevamente luego.", "error");
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

$('#categorias_table').on('click','#editar',function(){
    edit = 1;
    id=  $(this).data('id');
    nombre = $(this).data('name');
    location.href = currentLocation+"categorys_uso_edit?id="+id;
    $('#id').val(id);
    $('#nombre').val(nombre);


});