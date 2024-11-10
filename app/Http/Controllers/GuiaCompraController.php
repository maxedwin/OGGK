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
use App\Models\GuiaCompraH;
use App\Models\GuiaCompraD;
use App\Models\OrdenCompraH;
use Auth;
use DB;

class GuiaCompraController extends Controller
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
            $guia_compras = DB::table('guia_comprah')
            ->select('guia_comprah.*','proveedores.razon_social', 'trans.razon_social as trans')
            ->where('guia_comprah.idempresa',$empresa)
            ->join ('proveedores', 'guia_comprah.idproveedor', '=', 'proveedores.idproveedor')
            ->join ('proveedores as trans', 'guia_comprah.flete_trans', '=', 'trans.idproveedor')
            ->paginate($cant);

        }else{
            $guia_compras = DB::table('guia_comprah')
                ->where('idempresa',$empresa)
                ->where('numeracion','like','%'.$query.'%')
                ->join ('proveedores', 'guia_comprah.idproveedor', '=', 'proveedores.idproveedor')
                ->join ('proveedores as trans', 'guia_comprah.flete_trans', '=', 'trans.idproveedor')
                ->paginate($cant);
        }

        return view('guia_compra/listado_guia_compra', ['guia_compras' => $guia_compras]);
    }
    
    public function crear()        
    {
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $id = Auth::user()->id;

        $products = Producto::where('idempresa',$empresa)
                    ->where('idsucursal',$sucursal)
                    ->where('state',1)
                    ->where('stock_total','>',0)->get();

        $usuario = DB::table('users')
                    ->where('id',$id)
                    ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
                    ->join('empresas','users.idempresa','=','empresas.idempresa')
                    ->first();
                        

        return view('guia_compra/guia_compra')->with('products',$products)->with('usuario',$usuario);
    }

    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria')
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('producto.state',1)
                    ->orwhere('idsucursal',$sucursal);
            })
             ->where('producto.barcode', 'like', '%'.$busqueda.'%')
             ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
             ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%')

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->get();

        return json_encode($products);
    }

    public function buscar_proveedor(Request $request){
        $busqueda = $request['query'];
        $proveedores = DB::table('proveedores')
            ->where('idproveedor',$busqueda)
            ->get();
        return json_encode($proveedores);
    }

    public function buscar_oc_numeracion(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('orden_comprah')
            ->select('id_orden_comprah','numeracion')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->where('estado_doc','!=',2)
            ->get();
        return json_encode($cotis);
    }

    public function buscar_oc_numeracion_inc(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('orden_comprah')
            ->select('id_orden_comprah','numeracion')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->where('estado_doc','!=',2)
            // AFTDB
            ->where('estado_doc','!=',1)
            // AFTDB
            ->get();
        return json_encode($cotis);
    }

    public function buscar_oc_numeracion_recv(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('orden_comprah')
            ->select('id_orden_comprah','numeracion')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->where('estado_doc','!=',2)
            ->get();
        return json_encode($cotis);
    }

    public function buscar_oc_todo(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('orden_comprah')
            ->select('orden_comprah.*', 'p.razon_social')
            ->join('proveedores as p', 'orden_comprah.idproveedor', '=', 'p.idproveedor')
            ->where('id_orden_comprah','=', $busqueda)
            ->first();

        $cotis->detalle = DB::table('orden_comprad')
            ->select('orden_comprad.*', 'p.*')
            ->join('producto as p', 'p.idproducto', '=', 'orden_comprad.idproducto')
            ->where('id_orden_comprah','=', $busqueda)
            ->get();

        return json_encode($cotis);
    }

    public function buscar_oc_todo_fact(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('orden_comprah')
            ->select('orden_comprah.*', 'p.razon_social')
            ->join('proveedores as p', 'orden_comprah.idproveedor', '=', 'p.idproveedor')
            ->where('id_orden_comprah','=', $busqueda)
            ->first();

        $cotis->detalle = DB::table('orden_comprad')
            ->select('orden_comprad.*', 'p.*')
            ->join('producto as p', 'p.idproducto', '=', 'orden_comprad.idproducto')
            ->where('id_orden_comprah','=', $busqueda)
            ->get();

        $empresa = Auth::user()->idempresa;
        $cotis->facturas = DB::table('factura_comprah')
            ->select('factura_comprah.*','proveedores.razon_social', DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc") )
            ->where('factura_comprah.idempresa',$empresa)
            ->where('factura_comprah.id_guia_comprah','=',$busqueda)
            ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')
            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'factura_comprah.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'factura_comprah.id_guia_comprah')
            ->get();

        return json_encode($cotis);
    }

    public function store(Request $request){

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idproveedor = $request['idproveedor'];
        $id_orden_comprah = $request['id_orden_comprah'];
        $comentarios = $request['comentarios'];
        $f_emision = $request['f_emision'];
        $serie = $request['serie'];
        $numeracion = $request['numeracion'];
        $flete_trans = $request['flete_trans'];
        $flete_costo = $request['flete_costo'];
            
        $productos_json = $request['productos'];
        $state = 1;


        $guia_comprah = new GuiaCompraH;
        $guia_comprah->idempresa = $empresa;
        $guia_comprah->idsucursal = $sucursal;
        $guia_comprah->idusuario = $idusuario;
        $guia_comprah->id_orden_comprah = $id_orden_comprah;
        $guia_comprah->idproveedor = $idproveedor;
        $guia_comprah->serie = $serie;
        $guia_comprah->numeracion = $numeracion;
        $guia_comprah->flete_trans = $flete_trans;
        $guia_comprah->flete_costo = $flete_costo;

        $guia_comprah->comentarios = $comentarios;
        $guia_comprah->f_emision = $f_emision;
        $guia_comprah->save();

        $oc_state = OrdenCompraH::find($id_orden_comprah);
        $oc_state->estado_doc = 1;
        $oc_state->save();

        $productos = json_decode($productos_json);

        for($i = 0; $i < count($productos); $i++){
            // $product = Producto::find($productos[$i]->idproducto);
            // if($product->tipo == 1){
            //             $product->stock_total = $product->stock_total - $productos[$i]->stock_total;
            //             $product->save();
            // }
            $guia_comprad = new GuiaCompraD;
            $guia_comprad->id_guia_comprah = $guia_comprah->id_guia_comprah;
            $guia_comprad->idproducto = $productos[$i]->idproducto;
            $guia_comprad->cantidad = $productos[$i]->stock_total;
            $guia_comprad->idempresa = $empresa;
            $guia_comprad->save();

        }

        $respuesta = array();
        $respuesta[]= ['created'=> 200];
        $respuesta[] = ['id' => $guia_comprah->id_guia_comprah];

        return json_encode($respuesta);

    }
    
    public function show(Request $request){
    	$id = $request['id'];

    	$guia_compra = DB::table('guia_comprah')
                ->where('id_guia_comprah',$id)
                ->first();

    	$guia_compraD = DB::table('guia_comprad')
                ->select('guia_comprad.*','producto.*')
                ->where('guia_comprad.idempresa','=',$guia_compra->idempresa)
                ->where('guia_comprad.id_guia_comprah','=',$guia_compra->id_guia_comprah)
                ->join('producto','guia_comprad.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$guia_compra->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $proveedor = DB::table('proveedores')
                    ->where('idproveedor','=',$guia_compra->idproveedor)
                    ->first();

        return view('guia_compra/info_guia_compra')
            ->with('guia_compra',$guia_compra)
            ->with('guia_compraD',$guia_compraD)
            ->with('sucursal',$sucursal)
            ->with('proveedor',$proveedor);

    }


     public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $guiacompra = GuiaCompraH::find($id);
        $guiacompra->estado_doc = $status;
        $guiacompra->save();
        return response()->json(['accepted'], 202);
    }



}