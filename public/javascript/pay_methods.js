var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;


/**********BOTONES DENTRO DE LA TABLA DE CATEGORIAS ****************/
$('#paymethods_table').on('click','#eliminar' ,function() {
    id_paymethod =  $(this).data('id_paymethod');

    swal({
            title: "¿Estás seguro?",
            text: "No podrás recuperar este método de pago si lo eliminas",
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
                var jqxhr =  $.post(currentLocation+"paymethod_delete",{id_paymethod:id_paymethod},function(data,status){

                }).done(function(data) {
                    obj = JSON.parse(data);
                    if (obj.mensaje == 900){
                        swal({
                            title: "No se pudo eliminar",
                            text: "Siempre debe quedar al menos un método de pago. No puede eliminarlo.",
                            confirmButtonColor: "#2196F3",
                            type: "error"
                        },function(){});
                    }
                    else if(obj.mensaje == 200){
                        swal({
                            title: "Eliminado",
                            text: "El método de pago ha sido eliminado.",
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){
                            window.location.reload();
                        });
                    }
                }).fail(function() {
                    swal("Error al eliminar el método de pago", "Inténtelo nuevamente luego.", "error");
                });
            }
            else {
                swal({
                    title: "Cancelado",
                    text: "No se ha eliminado nada",
                    confirmButtonColor: "#2196F3",
                    type: "error"
                });
            }
        });
});

$('#pay_methods_table').on('click','#editar',function(){
    edit = 1;
    id_paymethod =  $(this).data('id_paymethod');
    nombre = $(this).data('nombre');
    descripcion_pre = $(this).data('descripcion_pre');
    descripcion_det = $(this).data('descripcion_det');
    location.href = currentLocation+"paymethod_edit?id_paymethod="+id_paymethod;
    $('#id_paymethod').val(id_paymethod);
    $('#nombre').val(nombre);
    $('#descripcion_pre').val(descripcion_pre);
    $('#descripcion_det').val(descripcion_det);
});