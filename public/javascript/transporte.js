var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;

$('#transportes').on('click','#editar',function(){
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
    idtransporte=  $(this).data('idtransporte');
    status= $(this).data('status');
    var jqxhr =  $.get(currentLocation+"transporte_state?id="+idtransporte+"&status="+status,function(status){
    }).done(function() {
        swal({
            title: "Cambio el estado!",
            text: "El Transporte ha cambiado su estado.",
            confirmButtonColor: "#66BB6A",
            type: "success"
        },function(){
            window.location.reload();
        });
    }).fail(function() {
        swal("Error no se ha cambiado el estado del transporte", "Intentelo nuevamente luego.", "error");
    })}
);  


/**********BOTONES DENTRO DE LA TABLA PRODUCTOS ****************/

$('#transportes_table').on('click', '#detalles', function(event) {
    //limpiar();
    event.preventDefault();
    idtransporte = $(this).data('product');
    window.location.replace(currentLocation+'single_product?idtransporte='+idtransporte);
});

$('#transportes_table').on('click','#eliminar' ,function() {
    //limpiar();
    idtransporte=  $(this).data('idtransporte');

    swal({
            title: "Estas seguro?",
            text: "No podras recuperar este transporte si lo eliminas!",
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
                var jqxhr =  $.post(currentLocation+"transporte_delete",{idtransporte:idtransporte},function(data,status){

                }).done(function() {
                    swal({
                        title: "Eliminado!",
                        text: "El transporte ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar transporte", "Intentelo nuevamente luego.", "error");
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
    idtransporte=  $(this).data('idtransporte');
    nombre_trans = $(this).data('nombre_trans');
    marca = $(this).data('marca');
    tipo = $(this).data('tipo');
    placa = $(this).data('placa');


    $('#idtransporte').val(idtransporte);
    $('#nombre_trans').val(nombre_trans);
    $('#marca').val(marca);
    $('#tipo').val(tipo);
    $('#placa').val(placa);

    $('#formulario').modal('show');
});
/***************************************************************/

/***********ACCIONES DENTRO DEL MODAL EDITAR Y CREAR ***********/
$('#formulario').on('click','#guardar_cambios',function(){
    $('#guardar_cambios').prop( "disabled", true );
    idtransporte = $('#idtransporte').val();
    nombre_trans = $('#nombre_trans').val();
    marca = $('#marca').val();
    tipo = $('#tipo').val();
    placa = $('#placa').val();


    if(validar_datos()){
        arrayPost = {idtransporte:idtransporte, nombre_trans:nombre_trans, marca:marca, tipo:tipo, placa:placa, edit:edit};
        console.log(arrayPost);
        var jqxhr =  $.post(currentLocation+"transporte_store",arrayPost,function(data,status){

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
$('#nuevo_transporte').on('click',function(){
    //limpiar();
    edit = 0;
    $('#idtransporte').val('');
    $('#nombre_trans').val('');
    $('#marca').val('');
    $('#tipo').val('');
    $('#placa').val('');

    $('#formulario').modal('show');
    if(validar_datos()){
        var array_datos = {}
    }

});

/****************************************************************/
/*****************VALIDAR****************************************/

function validar_datos(){
    nombre_trans =  $('#nombre_trans').val();

    if(nombre_trans  === undefined || nombre_trans === ''){
        $('#nombre_transgroup').addClass("has-error");
        return false;
    }
    return true;

}

function limpiar(){
    $('#nombre_transgroup').removeClass("has-error");
    $('#marcagroup').removeClass("has-error");
}