<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Servicios;
use App\Models\Producto;
use DB;
use App\User;
use Auth;


class ServiciosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        $cant = $request['cant'];
        $query = $request['query'];


        if(!isset($cant))$cant = 20;
        if(!isset($query)){
            $products = DB::table('producto')
                ->where('producto.tipo',2)
                ->where('idsucursal',$user)
                ->get();
                //->limit(1000)
                //->paginate(20);

        }else{
            $products = DB::table('producto')
                ->where('producto.tipo',2)
                ->where('producto.nombre','like','%'.$query.'%')
                ->where('idsucursal',$user)
                ->paginate($cant);
        }

        return view('servicios/lista_servicios2', ['products' => $products]);

    }
    public function create(Request $request)
    {
        //return view('servicios/nuevo_servicio');
        return view('servicios/nuevo_servicio2');

    }

    public function store(Request $request)
    {
        $sucursal = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        $idproducto = $request['idproducto'];
        $idcategoria = 0;
        $idproveedor = 0;
        $barcode = $request['barcode'];
        $nombre = $request['nombre'];
        $detalle = $request['descripcion'];
        $codigo_sunat = $request['cod_sunat'];
        $stock_imaginario = 0;
        $stock_total = 0;
        $tipo = 2;
        $medida_venta = 'ZZ';
        

        $peso_unidad = 0;
        $peso_unidad_und = "-";
        $precio = $request['precio'];
        $precio_rango_2 = $precio;

        $bool = false;

        $bool = DB::table('producto')->where('barcode', '=', $barcode)->first();

        if( !$bool ){
            if(!empty($idproducto)){
                $product = Producto::find($idproducto);
                $product->idcategoria = $idcategoria;
                $product->idproveedor = $idproveedor;
                $product->barcode = $barcode;
                $product->nombre = $nombre;
                $product->detalle = $detalle;
                $product->stock_imaginario = $stock_imaginario;
                //$product->stock_total = $stock_total;
                $product->peso_unidad = $peso_unidad;
                $product->peso_unidad_und = $peso_unidad_und;
                $product->precio = $precio;
                $product->tipo = $tipo;
                $product->medida_venta = $medida_venta;
                $product->precio_rango_2 = $precio_rango_2;
                $product->codigo_sunat = $codigo_sunat;

                $product->save();

             }else{
                $i = 0;
                $product = new Producto;
                $product->idempresa = $empresa;
                $product->idsucursal = $sucursal;
                $product->idcategoria = $idcategoria;
                $product->idproveedor = $idproveedor;
                $product->barcode = $barcode;
                $product->nombre = $nombre;
                $product->detalle = $detalle;
                $product->stock_imaginario = $stock_imaginario;
                //$product->stock_total = $stock_total;
                $product->peso_unidad = $peso_unidad;
                $product->peso_unidad_und = $peso_unidad_und;
                $product->precio = $precio;
                $product->tipo = $tipo;
                $product->medida_venta = $medida_venta;
                $product->precio_rango_2 = $precio_rango_2;
                $product->codigo_sunat = $codigo_sunat;

                $product->save();
             }
             return json_encode(['mensaje' => 201]);
        }                            
        else
            return json_encode(['mensaje' => 999]);
    }

    public function update(Request $request){
        $query = $request['id'];
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;
        if(!empty($query)){

            $producto = DB::table('producto')
                ->where('idsucursal',$user)
                ->where('idempresa',$empresa)
                ->where('idproducto',$query)
                ->where('tipo',2)
                ->first();

            if( !empty($producto) or !isset($producto)){
                return view('servicios/editar_servicio2',['mensaje' => '200'])->with('producto', $producto);
            }else{
                return json_encode(['mensaje' => '404']);
            }
        }
        else{
            return json_encode(['mensaje' => '404']);
        }
    }

    public function store_update(Request $request){
        $sucursal = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        $idproducto = $request['idproducto'];
        $barcode = $request['barcode'];
        $nombre = $request['nombre'];
        $detalle = $request['descripcion'];
        $precio = $request['precio'];
        $codigo_sunat = $request['cod_sunat'];
        $medida_venta = 'ZZ';
        $precio_rango_2 = $precio;

        $product = Producto::find($idproducto);
        $product->barcode = $barcode;
        $product->nombre = $nombre;
        $product->detalle = $detalle;
        $product->precio = $precio;
        $product->medida_venta = $medida_venta;
        $product->precio_rango_2 = $precio_rango_2;
        $product->codigo_sunat = $codigo_sunat;

        $product->save();

        return json_encode(['mensaje' => 201]);

    }

    public function destroy(Request $request){
         /*$currentUser = JWTAuth::parseToken()->authenticate();
         $token = JWTAuth::getToken();
         $user = JWTAuth::toUser($token);*/

        $id = $request['id'];
        $product = Producto::find($id);
        try {
            $product->delete();
            return response()->json(['accepted'], 202);
        } catch (Exception $e) {
            return response()->json(['conflict'], 409);
        }
    }
}