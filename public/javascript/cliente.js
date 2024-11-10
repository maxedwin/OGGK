var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';

$('#btn_guardar').click(function(event){
    var idcliente = $('#idcliente').val();
    var ruc_dni = $('#ruc_dni').val();
    var razon_social = $('#razon_social').val();
    var direccion = $('#direccion').val();
    var distrito = $('#distrito').val();
    var referencia = $('#referencia').val();
    var contacto_nombre = $('#contacto_nombre').val();
    var contacto_telefono = $('#contacto_telefono').val();
    var contacto_email = $('#contacto_email').val();
    var dias_credito = $('#dias_credito').val();
    var tipo_emp = $('#tipo_emp').val();

    console.log(idcliente);

    if(ruc_dni.length == 0){
        swal({
            title: "Upss!",
            text: "Debes agregar el RUC/DNI del cliente",
            confirmButtonColor: "#66BB6A",
            type: "error"
        },function(){
            //window.location.reload();
        });
        return;
    }
    if(razon_social.length == 0){
        swal({
            title: "Upss!",
            text: "Debes agregar la razon social del Cliente",
            confirmButtonColor: "#66BB6A",
            type: "error"
        },function(){
            //window.location.reload();
        });
        return;
    }
    if(referencia.length == 0){
        swal({
            title: "Upss!",
            text: "Debes agregar la referencia",
            confirmButtonColor: "#66BB6A",
            type: "error"
        },function(){
            //window.location.reload();
        });
        return;
    }

    console.log(idcliente);
    $.post(currentLocation+'guardar_cliente',{icliente:idcliente, dni:dni, ruc:ruc, nombres:nombres, apellidos:apellidos,direccion:direccion,telefono:telefono,email:email},function(data){
        obj = JSON.parse(data);
        if(obj.mensaje === 200){
            swal({
                title: "Ok!",
                text: "Se guardo correctamente!.",
                confirmButtonColor: "#66BB6A",
                type: "success"
            },function(){
               // window.location.reload();
            });
            return;
        }else{
            swal({
                title: "Error..!",
                text: "No se puede guardar el cliente, intentalo de nuevo luego.",
                confirmButtonColor: "#66BB6A",
                type: "error"
            },function(){
                window.location.reload();
            });
            return;
        }
    });

});
