<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\FacturaCompraH;
use App\Models\PagoEfectuado;
use App\User;
use Auth;
use DB;

class PagoEfectuadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $sucu = Auth::user()->idsucursal;
        $fc_pendientes = DB::table('factura_comprah')
                    ->select('factura_comprah.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', DB::raw("round(IFNULL(SUM(pr.pagado),0),2) as pagado_total"))
                    ->where('factura_comprah.estado_doc','=',0)
                    ->orWhere('factura_comprah.estado_doc','=',1)
                    ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')
                    ->leftJoin('pagos_efectuados as pr', 'pr.idfactcompra', '=', 'factura_comprah.id_factura_comprah')
                    ->groupBy("factura_comprah.id_factura_comprah")
                    ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$sucu)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $bancos = DB::table('bancos')->get();

        return view('pagos_efectuados/listado_FCpendientes', ['fc_pendientes' => $fc_pendientes])->with('sucursal',$sucursal)->with('bancos',$bancos);
    }

    public function store(Request $request){

        $user = Auth::user()->idempresa;
        $idusuario = Auth::user()->id;
        
        $id_factura_comprah = $request['id_factura_comprah'];
        $total = $request['total'];
        $valor_efectuado = $request['valor_efectuado'];
        $tipo_pago = $request['tipo_pago'];
        $por_pagar = $request['por_pagar2'];

        $nro_operacion = $request['nro_operacion'];
        $banco = $request['banco'];

        $pr = new PagoEfectuado;
        $pr->idfactcompra = $id_factura_comprah;
        $pr->total = $total;
        $pr->pagado = $valor_efectuado;
        $pr->por_pagar = $por_pagar;
        $pr->tipo_pago = $tipo_pago;
        $pr->idusuario = $idusuario;
        $pr->nro_operacion = $nro_operacion;
        $pr->idbanco = $banco;

        $pr->save();

        $caja = FacturaCompraH::find($id_factura_comprah);
        if( $por_pagar <= 0){
            $caja->estado_doc = 2;
            $caja->save();
        }


        return json_encode(['mensaje' => 200]);
    }

    public function update(Request $request){

        $idpr = $request['idpr'];
        $nro_operacion = $request['nro_operacion'];
        $banco = $request['banco'];

        $pr = PagoEfectuado::find($idpr);
        $pr->nro_operacion = $nro_operacion;
        $pr->idbanco = $banco;
        $pr->save();

        return json_encode(['mensaje' => 200]);
    }
    
    public function show(){
        $user = Auth::user()->idempresa;
        $sucu = Auth::user()->idsucursal;

        $prs = DB::table('pagos_efectuados')
                    ->select('pagos_efectuados.*', 'bancos.nombre as banco',
                        'factura_comprah.id_factura_comprah','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', 'factura_comprah.numeracion', 'factura_comprah.serie', 'factura_comprah.moneda')
                    ->join ('factura_comprah', 'factura_comprah.id_factura_comprah', '=', 'pagos_efectuados.idfactcompra')
                    ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')
                    ->leftJoin ('bancos', 'bancos.idbanco','=', 'pagos_efectuados.idbanco')
                    ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$sucu)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $bancos = DB::table('bancos')->get();
      
        return  view('pagos_efectuados/listado_pagos_efectuados', ['prs' => $prs])->with('sucursal',$sucursal)->with('bancos',$bancos);
    }

}