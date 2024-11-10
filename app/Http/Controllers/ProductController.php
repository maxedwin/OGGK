<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Imagick;
use Dingo\Api\Routing\Helpers;
use App\Models\Transacciones;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\ProductoTag;
use App\Tag;
use DB;
use App\User;
use Auth;
use Psy\Util\Json;
use Image;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        $cant = $request['cant'];
        $query = $request['query'];

        $idprod = 0;
        if(!isset($cant))$cant = 500;
        if(!isset($query)){
            $products = DB::table('producto')
                ->select('categorias.descripcion as subfami', 'cate.descripcion as fami', 'producto.*', 'prove.razon_social as prove', 'producto.idproducto as idprod', DB::raw("IFNULL(SUM(l.stock_lote), 0) as stockT"))
                //->where('producto.tipo',1) // 1 producto, 2 servicio
                ->where('idsucursal',$user)
                ->leftjoin('lote as l', 'l.idproducto', '=', 'producto.idproducto')
                ->leftjoin('almacenlote as a', 'a.idlote', '=', 'l.idlote')
                ->leftjoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
                ->leftjoin('categorias as cate','categorias.idpadre','=','cate.idcategoria')
                ->leftjoin('proveedores as prove','prove.idproveedor','=','producto.idproveedor')
                ->groupBy('producto.idproducto')
                ->get();
        }else{
            $products = DB::table('producto')
                ->select('categorias.descripcion as subfami', 'cate.descripcion as fami', 'producto.*', 'prove.razon_social as prove', 'producto.idproducto as idprod', DB::raw("IFNULL(SUM(l.stock_lote), 0) as stockT"))
                //->where('producto.tipo',1)
                ->where('producto.nombre','like','%'.$query.'%')
                ->where('idsucursal',$user)
                ->leftjoin('lote as l', 'l.idproducto', '=', 'producto.idproducto')
                ->leftjoin('almacenlote as a', 'a.idlote', '=', 'l.idlote')
                ->leftjoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
                ->leftjoin('categorias as cate','categorias.idpadre','=','cate.idcategoria')
                ->leftjoin('proveedores as prove','prove.idproveedor','=','producto.idproveedor')
                ->groupBy('producto.idproducto')
                ->get();
        }

        return view('products/lista_producto', ['products' => $products]);
    }

    public function create(Request $request)
    {
        $empresa = Auth::user()->idempresa;
        $categorias = DB::table('categorias')->where('idempresa',$empresa )
            ->whereNotNull('idpadre')
            ->where('idpadre','>',0)
            ->get();
        $und_medida = DB::table('unidades_medida')->get();  
        $proveedores = DB::table('proveedores')->get();
        $colores = DB::table('colores')->get();
        //$cod_sunat = DB::table('codigos_sunat_prods')->get();
        $cod_sunat = [];
        $categorias_uso = DB::table('categorias_uso')->get();
        $marcas = DB::table('marcas')->get();

        return view('products/nuevo_producto')->with('categorias', $categorias)->with('unidades',$und_medida)->with('proveedores',$proveedores)->with('colores',$colores)->with('cod_sunat',$cod_sunat)->with('marcas', $marcas)->with('categorias_uso', $categorias_uso);
    }

    public function update(Request $request){
        $query = $request['id'];
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;
        $und_medida = DB::table('unidades_medida')->get();
        $colores = DB::table('colores')->get();
        $selectedtags = ProductoTag::where('idproducto',$query)->get();
        $tags = DB::table('tags')->get();
        
        if(!empty($query)){
            $categorias = DB::table('categorias')->whereNotNull('idpadre')->where('idempresa',$empresa )->where('idpadre','>',0)->get();
            $proveedor = DB::table('proveedores')->whereNotNull('idproveedor')->where('idempresa',$empresa)->get();
            $marcas = DB::table('marcas')->get();
            $categorias_uso = DB::table('categorias_uso')->get();

            $producto = DB::table('producto')->select('producto.*','producto.state_tienda as desta','cat.*','colores.*','proveedores.*')
                ->where('producto.idsucursal',$user)
                ->where('producto.idempresa',$empresa)
                ->where('idproducto',$query)
                ->where('tipo',1)  //tipo 1 producto, tipo 2 servicio
                ->join('categorias as cat','cat.idcategoria','=','producto.idcategoria')
                ->join('colores','id_color','=','producto.color')
                ->join('proveedores','proveedores.idproveedor','=','producto.idproveedor')
                ->first();        

            if( !empty($producto) or !isset($producto)){
                return view('products/editar_producto',['mensaje' => '200'])
                ->with('producto', $producto)
                ->with('categorias', $categorias)
                ->with('unidades',$und_medida)
                ->with('proveedores',$proveedor)
                ->with('colores',$colores)
                ->with('marcas', $marcas)
                ->with('categorias_uso', $categorias_uso)
                ->with('selectedtags', $selectedtags)
                ->with('tags', $tags);
             }else{
                return json_encode(['mensaje' => '404']);
            }
        }
        else{
            return json_encode(['mensaje' => '404']);
        }
    }


    public function find(Request $request){
        $query = $request['nom'];
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        if(!empty($query)){
            $products = DB::table('producto')
                ->where('idsucursal',$user)
                ->where('idempresa',$empresa)
                ->where('producto.nombre','like','%'.$query.'%')
                ->get();

            return response()->json($products, 202);
        }
    }

    public function get(Request $request){
        $query = $request['id'];
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        if(!empty($query)){
            $products = DB::table('producto')
                ->where('idsucursal',$user)
                ->where('idempresa',$empresa)
                ->where('idproducto',$query)
                ->where('tipo',1)
                ->first();

                return response()->json($products, 202);
        }
    }


    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $producto = Producto::find($id);
        $producto->state = $status;
            $producto->save();
            return response()->json(['accepted'], 202);
    }

    public function store(Request $request){
        $sucursal = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        $idproducto = $request['idproducto'];
        $idcategoria = $request['idcategoria'];
        $idcategoria_uso = $request['idcategoria_uso'];
        $idproveedor = $request['idproveedor'];
        $idmarca = $request['idmarca'];
        $barcode = $request['barcode'];
        $nombre = $request['nombre'];
        $detalle = $request['descripcion'];
        $stock_total = $request['stock_total'];
        $volumen = $request['volumen'];
        $volumen_und = $request['volumen_und'];
        $cantidad_caja = $request['cantidad_caja'];
        $medida_venta = $request['medida_venta'];
        // $tipo_alm = $request['tipo_alm'];
        $peso_unidad = $request['peso_unidad'];
        $peso_unidad_und = $request['peso_unidad_und'];
        $color = $request['color'];
        $costo = $request['costo'];
        $precio = $request['precio'];
        $codigo_sunat = $request['cod_sunat'];
        $state_tienda = $request['state'];

        $bool = false;

        $bool = DB::table('producto')->where('barcode', '=', $barcode)->first();

        if( !$bool ){
            if(!empty($idproducto)){
                $product = Producto::find($idproducto);
                $product->idcategoria = $idcategoria;
                $product->idproveedor = $idproveedor;
                $product->idcategoria_uso = $idcategoria_uso;
                $product->idmarca = $idmarca;
                $product->barcode = $barcode;
                $product->nombre = $nombre;
                $product->detalle = $detalle;
                $product->stock_total = $stock_total;
                $product->volumen = $volumen;
                $product->volumen_und = $volumen_und;                        
                $product->cantidad_caja = $cantidad_caja;
                $product->medida_venta = $medida_venta;
                // $product->tipo_alm = $tipo_alm;
                $product->peso_unidad = $peso_unidad;
                $product->peso_unidad_und = $peso_unidad_und;
                $product->color = $color;
                $product->costo = $costo;
                $product->precio = $precio;
                $product->state_tienda = $state_tienda;
                $product->codigo_sunat = $codigo_sunat;

                $product->save();

             }else{
                $i = 0;
                $product = new Producto;
                $product->idempresa = $empresa;
                $product->idsucursal = $sucursal;
                $product->idcategoria = $idcategoria;
                $product->idproveedor = $idproveedor;
                $product->idcategoria_uso = $idcategoria_uso;
                $product->idmarca = $idmarca;
                $product->barcode = $barcode;
                $product->nombre = $nombre;
                $product->detalle = $detalle;
                $product->stock_total = $stock_total;
                $product->volumen = $volumen;
                $product->volumen_und = $volumen_und;                        
                $product->cantidad_caja = $cantidad_caja;
                $product->medida_venta = $medida_venta;
                // $product->tipo_alm = $tipo_alm;
                $product->peso_unidad = $peso_unidad;
                $product->peso_unidad_und = $peso_unidad_und;
                $product->color = $color;
                $product->costo = $costo;
                $product->precio = $precio;
                $product->state_tienda = $state_tienda;
                $product->codigo_sunat = $codigo_sunat;

                if($request->file('image')){
                        $image=$request->file('image');
                        if($image->isValid()){
                            $fileName=time().'-'.str_slug($request['nombre'],"-").'.'.$image->getClientOriginalExtension();
                            $large_image_path=public_path('images/large/'.$fileName);
                            $medium_image_path=public_path('images/medium/'.$fileName);
                            $small_image_path=public_path('images/small/'.$fileName);
                            //Resize Image

                            Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                            Image::make($image->getRealPath())->resize(600,600)->save('./images/medium/'. $fileName);
                            Image::make($image->getRealPath())->resize(300,300)->save('./images/small/'. $fileName);
                            $product->image=$fileName;
                        }
                }

                $product->save();
             }
             return json_encode(['mensaje' => 201]);
        }                            
        else
            return json_encode(['mensaje' => 999]);    
        

            //No guardaba por el json, corregir cuando se haga transacciones

            //  if($stock_total > 0){

            //      $transacts = new Transacciones;
            //      $transacts->idproducto = $product->idproducto;
            //      $transacts->idempresa = $empresa;
            //      $transacts->idsucursal = $sucursal;

            //      $transacts->stock_total = $stock_total;
            //      $transacts->state = 1;
            //      $transacts->tipo = 1;

            //      $transacts->save();
            //  }

            //return json_encode(['mensaje' => 201]);
            //  return response()->json(['created'], 201);

    }

    public function subir_ficha_tecnica(Request $request) {

        $idproducto = $request['idproducto'];
        $product = Producto::find($idproducto);

                if($request->file('ficha')){
                        $ficha=$request->file('ficha');
                        if($ficha->isValid()){
                            $fileName=time().'-'.str_slug($request['nombre'],"-").'.'.$ficha->getClientOriginalExtension();
                            $ficha->move('./files', $fileName);
                            $product->adjunto=$fileName;
                        }
                    }

        $product->save();

        $query = $request['idproducto'];
        
        if(!empty($query)){

            return redirect()->action('ProductController@update', ['id' => $query]);
        }
        else{
            return json_encode(['mensaje' => '404']);
        }

        return response()->json(['accepted'], 202);

    }


    public function subir_imagen(Request $request){
        $idproducto = $request['idproducto'];
        $product = Producto::find($idproducto);

                if($request->file('image')){
                        $image=$request->file('image');
                        if($image->isValid()){
                            $fileName=time().'-'.str_slug($request['nombre'],"-").'.'.$image->getClientOriginalExtension();
                            $large_image_path=public_path('images/large/'.$fileName);
                            $medium_image_path=public_path('images/medium/'.$fileName);
                            $small_image_path=public_path('images/small/'.$fileName);
                            //Resize Image

                            Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                            Image::make($image->getRealPath())->resize(600,600)->save('./images/medium/'. $fileName);
                            Image::make($image->getRealPath())->resize(300,300)->save('./images/small/'. $fileName);
                            $product->image=$fileName;
                        }
                    }

        $product->save();

        $query = $request['idproducto'];
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;
        $und_medida = DB::table('unidades_medida')->get();
        $colores = DB::table('colores')->get();
        
        if(!empty($query)){
            $categorias = DB::table('categorias')->whereNotNull('idpadre')->where('idempresa',$empresa )->where('idpadre','>',0)->get();
            $proveedor = DB::table('proveedores')->whereNotNull('idproveedor')->where('idempresa',$empresa)->get();
            $categorias_uso = DB::table('categorias_uso')->get();
            $producto = DB::table('producto')->select('producto.*','producto.state as desta','cat.*','colores.*','proveedores.*')
                ->where('producto.idsucursal',$user)
                ->where('producto.idempresa',$empresa)
                ->where('idproducto',$query)
                ->where('tipo',1)  //tipo 1 producto, tipo 2 servicio
                ->join('categorias as cat','cat.idcategoria','=','producto.idcategoria')
                ->join('colores','id_color','=','producto.color')
                ->join('proveedores','proveedores.idproveedor','=','producto.idproveedor')
                ->first();
            $marcas = DB::table('marcas')->get();         

            if( !empty($producto) or !isset($producto)){
                return view('products/editar_producto',['mensaje' => '200'])->with('producto', $producto)->with('categorias', $categorias)->with('unidades',$und_medida)->with('proveedores',$proveedor)->with('colores',$colores)->with('categorias_uso', $categorias_uso)->with('marcas', $marcas);
             }else{
                return json_encode(['mensaje' => '404']);
            }
        }
        else{
            return json_encode(['mensaje' => '404']);
        }

        return response()->json(['accepted'], 202);
    }

    public function store_update(Request $request){
        $sucursal = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;

        $idproducto = $request['idproducto'];
        $idcategoria = $request['idcategoria'];
        $idproveedor = $request['idproveedor'];
        $idcategoria_uso = $request['idcategoria_uso'];
        $idmarca = $request['idmarca'];
        $barcode = $request['barcode'];
        $nombre = $request['nombre'];
        $detalle = $request['descripcion'];
        $stock_total = $request['stock_total'];
        $volumen = $request['volumen'];
        $volumen_und = $request['volumen_und'];
        $cantidad_caja = $request['cantidad_caja'];
        $medida_venta = $request['medida_venta'];
        // $tipo_alm = $request['tipo_alm'];
        $peso_unidad = $request['peso_unidad'];
        $peso_unidad_und = $request['peso_unidad_und'];
        $color = $request['color'];
        $costo = $request['costo'];
        $precio = $request['precio'];
        $codigo_sunat = $request['cod_sunat'];
        $state_tienda = $request['state'];
        $features = $request['features'];
        $aplications = $request['aplications'];


        $product = Producto::find($idproducto);
        $product->idcategoria = $idcategoria;
        $product->idproveedor = $idproveedor;
        $product->idcategoria_uso = $idcategoria_uso;
        $product->idmarca = $idmarca;
        $product->barcode = $barcode;
        $product->nombre = $nombre;
        $product->detalle = $detalle;
        $product->stock_total = $stock_total;
        $product->volumen = $volumen;
        $product->volumen_und = $volumen_und;                        
        $product->cantidad_caja = $cantidad_caja;
        $product->medida_venta = $medida_venta;
        // $product->tipo_alm = $tipo_alm;
        $product->peso_unidad = $peso_unidad;
        $product->peso_unidad_und = $peso_unidad_und;
        $product->color = $color;
        $product->costo = $costo;
        $product->precio = $precio;
        $product->state_tienda = $state_tienda;
        $product->codigo_sunat = $codigo_sunat;
        $product->features = $features;
        $product->aplications = $aplications;

        $product->save();

        $selected_tags = $request['tags_select'];
        $tags_actuales = ProductoTag::where('idproducto',$idproducto)->pluck('idtag')->toarray();        
        $tags_borrar = ProductoTag::where('idproducto',$idproducto)->whereNotIn('idtag', $selected_tags)->pluck('id')->toarray();
        $tags_insertar = array_diff($selected_tags, $tags_actuales);
        
        foreach($tags_borrar as $tag_borrar){
            $productotag = ProductoTag::destroy($tag_borrar);
        }

        foreach($tags_insertar as $tag){
            $productotag = new ProductoTag();
            $productotag->idtag = $tag;
            $productotag->idproducto = $idproducto;
            $productotag->save();              
        }

        return json_encode(['mensaje' => 201]);
    }

    public function deleteFicha($id){
        //Products_model::where(['id'=>$id])->update(['image'=>'']);
        $delete_ficha=Producto::findOrFail($id);
        $ficha='./files/'.$delete_ficha->adjunto;
        if($delete_ficha){
            $delete_ficha->adjunto='';
            $delete_ficha->save();
            ////// delete ficha tecnica ///
            unlink($ficha);
        }
        
        $query = $id;
        
        if(!empty($query)){

            return redirect()->action('ProductController@update', ['id' => $query]);
        }
        else{
            return json_encode(['mensaje' => '404']);
        }

        return response()->json(['accepted'], 202);
    }


    public function destroy(Request $request){
         /*$currentUser = JWTAuth::parseToken()->authenticate();
         $token = JWTAuth::getToken();
         $user = JWTAuth::toUser($token);*/

        $id = $request['id'];
        $product = Producto::find($id);
        $tags_borrar = DB::table('productotag')->where('idproducto','=',$id)->get();
        try {
            foreach($tags_borrar as $productotag){                   
                $productotag->delete();                    
            }
            $product->delete();
            return response()->json(['accepted'], 202);
        } catch (Exception $e) {
            return response()->json(['conflict'], 409);
        }
    }

    public function deleteImage($id){
        //Products_model::where(['id'=>$id])->update(['image'=>'']);
        $delete_image=Producto::findOrFail($id);
        $image_large='./images/large/'.$delete_image->image;
        $image_medium='./images/medium/'.$delete_image->image;
        $image_small='./images/small/'.$delete_image->image;
        if($delete_image){
            $delete_image->image='';
            $delete_image->save();
            ////// delete image ///
            unlink($image_large);
            unlink($image_medium);
            unlink($image_small);
        }
        
        $query = $id;
        $user = Auth::user()->idsucursal;
        $empresa = Auth::user()->idempresa;
        $und_medida = DB::table('unidades_medida')->get();
        $colores = DB::table('colores')->get();
        
        if(!empty($query)){
            $categorias = DB::table('categorias')->whereNotNull('idpadre')->where('idempresa',$empresa )->where('idpadre','>',0)->get();
            $proveedor = DB::table('proveedores')->whereNotNull('idproveedor')->where('idempresa',$empresa)->get();
            $categorias_uso = DB::table('categorias_uso')->get();
            $producto = DB::table('producto')->select('producto.*','producto.state as desta','cat.*','colores.*','proveedores.*')
                ->where('producto.idsucursal',$user)
                ->where('producto.idempresa',$empresa)
                ->where('idproducto',$query)
                ->where('tipo',1)  //tipo 1 producto, tipo 2 servicio
                ->join('categorias as cat','cat.idcategoria','=','producto.idcategoria')
                ->join('colores','id_color','=','producto.color')
                ->join('proveedores','proveedores.idproveedor','=','producto.idproveedor')
                ->first();         

            if( !empty($producto) or !isset($producto)){
                return view('products/editar_producto',['mensaje' => '200'])->with('producto', $producto)->with('categorias', $categorias)->with('unidades',$und_medida)->with('proveedores',$proveedor)->with('colores',$colores)->with('categorias_uso', $categorias_uso);
             }else{
                return json_encode(['mensaje' => '404']);
            }
        }
        else{
            return json_encode(['mensaje' => '404']);
        }

        return response()->json(['accepted'], 202);
    }


    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $productos = DB::table('producto')
            ->where('nombre','like','%'.$busqueda.'%')
            // ->orwhere('descripcion','like','%'.$busqueda.'%')
            ->get();
        return json_encode($productos);
    }

    public function lista_lote(Request $request){
        $products = DB::table('producto')
            ->select('categorias.descripcion as subfami', 'cate.descripcion as fami', 'producto.*', 'producto.nombre as nombre_prod', 'l.stock_lote as stockT', 'l.f_venc as fvenc', 'l.codigo as lote', 'al.nombre as almacen' )
            ->join('lote as l', 'l.idproducto', '=', 'producto.idproducto')
            ->join('almacenlote as a', 'a.idlote', '=', 'l.idlote')
            ->join('almacen as al', 'al.idalmacen', '=', 'a.idalmacen')
            ->leftjoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->leftjoin('categorias as cate','categorias.idpadre','=','cate.idcategoria')
            ->orderBY('producto.idproducto')
            ->get();

        return view('products/lista_lote', ['products' => $products]);
    }

    public function lista_codigo_sunat(Request $request){
        $busqueda = $request['query'];
        $cods = DB::table('codigos_sunat_prods')->where('descripcion','like','%'.$busqueda.'%')->get();
        return json_encode($cods);
    }
}