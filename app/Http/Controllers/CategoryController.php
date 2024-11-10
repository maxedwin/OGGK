<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\Models\CategoriaUso;
use App\User;
use Auth;
use DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        /*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;
        $categorys = DB::table('categorias')
                    ->select('categorias.*','categorias.descripcion AS id','cate.descripcion AS parent','categorias.descripcion AS text', 'catuso.name AS categoriauso')
                    ->where('categorias.idempresa',$user)
                    ->leftJoin('categoria_uso as catuso','categorias.idcategoriauso','=','catuso.id')
                    ->leftJoin('categorias as cate','categorias.idpadre','=','cate.idcategoria')
                    ->get();
        return response()->json($categorys);
    }

    public function create()
    {
        $user = Auth::user()->idempresa;
        $categorias = DB::table('categorias')
                    ->select('categorias.*','categorias.descripcion',DB::raw("IFNULL(cate.descripcion,'ES_FAMILIA') as padre"),'cate.idcategoria AS idpadre','categorias.idcategoria','categorias.state', 'catuso.name as categoriauso')
                    ->where('categorias.idempresa',$user)
                    ->leftJoin('categorias_uso as catuso','categorias.idcategoriauso','=', 'catuso.id')
                    ->leftJoin('categorias as cate','categorias.idpadre','=','cate.idcategoria')
                    ->get();

        $categorias_padre = DB::table('categorias')
                    ->select('categorias.*','categorias.descripcion',DB::raw("IFNULL(cate.descripcion,'ES_FAMILIA') as padre"),'cate.idcategoria AS idpadre','categorias.idcategoria','categorias.state')
                    ->where('categorias.idempresa',$user)
                    ->where('categorias.idpadre',0)
                    ->leftJoin('categorias as cate','categorias.idpadre','=','cate.idcategoria')
                    ->get();

        $categorias_uso = DB::table('categorias_uso')
                    ->select('categorias_uso.*',)
                    ->get();

        return view('categorys/categorys')->with('categorias',$categorias)->with('categorias_padre',$categorias_padre)->with('categorias_uso',$categorias_uso) ;
    }

    public function get_ID(Request $request){
        $descr = $request['descripcion'];
        $user = Auth::user()->idempresa;
        $categorias =DB::table('Categoria')->where('descripcion','like','%'.$descr.'%')->where('idempresa',$user)->first();
        return response()->json($categorias);
    }

    public function store(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;
        $idcategoria = $request['idcategoria'];
        $descr = $request['descripcion'];
        $idpadre = $request['idpadre'];
        $editar = $request['edit'];
        $state_tienda = $request['state'];
        $idcategoriauso = $request['idcategoriauso'];

        if($editar){
            $category = Categoria::find($idcategoria);
            $category->descripcion = $descr;
            $category->idpadre = $idpadre;
            $category->idempresa = $user;
            $category->state_tienda = $state_tienda;
            $category->idcategoriauso = $idcategoriauso;

            $category->save();
        }else{
            $category = new Categoria;
            $category->descripcion = $descr;
            $category->idpadre = $idpadre;
            $category->idempresa = $user;
            $category->state_tienda = $state_tienda;
            $category->idcategoriauso = $idcategoriauso;

            $category->save();
        }



        return response()->json(['created'], 201);

    }
    public function show(Request $request){
    	$id = $request['id'];
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;
        //$idcategoriauso = $request['idcategoriauso'];

        $category = Categoria::where('idempresa',$user)->where('idcategoria',$id)->first();

        //$categoria_uso = CategoriaUso::where('id', $idcategoriauso)->first(); 

        return  response()->json($category);
    }

    public function edit($id){
    	
    }
    public function update(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;

        $id = $request['id'];
        $descr = $request['descripcion'];
        $idpadre = $request['parent'];
        $state_tienda = $request['state'];
        $idcategoriauso = $request['idcategoriauso'];

        $category = Categoria::find($id);
        $category->descripcion = $descr;
         if($idpadre != null){
                if(!(bool)$this->is_child($id,$idpadre)){
                     $category->idpadre = $idpadre;
                }else{
                   return response()->json(['conflict'], 409); 
               }              
        }
        $category->idempresa = $user;
        $category->state_tienda = $state_tienda;

        $category->idcategoriauso = $idcategoriauso;

        $category->save();

        return response()->json(['accepted'], 202);
    }
    private function is_child($idNodo, $idParent){
        if($idParent == null || $idParent == 0){
            return false;
        }
        $data = DB::table('Categoria')
                ->where('idcategoria',$idParent)
                ->where('idpadre',$idNodo)->first();

        return is_object($data);
    }

    public function destroy(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $id = $request['id'];
        $category = Categoria::find($id);
        
        try {
        	$category->delete();
        	return response()->json(['accepted'], 202);
        } catch (Exception $e) {
        	return response()->json(['conflict'], 409);
        }

        
    }

    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $categoria = Categoria::find($id);
        $categoria->state = $status;
        $categoria->save();
        return response()->json(['accepted'], 202);
    }


    public function family_duplicated(Request $request){

        $user = Auth::user()->idempresa;
        $idParent = $request['padre'];
        $descr = $request['descripcion'];
        $data = Categoria::where('descripcion',$descr)
                ->where('idempresa',$user)
                ->where('idpadre',$idParent)->count();

                //->select('descripcion')
                //->where('descripcion',$descr)
                //->where('idempresa',$user)
                //->where('idpadre',$idParent)
               
                
       return  response()->json($data);

             
    }

   
}