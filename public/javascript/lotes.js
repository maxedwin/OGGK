
var currentLocation =  $('meta[name="base_url"]').attr('content')+'/';


/***************************************************************/

/**********BOTONES DENTRO DE LA TABLA PRODUCTOS ****************/
table = $('#products_table').DataTable( {
    autoWidth: true,
    dom: 'lBfrtip',
    buttons: [
        'excel'
    ]
} );
