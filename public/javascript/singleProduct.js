var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
idprod = $('#guardar').data('id');
var table;
$.get( currentLocation+'transact_get?id='+idprod, function( data ) {
  console.log(data[0].state);
  var content ='';
  for (var i = 0; i < data.length; i++) {
      if(data[i].type === 1){
              content += '<tr class="font-blue-steel"><td>';        
      }else{
              content += '<tr class="font-yellow-casablanca"><td>';
      }
      if(data[i].state === 1){
        content += 'Activo';
      }else{
        content += 'Inactivo';
      }
      content += '</td><td>';

      if(data[i].type === 1){
        content += 'Entrada';        
      }else{
        content += 'Salida';
      }
      content += '</td><td>';
      if(data[i].type === 1){
              content += '+'+data[i].quantity;       
      }else{
              content += '-'+data[i].quantity;    
      }
      content += '</td><td>';
      content += data[i].created_at;
      content += '</td><td>';
      content += '</td></tr>';
    }
   
  $('#transacts').append(content);
  table = $('#transacts_table').DataTable( {
      "autoWidth": true
    } );
 });


$('#guardar').click(function(event) {
  var categID = $('#category').val();
  id = $(this).data('id');
  $.get( currentLocation+'categorysID?description='+categID, function( data ) {
      idcateg = data;      
      name = $('#name').val();
      //quantityTotal = $('#quantityTotal').val();
      price = $('#price').val();
      state = $('#state').val();
      var array = {id:id, name: name, categID: idcateg , quantityTotal:quantityTotal, price:price, state:state};
      console.log(array);
      $.post(currentLocation+'product_update', array , function(data, textStatus, xhr) {
        window.location.replace(currentLocation+'list_product');
      }).error(function() {
        toastr["error"]("No se logro guardar el producto, intentelo mas tarde")
      });
  });
});
$('#eliminar').click(function(event) {
  id = $(this).data('id');
  $.post(currentLocation+'product_delete', {id:id}, function(data, textStatus, xhr) {
        window.location.replace(currentLocation+'list_product');
      }).error(function() {
        toastr["error"]("No se logro eliminar el producto, intentelo mas tarde")
      });

});