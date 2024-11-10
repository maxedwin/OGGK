<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\CatalogoVirtual;
use App\User;
use Auth;
use DB;
use Image;

class Catalogos_virtualController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        $user = Auth::user()->idempresa;
        $catalogos_virtual = DB::table('catalogos')
                    ->select('catalogos.*')
                    ->get();
                    return view('catalogos_virtual/catalogos_virtual')->with('catalogos_virtual',$catalogos_virtual);
    }


    public function create(Request $request)
    {
        return view('catalogos_virtual/nuevo_catalogo_virtual');
    }


    public function get_ID(Request $request){
        $name = $request['name'];
        $user = Auth::user()->idempresa;
        $catalogos_virtual =DB::table('catalogos')->where('name','like','%'.$name.'%')->first();
        return response()->json($catalogos_virtual);
    }

    public function update(Request $request){
        $id = $request['id'];
        $catalogo_virtual = CatalogoVirtual::find($id);
        return view('catalogos_virtual/editar_catalogo_virtual',['mensaje' => '200'])->with('catalogo_virtual', $catalogo_virtual);
    }

    
    public function get(Request $request){
        $id = $request['id'];
        $empresa = Auth::user()->idempresa;

        if(!empty($id)){
            $catalogos_virtual = DB::table('catalogos')
                ->where('id',$id)
                ->first();

                return response()->json($catalogos_virtual, 202);
        }
    }
   
    
    public function store(Request $request){
        $catalogo_virtual = new CatalogoVirtual;
        $catalogo_virtual->name = $request['name'];

        if($request->file('image')){
            $image=$request->file('image');
            if($image->isValid()){
                $fileName=time().'-'.str_slug($request['name'],"-").'.'.$image->getClientOriginalExtension();
                Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                $catalogo_virtual->image=$fileName;
            }
        }
        if($request->file('ficha')){
            $ficha=$request->file('ficha');
            if($ficha->isValid()){
                $fileName=time().'-'.str_slug($request['name'],"-").'.'.$ficha->getClientOriginalExtension();
                $ficha->move('./files', $fileName);
                $catalogo_virtual->pdf=$fileName;
            }
        }
        $catalogo_virtual->save();

        return redirect()->action('Catalogos_virtualController@index');
    }



    public function show(Request $request){
    	$id = $request['id'];
    	$user = Auth::user()->idempresa;
        $category_uso = CatalogoVirtual::where('id',$id)->first();      
        return  response()->json($category_uso);
    }

    
    public function store_update(Request $request){
        $empresa = Auth::user()->idempresa;
        $id = $request['id'];
        $name = $request['name'];
        $catalogo_virtual = CatalogoVirtual::find($id);
        $catalogo_virtual->name = $request['name'];
        if($request->file('image')){
            $image=$request->file('image');            
            if($image->isValid()){
                $imageactual='./images/large/'.$catalogo_virtual->image;
                unlink($imageactual);
                $fileName=time().'-'.str_slug($request['name'],"-").'.'.$image->getClientOriginalExtension();
                Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                $catalogo_virtual->image=$fileName;
            }
        }
        if($request->file('ficha')){
            $ficha=$request->file('ficha');
            if($ficha->isValid()){
                $pdfactual='./files/'.$catalogo_virtual->pdf;
                unlink($pdfactual);
                $fileName=time().'-'.str_slug($request['name'],"-").'.'.$ficha->getClientOriginalExtension();
                $ficha->move('./files', $fileName);
                $catalogo_virtual->pdf=$fileName;
            }
        }


        $catalogo_virtual->save();
        return redirect()->action('Catalogos_virtualController@index');
    }

    
    public function delete(Request $request){
        $id = $request['id'];
        $catalogo_virtual = CatalogoVirtual::find($id);
        $image='./images/large/'.$catalogo_virtual->image;
        $pdfactual='./files/'.$catalogo_virtual->pdf;
        unlink($image);
        unlink($pdfactual);
        $catalogo_virtual->delete();
        return json_encode(['mensaje' => 200]);
    }

   

}
