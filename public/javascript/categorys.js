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

$('#products_table').on('click','#status', function(event){
    event.preventDefault();
    idcategoria=  $(this).data('idcategoria');
    status= $(this).data('status');
    var jqxhr =  $.get(currentLocation+"categoria_state?id="+idcategoria+"&status="+status,function(status){
    }).done(function() {
        swal({
            title: "Cambió el estado",
            text: "La Familia/SubFamilia ha cambiado su estado.",
            confirmButtonColor: "#66BB6A",
            type: "success"
        },function(){
            window.location.reload();
        });
    }).fail(function() {
        swal("Error no se ha cambiado el estado de la familia/subfamilia", "Inténtelo nuevamente luego.", "error");
    })}
);  


/**********BOTONES DENTRO DE LA TABLA PRODUCTOS ****************/

$('#categorias_table').on('click', '#detalles', function(event) {
    limpiar();
    event.preventDefault();
    id = $(this).data('product');
    window.location.replace(currentLocation+'single_product?id='+id);
});

$('#categorias_table').on('click','#eliminar' ,function() {
    limpiar();
    idcategoria=  $(this).data('idcategoria');

    swal({
            title: "¿Estás seguro?",
            text: "No podrás recuperar esta familia si la eliminas",
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
                var jqxhr =  $.post(currentLocation+"category_delete",{id:idcategoria},function(data,status){

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
                    swal("Error al eliminar familia", "Inténtelo nuevamente luego.", "error");
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
    limpiar();
    edit = 1;
    idcategoria=  $(this).data('idcategoria');
    descripcion = $(this).data('descripcion');
    padre = $(this).data('padre');
    state = $(this).data('estado');
    categoriauso = $(this).data('categoriauso');


    $('#idcategoria').val(idcategoria);
    $('#descripcion').val(descripcion);
    $('#padre').val(padre).change();
    $('#state').val(state).change();
    $('#categoriauso').val(categoriauso).change();

    $('#formulario').modal('show');
});
/***************************************************************/

/***********ACCIONES DENTRO DEL MODAL EDITAR Y CREAR ***********/
$('#formulario').on('click','#guardar_cambios',function(){
    $('#guardar_cambios').prop( "disabled", true );
    idcategoria = $('#idcategoria').val();
    descripcion = $('#descripcion').val();
    padre = $('#padre').val();
    categoriauso = $('#categoriauso').val();
    state = $('#state').val();
    vali=false;
    $.get(currentLocation+"family_duplicated_state?descripcion="+descripcion+"&padre="+padre,function(data){
      
      if(data==0 || edit){      
        if(validar_datos()){
              arrayPost = {idcategoria:idcategoria, descripcion:descripcion, idpadre:padre, edit:edit, state:state, idcategoriauso:categoriauso};
              console.log(arrayPost);
              var jqxhr =  $.post(currentLocation+"category_store",arrayPost,function(data,status){

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
      }
      else{
        //alert("Data: " + data);
        alert("Ya existe una familia con este nombre \n Ingresa un nuevo nombre. ");
        $('#guardar_cambios').prop( "disabled", false );
      }
    });

    //if(validar_familia){  
        
   // }
  /*}).fail(function() {
      $('#formulario').modal('hide');
            swal({
                  title:"Error al guardar", 
                  text: "Ya existe una  familia con ese nombre.",
                  type: "error"
                },
                function(){
                    window.location.reload()
                });

  }*/

});

/***************************************************************/

/*****************NUEVO PRODUCTO********************************/
$('#nuevo_producto').on('click',function(){
    limpiar();
    edit = 0;
    $('#idcategoria').val('');
    $('#descripcion').val('');
    $('#padre').val(0).change();
    $('#categoriauso').val(0).change();

    $('#formulario').modal('show');
    if(validar_datos()){
        var array_datos = {}
    }

});

/****************************************************************/
/*****************VALIDAR****************************************/

function validar_datos(){
    descripcion =  $('#descripcion').val();


    if(descripcion  === undefined || descripcion === ''){
        $('#nombregroup').addClass("has-error");
        return false;
    }






    return true;

}

function limpiar(){
    $('#nombregroup').removeClass("has-error");
    $('#categoriagroup').removeClass("has-error");
    $('#categoriausogroup').removeClass("has-error");
}

/****************************************************************/
/*
$('#guardar').click(function(event) {
  categID = $('#categID').val();
  description = $('#description').val();
  padre = null;


  if($('#padre').val() !== 0){
    padre = $('#padre').val();
  }

  if(padre == categID  ){s
    toastr["info"]("Escoger otra categoria padre.");
    return ;
  }

  if(categID !== ''){
    if(description !== ''){
        $.post(currentLocation+'category_update', {description: description, id: categID , parent:padre}, function(data, textStatus, xhr) {
            window.location.replace(currentLocation+'list_categorys');
            $('#categID').val('');
          }).error(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
              if (jqXHR.status === 409) {
           toastr["warning"]("Conflicto de Categorias!, prueba ingresando una categoria padre que no sea hijo de la primera.");
          }else{
             toastr["error"]("No se logro guardar la categoria, intentelo mas tarde");
          }
           
          });
    }else{
      toastr["warning"]("Debes escribir la descripcion de la categoria.");
      return;
    }
  
  }else{
    if(description !== ''){
        $.post(currentLocation+'category_store', {description: description, id: categID , parent:padre}, function(data, textStatus, xhr) {
          window.location.replace(currentLocation+'list_categorys');
          $('#categID').val('');
        }).error(function() {
          toastr["error"]("No se logro guardar el producto, intentelo mas tarde")
        });
    }else{
      toastr["warning"]("Debes escribir la descripcion de la categoria.");
      return;
    }
  }

  
});

$('#eliminar').click(function(event) {
   categID = $('#categID').val();
    if(categID !== ''){
      $.post(currentLocation+'category_delete', {id: categID}, function(data, textStatus, xhr) {
            window.location.replace(currentLocation+'list_categorys');
            $('#categID').val('');
          }).error(function() {
            toastr["error"]("No se logro guardar el eliminar, Puede que la categoria este siendo usada.")
          });
    }else{
         toastr["warning"]("Escoger categoria.")
    }

});
*/
