var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
var edit  = 0;

$('#categorias').on('click','#editar',function(){
  console.log("click");
    $('#formulario').modal('show');

});

// Basic setup
$(".colorpicker-basic").spectrum();

table = $('#products_table').DataTable( {
    "autoWidth": true,
} );




/**********BOTONES DENTRO DE LA TABLA TAGS ****************/

$('#tags_table').on('click','#eliminar' ,function() {
    limpiar();
    idtag =  $(this).data('idtag');

    swal({
            title: "¿Estás seguro?",
            text: "No podrás recuperar este tag si lo eliminas",
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
                var jqxhr =  $.post(currentLocation+"tag_delete",{idtag:idtag},function(data){

                }).done(function() {
                    swal({
                        title: "Eliminado",
                        text: "El tag ha sido eliminado.",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    },function(){
                        window.location.reload();
                    });
                }).fail(function() {
                    swal("Error al eliminar tag", "Inténtelo nuevamente luego.", "error");
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

$('#tags_table').on('click','#editar',function(){
    limpiar();
    edit = 1;
    idtag=  $(this).data('idtag');
    nombre = $(this).data('nombre');
    $('#idtag').val(idtag);
    $('#nombre').val(nombre);
    $('#formulario').modal('show');
});
/***************************************************************/

/***********ACCIONES DENTRO DEL MODAL EDITAR Y CREAR ***********/
$('#formulario').on('click','#guardar_cambios',function(){
    $('#guardar_cambios').prop( "disabled", true );
    idtag = $('#idtag').val();
    nombre = $('#nombre').val();
    vali=false;
    $.get(currentLocation+"tag_duplicated_state?nombre="+nombre,function(data){      
      if(data==0){      
        if(validar_datos()){
              arrayPost = {idtag:idtag, nombre:nombre, edit:edit};
              console.log(arrayPost);
              var jqxhr =  $.post(currentLocation+"tag_store",arrayPost,function(data){

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
        alert("Ya existe un tag con este nombre \n Ingresa un nuevo nombre. ");
        $('#guardar_cambios').prop( "disabled", false );
      }
    });
});

/***************************************************************/

/*****************NUEVO TAG********************************/
$('#nuevo_tag').on('click',function(){
    limpiar();
    edit = 0;
    $('#idtag').val('');
    $('#nombre').val('');
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
    if(nombre  === undefined || nombre === ''){
        $('#nombregroup').addClass("has-error");
        return false;
    }
    return true;
}

function limpiar(){
    $('#nombregroup').removeClass("has-error");
}