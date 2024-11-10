var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;

 $('#distrito').select2({dropdownParent: $('#formulario')});
 $('#provincia').select2({dropdownParent: $('#formulario')});
 $('#departamento').select2({dropdownParent: $('#formulario')});

$('#almacenes').on('click','#editar',function(){
	console.log("click");
    $('#formulario').modal('show');

});

// Basic setup
$(".colorpicker-basic").spectrum();

table = $('#products_table').DataTable( {
    "autoWidth": true,
} );

$('#products_table').on('click','#status', function(event){
    event.preventDefault();
    idalmacen=  $(this).data('idalmacen');
    status= $(this).data('status');
    var jqxhr =  $.get(currentLocation+"almacen_state?id="+idalmacen+"&status="+status,function(status){
    }).done(function() {
        swal({
            title: "Cambio el estado!",
            text: "El Almacen ha cambiado su estado.",
            confirmButtonColor: "#66BB6A",
            type: "success"
        },function(){
            window.location.reload();
        });
    }).fail(function() {
        swal("Error no se ha cambiado el estado del almacen", "Intentelo nuevamente luego.", "error");
    })}
);  


/**********BOTONES DENTRO DE LA TABLA PRODUCTOS ****************/

$('#almacenes_table').on('click', '#detalles', function(event) {
    //limpiar();
    event.preventDefault();
    idalmacen = $(this).data('product');
    window.location.replace(currentLocation+'single_product?idalmacen='+idalmacen);
});

$('#almacenes_table').on('click','#eliminar' ,function() {
    //limpiar();
    idalmacen=  $(this).data('idalmacen');

    swal({
            title: "Estas seguro?",
            text: "No podras recuperar este almacen si lo eliminas!",
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
                var jqxhr =  $.post(currentLocation+"almacen_delete",{idalmacen:idalmacen},function(data,status){

                }).done(function() {
                    swal({
                        title: "Eliminado!",
                        text: "El almacen ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar almacen", "Intentelo nuevamente luego.", "error");
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
    //limpiar();
    edit = 1;
    idalmacen=  $(this).data('idalmacen');
    nombre = $(this).data('nombre');
    direccion = $(this).data('direccion');
    distrito = $(this).data('distrito');
    provincia = $(this).data('provincia');
    departamento = $(this).data('departamento');


    $('#idalmacen').val(idalmacen);
    $('#nombre').val(nombre);
    $('#direccion').val(direccion);
    $('#distrito').val(distrito).change();
    $('#provincia').val(provincia).change();
    $('#departamento').val(departamento).change();

    $('#formulario').modal('show');
});
/***************************************************************/

/***********ACCIONES DENTRO DEL MODAL EDITAR Y CREAR ***********/
$('#formulario').on('click','#guardar_cambios',function(){
    $('#guardar_cambios').prop( "disabled", true );
    idalmacen = $('#idalmacen').val();
    nombre = $('#nombre').val();
    direccion = $('#direccion').val();
    distrito = $('#distrito').val();
    provincia = $('#provincia').val();
    departamento = $('#departamento').val();


    if(validar_datos()){
        arrayPost = {idalmacen:idalmacen, nombre:nombre, direccion:direccion, distrito:distrito, provincia:provincia, departamento:departamento, edit:edit};
        console.log(arrayPost);
        var jqxhr =  $.post(currentLocation+"almacen_store",arrayPost,function(data,status){

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
        $('#guardar_cambios').prop( "disabled", false );
          swal("Error al guardar", "Intentelo nuevamente luego.", "error");
      });

            $('#formulario').modal('hide');
        swal("Bien hecho!", "Se edito correctamente", "success")
    }

});

/***************************************************************/

/*****************NUEVO PRODUCTO********************************/
$('#nuevo_almacen').on('click',function(){
    //limpiar();
    edit = 0;
    $('#idalmacen').val('');
    $('#nombre').val('');
    $('#direccion').val('');
    $('#distrito').val('');
    $('#provincia').val('');
    $('#departamento').val('');

    $('#formulario').modal('show');
    if(validar_datos()){
        var array_datos = {}
    }

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
    $('#direcciongroup').removeClass("has-error");
}