<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Evaluaciones;
use DB;
use App\User;
use Auth;

class EvaluacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $evaluaciones = DB::table('evaluaciones as eval')
                ->select('eval.*','p.razon_social as prs','c.razon_social as crs', 'users.name', 'users.lastname')
                ->join ('proveedores as p', 'p.idproveedor', '=', 'eval.idevaluado')
                ->join ('clientes as c', 'c.idcliente', '=', 'eval.idevaluado')
                ->join ('users', 'users.id', '=', 'eval.idusuario')
                ->get();

        return view('evaluaciones/eval_listado')->with('evaluaciones', $evaluaciones);
    }

    public function list_prov(Request $request)
    {
        $proveedores = DB::table('proveedores')->get();
        
        return view('evaluaciones/eval_proveedor')->with('proveedores', $proveedores);
    }

    public function store(Request $request){
        $idusuario = Auth::user()->id;
        $idevaluado = $request['idevaluado'];
        $tipo_evaluacion = $request['tipo_evaluacion'];
        $puntaje = $request['puntaje'];
        
        $evaluacion = new Evaluaciones;
        $evaluacion->idusuario = $idusuario;
        $evaluacion->idevaluado = $idevaluado;
        $evaluacion->tipo_evaluacion = $tipo_evaluacion;
        $evaluacion->puntaje = $puntaje;

        if ($puntaje < 20){
            return json_encode(['error' => 999]);
        }else{
            $evaluacion->save();
            return json_encode(['created' => 200]);
        }
    }
    
}