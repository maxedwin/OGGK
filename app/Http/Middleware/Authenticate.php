<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App\Models\OrdenVentaH;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
          if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
       
            } else {
                return redirect()->guest('login');
            }
        }

        $puestoRol = Auth::user()->puesto;
        $actionName = Route::getCurrentRoute()->getActionName();

        $actions = [
            'App\Http\Controllers\HomeController@index' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16], 't' => true ],
            'App\Http\Controllers\HomeController@store' => [ 'r' => [1,2,3,4,5,6,7,9,11,12,13,14], 't' => false ], //
            'App\Http\Controllers\HomeController@delete' => [ 'r' => [1,2,3,4,5,6,7,9,11,12,13,14], 't' => false ], //

            'App\Http\Controllers\ReportesController@ventasxmes' => [ 'r' => [1,2,3,4,5,9,10,13,14,16], 't' => false ], //
            'App\Http\Controllers\ReportesController@ventasxdia' => [ 'r' => [1,2,3,4,5,13,14,16], 't' => false ], //

            'App\Http\Controllers\ClienteController@index' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,11,13,14,16], 't' => true ],
            'App\Http\Controllers\ClienteController@create' => [ 'r' => [1,2,3,4,5,6,9,10,13,14], 't' => true ],
            'App\Http\Controllers\ClienteController@llamada' => [ 'r' => [1,2,4,5,9,10,11], 't' => false ], //
            'App\Http\Controllers\ClienteController@update' => [ 'r' => [1,2,3,4,5,6,7,9,13,14], 't' => true ],
            'App\Http\Controllers\ClienteController@reclamo' => [ 'r' => [1,2,4,5,6,7,9], 't' => false ], //
            'App\Http\Controllers\ClienteController@delete' => [ 'r' => [1,2,4,5], 't' => false ], //
            'App\Http\Controllers\ClienteController@index_deudores' => [ 'r' => [1,2,4,5], 't' => true ],
            'App\Http\Controllers\ClienteController@index_reclamos' => [ 'r' => [1,2,4,5,9], 't' => true ],
            'App\Http\Controllers\ClienteController@clientMap' => [ 'r' => [1,2,4,5,6,7,8,9,10,11,12], 't' => true ],

            'App\Http\Controllers\PotencialClienteController@index' => [ 'r' => [1,2,3,4,5,6,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\PotencialClienteController@update' => [ 'r' => [1,2,3,4,5,6,7,9], 't' => true ],
            'App\Http\Controllers\PotencialClienteController@delete' => [ 'r' => [1,2,4,5], 't' => false ], //

            'App\Http\Controllers\ProveedorController@index' => [ 'r' => [1,2,3,4,5,6,7,9,10,16], 't' => true ],
            'App\Http\Controllers\ProveedorController@create' => [ 'r' => [1,2,3,4,5,6,9], 't' => true ],
            'App\Http\Controllers\ProveedorController@update' => [ 'r' => [1,2,3,4,5,6,9], 't' => true ],
            'App\Http\Controllers\ProveedorController@delete' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\ProductController@index' => [ 'r' => [1,2,3,4,5,6,7,9,10,12,16], 't' => true ],
            'App\Http\Controllers\ProductController@create' => [ 'r' => [1,2,3,4,5,6,7], 't' => true ],
            'App\Http\Controllers\ProductController@update' => [ 'r' => [1,2,3,4,5,6,7,12], 't' => true ],
            'App\Http\Controllers\ProductController@destroy' => [ 'r' => [1,2,4,5,6], 't' => false ], //
            'App\Http\Controllers\ProductController@lista_lote' => [ 'r' => [1,2,4,5,6,7,9,10,16], 't' => true ],

            'App\Http\Controllers\ServiciosController@index' => [ 'r' => [1,2,3,4,5,6,7,9,10,12,16], 't' => true ],
            'App\Http\Controllers\ServiciosController@create' => [ 'r' => [1,2,3,4,5,6,7], 't' => true ],
            'App\Http\Controllers\ServiciosController@update' => [ 'r' => [1,2,3,4,5,6,7,12], 't' => true ],
            'App\Http\Controllers\ServiciosController@destroy' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\CategoryController@create' => [ 'r' => [1,2,4,5,6,7,9], 't' => true ],
            'App\Http\Controllers\CategoryController@store' => [ 'r' => [1,2,4,5,6,7], 't' => false ], //
            'App\Http\Controllers\CategoryController@destroy' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\AlmacenController@create' => [ 'r' => [1,2,4,5,6,7,9], 't' => true ],
            'App\Http\Controllers\AlmacenController@store' => [ 'r' => [1,2,4,5,6,7], 't' => false ], //
            'App\Http\Controllers\AlmacenController@destroy' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\TransporteController@create' => [ 'r' => [1,2,4,5,6,7,9], 't' => true ],
            'App\Http\Controllers\TransporteController@store' => [ 'r' => [1,2,4,5,6,7], 't' => false ], //
            'App\Http\Controllers\TransporteController@destroy' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\CotizacionController@crear' => [ 'r' => [1,2,4,5,9,10], 't' => true ],
            'App\Http\Controllers\CotizacionController@index' => [ 'r' => [1,2,4,5,9,10,16], 't' => true ],
            'App\Http\Controllers\CotizacionController@show' => [ 'r' => [1,2,4,5,9,10,16], 't' => true ],
            'App\Http\Controllers\CotizacionController@ct_edit_comments' => [ 'r' => [1,2,4,5,9,10], 't' => false ], //
            'App\Http\Controllers\CotizacionController@ct_estado' => [ 'r' => [1,2,4,5], 't' => false ], //

            'App\Http\Controllers\OrdenVentaController@crear' => [ 'r' => [1,2,3,4,5,6,7,9,10,13,14], 't' => true ],
            'App\Http\Controllers\OrdenVentaController@index' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\OrdenVentaController@show' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\OrdenVentaController@ov_edit_comments' => [ 'r' => [1,2,3,4,5,6,7,9,10,13,14], 't' => false ], //
            'App\Http\Controllers\OrdenVentaController@index_tienda' => [ 'r' => [1,2,3,4,5,6,7,9,10,13,14], 't' => true ],
            'App\Http\Controllers\OrdenVentaController@por_confirmar' => [ 'r' => [1,2,4], 't' => true ],

            'App\Http\Controllers\GuiaRemisionController@crear' => [ 'r' => [1,2,3,4,5,6,7,9,13,14], 't' => true ],
            'App\Http\Controllers\GuiaRemisionController@index' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\GuiaRemisionController@show' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\GuiaRemisionController@exportData' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\GuiaRemisionController@gr_edit_comments' => [ 'r' => [1,2,3,4,5,6,7,9,13,14], 't' => false ], //
            'App\Http\Controllers\GuiaRemisionController@guias_pendientes' => [ 'r' => [1,2,3,4,5,6,7,9,13,14,16], 't' => true ],
            'App\Http\Controllers\GuiaRemisionController@gr_estado' => [ 'r' => [1,2,3,4,5,6,7,13,14], 't' => false ], //
            'App\Http\Controllers\GuiaRemisionController@gr_estado_reprogramar' => [ 'r' => [1,2,3,4,5,6,7,13,14], 't' => false ], //

            'App\Http\Controllers\CajaController@crear' => [ 'r' => [1,2,3,4,5,6,7,9,13,14], 't' => true ],
            'App\Http\Controllers\CajaController@index' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,15,16], 't' => true ],
            'App\Http\Controllers\CajaController@show' => [ 'r' => [1,2,3,4,5,6,7,8,9,10,13,14,16], 't' => true ],
            'App\Http\Controllers\CajaController@caja_edit_comments' => [ 'r' => [1,2,3,4,5,6,7,9,13,14], 't' => false ], //
            'App\Http\Controllers\CajaController@enlazar_guias' => [ 'r' => [1,2,3,4,5,6,7,9,13,14], 't' => false ], //
            'App\Http\Controllers\CajaController@caja_estado' => [ 'r' => [1,2,3,4,5,6,13,14], 't' => false ], //

            'App\Http\Controllers\NotaCreditoController@crear' => [ 'r' => [1,2,3,4,5,13,14], 't' => true ],
            'App\Http\Controllers\NotaCreditoController@index' => [ 'r' => [1,2,3,4,5,6,7,9,13,14,15,16], 't' => true ],
            'App\Http\Controllers\NotaCreditoController@show' => [ 'r' => [1,2,3,4,5,6,7,9,13,14,16], 't' => true ],
            'App\Http\Controllers\NotaCreditoController@nota_edit_comments' => [ 'r' => [1,2,3,4,5,6,13,14], 't' => false ], //

            'App\Http\Controllers\PagoRecibidoController@index' => [ 'r' => [1,2,3,4,5,6,7,8,11,14,16], 't' => true ],
            'App\Http\Controllers\PagoRecibidoController@store' => [ 'r' => [1,2,3,4,5,6,8,11,14], 't' => false ], //
            'App\Http\Controllers\PagoRecibidoController@show' => [ 'r' => [1,2,3,4,5,6,7,8,11,14,16], 't' => true ],

            'App\Http\Controllers\OrdenCompraController@crear' => [ 'r' => [1,2,4,5,6,9], 't' => true ],
            'App\Http\Controllers\OrdenCompraController@index' => [ 'r' => [1,2,3,4,5,6,7,9,16], 't' => true ],
            'App\Http\Controllers\OrdenCompraController@show' => [ 'r' => [1,2,4,5,6,7,8,9,16], 't' => true ],
            'App\Http\Controllers\OrdenCompraController@oc_edit_comments' => [ 'r' => [1,2,4,5,6,9], 't' => false ], //

            'App\Http\Controllers\FichaRecepcionController@crear' => [ 'r' => [1,2,4,5,6], 't' => true ],
            'App\Http\Controllers\FichaRecepcionController@index' => [ 'r' => [1,2,4,5,6,7,16], 't' => true ],
            'App\Http\Controllers\FichaRecepcionController@show' => [ 'r' => [1,2,4,5,6,7,16], 't' => true ],
            'App\Http\Controllers\FichaRecepcionController@fr_edit_comments' => [ 'r' => [1,2,4,5,6], 't' => false ], //
            'App\Http\Controllers\FichaRecepcionController@fr_estado' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\FacturaCompraController@crear' => [ 'r' => [1,2,4,5], 't' => true ],
            'App\Http\Controllers\FacturaCompraController@index' => [ 'r' => [1,2,4,5,6,7,16], 't' => true ],
            'App\Http\Controllers\FacturaCompraController@status' => [ 'r' => [1,2,4,5,6], 't' => false ], //

            'App\Http\Controllers\PagoEfectuadoController@index' => [ 'r' => [1,2,4,5,16], 't' => true ],
            'App\Http\Controllers\PagoEfectuadoController@store' => [ 'r' => [1,2,4,5], 't' => false ], //
            'App\Http\Controllers\PagoEfectuadoController@show' => [ 'r' => [1,2,4,5,16], 't' => true ],

            'App\Http\Controllers\ReportesController@ventas' => [ 'r' => [1,2,4,5,9,10,16], 't' => true ],
            'App\Http\Controllers\ReportesController@inventario' => [ 'r' => [1,2,4,5,6,7,16], 't' => true ],
            'App\Http\Controllers\ReportesController@ventas_kardex' => [ 'r' => [1,2,4,5,6,7,16], 't' => true ],
            'App\Http\Controllers\ReportesController@historial_facturas_detalle' => [ 'r' => [1,2,4,5,6,7,16], 't' => true ],

            'App\Http\Controllers\UserController@index' => [ 'r' => [1,2,4,5,6,7,8,9,10,11,12,16], 't' => true ],
            'App\Http\Controllers\UserController@disable_user' => [ 'r' => [1,2,4,5,11], 't' => false ], //
            'App\Http\Controllers\UserController@enable_user' => [ 'r' => [1,2,4,5,11], 't' => false ], //
            'App\Http\Controllers\UserController@change_password' => [ 'r' => [1,2], 't' => false ], //

            'App\Http\Controllers\EvaluacionesController@list_prov' => [ 'r' => [1,2,4,5,11], 't' => true ],
            'App\Http\Controllers\EvaluacionesController@index' => [ 'r' => [1,2,4,5,11], 't' => true ],

            'App\Http\Controllers\MarcaController@index' => [ 'r' => [1,2,3,4,16], 't' => true ],
            'App\Http\Controllers\MarcaController@productos' => [ 'r' => [1,2,3,4,16], 't' => true ],
            'App\Http\Controllers\MarcaController@store' => [ 'r' => [1,2,3,4], 't' => false ],
            'App\Http\Controllers\MarcaController@store_precios' => [ 'r' => [1,2,3,4], 't' => false ],

            'App\Http\Controllers\SupervicionController@visitas_llamadas' => [ 'r' => [1,2,4], 't' => true ],

            'App\Http\Controllers\AsistenciaController@index' => [ 'r' => [1,2,4,5,11,16], 't' => true ],
            'App\Http\Controllers\AsistenciaController@edit_attendance' => [ 'r' => [1,2,4,5,11], 't' => false ],

            'App\Http\Controllers\CajaBajaController@index' => [ 'r' => [1,2,3,4,5,6,7,9,13,14,15,16], 't' => true ],
            'App\Http\Controllers\CajaBajaController@com_baja' => [ 'r' => [1,2,3,4,5,13,14], 't' => false ],

        ];

        if (in_array($puestoRol, [1,2,4])) {
            $ordenesV = OrdenVentaH::where('estado_doc', 9)->offset(0)->limit(1)->get();
            if (count($ordenesV)) {
                Session::put('porConfirmar', true);
            } else {
                Session::put('porConfirmar', false);
            }
        } else {
            Session::put('porConfirmar', false);
        }


        if (in_array($puestoRol, [1,2,4])) {
            $ordenesV = OrdenVentaH::where('by_client', 1)->where('status_doc',1)->offset(0)->limit(1)->get();
            if (count($ordenesV)) {
                Session::put('OVTienda', true);
            } else {
                Session::put('OVTienda', false);
            }
        } else {
            Session::put('OVTienda', false);
        }

        
        if (array_key_exists($actionName, $actions)) {
            if (in_array($puestoRol, $actions[$actionName]['r'])) {
                return $next($request);
            }
            if ($actions[$actionName]['t']) {
                return back();
            } else {
                return response()->json(['error'], 404);  
            }
        }

        return $next($request);
    }


}
