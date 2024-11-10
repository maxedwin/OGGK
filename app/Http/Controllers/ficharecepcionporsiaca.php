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
use App\Models\Lote;
use App\Models\AlmacenLote;
use App\User;
use App\Models\FichaRecepcionH;
use App\Models\FichaRecepcionD;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;

class FichaRecepcionPorSiAcasoController extends Controller
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
            $ficha_recepcions = DB::table('ficha_recepcionh')
            ->select('ficha_recepcionh.*','proveedores.razon_social')
            ->where('ficha_recepcionh.idempresa',$empresa)
            ->join ('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
            ->paginate($cant);

        }else{
            $ficha_recepcions = DB::table('ficha_recepcionh')
                ->where('idempresa',$empresa)
                ->where('numeracion','like','%'.$query.'%')
                ->join ('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
                ->paginate($cant);
        }

        return view('ficha_recepcion/listado_ficha_recepcion', ['ficha_recepcions' => $ficha_recepcions]);
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

        $almacenes = DB::table('almacen')->get();                    
                        

        return view('ficha_recepcion/ficha_recepcion')->with('products',$products)->with('usuario',$usuario)->with('almacenes',$almacenes);
    }

    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria')
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

    public function buscar_proveedor(Request $request){
        $busqueda = $request['query'];
        $proveedores = DB::table('proveedores')
            ->where('razon_social','like','%'.$busqueda.'%')
            ->orwhere('ruc_dni','like','%'.$busqueda.'%')
            // ->orwhere('dni',$busqueda)
            // ->orwhere('ruc',$busqueda)
            ->get();
        return json_encode($proveedores);
    }

    public function buscar_FacturaCompra(Request $request){
        $busqueda = $request['query'];
        $facturas = DB::table('factura_comprah')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->get();
        return json_encode($facturas);
    }

    public function store(Request $request){

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idproveedor = $request['idproveedor'];
        $id_factura_comprah = $request['id_factura_comprah'];
        $almacen = $request['almacen'];
        $comentarios = $request['comentarios'];
        $f_emision = $request['f_emision'];
        $f_recepcion = $request['f_recepcion'];
        $serie = $request['serie'];
        $numeracion = $request['numeracion'];
            
        $productos_json = $request['productos'];
        $state = 1;

        $maximo_num = DB::table('ficha_recepcionh')
                        ->where('idempresa',$empresa)
                        ->where('idsucursal',$sucursal)
                        ->max('numeracion');
    
        $productos = json_decode($productos_json);

        $flag = FALSE;
        $respuesta = array();

        for($i = 0; $i < count($productos); $i++){
            $boolLote = DB::table('lote')->where('codigo', $productos[$i]->lote)->get();
                        
            if ($boolLote <> null){
                $flag = TRUE;
            }
        }

        if ($flag == FALSE){
            $ficha_recepcionh = new FichaRecepcionH;
            $ficha_recepcionh->idempresa = $empresa;
            $ficha_recepcionh->idsucursal = $sucursal;
            $ficha_recepcionh->idusuario = $idusuario;
            $ficha_recepcionh->idalmacen = $almacen;
            $ficha_recepcionh->id_factura_comprah = $id_factura_comprah;
            $ficha_recepcionh->idproveedor = $idproveedor;
            $ficha_recepcionh->serie = 'FR';
            $ficha_recepcionh->numeracion = intval($maximo_num) + 1 ;
            $ficha_recepcionh->comentarios = $comentarios;
            $ficha_recepcionh->f_emision = $f_emision;
            $ficha_recepcionh->f_recepcion = $f_recepcion;  
            $ficha_recepcionh->save(); 

            for($i = 0; $i < count($productos); $i++){
                $product = Producto::find($productos[$i]->idproducto);

                if( $product->tipo == 1 ){
                    $product->stock_total = $product->stock_total + $productos[$i]->stock_total;
                    $product->save();                    
                }

                $lote = new Lote;
                $lote->idproducto = $productos[$i]->idproducto;
                $lote->codigo = $productos[$i]->lote;
                $lote->f_venc = $productos[$i]->f_vencimiento;
                $lote->state = 1;
                $lote->stock_lote = $lote->stock_lote + $productos[$i]->stock_total;                
                $lote->save();

                $ficha_recepciond = new FichaRecepcionD;
                $ficha_recepciond->id_ficha_recepcionh = $ficha_recepcionh->id_ficha_recepcionh;
                $ficha_recepciond->idproducto = $productos[$i]->idproducto;
                $ficha_recepciond->cantidad = $productos[$i]->stock_total;
                $ficha_recepciond->idempresa = $empresa;
                $ficha_recepciond->idlote = $lote->idlote;
                $ficha_recepciond->save();

                $almacenlote = new AlmacenLote;
                $almacenlote->idlote = $lote->idlote;
                $almacenlote->idalmacen = $almacen;
                $almacenlote->state = 1;
                $almacenlote->save();       
            }

            $respuesta[]= ['created'=> 200];
            $respuesta[] = ['id' => $ficha_recepcionh->id_ficha_recepcionh];

            return json_encode($respuesta);

        }else{
            $respuesta[]= ['created'=> 500];
            $respuesta[] = ['id' => 999 ];
            return json_encode($respuesta);
        }          
        
    }
    
    public function show(Request $request){
    	$id = $request['id'];

    	$ficha_recepcion = DB::table('ficha_recepcionh')
                ->where('id_ficha_recepcionh',$id)
                ->first();

    	$ficha_recepcionD = DB::table('ficha_recepciond')
                ->select('ficha_recepciond.*','producto.*')
                ->where('ficha_recepciond.idempresa','=',$ficha_recepcion->idempresa)
                ->where('ficha_recepciond.id_ficha_recepcionh','=',$ficha_recepcion->id_ficha_recepcionh)
                ->join('producto','ficha_recepciond.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$ficha_recepcion->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $proveedor = DB::table('proveedores')
                    ->where('idproveedor','=',$ficha_recepcion->idproveedor)
                    ->first();

        return view('ficha_recepcion/info_ficha_recepcion')
            ->with('ficha_recepcion',$ficha_recepcion)
            ->with('ficha_recepcionD',$ficha_recepcionD)
            ->with('sucursal',$sucursal)
            ->with('proveedor',$proveedor);

    }


}