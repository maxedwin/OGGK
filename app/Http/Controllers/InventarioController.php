<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Models\Transacciones;
use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\Models\Producto;
use App\User;
use App\Models\CajaH;
use App\Models\CajaD;
use App\Models\Lote;
use App\Models\AlmacenLote;
use Auth;
use DB;


class InventarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       $id = Auth::user()->id;

        $movs = DB::table('transacciones')
                ->select('transacciones.*','transacciones.tipo as movi', 'transacciones.created_at as movi_fecha','user1.name as creador','user2.name as quien','producto.*')
                ->join ('producto', 'producto.idproducto', '=', 'transacciones.idproducto')
                ->leftJoin ('users as user1', 'user1.id', '=', 'transacciones.idusuario')
                ->leftJoin ('users as user2', 'user2.id', '=', 'transacciones.quien_uso')
                ->get();           

        return view('inventario/movimientos')->with('movs',$movs);
    }

    public function entradas_index()
    {
       $id = Auth::user()->id;

        $usuario = DB::table('users')
            ->where('id',$id)
            ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
            ->join('empresas','users.idempresa','=','empresas.idempresa')
            ->first();

        $almacenes = DB::table('almacen')->get();           


        return view('inventario/entradas')->with('usuario',$usuario)->with('almacenes',$almacenes);
    }
    
    public function salidas_index()
    {
       $id = Auth::user()->id;

        $usuario = DB::table('users')
            ->where('id',$id)
            ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
            ->join('empresas','users.idempresa','=','empresas.idempresa')
            ->first();

        $vendedores = DB::table('users')
                    ->where('puesto', '=', 6)->get();

        $almacenes = DB::table('almacen')->get();   


        return view('inventario/salidas')->with('usuario',$usuario)->with('vendedores',$vendedores)->with('almacenes',$almacenes);
    }

    public function buscar_inventario(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria')
            ->where('producto.tipo','=',1)
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('producto.stock_total','>=',1)
                    ->orwhere('producto.state',1)
                    ->orwhere('idsucursal',$sucursal);
            })
            ->where('producto.barcode', 'like', '%'.$busqueda.'%')
            ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
            ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%')

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->get();

        return json_encode($products);
    }

    public function store_entradas(Request $request){
        DB::beginTransaction(); // <-- first line  
    
    try{
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;
        $productos = json_decode($request['productos']);

        $almacen = $request['almacen'];   
        $razon = $request['razon'];
        $f_emision = $request['f_emision'];
        $tipo_mov = $request['tipo_mov'];

        $saved = false;


        for($i = 0; $i < count($productos); $i++){
            $product = Producto::find($productos[$i]->idproducto);

            $boolLote = DB::table('lote')->where('codigo', '=', $productos[$i]->lote)->first();
                        
            if ($boolLote <> null){
                $product = Producto::find($productos[$i]->idproducto);
                if($product->tipo == 1){
                        $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->stock_total;
                        $saved = $product->save();
                }

                $tmpLote2 = $boolLote->idlote;
                $lote = Lote::find($tmpLote2);
                $lote->stock_lote = $lote->stock_lote + $productos[$i]->stock_total;  
                $lote->f_venc = $productos[$i]->f_vencimiento;              
                $saved = $lote->save();

                $transacts = new Transacciones;
                $transacts->idproducto = $productos[$i]->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;
                $transacts->idusuario = $idusuario;

                $transacts->idalmacen = $almacen;
                $transacts->f_emision = $f_emision;
                $transacts->tipo_movimiento = $tipo_mov;
                $transacts->razon = $razon;
                $transacts->quien_uso = 0;
                $transacts->idlote = $lote->idlote;

                $transacts->cantidad = $productos[$i]->stock_total;
                $transacts->state = 1;
                $transacts->tipo = 1; // 1 entrada , 0 salidas
                
                $saved = $transacts->save();

            }else{
                $product = Producto::find($productos[$i]->idproducto);
                if($product->tipo == 1){
                        $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->stock_total;
                        $saved = $product->save();
                }

                $lote = new Lote;
                $lote->idproducto = $productos[$i]->idproducto;
                $lote->codigo = $productos[$i]->lote;
                $lote->f_venc = $productos[$i]->f_vencimiento;
                $lote->state = 1;
                $lote->stock_lote = $lote->stock_lote + $productos[$i]->stock_total;                
                $saved = $lote->save();

                $transacts = new Transacciones;
                $transacts->idproducto = $productos[$i]->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;
                $transacts->idusuario = $idusuario;

                $transacts->idalmacen = $almacen;
                $transacts->f_emision = $f_emision;
                $transacts->tipo_movimiento = $tipo_mov;
                $transacts->razon = $razon;
                $transacts->quien_uso = 0;
                $transacts->idlote = $lote->idlote;

                $transacts->cantidad = $productos[$i]->stock_total;
                $transacts->state = 1;
                $transacts->tipo = 1; // 1 entrada , 0 salidas
                
                $saved = $transacts->save();

                $almacenlote = new AlmacenLote;
                $almacenlote->idlote = $lote->idlote;
                $almacenlote->idalmacen = $almacen;
                $almacenlote->state = 1;
                $saved = $almacenlote->save();       
            }
        }

        if($saved)
        $childModelSaved = true; 
        else
        $childModelSaved = false; 

    }catch(Exception $e)
        {
             $childModelSaved = false;
        }

        if($childModelSaved)
        {
            DB::commit(); // YES --> finalize it 
            $respuesta = array();
            $respuesta[]= ['created'=> 200];
            $respuesta[] = ['id' => $transacts->idtransaccion];

            return json_encode($respuesta);

        }
        else
        {
            DB::rollBack(); // NO --> some error has occurred undo the whole thing
            $respuesta = array();
            $respuesta[]= ['deleted'=> 999];
            $respuesta[] = ['id' => 9999];

            return json_encode($respuesta);
        }
    }

    public function store_salidas(Request $request){

    DB::beginTransaction(); // <-- first line  
    
    try{
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;
        $productos = json_decode($request['productos']);

        $almacen = $request['almacen'];   
        $razon = $request['razon'];
        $quien = $request['quien'];
        $f_emision = $request['f_emision'];
        $tipo_mov = $request['tipo_mov'];

        $flag = true;
        $saved = true;

        foreach ($productos as $producto){
            
            $lote = Lote::find($producto->idlote);        
            if( $producto->stock_total > $lote->stock_lote )
                $flag = false;
            else {
                $product = Producto::find($producto->idproducto);
                if($product->tipo == 1){
                        $product->stock_imaginario = $product->stock_imaginario - $producto->stock_total;
                        $saved = $product->save();
                }

                $transacts = new Transacciones;
                $transacts->idproducto = $producto->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;
                $transacts->idusuario = $idusuario;

                $transacts->idalmacen = $almacen;
                $transacts->f_emision = $f_emision;
                $transacts->tipo_movimiento = $tipo_mov;
                $transacts->razon = $razon;
                $transacts->quien_uso = $quien;
                $transacts->idlote = $producto->idlote;

                $transacts->cantidad = $producto->stock_total;
                $transacts->state = 1;
                $transacts->tipo = 0; // 1 entrada , 0 salidas                
                $saved = $transacts->save();
                
                $lote->stock_lote = $lote->stock_lote - $producto->stock_total;
                $saved = $lote->save();
            }
        }

        if($saved && $flag)
        $childModelSaved = true; 
        else
        $childModelSaved = false; 

    }catch(Exception $e)
        {
             $childModelSaved = false;
        }

        if($childModelSaved)
        {
            DB::commit(); // YES --> finalize it 
            $respuesta = array();
            $respuesta[]= ['created'=> 200];
            $respuesta[] = ['id' => $transacts->idtransaccion];

            return json_encode($respuesta);

        }
        else
        {
            DB::rollBack(); // NO --> some error has occurred undo the whole thing
            $respuesta = array();
            $respuesta[]= ['deleted'=> 999];
            $respuesta[] = ['id' => 9999];

            return json_encode($respuesta);
        }
    }

}