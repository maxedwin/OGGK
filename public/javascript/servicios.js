
var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';


/***************************************************************/

/**********BOTONES DENTRO DE LA TABLA PRODUCTOS ****************/
table = $('#products_table').DataTable( {
    "autoWidth": true,
    "paging": false,
    "searching": false
} );


$('#products_table').on('click','#status', function(event){
    event.preventDefault();
    idproducto=  $(this).data('idproducto');
    status= $(this).data('status');
    var jqxhr =  $.get(currentLocation+"product_state?id="+idproducto+"&status="+status,function(status){
    }).done(function() {
        swal({
            title: "Cambio el estado!",
            text: "El producto ha cambiado su estado.",
            confirmButtonColor: "#66BB6A",
            type: "success"
        },function(){
            window.location.reload();
        });
    }).fail(function() {
        swal("Error no se ha cambiado el estado del producto", "Intentelo nuevamente luego.", "error");
    })}
);

$('#products_table').on('click','#eliminar' ,function() {
    limpiar();
    idproducto=  $(this).data('idproducto');
    swal({
            title: "Estas seguro?",
            text: "No podras recuperar este producto si lo eliminas!",
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
                var jqxhr =  $.post(currentLocation+"product_delete",{id:idproducto},function(data,status){

                }).done(function() {
                    swal({
                        title: "Eliminado!",
                        text: "El producto ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar producto", "Intentelo nuevamente luego.", "error");
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

$('#products_table').on('click','#editar',function(){
    var idproducto=  $(this).data('idproducto');
    window.open(currentLocation+"servicio_editar?id="+idproducto, "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no, toolbar=yes,scrollbars=yes,resizable=yes,,200,width=800,height=600");

});

function limpiar(){
    $('#img1').attr('src', currentLocation+'images/imagen.png');
    $('#img2').attr('src', currentLocation+'images/imagen.png');
    $('#img3').attr('src', currentLocation+'images/imagen.png');
    $('#img4').attr('src', currentLocation+'images/imagen.png');
    $('#nombregroup').removeClass("has-error");
    $('#categoriagroup').removeClass("has-error");
    $('#preciogroup').removeClass("has-error");
    $('#costogroup').removeClass("has-error");
    $('#procedenciagroup').removeClass("has-error");
    $('#fabricantegroup').removeClass("has-error");
    $('#ubicaciongroup').removeClass("has-error");
    $('#proveedorgroup').removeClass("has-error");
}
function strcmp(a, b)
{
    return (a<b?-1:(a>b?1:0));
}

/****************************************************************/
