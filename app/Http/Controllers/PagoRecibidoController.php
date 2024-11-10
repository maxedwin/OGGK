<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\CajaH;
use App\Models\PagoRecibido;
use App\Models\OrdenVentaH;
use App\User;
use Auth;
use DB;

class PagoRecibidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $sucu = Auth::user()->idsucursal;
        //$ft_pendientes = DB::table('cajah')
        $ft_pendientes = CajaH::with('guias')
                    ->select('cajah.*','clientes.razon_social', 'clientes.dias_credito', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'clientes.direccion', 'users.name', 'users.lastname', 'cajah.codigoNB', 'cajah.moneda'
                        ,DB::raw("round(IFNULL(SUM(pr.pagado),0),2) as pagado_total")
                        ,DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total"))
                    ->Where(function ($query2) {
                        $query2->where('cajah.estado_doc',0)
                                ->orwhere('cajah.estado_doc',1)
                                ->orwhere('cajah.estado_doc',4)
                                ->orwhere('cajah.estado_doc',6);
                    })
                    ->join ('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                    ->join ('users', 'users.id', '=', 'cajah.idvendedor')
                    ->leftJoin('pagos_recibidos as pr', 'pr.idcajah', '=', 'cajah.idcajah')
                    ->groupBy("cajah.idcajah")
                    ->get();

        //dd($ft_pendientes);

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$sucu)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $bancos = DB::table('bancos')->get();

        return view('pagos_recibidos/listado_FTpendientes', ['ft_pendientes' => $ft_pendientes])->with('sucursal',$sucursal)->with('bancos',$bancos);
    }

    public function store(Request $request){

        $user = Auth::user()->idempresa;
        $idusuario = Auth::user()->id;
        
        $idcajah = $request['idcajah'];
        $total = $request['total'];
        $valor_recibido = $request['valor_recibido'];
        $tipo_pago = $request['tipo_pago'];
        $por_pagar = $request['por_pagar2'];

        $nro_operacion = $request['nro_operacion'];
        $banco = $request['banco'];

        $pr = new PagoRecibido;
        $pr->idcajah = $idcajah;
        $pr->total = $total;
        $pr->pagado = $valor_recibido;
        $pr->por_pagar = $por_pagar;
        $pr->tipo_pago = $tipo_pago;
        $pr->idusuario = $idusuario;
        $pr->nro_operacion = $nro_operacion;
        $pr->idbanco = $banco;

        $pr->save();

        $caja = CajaH::find($idcajah);
        if( $por_pagar <= 0){
            $caja->estado_doc = 2;
            $caja->save();
        }

        if($caja->status_cob != -1) {
            $caja->status_cob = 2;
            if( $por_pagar <= 0){
                $caja->status_cob = 3;
            }
            $caja->save();

            $min_status_fact = DB::table('cajah')
                                    ->where('id_orden_ventah', $caja->id_orden_ventah)
                                    ->where('status_cob', '!=', 0)
                                    ->min('status_cob');

            $ov_state = OrdenVentaH::find($caja->id_orden_ventah);
            $ov_state->status_cob = $min_status_fact;
            $ov_state->save();
        }


        return json_encode(['mensaje' => 200]);
    }

    public function update(Request $request){

        $idpr = $request['idpr'];
        $nro_operacion = $request['nro_operacion'];
        $banco = $request['banco'];

        $pr = PagoRecibido::find($idpr);
        $pr->nro_operacion = $nro_operacion;
        $pr->idbanco = $banco;
        $pr->save();

        return json_encode(['mensaje' => 200]);
    }
    
    public function show(){
        $user = Auth::user()->idempresa;
        $sucu = Auth::user()->idsucursal;

        $prs = DB::table('pagos_recibidos')
                    ->select('pagos_recibidos.*', 'bancos.nombre as banco',
                        'cajah.idcajah','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'cajah.numeracion', 'cajah.tipo', 'cajah.codigoNB', 'cajah.moneda')
                    ->join ('cajah', 'cajah.idcajah', '=', 'pagos_recibidos.idcajah')
                    ->join ('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                    ->leftJoin ('bancos', 'bancos.idbanco','=', 'pagos_recibidos.idbanco')
                    ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$sucu)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $bancos = DB::table('bancos')->get();
      
        return  view('pagos_recibidos/listado_pago_recibido', ['prs' => $prs])->with('sucursal',$sucursal)->with('bancos',$bancos);
    }

    public function get_guias(Request $request) {
        $busqueda = $request['query'];
        $empresa = Auth::user()->idempresa;

        $guias = DB::table('guia_remisionh')->select(
                'guia_remisionh.*'
            )
                ->where('guia_remisionh.idempresa', $empresa)
                ->where('c.idcajah', '=', $busqueda)
                ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'guia_remisionh.id_guia_remisionh')
                ->leftJoin('cajah as c', 'c.idcajah', '=', 'cg.idcaja')
                ->get();

        return json_encode($guias);
    }

}