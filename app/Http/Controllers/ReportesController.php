<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Lote;
use App\Models\CajaH;
use App\Models\CajaD;
use App\Models\CotizacionH;
use App\Models\OrdenVentaH;
use App\Models\Visit;
use App\Models\Transacciones;
use App\User;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ReportesController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////*************VENTAS***********/////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function ventas()
	{
		$clientes = DB::table('clientes')->get();
		$vendedores = DB::table('users')->where('tienda_user',0)->get();

		$ordenesventas = $this->ordenesventas();

	    return view('reportes/ventas')->with('clientes',$clientes)->with('vendedores',$vendedores)->with('ordenesventas',$ordenesventas);
	}

	public function historial_facturas_detalle()
	{
			$cajah_model = CajaH::select('cajah.created_at','cajah.codigoNB','clientes.razon_social', 'producto.nombre as producto', 'orden_ventad.cantidad', 'orden_ventad.precio_unit','orden_ventad.precio_total')
            				->join('clientes', 'clientes.idcliente', '=', 'cajah.idcliente' )
            				->join('orden_ventah', 'orden_ventah.id_orden_ventah', '=', 'cajah.id_orden_ventah' )
            				->join('orden_ventad', 'orden_ventad.id_orden_ventah', '=', 'orden_ventah.id_orden_ventah' )
            				->join('producto', 'orden_ventad.idproducto', '=', 'producto.idproducto' )
	        					->orderBy('clientes.razon_social')
	        					->orderBy('cajah.codigoNB')
	        					->get();
	    return view('reportes/facturas')->with('cajah', $cajah_model);
	}

	public function ventas_kardex()
	{
		$clientes = DB::table('clientes')->get();
		$vendedores = DB::table('users')->where('tienda_user',0)->get();
		
		$productos = DB::table('producto')->get();
		$proveedores = DB::table('proveedores')->get();
		$almacenes = DB::table('almacen')->get();     
		
		$ordenesventas = $this->ordenesventas();

	    return view('reportes/ventas_kardex')->with('clientes',$clientes)->with('vendedores',$vendedores)->with('ordenesventas',$ordenesventas)->with('productos',$productos)
	    							  ->with('proveedores',$proveedores)->with('almacenes',$almacenes);
	}

	public function ventasxdia(Request $request)
	{
	 	$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;

	    $viewer = CajaH::select(DB::raw("SUM(total) as count"),DB::raw("concat(day(created_at),'"."-"."',monthname(created_at)) as days"))
	    	//DB::raw("day(created_at) as days"),DB::raw("month(created_at) as months")
			
	    	//->where(DB::raw("year(created_at)"), '=', Carbon::today()->year)
	        ->whereDate('created_at','>=', $fecha_ini)
            ->whereDate('created_at','<=', $fecha_fin)
	        ->orderBy(DB::raw("created_at"))
	        ->groupBy(DB::raw("day(created_at)"))
	        ->get()->toArray();
	    $days = array_column($viewer, 'days');
	    //$months = array_column($viewer, 'months');
	    $viewer = array_column($viewer, 'count');

		return json_encode(['viewer' => $viewer, 'days' => $days ]);
	}

	public function ventasxmes(Request $request)
	{
	    $viewer = CajaH::select(
	    						//DB::raw("SUM(total) as count"),
	    						DB::raw("round( SUM( IFNULL( total_nc,total ) ) ,2 ) as count"),
	    						DB::raw("monthname(created_at) as months"))
	    	->where(DB::raw("year(created_at)"), '=', Carbon::today()->year)
	    	->whereIn('estado_doc', array(0, 1, 2, 4, 6))
	        ->orderBy(DB::raw("created_at"))
	        ->groupBy(DB::raw("month(created_at)"))
	        ->get()->toArray();
	    $months = array_column($viewer, 'months');
	    $viewer = array_column($viewer, 'count');

		return json_encode(['viewer' => $viewer, 'months' => $months ]);
	}

	public function cotisxventas()
	{
	    $cotis = CotizacionH::select(DB::raw("COUNT(idcotizacionh) as count"),DB::raw("monthname(created_at) as months"))
	    	->where(DB::raw("year(created_at)"), '=', Carbon::today()->year)
	        ->orderBy(DB::raw("created_at"))
	        ->groupBy(DB::raw("month(created_at)"))
	        ->get()->toArray();

	    $months = array_column($cotis, 'months');
	    $cotis = array_column($cotis, 'count');

	    $viewer = OrdenVentaH::select(DB::raw("COUNT(orden_ventah.id_orden_ventah) as count"))
	    	->where(DB::raw("year(orden_ventah.created_at)"), '=', Carbon::today()->year)
	    	->join('cotizacionh as c', 'c.idcotizacionh', '=', 'orden_ventah.idcotizacionh' )
	        ->orderBy(DB::raw("orden_ventah.created_at"))
	        ->groupBy(DB::raw("month(orden_ventah.created_at)"))
	        ->get()->toArray();

	    $viewer = array_column($viewer, 'count');
	    	    
		return json_encode(['viewer' => $viewer, 'cotis' => $cotis, 'months' => $months ]);
	}

	public function ventasxvendedor(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	
		$viewer = CajaH::select(DB::raw("SUM(cajah.total) as count"),DB::raw("concat(u.name,'"." "."',u.lastname) as nombre"))
	    	//->where(DB::raw("year(cajah.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('cajah.created_at','>=', $fecha_ini)
            ->whereDate('cajah.created_at','<=', $fecha_fin)
            ->join('users as u', 'u.id', '=', 'cajah.idvendedor' )
	        ->orderBy(DB::raw("cajah.created_at"))
	        ->groupBy(DB::raw("cajah.idvendedor"))
	        ->get()->toArray();
	    $vendedor = array_column($viewer, 'nombre');
	    $viewer = array_column($viewer, 'count');

	    return json_encode(['viewer' => $viewer, 'vendedor' => $vendedor ]);
	}

	public function rankingclientes(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	
		$viewer = CajaH::select(DB::raw("SUM(cajah.total) as count"),DB::raw("clientes.razon_social as rs"))
	    	//->where(DB::raw("year(cajah.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('cajah.created_at','>=', $fecha_ini)
            ->whereDate('cajah.created_at','<=', $fecha_fin)
            ->join('clientes', 'clientes.idcliente', '=', 'cajah.idcliente' )
	        ->orderBy(DB::raw("SUM(cajah.total)"), 'desc')
	        ->groupBy(DB::raw("cajah.idcliente"))
	        ->limit(10)->get()->toArray();
	    $cliente = array_column($viewer, 'rs');
	    $viewer = array_column($viewer, 'count');

	    return json_encode(['viewer' => $viewer, 'cliente' => $cliente ]);
	}

	public function ventasxclientexmes(Request $request)
	{
		$cliente = $request->cliente;
	 	
		$viewer = CajaH::select(DB::raw("SUM(cajah.total) as count"),DB::raw("monthname(created_at) as months"))
	    	->where(DB::raw("year(cajah.created_at)"), '=', Carbon::today()->year)
            ->where('cajah.idcliente', '=', $cliente )
	        ->orderBy(DB::raw("cajah.created_at"))
	        ->groupBy(DB::raw("cajah.idcliente"),DB::raw("month(cajah.created_at)"))
	        ->get()->toArray();
	    $months = array_column($viewer, 'months');
	    $viewer = array_column($viewer, 'count');

	    return json_encode(['viewer' => $viewer, 'months' => $months ]);
	}

//SELECT v.idusuario, u.name , date(v.created_at), count(v.id) FROM visit v inner join users u on u.id=v.idusuario group by v.idusuario,date(v.created_at)

	public function visitasxvendedor(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;	 	

		$viewer = Visit::select(DB::raw("COUNT(visit.id) as count"), 'u.name as nombre')
			//->where(DB::raw("year(visit.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('visit.created_at','>=', $fecha_ini)
            ->whereDate('visit.created_at','<=', $fecha_fin)
            ->where('web_app', $request->tipo)
			->join('users as u', 'u.id', '=', 'visit.idusuario')
	        ->orderBy(DB::raw("visit.created_at"))
	        ->groupBy(DB::raw("visit.idusuario"))
	        ->get()->toArray();

	    $vendedor = array_column($viewer, 'nombre');
	    $viewer = array_column($viewer, 'count');
	    
		return json_encode(['viewer' => $viewer, 'vendedor' => $vendedor]);
	}

	public function visitasdetalladasxvendedor(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	$vendedor = $request->vendedor;

		$viewer = Visit::select(DB::raw("visit.created_at as fecha"), 'u.name' ,'visit.orden_venta', 'visit.motivo', 'visit.respuesta', DB::raw("IFNULL(c.razon_social,cli.razon_social) as rs"))
			//->where(DB::raw("year(visit.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('visit.created_at','>=', $fecha_ini)
            ->whereDate('visit.created_at','<=', $fecha_fin)
            ->where('visit.idusuario','=',$vendedor)
            ->where('web_app', $request->tipo)
			->leftJoin('users as u', 'u.id', '=', 'visit.idusuario')
			
			->leftJoin('clienteubicacion as cu', 'cu.idcliubic', '=', 'visit.idcliubic')
			->leftJoin('clientes as cli', 'cli.idcliente','=', 'visit.idcliente')
			->leftJoin('clientes as c', 'c.ruc_dni','=', 'cu.ruc_dni')			
			
			->orderBy(DB::raw("visit.created_at"))
	        ->get();

	    return json_encode(['viewer' => $viewer]);
	}

	public function ordenesventas()
	{
		$viewer = DB::table('orden_ventah as ovh')
			->select('ovh.created_at as fecha', DB::raw("DAYNAME(ovh.created_at) as dia"), 'u.name as vendedor', 'c.distrito', 'ovh.codigoNB as np', 'c.ruc_dni', 'c.razon_social', 'p.nombre as producto', 'ovd.cantidad', 'ovd.precio_unit', 'ovh.estado_doc', 'ovh.moneda')
			->join('clientes as c', 'c.idcliente', '=', 'ovh.idcliente' )
			->join('users as u', 'u.id', '=', 'ovh.idvendedor')
			->leftJoin('orden_ventad as ovd', 'ovd.id_orden_ventah', '=', 'ovh.id_orden_ventah')
			->leftJoin('producto as p','p.idproducto','=', "ovd.idproducto")			
			->orderBy('ovh.created_at')
	        ->get();

	    return $viewer;
	}



	public function utilidad(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	$tipo_cambio = $request->tipo_cambio;

	 	if($tipo_cambio==null)
	 		$tipo_cambio=1;

		$viewer = 	CajaD::select( 
							'prod.barcode',
							'prod.nombre',

							DB::raw(" ROUND( SUM(cajad.cantidad_m2) ,3) as cacanti"),

							DB::raw(" ROUND( prod.costo * '".$tipo_cambio."' ,3)  as occosto"),

							DB::raw(" ROUND( SUM(cajad.precio_m2) / COUNT(cajad.idproducto) ,3) as caprecio"),

							DB::raw("  	ROUND(
												(	(	( prod.costo * '".$tipo_cambio."') + 
														(	SUM(cajad.precio_m2) / COUNT(cajad.idproducto) )
													)/2
												)
										,3) as promedio
									"),

							DB::raw("	ROUND(
												(( 	(	( prod.costo *'".$tipo_cambio."') + 
														(	SUM(cajad.precio_m2) / COUNT(cajad.idproducto) )
													)/2
												) - ( prod.costo *'".$tipo_cambio."')) 
										,3) as utilidad
									")
					)

					->join('producto as prod', 'prod.idproducto', '=', 'cajad.idproducto')
			        ->join('cajah', 'cajah.idcajah', '=', 'cajad.idcajah')
			
	        		->whereDate('cajah.f_emision','>=', $fecha_ini)
            		->whereDate('cajah.f_emision','<=', $fecha_fin)			

					->groupBy('cajad.idproducto')
	        		->get();

	    return json_encode(['viewer' => $viewer]);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////*************KARDEX***********/////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function kardex(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	$tipo = $request->tipo;
	 	$idproducto = $request->idproducto;
	 	$idalmacen = $request->idalmacen;

	 	//************** HAY PROVEEDOR NO HAY PRODUCTO ********************//
	 	if( $idproducto==0 && $tipo==1 && $idalmacen!=0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	'' AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 0)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto==0 && $tipo==1 && $idalmacen==0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	'' AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 0)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto==0 && $tipo==0 && $idalmacen!=0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	'' AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 1)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto==0 && $tipo==0 && $idalmacen==0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	'' AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 1)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }

	    //************** NO HAY PROVEEDOR HAY PRODUCTO ********************//
	    elseif( $idproducto!=0 && $tipo==1 && $idalmacen!=0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	'' AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 0)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto!=0 && $tipo==1 && $idalmacen==0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	'' AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 0)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto!=0 && $tipo==0 && $idalmacen!=0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	'' AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 1)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto!=0 && $tipo==0 && $idalmacen==0 ){
			
			$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	'' AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.tipo','!=', 1)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }
	    //************** HAY PROVEEDOR NO HAY PRODUCTO  AMBAS********************//
		elseif( $idproducto==0 && $tipo==4 && $idalmacen!=0){

	    	$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto==0 && $tipo==4 && $idalmacen==0){

	    	$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);
	    }

	    //************** NO HAY PROVEEDOR HAY PRODUCTO  AMBAS********************//
		elseif( $idproducto!=0 && $tipo==4 && $idalmacen!=0){

	    	$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);

	    }elseif( $idproducto!=0 && $tipo==4 && $idalmacen==0){

	    	$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);
	    }


	    //************** HAY PROVEEDOR HAY PRODUCTO ********************//
		elseif( $idproducto!=0 && $tipo==4 && $idalmacen!=0){

	    	$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->where('transacciones.idalmacen', $idalmacen)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);
	    }
	    else{

	    	$viewer = 	Transacciones::select( 
							'transacciones.f_emision as fecha',

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN concat('NC-',nc.numeracion)
								    		WHEN transacciones.tipo_documento = 1 THEN concat('GR-',guia.numeracion)
								    		WHEN transacciones.tipo_documento = 3 THEN concat('FR-',ficha.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_sustento "),

							DB::raw(" 	CASE
											WHEN transacciones.tipo_documento = 2 THEN nc.codigoNB
								    		WHEN transacciones.tipo_documento = 1 THEN caja.codigoNB
								    		WHEN transacciones.tipo_documento = 3 THEN concat('OC-',oc.numeracion)
								    		ELSE concat('MS-','nohay')
										END AS  doc_referencia "),

							'prod.barcode',
							'prod.nombre',
							
							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 1 THEN transacciones.cantidad
								    		ELSE ''
										END AS  entradas "),

							DB::raw(" 	CASE
								    		WHEN transacciones.tipo = 0 THEN transacciones.cantidad
								    		ELSE ''
										END AS  salidas "),

							DB::raw(" 	transacciones.stockT AS  saldo")													
					)

					->join('producto as prod', 'prod.idproducto', '=', 'transacciones.idproducto')

			        ->leftJoin('guia_remisionh as guia', 'guia.id_guia_remisionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('ficha_recepcionh as ficha', 'ficha.id_ficha_recepcionh', '=', 'transacciones.iddocumento')
			        ->leftJoin('nota_creditoh as nc', 'nc.id_nota_creditoh', '=', 'transacciones.iddocumento')

			        ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia.id_guia_remisionh')
			        ->leftJoin('cajah as caja', 'cg.idcaja', '=', 'caja.idcajah')
			        ->leftJoin('orden_comprah as oc', 'ficha.id_orden_comprah', '=', 'oc.id_orden_comprah')
									       			        	
	        		->where('transacciones.tipo','!=', 2)
	        		->where('prod.idproducto',(int)$idproducto)
	        		->whereDate('transacciones.f_emision','>=', $fecha_ini)
            		->whereDate('transacciones.f_emision','<=', $fecha_fin)
	        		->orderBY('transacciones.idtransaccion')
	        		->get();

	    	return json_encode(['viewer' => $viewer]);
	    }
	}	






//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////*************INVENTARIO***********/////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	/// FACTURA ANULADA =>> ESTADO_DOC = 3

	public function inventario()
	{
		$clientes = DB::table('clientes')->get();

		$productos = DB::table('producto')->select('idproducto','nombre')->get();

		$categorias = DB::table('categorias')
            ->whereNotNull('idpadre')
            ->where('idpadre','>',0)
            ->get();

		$vendedores = DB::table('users')->where('tienda_user',0)->get();

		$prods_venc = $this->productosvencidos();

	    return view('reportes/inventario')->with('vendedores',$vendedores)->with('clientes',$clientes)->with('prods_venc',$prods_venc)->with('productos',$productos)->with('categorias',$categorias);
	}

	public function productosxfechas(Request $request)
	{
	 	$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	$categoria  = $request->categoria;	 


	    $viewer = CajaD::select(DB::raw("SUM(cantidad) as count"),DB::raw("producto.nombre as nombre"))
	    	//->where(DB::raw("year(cajad.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('cajad.created_at','>=', $fecha_ini)
            ->whereDate('cajad.created_at','<=', $fecha_fin)
	        ->join('producto','producto.idproducto','=', "cajad.idproducto")
	        ->join('cajah', 'cajah.idcajah', '=', 'cajad.idcajah')
	        ->where('cajah.estado_doc', '!=', 3)
	        ->where('producto.idcategoria', '=', $categoria)
	        ->orderBy(DB::raw("SUM(cantidad)"), 'desc')
	        ->groupBy(DB::raw("cajad.idproducto"))
	        ->get()->toArray();
	    $prods = array_column($viewer, 'nombre');
	    $viewer = array_column($viewer, 'count');
	    
		return json_encode(['viewer' => $viewer, 'prods' => $prods]);
	}

	public function productosvencidos()
	{
		$viewer = DB::table('lote')
			->select('producto.barcode as barcode','producto.nombre as nombre','lote.codigo as lote','lote.f_venc as fecha')
			->whereDate('lote.f_venc', '<', Carbon::today()->addMonths(3)->toDateString())
			->where('lote.f_venc','<>','0000-00-00')
			->where('lote.codigo','<>','SL')
	        ->join('producto','producto.idproducto','=', "lote.idproducto")
	        ->orderBy('producto.barcode')
	        ->get();

	    return $viewer;
	}

	public function productosxvendedor(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	$vendedor = $request->vendedor;
	 	
		$viewer = CajaD::select(DB::raw("SUM(cajad.cantidad) as count"),DB::raw("producto.nombre as nombre"))
			//->where(DB::raw("year(cajad.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('cajad.created_at','>=', $fecha_ini)
            ->whereDate('cajad.created_at','<=', $fecha_fin)
			->join('cajah','cajah.idcajah','=','cajad.idcajah')
			->where('cajah.idvendedor','=',$vendedor)
	        ->join('producto','producto.idproducto','=', "cajad.idproducto")
	        ->orderBy(DB::raw("SUM(cantidad)"), 'desc')
	        ->groupBy(DB::raw("cajad.idproducto"))
	        ->get()->toArray();
	    $prods = array_column($viewer, 'nombre');
	    $viewer = array_column($viewer, 'count');
	    
		return json_encode(['viewer' => $viewer, 'prods' => $prods]);
	}

	//SELECT p.idproducto, p.barcode, p.nombre,p.stock_total FROM producto p left join cajad c on c.idproducto=p.idproducto where c.idproducto is null
	//SELECT p.idproducto, p.barcode, p.nombre,p.stock_total , c.* FROM producto p left join cajad c on c.idproducto=p.idproducto where c.created_at>='2019-08-08' and c.created_at<='2019-09-09' and c.idcajad is null
	public function productosnorotados()
	{
		$viewer = DB::table('producto as p')
			->select('p.barcode', 'p.nombre', 'p.stock_total')
			->leftJoin('cajad', 'cajad.idproducto', '=', 'p.idproducto')
			->whereNull('cajad.idproducto')
	        ->orderBy('p.barcode')
	        ->get();

	    return json_encode(['viewer' => $viewer]);
	}

	public function productosxcliente(Request $request)
	{
		$fecha_ini = $request->fecha_ini;
	 	$fecha_fin = $request->fecha_fin;
	 	$cliente = $request->cliente;
	 	
		$viewer = CajaD::select(DB::raw("SUM(cajad.cantidad) as count"),DB::raw("producto.nombre as nombre"))
			//->where(DB::raw("year(cajad.created_at)"), '=', Carbon::today()->year)
	        ->whereDate('cajad.created_at','>=', $fecha_ini)
            ->whereDate('cajad.created_at','<=', $fecha_fin)
			->join('cajah','cajah.idcajah','=','cajad.idcajah')
			->where('cajah.idcliente','=',$cliente)
	        ->join('producto','producto.idproducto','=', "cajad.idproducto")
	        ->orderBy(DB::raw("SUM(cantidad)"), 'desc')
	        ->groupBy(DB::raw("cajad.idproducto"), DB::raw("cajah.idcliente"))
	        ->get()->toArray();
	    $prods = array_column($viewer, 'nombre');
	    $viewer = array_column($viewer, 'count');
	    
		return json_encode(['viewer' => $viewer, 'prods' => $prods]);
	}

}


