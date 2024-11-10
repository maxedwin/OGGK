<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use JWTAuth;

use Dingo\Api\Routing\Helpers;

use App\Models\Cliente;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\Visit;
use App\Models\Reclamo;
use App\Models\Sunat;
use App\Models\padron;
use App\Models\GuiaRemisionD;
use App\Models\GuiaRemisionH;
use App\Models\CajaH;


use DB;
use App\User;
use Auth;

class FrontReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        
        $guiaModel = new GuiaRemisionH();
        $guias = $guiaModel->pending_guiaremisonh(50);
        return response()->json($guias);
    }

    public function factbol(Request $request){
        
        $cajahModel = new CajaH();
        $facts = $cajahModel->historial_deudores();
        return response()->json($facts);
    }

}