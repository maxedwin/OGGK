<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use JWTAuth;
use DB;
use Auth;


class DocumentosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showListado(){

        return view('documentos/documentos');
    }

    public function showCreateDocumento(){

        return view('documentos/create_documento');
    }


    public function getProductosServicios(Request $request){
        $query=$request['query'];
        $productos = DB::select("select p.nombre,p.precio from producto p where p.nombre like '%".$query."%' UNION  select s.nombre,s.precio from servicios s where s.nombre like '%".$query."%'");
        return (json_encode($productos));
    }

    // public function getClienteId(Request $request){
    //     $dni=$request['dni'];
    //     $cliente = DB::select("select * from clientes where dni='".$dni."'");
    //     return (json_encode($cliente));
    // }

    public function getClienteRuc(Request $request){
        $ruc=$request['ruc_dni'];
        $cliente = DB::select("select * from clientes where ruc='".$ruc_dni."'");
        return (json_encode($cliente));
    }

    public function printProforma(Request $request){
        return view('documentos/print_proforma',array('data'=>$_POST));
    }

}