var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit = 0;

$('#marca').on('click','#editar',function(){
	console.log("click");
    $('#formulario').modal('show');

});


/**********BOTONES DENTRO DE LA TABLA PRODUCTOS ****************/

$('#marca_table').on('click', '#detalles', function(event) {
    limpiar();
    event.preventDefault();
    id = $(this).data('product');
    window.location.replace(currentLocation+'single_product?id='+id);
});

$('#marca_table').on('click','#eliminar' ,function() {
    limpiar();
    idmarca=  $(this).data('idmarca');

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
                var jqxhr =  $.post(currentLocation+"marca_delete",{idmarca:idmarca},function(data,status){

                }).done(function() {
                    swal({
                        title: "Eliminado!",
                        text: "La Marca ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar marca", "Intentelo nuevamente luego.", "error");
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

$('#marca_table').on('click','#editar',function(){
    limpiar();
    edit = 1;

    idmarca=  $(this).data('idmarca');
    imagen = $(this).data('imagen');
    nombre = $(this).data('nombre');
    descripcion = $(this).data('descripcion');


    $('#idmarca').val(idmarca);
    $('#nombre').val(nombre);
    $('#descripcion').val(descripcion);
    $('#img').attr('src',imagen);




    $('#formulario').modal('show');
});
/***************************************************************/

/***********ACCIONES DENTRO DEL MODAL EDITAR Y CREAR ***********/
$('#formulario').on('click','#guardar_cambios',function(){
    idmarca = $('#idmarca').val();
    descripcion = $('#descripcion').val();
    nombre = $('#nombre').val();
    var imagen = $("#imagen")[0].files;

    var formData = new FormData();

    formData.append('imagen', imagen[0]);
    formData.append('idmarca',idmarca);
    formData.append("descripcion",descripcion);
    formData.append("nombre",nombre);
    formData.append("edit",edit);


    if(validar_datos()){
        $.ajax({
            url: currentLocation+"marca_store", //You can replace this with MVC/WebAPI/PHP/Java etc
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#formulario').modal('hide');

                swal({
                        title: "Bien hecho!",
                        text: "Se guardo correctamente",
                        type: "success"
                    },
                    function(){
                         window.location.reload()
                    });
            },
            error: function (error) { swal("Error al guardar", "Intentelo nuevamente luego.", "error"); }

        });
    }

});

/***************************************************************/

/*****************NUEVO PRODUCTO********************************/
$('#nuevo_producto').on('click',function(){
    limpiar();
    edit = 0;

    $('#idmarca').val('');
    $('#nombre').val('');
    $('#descripcion').val('');
    $('#img').attr('src',' ');

    $('#formulario').modal('show');
});

/****************************************************************/
/*****************VALIDAR****************************************/

function validar_datos(){
    nombre =  $('#nombre').val();


    if(nombre  === undefined || nombre === ''){
        $('#nombregroup').addClass("has-error");
        return false;
    }


    return true;

}

function limpiar(){
    $('#nombregroup').removeClass("has-error");
    $('#categoriagroup').removeClass("has-error");

}
