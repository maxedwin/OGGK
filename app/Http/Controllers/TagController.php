<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Tag;
use App\ProductoTag;
use Exception;
use DB;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $tags = DB::table('tags')->get();
        return response()->json($tags);
    }

    public function create()
    {
        $tags = DB::table('tags')->get();
        return view('tag/tags')->with('tags',$tags);
    }

    public function store(Request $request)
    {
        $idtag = $request['idtag'];
        $nombre = $request['nombre'];
        $editar = $request['edit'];
        if($editar){
            $tag = Tag::find($idtag);
            $tag->nombre = $nombre;
            $tag->save();
        }else{
            $tag = new Tag;
            $tag->nombre = $nombre;
            $tag->save();
        }
        return response()->json(['created'], 201);
    }

    public function update(Request $request){
    	$idtag = $request['idtag'];
        $nombre = $request['nombre'];
        $tag = Tag::find($idtag);
        $tag->nombre = $nombre;
        $tag->save();
        return response()->json(['accepted'], 202);
    }

    public function destroy(Request $request){
    	$idtag = $request['idtag'];
        $tag = Tag::find($idtag);
        try {
            ProductoTag::where('idtag','=',$idtag)->delete(); 
        	$tag->delete();
        	return response()->json(['accepted'], 202);
        } catch (Exception $e) {
        	return response()->json(['conflict'], 409);
        }  
    }

    public function tag_duplicated(Request $request){
        $nombre = $request['nombre'];
        $data = Tag::where('nombre',$nombre)->count();       
        return  response()->json($data);             
    }
}
