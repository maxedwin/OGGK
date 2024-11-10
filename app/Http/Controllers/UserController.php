<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Quotation;


use Illuminate\Http\Response;
use JWTAuth;

use App\User;
use Auth;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $currentUser = JWTAuth::parseToken()->authenticate();
        
        $empresa = Auth::user()->idempresa;

    

        $usuarios = DB::table('users')
                ->where('idempresa',$empresa)
                ->join('puestos', 'users.puesto','=', 'puestos.idpuesto')
                ->orderBy('activated', 'desc')
                ->get();

        return view('usuarios/listado_usuarios', ['usuarios' => $usuarios]);
    }

    public function vendedores()
    {
        // $currentUser = JWTAuth::parseToken()->authenticate();
        $empresa = Auth::user()->idempresa;
        $usuarios = DB::table('users')
                ->select('id', 'name', 'lastname')
                ->where('idempresa',$empresa)
                ->whereIn('puesto',['1','4','6'])
                ->where('activated', 1)
                ->get();
        return json_encode(['vendedores' => $usuarios,'mensaje' => 200]);
    }

    public function disable_user(Request $request){

        $idusuario = $request['idusuario'];
        $user = User::find($idusuario);
        $user->activated = 0;
        $user->save();

        return json_encode(['mensaje' => 200]);
    }

    public function enable_user(Request $request){

        $idusuario = $request['idusuario'];
        $user = User::find($idusuario);
        $user->activated = 1;
        $user->save();
        
        return json_encode(['mensaje' => 200]);
    }

    public function change_password(Request $request) {

        $idusuario = $request['idusuario'];
        $user = User::find($idusuario);
        $user->password = bcrypt($request['new_pass']);
        $user->save();
        
        return json_encode(['mensaje' => 200]);
    }
}