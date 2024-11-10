<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use App\Models\Cliente;
use App\PotencialCliente;
use App\Models\Visit;
use App\User;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SupervicionController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth',['except' => ['update_scheduling_visit']]);
    }

    public function visitas_llamadas()
    {
        $usuarios = DB::table('users')->get();

        return view('supervicion/visitas_llamadas')->with('usuarios',$usuarios);
    }

    public function get_visitas_llamadas(Request $request)
    {
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;
        $tipo = (int)$request->tipo;
        $idusuario = (int)$request->idusuario;

        $arrayTipo = ($tipo == 4 ? [0,1] : [$tipo]);

        $visitas = Visit::whereIn('visit.web_app', $arrayTipo)
                    ->select('visit.*', 'users.name', 'users.lastname', 'c.ruc_dni', 'c.razon_social', 'p.nombre_comercial')
                    ->leftjoin('users', 'users.id', '=', 'visit.idusuario')
                    ->leftjoin('clientes as c', 'c.idcliente', '=', 'visit.idcliente')
                    ->leftjoin('potencial_clientes as p', 'p.idpotencial', '=', 'visit.id_cliente_no_ruc')
                    ->whereDate('visit.created_at','>=', $fecha_ini)
                    ->whereDate('visit.created_at','<=', $fecha_fin)
                    ->orderBY('visit.created_at', 'desc');
        
        if ($idusuario != 0) {
            $visitas = $visitas->where('visit.idusuario',(int)$idusuario);        }

        $visitas = $visitas->get();

        return json_encode(['visitas' => $visitas, 'ini' => 0]);
    }

    public function update_scheduling_visit(Request $request) {

        if ($request->token == 'fed23n5ctyv75n99xe7mnc732mc3hr4n3x6d4exqdy3h3d32r4juytewew') {
            
            $clientes = Cliente::with('last_visit')->get();
            $potenciales = PotencialCliente::with('last_visit')->get();
            $now = Carbon::now();
            $daysFilter = 15;

            for($i = 0; $i < count($clientes); $i++){

                $clientes[$i]->dias_visita = -1;
                if ($clientes[$i]->last_visit) {
                    $created = new Carbon($clientes[$i]->last_visit->created_at);
                    $days = $created->diff($now)->days;
                    $clientes[$i]->dias_visita = ($daysFilter-$days);
                }
                $clientes[$i]->save();
            }
            for($i = 0; $i < count($potenciales); $i++){

                $potenciales[$i]->dias_visita = -1;
                if ($potenciales[$i]->last_visit) {
                    $created = new Carbon($potenciales[$i]->last_visit->created_at);
                    $days = $created->diff($now)->days;
                    $potenciales[$i]->dias_visita = ($daysFilter-$days);
                }
                $potenciales[$i]->save();
            }
            return;
        }
    }

}