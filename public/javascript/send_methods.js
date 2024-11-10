var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;

// Basic setup
$(".colorpicker-basic").spectrum();

table = $('#send_methods_table').DataTable( {
    "autoWidth": true,
} );




/**********BOTONES DENTRO DE LA TABLA SEND METHODS ****************/

$('#send_methods_table').on('click','#eliminar' ,function() {
    limpiar();
    id_sendmethod =  $(this).data('id_sendmethod');

    swal({
            title: "¿Estás seguro?",
            text: "No podrás recuperar este método de envío si lo eliminas",
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
                var jqxhr =  $.post(currentLocation+"sendmethod_delete",{id_sendmethod:id_sendmethod},function(data){

                }).done(function(data) {
                    if (data.message == 'exception'){
                        swal({
                            title: "No se pudo eliminar",
                            text: "Siempre debe quedar al menos un método de envío. No puede eliminarlo.",
                            confirmButtonColor: "#2196F3",
                            type: "error"
                        },function(){});
                    }
                    else if(data.message == 'accepted'){
                        swal({
                            title: "Eliminado",
                            text: "El método de envío ha sido eliminado.",
                            confirmButtonColor: "#66BB6A",
                            type: "success"
                        },function(){
                            window.location.reload();
                        });
                    }                   
                }).fail(function() {
                    swal("Error al eliminar método de envío", "Inténtelo nuevamente luego.", "error");
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

$('#send_methods_table').on('click','#editar',function(){
    limpiar();
    edit = 1;
    id_sendmethod=  $(this).data('id_sendmethod');
    nombre = $(this).data('nombre');
    descripcion = $(this).data('descripcion');
    precio = $(this).data('precio');
    $('#id_sendmethod').val(id_sendmethod);
    $('#nombre').val(nombre);
    $('#descripcion').val(descripcion);
    $('#precio').val(precio);
    $('#formulario').modal('show');
});
/***************************************************************/

/***********ACCIONES DENTRO DEL MODAL EDITAR Y CREAR ***********/
$('#formulario').on('click','#guardar_cambios',function(){
    $('#guardar_cambios').prop( "disabled", true );
    id_sendmethod = $('#id_sendmethod').val();
    nombre = $('#nombre').val();
    descripcion = $('#descripcion').val();
    precio =  $('#precio').val();
    vali=false;
    $.get(currentLocation+"sendmethod_duplicated_state?nombre="+nombre,function(data){      
      if(data==0 || edit){      
        if(validar_datos()){
              arrayPost = {id_sendmethod:id_sendmethod, nombre:nombre, descripcion:descripcion, precio:precio, edit:edit};
              console.log(arrayPost);
              var jqxhr =  $.post(currentLocation+"sendmethod_store",arrayPost,function(data){

            }).done(function() {
                $('#formulario').modal('hide');

                swal({
                        title: "Bien hecho",
                        text: "Se guardó correctamente",
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
                      text: "Inténtelo nuevamente luego.",
                      type: "error"
                    },
                    function(){
                        window.location.reload()
                    });
            });

                  $('#formulario').modal('hide');
              swal("Bien hecho", "Se editó correctamente", "success")
          }
          else{
            alert("Faltan campos por llenar. \n Asegúrese de llenar todos los campos solicitados.");
            $('#guardar_cambios').prop( "disabled", false );
          }
      }
      else{
        alert("Ya existe un método de envío con este nombre \n Ingresa un nuevo nombre. ");
        $('#guardar_cambios').prop( "disabled", false );
      }
    });
});

/***************************************************************/

/*****************NUEVO METODO DE ENVIO********************************/
$('#nuevo_sendmethod').on('click',function(){
    limpiar();
    edit = 0;
    $('#id_sendmethod').val('');
    $('#nombre').val('');
    $('#descripcion').val('');
    $('#precio').val(0);
    $('#formulario').modal('show');
    if(validar_datos()){
        var array_datos = {}
    }
});

/****************************************************************/
/*****************VALIDAR****************************************/

function validar_datos(){
    console.log("Entró a validar")
    nombre =  $('#nombre').val();
    descripcion = $('#descripcion').val();
    precio = $('#precio').val();
    
    result = true;

    if(nombre  === undefined || nombre === ''){
        $('#nombregroup').addClass("has-error");
        result = false;
    }
    if(descripcion === undefined || descripcion === ''){
        $('#descripciongroup').addClass("has-error");
        result = false;
    }
    if(precio  === undefined || precio === ''){
        $('#preciogroup').addClass("has-error");
        result = false;
    }
    return result;
}

function limpiar(){
    $('#nombregroup').removeClass("has-error");
    $('#descripciongroup').removeClass("has-error");
    $('#preciogroup').removeClass("has-error");
}