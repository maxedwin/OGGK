<?php
Route::get('/form', ['as' => 'form', 'uses' => 'FormController@index']);

Route::group(['middleware' => 'cors'], function(){
    Route::post('Auth_token','ApiAuthController@UserAuth');
    Route::get('prueba','PruebaController@index');
});

Route::get('/maintenance', function () {
    return view('maintenance_page');
});

  //already there after laravel auth scaffold
Route::group(['middleware' => ['maintenance', 'web']], function () {
    Route::auth();  
    Route::get('register', 'Auth\AuthController@showRegistrationForm');
    Route::post('register', 'Auth\AuthController@register');
});

Route::group(['middleware' => ['maintenance', 'web']], function () {

    Route::get('home', 'HomeController@index');
    Route::get('/', 'HomeController@index');

    Route::post('store_nota', 'HomeController@store')->name('store_nota');
    Route::get('delete_nota/{id}','HomeController@delete');
    //Route::get('home', 'HomeController@chartjs');
    //Route::get('/', 'HomeController@chartjs');


    Route::get('login/', function () {
        return view('login');
    });

    Route::get('mail',function(){
    	dd(Config::get('mail'));    	
    });
    Route::get('user/activation/{token}', 'Auth\AuthController@activateUser')->name('user.activate');
});

Route::group(['middleware' => ['maintenance', 'web']], function () {
    Route::auth();
    //////////////////////////////Con LOGGIN///////////////////////////////
    Route::get('listado_usuarios','UserController@index');
    Route::post('cambiar_pass','UserController@change_password');
    Route::post('disable_user','UserController@disable_user');
    Route::post('enable_user','UserController@enable_user');
    Route::get('vendedores','UserController@vendedores');


    Route::get('routes','TrackingController@index');


    //EVALUACIONES ********
    Route::get('eval_proveedor','EvaluacionesController@list_prov');
    Route::get('eval_listado','EvaluacionesController@index');
    Route::post('crear_evaluacion','EvaluacionesController@store');


     //categorys resfull
    Route::get('family_duplicated_state','CategoryController@family_duplicated');
    Route::get('categorys','CategoryController@index');
    Route::post('category_store','CategoryController@store');
    Route::get('category_get','CategoryController@show');
    Route::post('category_update','CategoryController@update');
    Route::post('category_delete','CategoryController@destroy');
    Route::get('categorysID','CategoryController@get_ID');
    Route::get('list_categorys','CategoryController@create');
    Route::get('categoria_state','CategoryController@status');


    //categorias de uso (tienda)
    Route::get('list_categorias_uso', 'Categorias_usoController@index');
    Route::get('nuevo_categorys_uso','Categorias_usoController@create');
    Route::post('categorys_uso_store','Categorias_usoController@store')->name("categorys_uso_store");
    Route::post('categorys_uso_delete','Categorias_usoController@delete');
    Route::get('categorys_uso_edit','Categorias_usoController@update');
    Route::post('categorys_uso_edit_store','Categorias_usoController@store_update')->name("categorys_uso_edit_store");



    //catalogos virtuales (tienda)
    Route::get('list_catalogos_virtual', 'Catalogos_virtualController@index');
    Route::get('nuevo_catalogos_virtual','Catalogos_virtualController@create');
    Route::post('catalogos_virtual_store','Catalogos_virtualController@store')->name("catalogos_virtual_store");
    Route::post('catalogos_virtual_delete','Catalogos_virtualController@delete');
    Route::get('catalogos_virtual_edit','Catalogos_virtualController@update');
    Route::post('catalogos_virtual_edit_store','Catalogos_virtualController@store_update')->name("catalogos_virtual_edit_store");


    //Tags de busqueda (tienda)
    Route::get('tag_duplicated_state','TagController@tag_duplicated');
    Route::get('tags','TagController@index');
    Route::post('tag_store','TagController@store');
    Route::post('tag_update','TagController@update');
    Route::post('tag_delete','TagController@destroy');
    Route::get('list_tags','TagController@create');


    //Metodos de envio (tienda)
    Route::get('sendmethod_duplicated_state','SendMethodController@sendmethod_duplicated');
    Route::get('send_methods','SendMethodController@index');
    Route::post('sendmethod_store','SendMethodController@store');
    Route::post('sendmethod_update','SendMethodController@update');
    Route::post('sendmethod_delete','SendMethodController@destroy');
    Route::get('list_sendmethods','SendMethodController@create');

    //Metodos de pago (tienda)
    Route::get('list_paymethods', 'PayMethodController@index');
    Route::get('nuevo_paymethod','PayMethodController@create');
    Route::post('paymethod_store','PayMethodController@store')->name("paymethod_store");
    Route::post('paymethod_delete','PayMethodController@delete');
    Route::get('paymethod_edit','PayMethodController@update');
    Route::post('paymethod_edit_store','PayMethodController@store_update')->name("paymethod_edit_store");

    //transporte resfull
    Route::get('transportes','TransporteController@index');
    Route::post('transporte_store','TransporteController@store');
    Route::get('transporte_get','TransporteController@show');
    Route::post('transporte_update','TransporteController@update');
    Route::post('transporte_delete','TransporteController@destroy');
    Route::get('transportesID','TransporteController@get_ID');
    Route::get('list_transportes','TransporteController@create');
    Route::get('transporte_state','TransporteController@status');

    
    Route::get('almacenes','AlmacenController@index');
    Route::post('almacen_store','AlmacenController@store');
    Route::get('almacen_get','AlmacenController@show');
    Route::post('almacen_update','AlmacenController@update');
    Route::post('almacen_delete','AlmacenController@destroy');
    Route::get('almacenID','AlmacenController@get_ID');
    Route::get('list_almacenes','AlmacenController@create');
    Route::get('almacen_state','AlmacenController@status');


    //product resfull
    Route::get('list_product/','ProductController@index');
    Route::get('producto_nuevo','ProductController@create');
    Route::get('producto_editar','ProductController@update');
    Route::post('product_store','ProductController@store');
    Route::post('product_store_update','ProductController@store_update');
    Route::post('product_delete','ProductController@destroy');
    Route::get('product_state','ProductController@status');
    Route::get('product_find','ProductController@find');
    Route::get('product_get','ProductController@get');
    Route::get('lista_lote','ProductController@lista_lote');
    Route::get('lista_codigo_sunat','ProductController@lista_codigo_sunat');
    Route::post('subir_imagen','ProductController@subir_imagen')->name("subir_imagen");
    Route::get('delete-image/{id}','ProductController@deleteImage');
    Route::post('subir_ficha_tecnica','ProductController@subir_ficha_tecnica')->name("subir_ficha_tecnica");
    Route::get('delete-ficha/{id}','ProductController@deleteFicha');

    

   
    //SERVICIOS
    Route::get('list_servicios/','ServiciosController@index');
    Route::get('servicio_nuevo/','ServiciosController@create');
    Route::get('servicio_editar','ServiciosController@update');
    Route::post('servicio_store','ServiciosController@store');
    Route::post('servicio_store_update','ServiciosController@store_update');
    Route::post('servicio_delete','ServiciosController@destroy');

    //CLIENTES
    Route::get('list_clientes','ClienteController@index');
    Route::get('list_clientes_tienda','ClienteController@index_tienda');
    Route::get('list_clientes_nuevos','ClienteController@index');
    Route::get('list_clientes_frecuentes','ClienteController@index');
    Route::get('list_clientes_no_frecuentes','ClienteController@index');
    Route::get('list_potenciales','PotencialClienteController@index');
    Route::get('listado_reclamos','ClienteController@index_reclamos');
    Route::get('listado_deudores','ClienteController@index_deudores');
    Route::get('listado_deudores/{criteria}','ClienteController@index_deudores');
    Route::get('switch_tipo_clientes','ClienteController@switch_tipo_clientes');
    Route::get('nuevo_cliente','ClienteController@create');
    Route::get('editar_cliente','ClienteController@update');
    Route::get('editar_potencial','PotencialClienteController@update');
    Route::get('promover_potencial','PotencialClienteController@promote');
    Route::get('eliminar_cliente','ClienteController@delete');
    Route::get('eliminar_potencial','PotencialClienteController@delete');
    Route::post('guardar_cliente','ClienteController@store');
    Route::post('store_update_cli','ClienteController@store_update_cli');
    Route::post('store_update_pot','PotencialClienteController@store_update_pot');
    Route::get('cliente_state','ClienteController@status');
    Route::get('clienteSINRUC','ClienteController@cliente_sinruc')->name("clienteSINRUC");
    Route::post('buscarRuc','ClienteController@buscar_ruc')->name("buscarRuc");
    Route::post('buscarReniec','ClienteController@buscar_reniec')->name("buscarReniec");
    Route::post('llamada','ClienteController@llamada');
    Route::post('reclamo','ClienteController@reclamo');
    Route::get('mapa_clientes','ClienteController@clientMap');
    Route::get('ubicaciones_clientes','ClienteController@clienteUbicaciones')->name('ubicaciones_clientes');
    Route::post('allCliente', 'ClienteController@allCliente' )->name('allCliente');
    Route::get('/exportCliente', 'ClienteController@exportData')->name('exportCliente');


    Route::get('cambiar_enproceso','ClienteController@cambiar_enproceso');
    Route::get('cambiar_solucionado','ClienteController@cambiar_solucionado');
    

    //PROVEEDORES
    Route::get('list_proveedores','ProveedorController@index');
    Route::get('nuevo_proveedor','ProveedorController@create');
    Route::get('editar_proveedor','ProveedorController@update');
    Route::get('eliminar_proveedor','ProveedorController@delete');
    Route::post('guardar_proveedor','ProveedorController@store');
    Route::post('store_update_prov','ProveedorController@store_update_prov');
    Route::get('proveedor_state','ProveedorController@status');

    //transact resfull
    Route::get('transacts','TransactController@index');
    Route::post('transact_store','TransactController@store');
    Route::get('transact_get','TransactController@show');
    Route::put('transact_update','TransactController@update');
    Route::delete('transact_delete','TransactController@destroy');

    Route::get('ins','TransactController@create'); 
    Route::get('outs','TransactController@create');

    //caja
    Route::get('listado_caja','CajaController@index');
    Route::get('listado_caja_detallado','CajaController@index_detallado');
    Route::get('caja','CajaController@crear');
    Route::get('buscarProductoCaja','CajaController@buscar_producto')->name("buscarProductoCaja");
    Route::get('buscarLote','CajaController@buscar_lote')->name("buscarLote");
    Route::get('buscarCliente','CajaController@buscar_cliente');
    Route::get('buscarGRTodo','CajaController@buscar_gr_todo');
    Route::post('crear_caja','CajaController@store');
    Route::get('info_caja','CajaController@show');
    Route::get('caja_state','CajaController@status');
    Route::post('enlazar_guias','CajaController@enlazar_guias');
    Route::post('caja_estado','CajaController@caja_estado');
    Route::post('caja_update_codigonb','CajaController@update_codigoNB');
    Route::post('caja_update_vendedor','CajaController@update_vendedor');
    Route::post('caja_edit_comments','CajaController@caja_edit_comments');
    Route::get('exchange_pen','CajaController@exchange');
    Route::post('allFB', 'CajaController@allFB' )->name('allFB');
    Route::get('/exportFB', 'CajaController@exportData')->name('exportFB');


    //cotizacion
    Route::get('listado_cotizacion','CotizacionController@index');
    Route::get('cotizacion','CotizacionController@crear');
    Route::get('buscarProductoCoti','CotizacionController@buscar_producto');
    Route::get('buscarCliente','CotizacionController@buscar_cliente');
    Route::post('crear_cotizacion','CotizacionController@store');
    Route::get('info_cotizacion','CotizacionController@show');
    Route::get('print_cotizacion','CotizacionController@print');
    Route::get('cotizacion_state','CotizacionController@status');
    Route::post('ct_estado','CotizacionController@ct_estado');
    Route::post('ct_edit_comments','CotizacionController@ct_edit_comments');

    //ordenVenta
    Route::get('listado_orden_venta','OrdenVentaController@index');
    Route::get('listado_orden_venta_tienda','OrdenVentaController@index_tienda');
    Route::get('listado_orden_venta_detallado','OrdenVentaController@index_detallado');
    Route::get('listado_orden_venta_por_confirmar','OrdenVentaController@por_confirmar');
    Route::get('orden_venta','OrdenVentaController@crear');
    Route::get('buscarProducto','OrdenVentaController@buscar_producto');
    Route::get('buscarClienteOV','OrdenVentaController@buscar_clienteOV');
    Route::get('buscarCotiNum','OrdenVentaController@buscar_coti_numeracion');
    Route::get('buscarCotiTodo','OrdenVentaController@buscar_coti_todo');
    Route::post('crear_orden_venta','OrdenVentaController@store');
    Route::get('info_orden_venta','OrdenVentaController@show');
    Route::get('info_orden_venta_tienda','OrdenVentaController@show_tienda');
    Route::get('ordenventa_state','OrdenVentaController@status');
    Route::post('ov_estado','OrdenVentaController@ov_estado');
    Route::post('orden_update_vendedor','OrdenVentaController@update_vendedor');
    Route::post('ov_complete_order','OrdenVentaController@ov_complete_order');
    Route::post('ov_edit_comments','OrdenVentaController@ov_edit_comments');
    Route::post('confirm_ov','OrdenVentaController@confirm_ov');
    Route::post('deny_ov','OrdenVentaController@deny_ov');

    Route::post('ov_add_hr','OrdenVentaController@add_hoja_ruta');
    Route::post('quitar_hoja_ruta','OrdenVentaController@quitar_hoja_ruta');
    Route::get('hoja_ruta_admin','OrdenVentaController@hoja_ruta');
    Route::get('hoja_ruta_next_day','OrdenVentaController@hoja_ruta_next_day');
    Route::get('hoja_ruta_reporte','OrdenVentaController@hoja_ruta_reporte');

    Route::post('allOV', 'OrdenVentaController@allOV' )->name('allOV');
    Route::get('/exportOV', 'OrdenVentaController@exportData')->name('exportOV');

    Route::get('anularCajaRechazada','CajaController@anularCajaRechazada');


    //guiaremision
    Route::get('listado_guia_remision','GuiaRemisionController@index');
    Route::get('listado_guia_remision_detallado','GuiaRemisionController@index_detallado');
    Route::get('listado_GRpendientes','GuiaRemisionController@guias_pendientes');
    Route::get('guia_remision','GuiaRemisionController@crear');
    Route::get('guia_remisionsolopdf','GuiaRemisionController@crearsolopdf');    
    Route::post('generar_pdf','GuiaRemisionController@generatePDF');

   
    
    Route::post('checkCDRGR','GuiaRemisionController@checkCDRGR');
    Route::post('checkCDRFB','CajaController@checkCDRFB');
    Route::post('checkCDRNC','NotaCreditoController@checkCDRNC');
    
    Route::get('buscarProductoGR','GuiaRemisionController@buscar_producto');
    Route::get('buscarCliente','GuiaRemisionController@buscar_cliente');
    Route::get('buscarOV','GuiaRemisionController@buscar_ov_numeracion');
    Route::get('buscarOVInc','GuiaRemisionController@buscar_ov_numeracion_inc');
    Route::get('buscarOVPDF','GuiaRemisionController@buscar_ov_pdf');
    Route::get('buscarOVTodo','GuiaRemisionController@buscar_ov_todo');
    Route::get('buscarGuias','GuiaRemisionController@buscar_guias');
    Route::get('buscarOVTodoGuia','GuiaRemisionController@buscar_ov_todo_guia');
    Route::post('crear_guia_remision','GuiaRemisionController@store');
    Route::get('info_guia_remision','GuiaRemisionController@show');
    Route::get('lotes_guia_remision','GuiaRemisionController@lotes');
    Route::post('gr_estado','GuiaRemisionController@gr_estado');
    Route::post('gr_estado_reprogramar','GuiaRemisionController@gr_estado_reprogramar');
    Route::get('lista_codigo_ubigeo','GuiaRemisionController@lista_codigo_ubigeo');
    Route::post('gr_estado_anulado','GuiaRemisionController@gr_estado_anulado');
    Route::post('update_codigonb','GuiaRemisionController@update_codigoNB');
    Route::post('gr_edit_comments','GuiaRemisionController@gr_edit_comments');
    Route::post('gr_detail','GuiaRemisionController@gr_detail');
    Route::post('gr_history','GuiaRemisionController@gr_history');

    
    Route::post('allGR', 'GuiaRemisionController@allGR' )->name('allGR');
    Route::get('/exportGR', 'GuiaRemisionController@exportData')->name('exportGR');

     //guiatraslado
     /*Route::get('listado_guia_traslado','GuiaTrasladoController@index');
     Route::get('listado_guia_traslado_detallado','GuiaTrasladoController@index_detallado');
     Route::get('listado_GTpendientes','GuiaTrasladoController@guias_pendientes');
     Route::get('guia_traslado','GuiaTrasladoController@crear');
     Route::get('guia_trasladosolopdf','GuiaTrasladoController@crearsolopdf');    
     Route::post('generar_pdf','GuiaTrasladoController@generatePDF');*/
 
    
     
     Route::post('checkCDRGT','GuiaTrasladoController@checkCDRGR');     
     Route::get('buscarProductoGR','GuiaTrasladoController@buscar_producto');
     Route::get('buscarGuiasT','GuiaTrasladoController@buscar_guias');
     Route::get('buscarOVTodoGuiaT','GuiaTrasladoController@buscar_ov_todo_guia');
     Route::post('crear_guia_traslado','GuiaTrasladoController@store');
     Route::get('info_guia_traslado','GuiaTrasladoController@show');
     Route::post('gt_estado','GuiaTrasladoController@gr_estado');
     Route::post('gt_estado_reprogramar','GuiaTrasladoController@gr_estado_reprogramar');
     Route::post('gt_estado_anulado','GuiaTrasladoController@gr_estado_anulado');
     Route::post('gt_edit_comments','GuiaTrasladoController@gr_edit_comments');
     Route::post('gt_detail','GuiaTrasladoController@gr_detail');
 
     
     Route::post('allGT', 'GuiaTrasladoController@allGR' )->name('allGT');
     Route::get('/exportGT', 'GuiaTrasladoController@exportData')->name('exportGT');

    //notacredito
    Route::get('listado_nota_credito','NotaCreditoController@index');
    Route::get('listado_nota_credito_detallado','NotaCreditoController@index_detallado');
    Route::get('nota_credito','NotaCreditoController@crear');
    Route::get('nota_creditosolopdf','NotaCreditoController@crearsolopdf');
    Route::post('nota_creditogenerar_pdf','NotaCreditoController@generatePDF');
    Route::get('buscarProductoNC','NotaCreditoController@buscar_producto')->name("buscarProductoNC");
    Route::get('buscarLoteNC','NotaCreditoController@buscar_lote')->name("buscarLoteNC");
    Route::get('buscarLoteSimple','NotaCreditoController@buscar_lote_simple')->name("buscarLoteSimple");
    Route::get('buscarFTNum','NotaCreditoController@buscar_ft_numeracion');
    Route::get('buscarFTNumpdf','NotaCreditoController@buscar_ft_numeracionpdf');
    Route::get('buscarFTTodo','NotaCreditoController@buscar_ft_todo');
    Route::get('buscarFTTodopdf','NotaCreditoController@buscar_ft_todopdf');
    Route::get('buscarCliente','NotaCreditoController@buscar_cliente');
    Route::get('buscarFactBol','NotaCreditoController@buscar_factbol');
    Route::post('crear_nota_credito','NotaCreditoController@store');
    Route::get('info_nota_credito','NotaCreditoController@show');
    Route::get('notacredito_state','NotaCreditoController@status');
    Route::post('nota_update_codigonb','NotaCreditoController@update_codigoNB');
    Route::post('nota_update_vendedor','NotaCreditoController@update_vendedor');
    Route::post('nota_edit_comments','NotaCreditoController@nota_edit_comments');
    Route::post('allNC', 'NotaCreditoController@allNC' )->name('allNC');
    Route::get('/exportNC', 'NotaCreditoController@exportData')->name('exportNC');


    //ordenCompra
    Route::get('listado_orden_compra','OrdenCompraController@index');
    Route::get('listado_orden_compra_detallado','OrdenCompraController@index_detallado');
    Route::get('orden_compra','OrdenCompraController@crear');
    Route::get('buscarProducto','OrdenCompraController@buscar_producto');
    Route::get('buscarProveedor','OrdenCompraController@buscar_proveedor');
    Route::post('crear_orden_compra','OrdenCompraController@store');
    Route::get('info_orden_compra','OrdenCompraController@show');
    Route::get('ordencompra_state','OrdenCompraController@status');
    Route::post('oc_estado','OrdenCompraController@oc_estado');
    Route::post('oc_anular_descuento','OrdenCompraController@oc_anular_descuento');
    Route::post('oc_complete_order','OrdenCompraController@oc_complete_order');
    Route::post('oc_edit_comments','OrdenCompraController@oc_edit_comments');
    Route::post('allOC', 'OrdenCompraController@allOC' )->name('allOC');
    Route::get('/exportOC', 'OrdenCompraController@exportData')->name('exportOC');

    //guiaCompra
    Route::get('listado_guia_compra','GuiaCompraController@index');
    Route::get('guia_compra','GuiaCompraController@crear');
    Route::get('buscarProducto','GuiaCompraController@buscar_producto');
    Route::get('buscarProvGR','GuiaCompraController@buscar_proveedor');
    Route::get('buscarOCNum','GuiaCompraController@buscar_oc_numeracion');
    Route::get('buscarOCNumInc','GuiaCompraController@buscar_oc_numeracion_inc');
    Route::get('buscarOCNumRecv','GuiaCompraController@buscar_oc_numeracion_recv');
    Route::get('buscarOCTodo','GuiaCompraController@buscar_oc_todo');
    Route::get('buscarOCTodoFact','GuiaCompraController@buscar_oc_todo_fact');
    Route::post('crear_guia_compra','GuiaCompraController@store');
    Route::get('info_guia_compra','GuiaCompraController@show');
    Route::get('guiacompra_state','GuiaCompraController@status');

    //facturaaCompra
    Route::get('listado_factura_compra','FacturaCompraController@index');
    Route::get('factura_compra','FacturaCompraController@crear');
    Route::get('buscarProducto','FacturaCompraController@buscar_producto');
    Route::get('buscarProveedor','FacturaCompraController@buscar_proveedor');
    Route::get('buscarGuiaCompra','FacturaCompraController@buscar_GuiaCompra');
    Route::get('buscarGCTodo','FacturaCompraController@buscar_gc_todo');
    Route::post('crear_factura_compra','FacturaCompraController@store');
    Route::get('info_factura_compra','FacturaCompraController@show');
    Route::post('facturacompra_state','FacturaCompraController@status');
    Route::post('allFC', 'FacturaCompraController@allFC' )->name('allFC');
    Route::get('/exportFC', 'FacturaCompraController@exportData')->name('exportFC');

    //FichaRecepcion
    Route::get('listado_ficha_recepcion','FichaRecepcionController@index');
    Route::get('listado_ficha_recepcion_detallado','FichaRecepcionController@index_detallado');
    Route::get('ficha_recepcion','FichaRecepcionController@crear');
    Route::get('buscarProducto','FichaRecepcionController@buscar_producto');
    Route::get('buscarProveedor','FichaRecepcionController@buscar_proveedor');
    Route::get('buscarFCNum','FichaRecepcionController@buscar_fc_numeracion');
    Route::get('buscarFCTodo','FichaRecepcionController@buscar_fc_todo');    
    Route::post('crear_ficha_recepcion','FichaRecepcionController@store');
    Route::get('info_ficha_recepcion','FichaRecepcionController@show');
    Route::get('ficha_state','FichaRecepcionController@status');
    Route::post('fr_estado','FichaRecepcionController@fr_estado');
    Route::post('fr_edit_comments','FichaRecepcionController@fr_edit_comments');
    Route::post('allFR', 'FichaRecepcionController@allFR' )->name('allFR');
    Route::get('/exportFR', 'FichaRecepcionController@exportData')->name('exportFR');


    //inventario
    Route::get('entradas','InventarioController@entradas_index');
    Route::get('salidas','InventarioController@salidas_index');
    Route::get('movimientos','InventarioController@index');
    Route::get('buscarInventario','InventarioController@buscar_inventario');
    Route::post('crear_entrada','InventarioController@store_entradas');
    Route::post('crear_salida','InventarioController@store_salidas');


        //caja
    Route::get('listado_FTpendientes','PagoRecibidoController@index');
    Route::post('pr_store','PagoRecibidoController@store');
    Route::post('pr_update','PagoRecibidoController@update');
    Route::get('listado_pago_recibido','PagoRecibidoController@show');
    Route::get('pr_get_guias','PagoRecibidoController@get_guias');


    Route::get('listado_FCpendientes','PagoEfectuadoController@index');
    Route::post('pe_store','PagoEfectuadoController@store');
    Route::post('pe_update','PagoEfectuadoController@update');
    Route::get('listado_pagos_efectuados','PagoEfectuadoController@show');

    //documentos
    //Route::get('documentos','DocumentosController@showListado');
    //Route::get('crear_documento','DocumentosController@showCreateDocumento');
    //Route::post('get_descripcion','DocumentosController@getProductosServicios');
    //Route::post('get_cliente_dni','DocumentosController@getClienteId');
    //Route::post('get_cliente_ruc','DocumentosController@getClienteRuc');
    //Route::post('print_proforma','DocumentosController@printProforma');
    //Route::post('print_orden','DocumentosController@printOrden');


    ////*********REPORTES*****

    //VENTAS
    Route::get('ventas', 'ReportesController@ventas');
    Route::get('ventas_kardex', 'ReportesController@ventas_kardex');
    Route::get('ventasxdia', 'ReportesController@ventasxdia')->name("ventasxdia");
    Route::get('ventasxmes', 'ReportesController@ventasxmes')->name("ventasxmes");
    Route::get('cotisxventas', 'ReportesController@cotisxventas')->name("cotisxventas");
    Route::get('ventasxvendedor', 'ReportesController@ventasxvendedor')->name("ventasxvendedor");
    Route::get('rankingclientes', 'ReportesController@rankingclientes')->name("rankingclientes");
    Route::get('ventasxclientexmes', 'ReportesController@ventasxclientexmes')->name("ventasxclientexmes");
    Route::get('visitasxvendedor', 'ReportesController@visitasxvendedor')->name("visitasxvendedor"); 
    Route::get('visitasdetalladasxvendedor', 'ReportesController@visitasdetalladasxvendedor')->name("visitasdetalladasxvendedor");
    Route::get('utilidad', 'ReportesController@utilidad')->name("utilidad");
    Route::get('kardex', 'ReportesController@kardex')->name("kardex");
    Route::get('facturas', 'ReportesController@historial_facturas_detalle')->name("facturas");


    ///INVENTARIO
    Route::get('inventario', 'ReportesController@inventario');
    Route::get('productosxfechas', 'ReportesController@productosxfechas')->name("productosxfechas");
    //Route::get('inventario', 'ReportesController@productosvencidos');
    Route::get('productosxvendedor', 'ReportesController@productosxvendedor')->name("productosxvendedor");
    Route::get('productosnorotados', 'ReportesController@productosnorotados')->name("productosnorotados");
    Route::get('productosxcliente', 'ReportesController@productosxcliente')->name("productosxcliente");


    ///Marcas
    Route::get('marcas', 'MarcaController@index');
    Route::post('guardar_marca', 'MarcaController@store');
    Route::get('productos_marca', 'MarcaController@productos');
    Route::post('guardar_precios_producto', 'MarcaController@store_precios');


    ///Supervicion
    Route::get('visitas_llamadas', 'SupervicionController@visitas_llamadas');
    Route::get('get_visitas_llamadas', 'SupervicionController@get_visitas_llamadas')->name("get_visitas_llamadas");
    Route::get('update_scheduling_visit', 'SupervicionController@update_scheduling_visit');

    
    //Comunicaciones de Baja
    Route::get('comunicacion_baja', 'CajaBajaController@index');
    Route::post('de_baja', 'CajaBajaController@com_baja');

    //front reports
    Route::get('front-report', 'FrontReportController@index');
    Route::get('front-report/facts', 'FrontReportController@factbol');

    Route::get('test_invoice', 'CajaController@testGreen');
    Route::get('test_note', 'NotaCreditoController@testGreen');
    Route::get('test_despatch', 'GuiaRemisionController@testGreen');
    Route::get('test_voided', 'CajaBajaController@testGreen');


    //asistencia
    Route::get('lista_asistencia','AsistenciaController@index')->name('lista-asistencia');
    Route::get('asistencia_check', 'AsistenciaController@check')->name('asistencia-check');
    Route::post('asistencia_check', 'AsistenciaController@save')->name('asistencia-save');
    Route::post('editar_asistencia','AsistenciaController@edit_attendance');

    Route::get('cookie_maintenance','MaintenanceController@getCookie');

    //tienda slides
    Route::get('slides', 'SlideController@index');
    Route::post('slide_store', 'SlideController@store')->name("slide_store");
    Route::post('slide_delete','SlideController@delete');
    Route::post('slide_active','SlideController@active');

});

