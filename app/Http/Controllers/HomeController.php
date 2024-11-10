<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CajaH;
use App\Notas;
use App\User;
use DB;
use JWTAuth;
use Auth;
use App\Quotation;
use App\Models\GuiaRemisionH;
use Gate;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $guiaModel = new GuiaRemisionH();
        $guias = [];
        if (Gate::allows('entregas_pendientes')) {
            $guias = $guiaModel->pending_guiaremisonh($limit=null);
        }

        $cajahModel = new CajaH();
        $facts = [];
        if (Gate::allows('facturas_pendientes')) {
            $facts = $cajahModel->pending_cajah($limit=null);
        }

    	$notas = Notas::where('user_id',Auth::user()->id)->get();
        return view('home', ['notas' => $notas, 'pending_guias' => $guias, 'pending_facts' => $facts]); 
    }

    public function store(Request $request)
    {
    	$nota = new Notas();
    	$nota->titulo = $request['titulo'];
    	$nota->texto = $request['texto'];
    	$nota->user_id = Auth::user()->id;
    	$nota->save();

        return redirect('home'); 
    }

    public function delete($id)
    {
    	$nota = Notas::findOrFail($id);
    	$nota->delete();

        return redirect('home'); 
    }



}
