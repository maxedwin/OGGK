<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Marca;
use App\Models\Producto;
use App\User;
use Auth;
use DB;

class MarcaController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() 
    {
        $marcas = DB::table('marcas')->get();
        return view('marca/listado_marcas', ['marcas' => $marcas]);
    }

    public function store(Request $request)
    {
    	$idmarca = $request['idmarca'];
        $nombre = $request['nombre'];
        
        $editar = (int)$request['edit'];

        $marca = new Marca;
        if($editar != 0){
            $marca = Marca::find($idmarca);
        }
        $marca->nombre = $nombre;
        $marca->save();

        return response()->json(['created'], 201);
    }

    public function productos(Request $request)
    {
    	$idmarca = $request['id'];
    	$productos = DB::table('producto')
    					->where('idmarca', $idmarca)->get();

    	return view('marca/listado_productos_marca')->with('productos', $productos);
    }

    public function store_precios(Request $request)
    {
    	$idproducto = $request['idproducto'];
        $costo_sin_igv = $request['costo_sin_igv'];
        $precio_rango_0 = $request['precio_rango_0'];
        $precio_rango_1 = $request['precio_rango_1'];
        $precio_rango_2 = $request['precio_rango_2'];
        $cantidad_caja = $request['cantidad_caja'];

        $producto = Producto::find($idproducto);
        $producto->costo_sin_igv = $costo_sin_igv;
        $producto->precio_rango_0 = $precio_rango_0;
        $producto->precio_rango_1 = $precio_rango_1;
        $producto->precio_rango_2 = $precio_rango_2;
        $producto->cantidad_caja = $cantidad_caja;
        $producto->save();

        return response()->json(['created'], 201);
    }
}