var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
$('#guardar').click(function(event) {
      productID = $(this).data('id');
      type = $('#type').val();
      quantity = $('#quantity').val();
      state = 1;

      $.post(currentLocation+'transact_store', {productID: productID, type: type , quantity:quantity, state:state}, function(data, textStatus, xhr) {
        window.location.replace(currentLocation+'single_product?id='+productID);
      }).error(function(jqXHR, textStatus, errorThrown) {
      	if(jqXHR.status ===409){
      		toastr["warning"]("La cantidad de salidad es superior al stock actual.");
      		return;
      	}
        toastr["error"]("No se logro realizar la transaccion, intentelo mas tarde")
      });
});

