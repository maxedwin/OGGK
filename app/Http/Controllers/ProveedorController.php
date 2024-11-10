<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use JWTAuth;
use Dingo\Api\Routing\Helpers;
use App\Models\Proveedor;
use DB;
use App\User;
use Auth;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $empresa = Auth::user()->idempresa;

        $cant = $request['cant'];
        $query = $request['query'];

        if(!isset($cant))$cant = 100;
        if(!isset($query)){
            $proveedores = DB::table('proveedores')
                ->where('idempresa',$empresa)
                ->join('tipo_empresa','proveedores.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->get();
        }else{
            $proveedores = DB::table('proveedores')
                ->where('idempresa',$empresa)
                ->where('razon_social','like', '%'.$query.'%')
                ->orWhere('ruc_dni','like', '%'.$query.'%')
                ->orWhere('distrito','like', '%'.$query.'%')
                ->join('tipo_empresa','proveedores.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->get();

        }

        return view('proveedor/proveedores', ['proveedores' => $proveedores]);
    }
    public function create(Request $request)
    {
        $departamentos = DB::table('departamentos')->get();
        $provincias = DB::table('provincias')->get();
        $distritos = DB::table('distritos')->get();   
            
        $tipo_emp = DB::table('tipo_empresa')
            ->whereNotNull('id_tipoemp')
            ->get();   
            
        return view('proveedor/nuevoProveedor')->with('departamentos', $departamentos)->with('provincias', $provincias)->with('distritos', $distritos)->with('tipos_emp', $tipo_emp);
    }

    public function update(Request $request){
        $idproveedor = $request['idproveedor'];
        $proveedor = Proveedor::find($idproveedor);

        $departamentos = DB::table('departamentos')->get();
        $provincias = DB::table('provincias')->get();
        $distritos = DB::table('distritos')->get(); 

        $tipo_emp = DB::table('tipo_empresa')->where('id_tipoemp',$proveedor->tipo_emp)->get();   
        $tipo_emp2 = DB::table('tipo_empresa')->get();   

        return view('proveedor/editarProveedor')->with('proveedor',$proveedor)->with('departamentos', $departamentos)->with('provincias', $provincias)->with('distritos', $distritos)
                                                                              ->with('tipos_emp', $tipo_emp)->with('tipos_emp2', $tipo_emp2);    
    }

    public function store(Request $request){
        $empresa = Auth::user()->idempresa;
        $idproveedor = $request['idproveedor'];
        $ruc_dni = $request['ruc_dni'];
        $razon_social = $request['razon_social'];
        $direccion = $request['direccion'];
        $distrito = $request['distrito'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];
        $contacto_nombre = $request['contacto_nombre'];
        $contacto_telefono = $request['contacto_telefono'];
        $contacto_email = $request['contacto_email'];
        $tipo_pago = $request['tipo_pago'];
        $dias_credito = $request['dias_credito'];
        $moneda = $request['moneda'];
        $tipo_emp = $request['tipo_emp'];

        $bool = false;

        $bool = DB::table('proveedores')->where('ruc_dni', '=', $ruc_dni)->first();

        if( !$bool ){
            if(!empty($idproveedor)){
                $proveedor = Proveedor::find($idproveedor);
                $proveedor->idempresa = $empresa;
                $proveedor->ruc_dni = $ruc_dni;
                $proveedor->razon_social = $razon_social;
                $proveedor->direccion = $direccion;
                $proveedor->distrito = $distrito;
                $proveedor->provincia = $provincia;
                $proveedor->departamento = $departamento;
                $proveedor->contacto_nombre = $contacto_nombre;
                $proveedor->contacto_telefono = $contacto_telefono;
                $proveedor->contacto_email = $contacto_email;
                $proveedor->tipo_pago = $tipo_pago;
                $proveedor->dias_credito = $dias_credito;
                $proveedor->moneda = $moneda;
                $proveedor->tipo_emp = $tipo_emp;

                $proveedor->save();
            }else{
                $proveedor = new Proveedor;
                $proveedor->idempresa = $empresa;
                $proveedor->ruc_dni = $ruc_dni;
                $proveedor->razon_social = $razon_social;
                $proveedor->direccion = $direccion;
                $proveedor->distrito = $distrito;
                $proveedor->provincia = $provincia;
                $proveedor->departamento = $departamento;
                $proveedor->contacto_nombre = $contacto_nombre;
                $proveedor->contacto_telefono = $contacto_telefono;
                $proveedor->contacto_email = $contacto_email;
                $proveedor->tipo_pago = $tipo_pago;
                $proveedor->dias_credito = $dias_credito;
                $proveedor->moneda = $moneda;
                $proveedor->tipo_emp = $tipo_emp;

                $proveedor->save();
            }
            return json_encode(['mensaje' => 200]);
        }
        else
            return json_encode(['mensaje' => 999]);                
    }

    public function store_update_prov(Request $request){
        $empresa = Auth::user()->idempresa;
        $idproveedor = $request['idproveedor'];
        $ruc_dni = $request['ruc_dni'];
        $razon_social = $request['razon_social'];
        $direccion = $request['direccion'];
        $distrito = $request['distrito'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];
        $contacto_nombre = $request['contacto_nombre'];
        $contacto_telefono = $request['contacto_telefono'];
        $contacto_email = $request['contacto_email'];
        $tipo_pago = $request['tipo_pago'];
        $dias_credito = $request['dias_credito'];
        $moneda = $request['moneda'];
        $tipo_emp = $request['tipo_emp'];

        $proveedor = Proveedor::find($idproveedor);
        $proveedor->idempresa = $empresa;
        $proveedor->ruc_dni = $ruc_dni;
        $proveedor->razon_social = $razon_social;
        $proveedor->direccion = $direccion;
        $proveedor->distrito = $distrito;
        $proveedor->provincia = $provincia;
        $proveedor->departamento = $departamento;
        $proveedor->contacto_nombre = $contacto_nombre;
        $proveedor->contacto_telefono = $contacto_telefono;
        $proveedor->contacto_email = $contacto_email;
        $proveedor->tipo_pago = $tipo_pago;
        $proveedor->dias_credito = $dias_credito;
        $proveedor->moneda = $moneda;
        $proveedor->tipo_emp = $tipo_emp;

        $proveedor->save();

        return json_encode(['mensaje' => 200]);
    }
    
    public function delete(Request $request){
        $idproveedor = $request['idproveedor'];
        $proveedor = Proveedor::find($idproveedor);
        $proveedor->delete();
        return json_encode(['mensaje' => 200]);
    }

    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $proveedor = Proveedor::find($id);
        $proveedor->est_ent = $status;
        $proveedor->save();
        return response()->json(['accepted'], 202);
    }
}
