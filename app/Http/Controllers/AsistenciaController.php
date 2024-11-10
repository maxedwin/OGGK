<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon;
use App\Http\Requests;

use App\Asistencia;

class AsistenciaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

  public function index()
    {
        $asistencias = DB::table('asistencias')
        ->select('asistencias.*', 'users.name', 'users.lastname')
        ->join('users', 'asistencias.idusuario', '=', 'users.id', 'left')
        ->orderBy('asistencias.id', 'desc')
        ->get();

        //$asistencias = Asistencia::orderBy('id', 'DESC')->get();                

        return view('asistencia/attendance_list', ['asistencias' => $asistencias]);
    }



 public function check(Request $request)
    {


    	

        $asistencia = Asistencia::where('idusuario', Auth::id())
                ->where('register_date', Carbon::now()->format('Y-m-d'))
                //->where('check_out', '0')
                ->first();


        return view('asistencia.attendance')->with('asistencia',$asistencia);

  
    }



    public function save(Request $request)
    {


        $id=$request['input_id'];
        $asistencia = new Asistencia;

        if($id>0){
        	$asistencia = Asistencia::find($id);
            $asistencia->check_out = Carbon::now()->format('H:i');
            $asistencia->hours = $this->diffHours($asistencia->check_in, $asistencia->check_out);

        }
        else{     	 				
			$asistencia->check_in = Carbon::now()->format('H:i');
            $asistencia->check_out = "0";
            $asistencia->register_date = Carbon::now()->format('Y-m-d');	
  
        }
        $asistencia->idusuario = Auth::id();
        $asistencia->save();
        

        return redirect()->route('asistencia-check')->with('asistencia',$asistencia);


     

    }

      public function edit_attendance(Request $request) {

        $id = $request['id'];
        $checkin=  $request['checkin'];
        $checkout=  $request['checkout'];
        $descripcion=  $request['descripcion'];

        $asistencia = Asistencia::find($id);

        $asistencia->check_in =  $checkin;
        $asistencia->check_out = $checkout;
        $asistencia->hours = $this->diffHours($asistencia->check_in, $asistencia->check_out);
        $asistencia->descripcion= $descripcion; 
        $asistencia->save();
        
        return json_encode(['mensaje' => 200]);
    }


        private function diffHours($ini, $end) {
        $minEnd = ((int) substr($end, 0, 2)) * 60 + ((int) substr($end, 3, 2));
        $minIni = ((int) substr($ini, 0, 2)) * 60 + ((int) substr($ini, 3, 2));
        return ($minEnd - $minIni) / 60;
    }



}
