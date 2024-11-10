<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\CategoriaUso;
use App\Models\MarcaCategoria;
use App\User;
use Auth;
use DB;
use Image;

class Categorias_usoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        $user = Auth::user()->idempresa;
        $categorias_uso = DB::table('categorias_uso')
                    ->select('categorias_uso.*')
                    ->where('categorias_uso.idempresa',$user)
                    ->get();
                    return view('categorys_uso/categorys_uso')->with('categorias_uso',$categorias_uso);
    }


    public function create(Request $request)
    {
        return view('categorys_uso/nuevo_categorys_uso');
    }


    public function get_ID(Request $request){
        $name = $request['name'];
        $user = Auth::user()->idempresa;
        $categorias_uso =DB::table('CategoriaUso')->where('name','like','%'.$name.'%')->where('idempresa',$user)->first();
        return response()->json($categorias_uso);
    }

    public function update(Request $request){
        $id = $request['id'];
        $categoria_uso = CategoriaUso::find($id);

        $categoria_uso = CategoriaUso::find($id);
        $selectedmarcas = MarcaCategoria::where('idcategoriauso',$id)->get();
        $marcas = DB::table('marcas')->get();

        return view('categorys_uso/editar_categorys_uso',['mensaje' => '200'])->with('categoria_uso', $categoria_uso)->with('marcas',$marcas)->with('selectedmarcas',$selectedmarcas);
    }

    
    public function get(Request $request){
        $id = $request['id'];
        $empresa = Auth::user()->idempresa;

        if(!empty($id)){
            $categorias_uso = DB::table('categorias_uso')
                ->where('idempresa',$empresa)
                ->where('id',$id)
                ->first();

                return response()->json($categorias_uso, 202);
        }
    }
   
    
    public function store(Request $request){
        $categoria_uso = new CategoriaUso;
        $categoria_uso->name = $request['name'];

        if($request->file('image')){
            $image=$request->file('image');
            if($image->isValid()){
                $fileName=time().'-'.str_slug($request['name'],"-").'.'.$image->getClientOriginalExtension();
                Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                $categoria_uso->image=$fileName;
            }
        }
        $categoria_uso->save();

        return redirect()->action('Categorias_usoController@index');
    }



    public function show(Request $request){
    	$id = $request['id'];
    	$user = Auth::user()->idempresa;
        $category_uso = CategoriaUso::where('idempresa',$user)->where('idcategoria',$id)->first();      
        return  response()->json($category_uso);
    }


    public function store_update(Request $request){
        $empresa = Auth::user()->idempresa;
        $id = $request['id'];
        $name = $request['name'];
        $categoria_uso = CategoriaUso::find($id);
        $categoria_uso->name = $request['name'];
        if($request->file('image')){
            $image=$request->file('image');
            if($image->isValid()){
                if ( !is_null($categoria_uso->image) )
                {
                    $imageactual='./images/large/'.$categoria_uso->image;
                    unlink($imageactual);
                }                
                $fileName=time().'-'.str_slug($request['name'],"-").'.'.$image->getClientOriginalExtension();
                Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                $categoria_uso->image=$fileName;
            }
        } 
        $categoria_uso->save();

        $selected_marcas = $request['marcas_select'];
        $marcas_actuales = MarcaCategoria::where('idcategoriauso',$id)->pluck('idmarca')->toarray();        
        $marcas_borrar = MarcaCategoria::whereNotIn('idmarca', $selected_marcas)->pluck('id')->toarray();
        $marcas_insertar = array_diff($selected_marcas, $marcas_actuales);
        
        foreach($marcas_borrar as $marca){
            $marcacategoria = MarcaCategoria::destroy($marca);
        }

        foreach($marcas_insertar as $marca){
            $marcacategoria = new MarcaCategoria();
            $marcacategoria->idmarca = $marca;
            $marcacategoria->idcategoriauso = $id;
            $marcacategoria->save();              
        }

        return redirect()->action('Categorias_usoController@index');
    }
    
    public function delete(Request $request){
        $id = $request['id'];
        $marcacategoria = MarcaCategoria::where('idcategoriauso',$id);
        $marcacategoria->delete();
        $categoria_uso = CategoriaUso::find($id);
        $image='./images/large/'.$categoria_uso->image;
        unlink($image);
        $categoria_uso->delete();
        return json_encode(['mensaje' => 200]);
    }   

}
