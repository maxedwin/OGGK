<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\SendMethod;
use Exception;
use DB;

class SendMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $tags = DB::table('send_methods')->get();
        return response()->json($tags);
    }

    public function create()
    {
        $send_methods = DB::table('send_methods')->get();
        return view('send_method/send_methods')->with('send_methods',$send_methods);
    }

    public function store(Request $request)
    {
        $this->validate($request, [        
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
        ]);
        $id_sendmethod = $request['id_sendmethod'];
        $nombre = $request['nombre'];
        $descripcion = $request['descripcion'];
        $precio = $request['precio'];
        $editar = $request['edit'];
        if($editar){
            $send_method = SendMethod::find($id_sendmethod);
            $send_method->nombre = $nombre;
            $send_method->descripcion = $descripcion;
            $send_method->precio = $precio;
            $send_method->save();
        }else{
            $send_method = new SendMethod();
            $send_method->nombre = $nombre;
            $send_method->descripcion = $descripcion;
            $send_method->precio = $precio;
            $send_method->save();
        }
        return response()->json(['created'], 201);
    }

    public function update(Request $request){
    	$id_sendmethod = $request['id_sendmethod'];
        $nombre = $request['nombre'];
        $descripcion = $request['descripcion'];
        $precio = $request['precio'];
        $send_method = SendMethod::find($id_sendmethod);
        $send_method->nombre = $nombre;
        $send_method->descripcion = $descripcion;
        $send_method->precio = $precio;
        $send_method->save();
        return response()->json(['accepted'], 202);
    }

    public function destroy(Request $request){    	
        $num_pay_methods = SendMethod::all()->count();
        if ( $num_pay_methods <= 1 )
        {
            return response()->json(['message'=>'exception'], 202);
        }
        $id_sendmethod = $request['id_sendmethod'];
        $send_method = SendMethod::find($id_sendmethod);       
        try {            
        	$send_method->delete();
        	return response()->json(['message'=>'accepted'], 202);
        } catch (Exception $e) {
        	return response()->json(['message'=>'conflict'], 409);
        }        
    }

    public function sendmethod_duplicated(Request $request){
        $nombre = $request['nombre'];
        $data = SendMethod::where('nombre',$nombre)->count();       
        return  response()->json($data);             
    }
}
