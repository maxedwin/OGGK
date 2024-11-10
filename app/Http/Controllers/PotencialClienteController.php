<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\PotencialCliente;
use DB;
use App\Models\Cliente;
use App\Models\ClienteUbicacion;
use App\Models\CajaH;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\Visit;
use App\Models\Reclamo;
use App\Models\Sunat;
use App\Models\padron;
use App\User;
use Auth;
use App\MarcaCliente;

class PotencialClienteController extends Controller
{
    public function index(Request $request)
    {
        $empresa = Auth::user()->idempresa;

        $cant = $request['cant'];
        $query = $request['query'];

        //if(!isset($cant))$cant = 100;
        if(!isset($query)){
            $potencial_clientes = PotencialCliente::
                select('potencial_clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*')
                ->where('potencial_clientes.idempresa',$empresa)
                ->leftJoin('tipo_empresa','potencial_clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','potencial_clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','potencial_clientes.idvendedor')
                //->paginate($cant);
                ->get();
        }else{
            $potencial_clientes = DB::table('potencial_clientes')
                ->select('potencial_clientes.*', 'users.name', 'tipo_empresa.*')
                ->where('potencial_clientes.idempresa',$empresa)

                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('potencial_clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })
                               
                ->leftJoin('tipo_empresa','potencial_clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','potencial_clientes.idusuario')
                //->paginate(500);
                ->get();
        }

        return view('cliente/potenciales', ['clientes' => $potencial_clientes]);
    }

    
    public function promote(Request $request){
        $idcliente = $request['idcliente'];
        $cliente = PotencialCliente::find($idcliente);
        $selectedmarcas=MarcaCliente::where('idcliente',$idcliente)->where('isPotencial',1)->get();
        $visit = Visit::select(DB::raw("date(visit.created_at) as fecha"), DB::raw("IFNULL(c.razon_social,cli.razon_social) as rs"))
            ->leftJoin('clienteubicacion as cu', 'cu.idcliubic', '=', 'visit.idcliubic')
            ->leftJoin('clientes as cli', 'cli.idcliente','=', 'visit.idcliente')
            ->leftJoin('clientes as c', 'c.ruc_dni','=', 'cu.ruc_dni')                      
            ->where('cli.idcliente', $idcliente)
            ->orWhere('c.idcliente', $idcliente)
            ->orderBy('visit.id', 'desc')
            ->first();
        //$clienteubicacion= array('latitud' => null,'longitud' => null);

        $clienteubicacion =DB::table('clienteubicacion')->where('idcliente',$idcliente)->whereNull('ruc_dni')->first();
        if(empty($clienteubicacion)){
            $clienteubicacion= new ClienteUbicacion;
            $clienteubicacion->idcliente= $cliente->idpotencial;                     
            $clienteubicacion->direccion =$cliente->direccion;
            $clienteubicacion->distrito = $cliente->distrito;
            $clienteubicacion->save();
        }

        $fecha_pedido = OrdenVentaH::select(DB::raw("date(created_at) as fecha"))
                        ->where('idcliente', $idcliente)
                        ->orderBy('id_orden_ventah', 'desc')
                        ->first();

        $sql = OrdenVentaH::select('id_orden_ventah')
                        ->where('idcliente', $idcliente)
                        ->orderBy('id_orden_ventah', 'desc')
                        ->limit(2);

        $productos = DB::table( DB::raw("({$sql->toSql()}) as ovh") )
                        ->select('p.barcode','p.nombre','ovd.cantidad','ovd.precio_unit as precio')
                        ->mergeBindings($sql->getQuery())
                        ->join('orden_ventad as ovd', 'ovd.id_orden_ventah','=','ovh.id_orden_ventah')
                        ->join('producto as p', 'p.idproducto','=','ovd.idproducto')
                        ->get();   

        $departamentos = DB::table('departamentos')->get();
        $provincias = DB::table('provincias')->get();
        $distritos = DB::table('distritos')->get(); 

        $tipo_emp = DB::table('tipo_empresa')->where('id_tipoemp',$cliente->tipo_emp)->get();   
        $tipo_emp2 = DB::table('tipo_empresa')->get();   

        $usuarios = DB::table('users')->where('tienda_user',0)->get();
        $marcas = DB::table('marcas')->get();  

        return view('cliente/editarCliente')->with('cliente',$cliente)->with('clienteubicacion',$clienteubicacion)->with('visit',$visit)->with('fecha_pedido',$fecha_pedido)
                                            ->with('departamentos', $departamentos)->with('provincias', $provincias)->with('distritos', $distritos)
                                            ->with('tipos_emp', $tipo_emp)->with('tipos_emp2', $tipo_emp2)->with('productos', $productos)
                                            ->with('usuarios',$usuarios)->with('marcas',$marcas)->with('selectedmarcas',$selectedmarcas);                                                                       
    }


    private function get_code($distrito){
        $disArray = explode(" ", $distrito);
        if(count($disArray)==1){
            $code=substr($disArray[0], 0, 3);
        }
        elseif(count($disArray)==2){
            $code=substr($disArray[0], 0, 1) . substr($disArray[1], 0, 2);
        }
        else{
            $code=substr($disArray[0], 0, 1);
            $code.=substr($disArray[1], 0, 1);
            $code.=substr($disArray[2], 0, 1);           
        }
        return $code;

    }



    public function update(Request $request){
        $idcliente = $request['idcliente'];
        $cliente = PotencialCliente::find($idcliente);
        $selectedmarcas=MarcaCliente::where('idcliente',$idcliente)->where('isPotencial',1)->get();
       
        //$clienteubicacion= array('latitud' => null,'longitud' => null);

        $clienteubicacion =DB::table('clienteubicacion')->where('idcliente',$idcliente)->whereNull('ruc_dni')->first();
        if(empty($clienteubicacion)){
            $clienteubicacion= new ClienteUbicacion;
            $clienteubicacion->idcliente= $cliente->idpotencial;                     
            $clienteubicacion->direccion =$cliente->direccion;
            $clienteubicacion->distrito = $cliente->distrito;
            $clienteubicacion->save();
        }
           

        $departamentos = DB::table('departamentos')->get();
        $provincias = DB::table('provincias')->get();
        $distritos = DB::table('distritos')->get(); 

        $tipo_emp = DB::table('tipo_empresa')->where('id_tipoemp',$cliente->tipo_emp)->get();   
        $tipo_emp2 = DB::table('tipo_empresa')->get();   

        $usuarios = DB::table('users')->where('tienda_user',0)->get();
        $marcas = DB::table('marcas')->get();  

        return view('cliente/editarPotencial')->with('cliente',$cliente)->with('clienteubicacion',$clienteubicacion)
                                            ->with('departamentos', $departamentos)->with('provincias', $provincias)->with('distritos', $distritos)
                                            ->with('tipos_emp', $tipo_emp)->with('tipos_emp2', $tipo_emp2)
                                            ->with('usuarios',$usuarios)->with('marcas',$marcas)->with('selectedmarcas',$selectedmarcas);                                                                       
    }


    public function store_update_pot(Request $request){
        $empresa = Auth::user()->idempresa;
        $idusuario = Auth::user()->id;
        $idcliente = $request['idcliente'];
        $idvendedor = $request['idvendedor'];
       
        $nombre_comercial = $request['nombre_comercial'];
        
        $direccion = $request['direccion'];
        $distrito = $request['distrito'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];
        $marcas = $request['marcas'];
        $borrarmarcas = $request['borrarmarcas'];

        $lat = $request['lat'];
        $lng = $request['lng'];
        $location_type = $request['location_type'];

        $codigo=$this->get_code($distrito);
        
        
        $contacto_nombre = $request['contacto_nombre'];
        $contacto_telefono = $request['contacto_telefono'];
        $contacto_telefono2 = $request['contacto_telefono1'];
        $contacto_telefono3 = $request['contacto_telefono2'];
        $contacto_telefono4 = $request['contacto_telefono3'];
        $contacto_telefono5 = $request['contacto_telefono4'];
        $contacto_email = $request['contacto_email'];
        $tipo_emp = $request['tipo_emp'];

        $cliente = PotencialCliente::where('idpotencial', $idcliente)->first(); //donde el ruc y el idcliente sean iguales

        if($cliente){
                        
            $cliente->idempresa = $empresa;
            $cliente->idusuario = $idusuario;
            $cliente->idvendedor = $idvendedor;
            
            $cliente->nombre_comercial = $nombre_comercial;

            $cliente->direccion = $direccion;
            
            $cliente->distrito = $distrito;
            $cliente->provincia = $provincia;
            $cliente->departamento = $departamento;
            $cliente->codigo = $codigo;
                    
            $cliente->contacto_nombre = $contacto_nombre;
            $cliente->contacto_telefono = $contacto_telefono;
            $cliente->contacto_telefono2 = $contacto_telefono2;
            $cliente->contacto_telefono3 = $contacto_telefono3;
            $cliente->contacto_telefono4 = $contacto_telefono4;
            $cliente->contacto_telefono5 = $contacto_telefono5;
            $cliente->contacto_email = $contacto_email;
            
            $cliente->tipo_emp = $tipo_emp;

            $cliente->save();

            $clienteubicacion = ClienteUbicacion::where('idcliente',$idcliente)->whereNull('ruc_dni')->first();

            if($clienteubicacion){
                if( $lat != 0 && $lng != 0 ){
                $clienteubicacion->latitud = $lat;
                $clienteubicacion->longitud = $lng;
                }
                $clienteubicacion->location_type = $location_type;
                $clienteubicacion->direccion = $direccion;
                $clienteubicacion->distrito = $distrito;
                $clienteubicacion->save();
            }


            if($marcas != "vacio"){
                $marcas1 = explode(",", $marcas);
                foreach($marcas1 as $marca){                   
                    
                    $marcacliente= new MarcaCliente;
                    $marcacliente->idmarca= $marca;
                    $marcacliente->idcliente=$cliente->idpotencial;
                    $marcacliente->isPotencial= 1;
                    $marcacliente->save();
                    
                }               
            }

            if($borrarmarcas != "vacio"){
                $borrarmarcas1 = explode(",", $borrarmarcas);
                foreach($borrarmarcas1 as $marca){                   
                    $marcacliente= MarcaCliente::where('idmarca',$marca)->where('idcliente',$idcliente)->where('isPotencial',1)->first();
                    $marcacliente->delete();
                    
                }               
            }
 
            return json_encode(['mensaje' => 200]);
        
       
        }
    }




    public function delete(Request $request){
        $idcliente = $request['idcliente'];
        $cliente = PotencialCLiente::find($idcliente);
        $clienteubicacion= ClienteUbicacion::where('idcliente',$idcliente)->whereNull('ruc_dni')->first();
        $marcaclientes= MarcaCliente::where('idcliente',$cliente->idpotencial)->where('isPotencial',1)->delete();
        $cliente->delete();
        $clienteubicacion->delete();
       



        return json_encode(['mensaje' => 200]);
    }

}
