<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Transporte;
use App\User;
use Auth;
use DB;

class TransporteController extends Controller
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
        $transportes = DB::table('transporte')
                    ->get();
        return response()->json($transportes);
    }

    public function create()
    {
        $user = Auth::user()->idempresa;
        $transportes = DB::table('transporte')->get();

        return view('transporte/transporte')->with('transportes',$transportes);
    }

    public function store(Request $request){
        /*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;
        $idtransporte = $request['idtransporte'];
        $nombre_trans = $request['nombre_trans'];
        $marca = $request['marca'];
        $tipo = $request['tipo'];
        $placa = $request['placa'];
        $editar = $request['edit'];

        if($editar){
            $transporte = Transporte::find($idtransporte);
            $transporte->nombre_trans = $nombre_trans;
            $transporte->marca = $marca;
            $transporte->tipo = $tipo;
            $transporte->placa = $placa;
            $transporte->idempresa = $user;
            $transporte->save();
        }else{
            $transporte = new Transporte;
            $transporte->nombre_trans = $nombre_trans;
            $transporte->marca = $marca;
            $transporte->tipo = $tipo;
            $transporte->placa = $placa;
            $transporte->idempresa = $user;
            $transporte->save();
        }

        return response()->json(['created'], 201);

    }
    
    public function show(Request $request){
        $idtransporte = $request['idtransporte'];
        /*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;

        $transporte = Transporte::where('idempresa',$user)->where('idtransporte',$idtransporte)->first();
      
        return  response()->json($transporte);
    }

    public function edit($id){
        
    }

    public function update(Request $request){
        /*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->idempresa;

        $idtransporte = $request['idtransporte'];
        $nombre_trans = $request['nombre_trans'];
        $marca = $request['marca'];
        $tipo = $request['tipo'];
        $placa = $request['placa'];

        $transporte = Transporte::find($idtransporte);
        $transporte->nombre_trans = $nombre_trans;
        $transporte->marca = $marca;
        $transporte->tipo = $tipo;
        $transporte->placa = $placa;
        $transporte->idempresa = $user;

        $transporte->save();

        return response()->json(['accepted'], 202);
    }

    public function destroy(Request $request){
        /*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $idtransporte = $request['idtransporte'];
        $transporte = Transporte::find($idtransporte);
        
        try {
            $transporte->delete();
            return response()->json(['accepted'], 202);
        } catch (Exception $e) {
            return response()->json(['conflict'], 409);
        }
    }

    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $transporte = Transporte::find($id);
        $transporte->state = $status;
        $transporte->save();
        return response()->json(['accepted'], 202);
    }
}