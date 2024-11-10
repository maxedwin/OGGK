var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';
 $.get( currentLocation+'categorys/', function( cate ) {
 		var array = []
 		$.each(cate, function(index, val) {
 			 var objJS = new Object(); 
 			 objJS.id = val.id;
 			 if(val.parent==null){
 			 	objJS.parent = "#";
 			 }else{
 			 	objJS.parent = val.parent
 			 }
			 objJS.text = val.text;
			 objJS.icon = "jstree-icon jstree-themeicon fa fa-folder icon-state-warning icon-lg jstree-themeicon-custom";
			 array.push(objJS);
 		});
 });

$('#guardar').click(function(event) {
  var categID = $('#category').val();
  if(categID == 0){
     toastr["warning"]("Debes escoger una categoria.")
     return;
  }  
      name = $('#name').val();
      quantityTotal = $('#quantityTotal').val();
      price = $('#price').val();
      state = 1;
      type = 1;

      $.post(currentLocation+'product_store', {name: name, categID: categID , quantityTotal:quantityTotal, price:price, state:state, type:type}, function(data, textStatus, xhr) {
        window.location.replace(currentLocation+'list_product');
      }).error(function() {
        toastr["error"]("No se logro guardar el producto, intentelo mas tarde")
      });

});

