<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Models\Transacciones;
use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\Models\Producto;
use App\User;
use App\Models\CotizacionH;
use App\Models\CotizacionD;
use Auth;
use DB;

class CotizacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];

        if(!isset($cant))$cant = 500;
        if(!isset($query)){
            $cotizaciones = DB::table('cotizacionh')->select('cotizacionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('cotizacionh.idempresa',$empresa)
            ->join ('clientes', 'cotizacionh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'cotizacionh.idvendedor')
            ->get();

        }else{
            $cajas = DB::table('cotizacionh')->select('cotizacionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
                ->where('idempresa',$empresa)
                ->where('numeracion','like','%'.$query.'%')
                ->join ('clientes', 'cotizacionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'cotizacionh.idvendedor')
                ->paginate($cant);
        }

        return view('cotizacion/listado_cotizacion', ['cotizaciones' => $cotizaciones]);
    }
    
    public function crear(Request $request)        
    {
        $idcliente = 0;
        $razon_social ='';
        if($request['idcliente']!=null){
            $idcliente = $request['idcliente'];
            $tmp = DB::table('clientes')->select('razon_social')->where('idcliente',$idcliente)->first();
            $razon_social = $tmp->razon_social;
        }

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $id = Auth::user()->id;

        $products = Producto::where('idempresa',$empresa)
                    ->where('idsucursal',$sucursal)
                    ->where('state',1)->get();

        $usuario = DB::table('users')
                    ->where('id',$id)
                    ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
                    ->join('empresas','users.idempresa','=','empresas.idempresa')                    
                    ->first();
                        
        $vendedores = DB::table('users')->where('tienda_user',0)->get();

        return view('cotizacion/cotizacion')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('idclienteagregar',$idcliente)->with('iduser', $id)
        ->with('rsclienteagregar',$razon_social);
    }

    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria', DB::raw("IFNULL(SUM(lote.stock_lote), 0) as stockT"))
            
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('producto.state',1)
                    ->orwhere('idsucursal',$sucursal);
            })

            ->Where(function ($query2) use ($busqueda) {
                $query2->where('producto.barcode', 'like', '%'.$busqueda.'%')
                    ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
                    ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%');
            })

            ->leftjoin('lote', 'lote.idproducto', '=', 'producto.idproducto')
            ->leftjoin('almacenlote', 'almacenlote.idlote', '=', 'lote.idlote')

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')

            ->groupBy('producto.idproducto')
            ->get();

        return json_encode($products);
    }

    public function buscar_cliente(Request $request){
        $busqueda = $request['query'];
        $clientes = DB::table('clientes')
            ->where('razon_social','like','%'.$busqueda.'%')
            ->orwhere('ruc_dni','like','%'.$busqueda.'%')
            // ->orwhere('dni',$busqueda)
            // ->orwhere('ruc',$busqueda)
            ->get();
        return json_encode($clientes);
    }


    public function store(Request $request){

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idcliente = $request['idcliente'];
        $subtotal = $request['subtotal'];
        $igv = $request['igv'];
        $total = $request['total'];
        $paga = $request['paga'];
        $vuelto = $request['vuelto'];
        $descuento = $request['descuento'];
        $comentarios = $request['comentarios'];
        $f_entrega = $request['f_entrega'];
        $f_cobro = $request['f_cobro'];
        $margen = $request['margen'];
        $idvendedor = $request['idvendedor'];
        $moneda = $request['moneda'];
            
        $productos_json = $request['productos'];
        
        $state = 1;


        $cotizacionh = new CotizacionH;
        $cotizacionh->idempresa = $empresa;
        $cotizacionh->idsucursal = $sucursal;
        $cotizacionh->idusuario = $idusuario;
        $cotizacionh->idcliente = $idcliente;
        $cotizacionh->idvendedor = $idvendedor;

        $maximo_num = DB::table('cotizacionh')
                        ->where('idempresa',$empresa)
                        ->where('idsucursal',$sucursal)
                        ->max('numeracion');

        $cotizacionh->numeracion = intval($maximo_num) + 1 ;

        $cotizacionh->paga = $paga;
        $cotizacionh->igv = $igv;
        $cotizacionh->vuelto = $vuelto;
        $cotizacionh->descuento = $descuento;
        $cotizacionh->subtotal = $subtotal;
        $cotizacionh->total = $total;
        $cotizacionh->comentarios = $comentarios;
        $cotizacionh->f_entrega = $f_entrega;
        $cotizacionh->f_cobro = $f_cobro;
        $cotizacionh->margen = $margen;
        $cotizacionh->moneda = $moneda;
        $cotizacionh->save();


        $productos = json_decode($productos_json);

        for($i = 0; $i < count($productos); $i++){
            // $product = Producto::find($productos[$i]->idproducto);
            // if($product->tipo == 1){
            //             $product->stock_total = $product->stock_total - $productos[$i]->stock_total;
            //             $product->save();
            // }
            $cotizaciond = new CotizacionD;
            $cotizaciond->idcotizacionh = $cotizacionh->idcotizacionh;
            $cotizaciond->idproducto = $productos[$i]->idproducto;
            $cotizaciond->cantidad = $productos[$i]->stock_total;
            $cotizaciond->precio_unit = $productos[$i]->precio;
            $cotizaciond->precio_total = $productos[$i]->precio * $productos[$i]->stock_total ;
            $cotizaciond->idempresa = $empresa;
            $cotizaciond->save();

        }

        $respuesta = array();
        $respuesta[]= ['created'=> 200];
        $respuesta[] = ['id' => $cotizacionh->idcotizacionh];

        return json_encode($respuesta);

    }

    public function show(Request $request){
    	$id = $request['id'];

    	$cotizacion = DB::table('cotizacionh')
                ->where('idcotizacionh',$id)
                ->first();

    	$cotizacionD = DB::table('cotizaciond')
                ->select('cotizaciond.*','producto.*')
                ->where('cotizaciond.idempresa','=',$cotizacion->idempresa)
                ->where('cotizaciond.idcotizacionh','=',$cotizacion->idcotizacionh)
                ->join('producto','cotizaciond.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$cotizacion->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$cotizacion->idcliente)
                    ->first();

        return view('cotizacion/info_cotizacion')
            ->with('cotizacion',$cotizacion)
            ->with('cotizacionD',$cotizacionD)
            ->with('sucursal',$sucursal)
            ->with('cliente',$cliente);

    }

    public function print(Request $request){
        $id = $request['id'];

        $cotizacion = DB::table('cotizacionh')
                ->where('idcotizacionh',$id)
                ->first();

        $cotizacionD = DB::table('cotizaciond')
                ->select('cotizaciond.cantidad as cantidad','cotizaciond.*','producto.*')
                ->where('cotizaciond.idempresa','=',$cotizacion->idempresa)
                ->where('cotizaciond.idcotizacionh','=',$cotizacion->idcotizacionh)
                ->join('producto','cotizaciond.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$cotizacion->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$cotizacion->idcliente)
                    ->first();

        return view('cotizacion/print_cotizacion')
            ->with('cotizacion',$cotizacion)
            ->with('cotizacionD',$cotizacionD)
            ->with('sucursal',$sucursal)
            ->with('cliente',$cliente);

    }

     public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $cotizacionh = CotizacionH::find($id);
        $cotizacionh->estado_doc = $status;
        $cotizacionh->save();
        return response()->json(['accepted'], 202);
    }

    public function ct_estado(Request $request){
        $id_orden_ventah = $request['id_orden_ventah'];
        $orden_ventah = CotizacionH::find($id_orden_ventah);
        $orden_ventah->estado_doc = 2;
        $orden_ventah->save();

        return json_encode(['mensaje' => 200]);
    }

    public function ct_edit_comments(Request $request) {

        $cotizacionh = CotizacionH::find($request['id_reg']);
        $cotizacionh->comentarios = $request['comments'];
        $cotizacionh->save();

        return json_encode(['mensaje' => 200]);
    }


}