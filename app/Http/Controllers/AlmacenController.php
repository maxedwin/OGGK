<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Almacen;
use App\User;
use Auth;
use DB;

class AlmacenController extends Controller
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
        $almacenes = DB::table('almacen')
                    ->get();
        return response()->json($almacenes);
    }

    public function create()
    {
        $user = Auth::user()->idempresa;
        $almacenes = DB::table('almacen')->get();

        $departamentos = DB::table('departamentos')->get();
        $provincias = DB::table('provincias')->get();
        $distritos = DB::table('distritos')->get();                     

        return view('almacen/list_almacenes')->with('almacenes',$almacenes)->with('departamentos', $departamentos)->with('provincias', $provincias)->with('distritos', $distritos) ;
    }

    public function get_ID(Request $request){
        $descr = $request['nombre'];
        $user = Auth::user()->idempresa;
        $almacenes =DB::table('almacen')->where('nombre','like','%'.$descr.'%')->where('idempresa',$user)->first();
        return response()->json($almacenes);
    }

    public function store(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;
        $idalmacen = $request['idalmacen'];
        $nombre = $request['nombre'];
        $direccion = $request['direccion'];
        $distrito = $request['distrito'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];
        $editar = $request['edit'];

        if($editar){
            $almacen = Almacen::find($idalmacen);
            $almacen->nombre = $nombre;
            $almacen->direccion = $direccion;
            $almacen->distrito = $distrito;
            $almacen->provincia = $provincia;
            $almacen->departamento = $departamento;
            $almacen->idempresa = $user;

            $almacen->save();
        }else{
            $almacen = new Almacen;
            $almacen->nombre = $nombre;
            $almacen->direccion = $direccion;
            $almacen->distrito = $distrito;
            $almacen->provincia = $provincia;
            $almacen->departamento = $departamento;
            $almacen->idempresa = $user;

            $almacen->save();
        }

        return response()->json(['created'], 201);

    }
    
    public function show(Request $request){
    	$idalmacen = $request['idalmacen'];
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;

        $almacen = Almacen::where('idempresa',$user)->where('idalmacen',$idalmacen)->first();
      
        return  response()->json($almacen);
    }

    public function edit($id){
    	
    }

    public function update(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;

        $idalmacen = $request['idalmacen'];
        $nombre = $request['nombre'];
        $direccion = $request['direccion'];
        $distrito = $request['distrito'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];

        $almacen = Almacen::find($idalmacen);
        $almacen->nombre = $nombre;
        $almacen->direccion = $direccion;
        $almacen->distrito = $distrito;
        $almacen->provincia = $provincia;
        $almacen->departamento = $departamento;
        $almacen->idempresa = $user;

        $almacen->save();

        return response()->json(['accepted'], 202);
    }

    public function destroy(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $idalmacen = $request['idalmacen'];
        $almacen = Almacen::find($idalmacen);
        
        try {
        	$almacen->delete();
        	return response()->json(['accepted'], 202);
        } catch (Exception $e) {
        	return response()->json(['conflict'], 409);
        }
    }

    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $almacen = Almacen::find($id);
        $almacen->state = $status;
        $almacen->save();
        return response()->json(['accepted'], 202);
    }
}