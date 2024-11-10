<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use JWTAuth;

use Dingo\Api\Routing\Helpers;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\ClienteUbicacion;
use App\Models\CajaH;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\Visit;
use App\MarcaCliente;
use App\Models\Reclamo;
use App\Models\Sunat;
use App\Models\padron;
use App\PotencialCliente;
use DB;
use App\User;
use Auth;
use Helper;
use DateTime;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['switch_tipo_clientes']]);
    }

    public function index(Request $request)
    {
        $empresa = Auth::user()->idempresa;

        $cant = $request['cant'];
        $query = $request['query'];
        $i= $request['i'];

        //if(!isset($cant))$cant = 100;
       
    

        return view('cliente/clientes')->with('in', $i);
    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    public function allCliente(Request $request)
    {
        $columns = array( 
            0 =>'ruc_dni', 
            1 =>'codigo',
            2=> 'razon_social',
            3=> 'nombre_comercial',
            4=> 'contacto_telefono',
            5=> 'direccion',
            6=> 'distrito',
            7=> 'provincia',
            8=> 'vendedor',
            9=> 'name',
            10=> 'created_at',
            11=> 'porcentaje',
            12=> 'dias_visita',
            13=> 'ruc_dni',


                        );
  
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $f_inicio ='1000-01-01';
        $f_fin ='5000-12-12';
        $i=$request['i'];
        if($request['f_inicio']){        
        $f_inicio = $request['f_inicio'];
        }
        if($request['f_fin']){
        $date_fin = new DateTime($request['f_fin']);
        $date_fin->modify('+1 day');       
        $f_fin = $date_fin->format('Y-m-d');
        }
        $cant = 15;
        if(!$i){
            $totalData =DB::table('clientes')->where('clientes.idempresa',$empresa)->count();

        }
        else{
            $totalData =DB::table('clientes')->where('clientes.idempresa',$empresa)->where('clientes.estado_cliente',$i)->count();

        }
        if(empty($request->input('search.value'))){
            if(!$i){
                $i=0;
            $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*',
                             DB::raw("
                                    (
                                        (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                                    )
                                as porcentaje")
                        )
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')
                //->paginate($cant);
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
                $totalFiltered = DB::table('clientes')
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')->count();

            }
            else{
                $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*',
                             DB::raw("
                                    (
                                        (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                                    )
                                as porcentaje")
                        )
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.estado_cliente',$i)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')
                //->paginate($cant);
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalFiltered = DB::table('clientes')                
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.estado_cliente',$i)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')->count();

            }
        }else{
            $query = $request->input('search.value'); 

            if(!$i){

            $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*', DB::raw("
                (
                    (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                )
            as porcentaje"))
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)

                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })
                               
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')

                //->paginate(500);
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalFiltered =DB::table('clientes')
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->where('clientes.estado_cliente',$i)

                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })                               
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                //->paginate(500);
                ->count();

            }
            else{
                $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*', DB::raw("
                (
                    (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                )
            as porcentaje"))
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.estado_cliente',$i)

                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)

                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })
                               
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')

                //->paginate(500);
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalFiltered =DB::table('clientes')                
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.estado_cliente',$i)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })
                               
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                //->paginate(500);
                ->count();

            }

        }
            

        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($clientes))
        {
            //$this->debug_to_console($clientes);
            if (is_array($clientes) || is_object($clientes))
            foreach ($clientes as $cliente)
            {
                
                 
                $dias = $cliente->dias_visita;
                $color = '#aa9d87';
                if ($cliente->dias_visita == 0) {
                    $color = '#87ceff';
                } else if ($cliente->dias_visita < 0) {
                    $color = '#fd7940';
                    $dias = 0;
                }
                

                $nestedData['ruc_dni']=" <td style='background-color:{$color}'>{$cliente->ruc_dni }";
                $nestedData['codigo']="{$cliente->codigo }";
                $nestedData['razon_social']="{$cliente->razon_social }";
                $nestedData['nombre_comercial']="{$cliente->nombre_comercial }";
                $nestedData['contacto_telefono']="{$cliente->contacto_telefono}";
                $nestedData['direccion']="{$cliente->direccion }";
                $nestedData['distrito']="{$cliente->distrito}";
                $nestedData['provincia']="{$cliente->provincia}";
                /*{{ $cliente->contacto_nombre }}
                    {{ dump($cliente) }} 
                {{ $cliente->contacto_telefono }}
                {{ $cliente->tipoemp_nombre }}*/
                $nestedData['vendedor']="{$cliente->vendedor}";
                $nestedData['name']="{$cliente->name}";
                $nestedData['created_at']=date('d/m/Y', strtotime($cliente->created_at));

                $nestedData['porcentaje']= intval(((12-$cliente->porcentaje)*100)/12). "%";
                $nestedData['dias_visita']=$cliente->dias_visita;
                
                $nestedData['acciones']="<button type='button' class='btn btn-success btn-xs'
                            data-idcliente     = '{$cliente->idcliente}'
                            data-ruc_dni       = '{$cliente->ruc_dni}'
                            data-razon_social  = '{$cliente->razon_social}'     
                            data-toggle       = 'modal'
                            id='llamar'> 
                        <i class='glyphicon glyphicon-earphone position-center'></i>
                    </button>
                    <button type='button' class='btn btn-info btn-xs'
                            data-idcliente    = '{$cliente->idcliente}'         
                            data-toggle       = 'modal'
                            id='agregar'> 
                        <i class='glyphicon glyphicon-plus position-center'></i>
                    </button>
                    <button type='button' class='btn btn-light btn-xs'
                            data-idcliente    ='{$cliente->idcliente}'
                            id='editar' >
                        <i class='glyphicon glyphicon-eye-open position-center'></i>
                    </button>                            
                    <button type='button' class='btn btn-warning btn-xs' 
                            data-idcliente     = '{$cliente->idcliente}'
                            data-ruc_dni       = '{$cliente->ruc_dni}'
                            data-razon_social  = '{$cliente->razon_social}'     
                            data-toggle       = 'modal'
                            id='reclamar'> 
                        <i class='glyphicon glyphicon-book position-center'></i>
                    </button>
                    <button type='button' class='btn btn-danger btn-xs' id='eliminar'
                            data-idcliente='{$cliente->idcliente}'>
                        <i class='icon-cancel-square2 position-center'></i>
                    </button>"   ;
            
    


                $data[] = $nestedData;

            }
        }
        


          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data); 
        
    }

    public function ExportExcel($customer_data,$cols){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $errased=array();
        for($i=0; $i<13; $i++){
            if(!in_array($i,$cols)){
                array_push($errased,$i+1);
            }
        }

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($customer_data);
            for($j=0; $j<count($errased); $j++){
                    $spreadSheet->getActiveSheet()->removeColumnByIndex($errased[$j]);
                    for($k=$j+1; $k<count($errased);$k++ ){
                        $errased[$k]-=1;
                    }
            }
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Clientes-SolucionesOGGK.xls"');
            header('Cache-Control: max-age=0');
            //ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }


    function exportData(Request $request){    

        $cols = json_decode($request['cols']);
        $columns = array( 
            0 =>'ruc_dni', 
            1 =>'codigo',
            2=> 'razon_social',
            3=> 'nombre_comercial',
            4=> 'contacto_telefono',
            5=> 'direccion',
            6=> 'distrito',
            7=> 'provincia',
            8=> 'vendedor',
            9=> 'name',
            10=> 'created_at',
            11=> 'porcentaje',
            12=> 'dias_visita',
            13=> 'ruc_dni',


                        );

            $data_array[] = array( 
                "RUC/DNI", 
                "Codigo",
                "Razon Social/Nombre",
                "Nombre Comercial",
                "Contacto Telefono",
                "Direccion",
                "Distrito",
                "Provincia",
                "Vendedor",
                "Creador",
                "Creado",
                "% Datos",
                "Dias para nueva visita"
            );         
  
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];
        $order =$columns[$request['order']];
        $dir = $request['dir'];
        $i=$request['i'];
        $f_inicio ='1000-01-01';
        $f_fin ='5000-12-12';
        if($request['f_inicio']){        
        $f_inicio = $request['f_inicio'];
        }
        if($request['f_fin']){
            $date_fin = new DateTime($request['f_fin']);
            $date_fin->modify('+1 day');       
            $f_fin = $date_fin->format('Y-m-d');
        }


        if(empty($request['search'])){
            if(!$i){
                $i=0;
            $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*',
                             DB::raw("
                                    (
                                        (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                                    )
                                as porcentaje")
                        )
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')
                //->paginate($cant);            
                ->orderBy($order,$dir)
                ->get();               

            }
            else{
                $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*',
                             DB::raw("
                                    (
                                        (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                                    )
                                as porcentaje")
                        )
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.estado_cliente',$i)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')
                //->paginate($cant);             
                ->orderBy($order,$dir)
                ->get();

            

            }
        }else{
            $query = $request['search']; 

            if(!$i){

            $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*', DB::raw("
                (
                    (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                )
            as porcentaje"))
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)

                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })
                               
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')

                //->paginate(500);              
                ->orderBy($order,$dir)
                ->get();

            

            }
            else{
                $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*',DB::raw("
                (
                    (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                  + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                )
            as porcentaje"))
                ->where('clientes.idempresa',$empresa)
                ->where('clientes.estado_cliente',$i)
                ->where('clientes.created_at', '>=', $f_inicio)
                ->where('clientes.created_at', '<=', $f_fin)
                ->Where(function ($query2) use ($query) {
                $query2->where('razon_social','like', '%'.$query.'%')
                    ->orWhere('ruc_dni','like', '%'.$query.'%')
                    ->orWhere('clientes.distrito','like', '%'.$query.'%')
                    ->orWhere('users.name','like', '%'.$query.'%')
                    ->orWhere('tipo_empresa.tipoemp_nombre','like', '%'.$query.'%');
                })
                               
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')

                //->paginate(500);
             
                ->orderBy($order,$dir)
                ->get();               

            }

        }
       
        $formato = 'Y-m-d H:i:s';
        $status_ent_gr =[
            0 => 'ANULADO',
            1 => 'PENDIENTE ENTREGA',
            2 => 'ENTREGA PARCIAL',
            3 => 'ENTREGADO',
            4 => 'NC'
            ];

        foreach($clientes as $cliente)
        {
            $fecha = DateTime::createFromFormat($formato, $cliente->created_at);
            $est;           
            $data_array[] = array(
                'RUC/DNI' =>  $cliente->ruc_dni ,
                'Codigo' => !$cliente->codigo? '--':$cliente->codigo,
                'Razon Social/Nombre' => $cliente->razon_social,
                'Nombre Comercial' => $cliente->nombre_comercial,
                'Contacto Telefono' =>!$cliente->contacto_telefono? '--':$cliente->contacto_telefono,
                'Direccion' => $cliente->direccion,
                'Distrito' => $cliente->distrito,
                'Provincia' => $cliente->provincia,
                'Vendedor' => !$cliente->vendedor? '--':$cliente->vendedor,
                'Creador' => !$cliente->name? '--':$cliente->name,
                'Creado' => date('d/m/Y', strtotime($cliente->created_at)),
                '% Datos' => intval(((12-$cliente->porcentaje)*100)/12). "%",
                'Dias para nueva visita' =>! $cliente->dias_visita?'0':$cliente->dias_visita,
                
               
            );
        }
      

        $this->ExportExcel($data_array,$cols);
    }


    public function index_tienda()
    {
        $empresa = Auth::user()->idempresa;
        $users_dni= User::select('dni')->where('tienda_user',1)->get();
           
            $clientes = DB::table('clientes')
                ->select('clientes.*', 'users.name', 'users2.name as vendedor', 'tipo_empresa.*',
                             DB::raw("
                                    (
                                        (CASE WHEN clientes.nombre_comercial IS NULL OR clientes.nombre_comercial='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_nombre IS NULL OR clientes.contacto_nombre='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_telefono IS NULL OR clientes.contacto_telefono='' OR contacto_telefono=0 OR contacto_telefono=1 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.contacto_email IS NULL OR clientes.contacto_email='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_cliente IS NULL OR clientes.tipo_cliente='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_pago IS NULL or tipo_pago=99 THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.tipo_emp IS NULL OR clientes.tipo_emp='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.dias_credito IS NULL THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.direccion IS NULL OR clientes.direccion='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.distrito IS NULL OR clientes.distrito='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.provincia IS NULL OR clientes.provincia='' THEN 1 ELSE 0 END)
                                      + (CASE WHEN clientes.departamento IS NULL OR clientes.departamento='' THEN 1 ELSE 0 END) 
                                    )
                                as porcentaje")
                        )
                ->where('clientes.idempresa',$empresa)
                ->whereIn('clientes.ruc_dni',$users_dni)
                ->leftJoin('tipo_empresa','clientes.tipo_emp','=','tipo_empresa.id_tipoemp')
                ->leftJoin('users','users.id','=','clientes.idusuario')
                ->leftJoin('users as users2','users2.id','=','clientes.idvendedor')
                //->paginate($cant);
                ->get();
    

        return view('cliente/clientes_tienda', ['clientes' => $clientes]);
    }


    public function switch_tipo_clientes(Request $request){
        $i= $request['i'];
        if($i=="hIdm3oZayT19lDqObufyI4MpUDtGhu"){

            Cliente::chunk(100, function($clientes)
            {
                foreach ($clientes as $cliente)
                {
                    $fecha_pedido = OrdenVentaH::where('idcliente', $cliente->idcliente)
                    ->orderBy('id_orden_ventah', 'desc')
                    ->first();
                    if($fecha_pedido){
                        if($fecha_pedido->created_at > Carbon::now()->subDays(60)){
                            $cliente->estado_cliente=2; //frecuente
                            
                        }
                        else{
                            $cliente->estado_cliente=3; //no frecuente
                        }
                        
                    }
                    else{
                        $cliente->estado_cliente=1; //cliente nuevo
                    }
                    $cliente->save();
                }
            });
        }
    }


    public function index_reclamos()
    {
        $reclamos = DB::table('reclamos')->select('reclamos.*','reclamos.created_at as fecha', 'clientes.razon_social as razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.*')
            ->join ('clientes', 'clientes.idcliente', '=', 'reclamos.idcliente')
            ->join ('users', 'users.id', '=', 'reclamos.idusuario')
            ->get();
        return view('cliente/listado_reclamos', ['reclamos' => $reclamos]);
    }

    public function index_deudores($pending = null)
    {
        $cajahModel = new CajaH();
        $list =  $cajahModel->historial_deudores($pending);
        return view('cliente/listado_deudores', ['clientes' => $list, 'count' => count($list)]);
    }

    public function create(Request $request)
    {
        $id = Auth::user()->id;

        $usuario = DB::table('users')
                    ->where('id',$id)
                    ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
                    ->join('empresas','users.idempresa','=','empresas.idempresa')
                    ->first();

        $departamentos = DB::table('departamentos')->get();
        $provincias = DB::table('provincias')->get();
        $distritos = DB::table('distritos')->get(); 

        $marcas = DB::table('marcas')->get();  
            
        $tipo_emp = DB::table('tipo_empresa')->get();   
            
        return view('cliente/nuevoCliente')->with('departamentos', $departamentos)->with('provincias', $provincias)->with('distritos', $distritos)->with('tipos_emp', $tipo_emp)->with('usuario',$usuario)->with('marcas',$marcas);
    }

    /*
    select p.nombre from 
        (select id_orden_ventah FROM orden_ventah where idcliente=13 order by id_orden_ventah desc limit 2) ovh
        join orden_ventad ovd on ovd.id_orden_ventah=ovh.id_orden_ventah
        join producto p on p.idproducto=ovd.idproducto
    */


    public function update(Request $request){
        $idcliente = $request['idcliente'];
        $cliente = Cliente::find($idcliente);
        
        $selectedmarcas=MarcaCliente::where('idcliente',$idcliente)->where('isPotencial',0)->get();
        $clienteubicacion =DB::table('clienteubicacion')->where('idcliente',$idcliente)->whereNotNull('ruc_dni')->first();
        if(empty($clienteubicacion)){
            $clienteubicacion= new ClienteUbicacion;
            $clienteubicacion->idcliente= $cliente->idcliente;
            $clienteubicacion->ruc_dni = $cliente->ruc_dni;                     
            $clienteubicacion->direccion =$cliente->direccion;
            $clienteubicacion->distrito = $cliente->distrito;
            $clienteubicacion->save();
        }
        

        $visit = Visit::select(DB::raw("date(visit.created_at) as fecha"), DB::raw("IFNULL(c.razon_social,cli.razon_social) as rs"))
            ->leftJoin('clienteubicacion as cu', 'cu.idcliubic', '=', 'visit.idcliubic')
            ->leftJoin('clientes as cli', 'cli.idcliente','=', 'visit.idcliente')
            ->leftJoin('clientes as c', 'c.ruc_dni','=', 'cu.ruc_dni')                      
            ->where('cli.idcliente', $idcliente)
            ->orWhere('c.idcliente', $idcliente)
            ->orderBy('visit.id', 'desc')
            ->first();

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

    public function store(Request $request){
        $empresa = Auth::user()->idempresa;
        $idusuario = Auth::user()->id;
        $idcliente = $request['idcliente'];
        $ruc_dni = $request['ruc_dni'];
        $razon_social = $request['razon_social'];
        $nombre_comercial = $request['nombre_comercial'];
        
        $direccion = $request['direccion'];
        $direccion_ent = $request['direccionent'];
        $distrito = $request['distrito'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];
        $contacto_ruta = $request['contacto_ruta'];
        $sec = $request['sec'];
        
        $contacto_nombre = $request['contacto_nombre'];
        $contacto_telefono = $request['contacto_telefono'];
        $contacto_telefono2 = $request['contacto_telefono1'];
        $contacto_telefono3 = $request['contacto_telefono2'];
        $contacto_telefono4 = $request['contacto_telefono3'];
        $contacto_telefono5 = $request['contacto_telefono4'];
        $contacto_email = $request['contacto_email'];
        $dias_credito = $request['dias_credito'];
        $tipo_emp = $request['tipo_emp'];
        $tipo_pago = $request['tipo_pago'];
        $tipo_cliente = $request['tipo_cliente'];

        $codigo=$this->get_code($distrito,$contacto_ruta,$sec);

        $marcas = $request['marcas'];
        
        $lat = $request['lat'];
        $lng = $request['lng'];
        $location_type = $request['location_type'];
        $cliente_estado = $request['cliente_estado'];




        if($cliente_estado =="POTENCIAL"){
            $cliente = new PotencialCliente;
            $cliente->idempresa = $empresa;
            $cliente->idusuario = $idusuario;
            $cliente->nombre_comercial = $nombre_comercial;

            $cliente->direccion = $direccion;
            $cliente->distrito = $distrito;
            $cliente->provincia = $provincia;
            $cliente->departamento = $departamento;
            
            $cliente->contacto_nombre = $contacto_nombre;
            $cliente->contacto_telefono = $contacto_telefono;
            $cliente->contacto_telefono2 = $contacto_telefono2;
            $cliente->contacto_telefono3 = $contacto_telefono3;
            $cliente->contacto_telefono4 = $contacto_telefono4;
            $cliente->contacto_telefono5 = $contacto_telefono5;
            $cliente->contacto_email = $contacto_email;
            
            $cliente->tipo_emp = $tipo_emp;
            

            $cliente->save();
            
            $clienteubicacion = ClienteUbicacion::where('idcliente',$cliente->idpotencial)->whereNull('ruc_dni')->first();
            if(!$clienteubicacion){
                $clienteubicacion = new ClienteUbicacion;
            }      
                    $clienteubicacion->idcliente = $cliente->idpotencial;               
  
                    $clienteubicacion->direccion = $direccion;
                    $clienteubicacion->distrito = $distrito;
                    if( $lat != 0 && $lng != 0 ){
                        $clienteubicacion->latitud = $lat;
                        $clienteubicacion->longitud = $lng;
        
                    }

            $clienteubicacion->save();
            
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
            return json_encode(['mensaje' => 201]);

        }

        else{
            
              
            

            if      ( (substr($ruc_dni, 0, 2) == 20 || substr($ruc_dni, 0, 2) == '20' ) && strlen((string)$ruc_dni)==11 ) {
                        $tipo_documento = 6;
            }elseif ( (substr($ruc_dni, 0, 2) == 10 || substr($ruc_dni, 0, 2) == '10' ) && strlen((string)$ruc_dni)==11 ) {
                        $tipo_documento = 6;
            }else {
                        $tipo_documento = 1;
            }

            
            $bool = false;
            $bool = DB::table('clientes')->where('ruc_dni', '=', $ruc_dni)->first();

            if( !$bool ){
                if(!empty($idcliente)){
                    $cliente = Cliente::find($idcliente);
                    $cliente->idempresa = $empresa;
                    $cliente->idusuario = $idusuario;
                    $cliente->ruc_dni = $ruc_dni;
                    $cliente->razon_social = $razon_social;
                    $cliente->nombre_comercial = $nombre_comercial;
                    $cliente->ruta = $contacto_ruta;
                    $cliente->secuencia = $sec;

                    $cliente->codigo = $codigo;

                    $cliente->direccion = $direccion;
                    $cliente->distrito = $distrito;
                    $cliente->provincia = $provincia;
                    $cliente->departamento = $departamento;
                    
                    $cliente->contacto_nombre = $contacto_nombre;
                    $cliente->contacto_telefono = $contacto_telefono;
                    $cliente->contacto_email = $contacto_email;
                    $cliente->dias_credito = $dias_credito;
                    $cliente->tipo_emp = $tipo_emp;
                    $cliente->tipo_pago = $tipo_pago;
                    $cliente->tipo_cliente = $tipo_cliente;
                    $cliente->tipo_documento = $tipo_documento;

                    $cliente->save();

                    $clienteubicacion = ClienteUbicacion::where('idcliente',$idcliente)->whereNotNull('ruc_dni')->first();
                    if(!$clienteubicacion){
                        $clienteubicacion = new ClienteUbicacion;
                    }
                    $clienteubicacion->ruc_dni = $ruc_dni;
                    $clienteubicacion->idcliente = $cliente->idcliente;               
                    $clienteubicacion->location_type = $location_type;
                    $clienteubicacion->direccion = $direccion;
                    $clienteubicacion->distrito = $distrito;
                    if( $lat != 0 && $lng != 0 ){
                        $clienteubicacion->latitud = $lat;
                        $clienteubicacion->longitud = $lng;
        
                    }
                    $clienteubicacion->save();

                }else{
                    $cliente = new Cliente;
                    $cliente->idempresa = $empresa;
                    $cliente->idusuario = $idusuario;
                    $cliente->ruc_dni = $ruc_dni;
                    $cliente->razon_social = $razon_social;
                    $cliente->nombre_comercial = $nombre_comercial;                    
                    $cliente->estado_cliente = 1; //1 nuevo, 2 frecuente, 3 no frecuente

                    if($sec){
                        $cliente->secuencia = $sec;
                        
                    }
                    if($contacto_ruta){
                        $cliente->ruta = $contacto_ruta;
                    }
                    if($sec && $contacto_ruta ){
                        $cliente->codigo = $codigo;
                    }

                    $cliente->direccion = $direccion;
                    $cliente->direccion_ent = $direccion_ent;
                    $cliente->distrito = $distrito;
                    $cliente->provincia = $provincia;
                    $cliente->departamento = $departamento;
                    
                    $cliente->contacto_nombre = $contacto_nombre;
                    $cliente->contacto_telefono = $contacto_telefono;
                    $cliente->contacto_telefono2 = $contacto_telefono2;
                    $cliente->contacto_telefono3 = $contacto_telefono3;
                    $cliente->contacto_telefono4 = $contacto_telefono4;
                    $cliente->contacto_telefono5 = $contacto_telefono5;
                    $cliente->contacto_email = $contacto_email;
                    $cliente->dias_credito = $dias_credito;
                    $cliente->tipo_emp = $tipo_emp;
                    $cliente->tipo_pago = $tipo_pago;
                    $cliente->tipo_cliente = $tipo_cliente;
                    $cliente->tipo_documento = $tipo_documento;

                

                    $cliente->save();

                    $clienteubicacion = new ClienteUbicacion;
                    $clienteubicacion->ruc_dni = $ruc_dni;
                    $clienteubicacion->idcliente = $cliente->idcliente;               
                    $clienteubicacion->location_type = $location_type;
                    $clienteubicacion->direccion = $direccion;
                    $clienteubicacion->distrito = $distrito;
                    if( $lat != 0 && $lng != 0 ){
                        $clienteubicacion->latitud = $lat;
                        $clienteubicacion->longitud = $lng;
    
                    }
                    $clienteubicacion->save();

                    if($marcas != "vacio"){
                        $marcas1 = explode(",", $marcas);
                        foreach($marcas1 as $marca){
                            $marcacliente= new MarcaCliente;
                            $marcacliente->idmarca= $marca;
                            $marcacliente->idcliente=$cliente->idcliente;
                            $marcacliente->isPotencial= 0;
                            $marcacliente->save();
                        }               
                    }

                }
                return json_encode(['mensaje' => 200]);
            }                            
            else
                return json_encode(['mensaje' => 999]);    
        }
    }

    private function get_code($distrito,$contacto_ruta,$sec){
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
        if(strlen($contacto_ruta)==1){
            $code.="0". $contacto_ruta;
        }
        else{
            $code.= $contacto_ruta;
        }

        $code.= $sec;
        


        return $code;

    }

    public function store_update_cli(Request $request){
        $empresa = Auth::user()->idempresa;
        $idusuario = Auth::user()->id;
        $idcliente = $request['idcliente'];
        $idvendedor = $request['idvendedor'];
        $ruc_dni = $request['ruc_dni'];
        $razon_social = $request['razon_social'];
        $nombre_comercial = $request['nombre_comercial'];
        $direccion_ent= $request['direccion_ent'];
        $direccion = $request['direccion'];
        $distrito = $request['distrito'];
        $contacto_ruta = $request['contacto_ruta'];
        $sec = $request['sec'];
        $provincia = $request['provincia'];
        $departamento = $request['departamento'];
        $marcas = $request['marcas'];
        $borrarmarcas = $request['borrarmarcas'];

        $lat = $request['lat'];
        $lng = $request['lng'];
        $location_type = $request['location_type'];

        $codigo=$this->get_code($distrito,$contacto_ruta,$sec);
        
        
        $contacto_nombre = $request['contacto_nombre'];
        $contacto_telefono = $request['contacto_telefono'];
        $contacto_telefono2 = $request['contacto_telefono1'];
        $contacto_telefono3 = $request['contacto_telefono2'];
        $contacto_telefono4 = $request['contacto_telefono3'];
        $contacto_telefono5 = $request['contacto_telefono4'];
        $contacto_email = $request['contacto_email'];
        $dias_credito = $request['dias_credito'];
        $tipo_emp = $request['tipo_emp'];
        $tipo_pago = $request['tipo_pago'];
        $tipo_cliente = $request['tipo_cliente'];

        $cliente = Cliente::where('idcliente', $idcliente)->where('ruc_dni', '=', $ruc_dni)->first(); //donde el ruc y el idcliente sean iguales

        if($cliente){
            
            $cliente->idempresa = $empresa;
            $cliente->idusuario = $idusuario;
            $cliente->idvendedor = $idvendedor;
            $cliente->ruc_dni = $ruc_dni;
            $cliente->razon_social = $razon_social;
            $cliente->nombre_comercial = $nombre_comercial;

            $cliente->direccion = $direccion;
            $cliente->direccion_ent = $direccion_ent;
            $cliente->distrito = $distrito;
            $cliente->provincia = $provincia;
            $cliente->departamento = $departamento;

            if($sec){
                $cliente->secuencia = $sec;
                
            }
            else{
                $cliente->secuencia = null;
            }

            if($contacto_ruta){
                $cliente->ruta = $contacto_ruta;
            }
            else{
                $cliente->ruta = null;
            }

            if($sec && $contacto_ruta ){
                $cliente->codigo = $codigo;
            }
            else{
                $cliente->codigo = null;
            }
                    
            $cliente->contacto_nombre = $contacto_nombre;
            $cliente->contacto_telefono = $contacto_telefono;
            $cliente->contacto_telefono2 = $contacto_telefono2;
            $cliente->contacto_telefono3 = $contacto_telefono3;
            $cliente->contacto_telefono4 = $contacto_telefono4;
            $cliente->contacto_telefono5 = $contacto_telefono5;
            $cliente->contacto_email = $contacto_email;
            $cliente->dias_credito = $dias_credito;
            $cliente->tipo_emp = $tipo_emp;
            $cliente->tipo_pago = $tipo_pago;
            $cliente->tipo_cliente = $tipo_cliente;

            $cliente->save();

            $clienteubicacion = ClienteUbicacion::where('idcliente',$idcliente)->whereNotNull('ruc_dni')->first();

            if($clienteubicacion){
                $clienteubicacion->ruc_dni = $ruc_dni;
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
                    $marcacliente->idcliente=$cliente->idcliente;
                    $marcacliente->isPotencial= 0;
                    $marcacliente->save();
                    
                }               
            }

            if($borrarmarcas != "vacio"){
                $borrarmarcas1 = explode(",", $borrarmarcas);
                foreach($borrarmarcas1 as $marca){                   
                    $marcacliente= MarcaCliente::where('idmarca',$marca)->where('idcliente',$cliente->idcliente)->where('isPotencial',0)->first();
                    $marcacliente->delete();
                    
                }               
            }
 
            return json_encode(['mensaje' => 200]);
        
       
        }
        else{ //crear el cliente nuevo y borrar el potencial
            $potencial = PotencialCliente::find($idcliente);
            
            if(!$potencial){
                return json_encode(['mensaje' => 999]);
            }
            else{

                if      ( (substr($ruc_dni, 0, 2) == 20 || substr($ruc_dni, 0, 2) == '20' ) && strlen((string)$ruc_dni)==11 ) {
                    $tipo_documento = 6;
                }elseif ( (substr($ruc_dni, 0, 2) == 10 || substr($ruc_dni, 0, 2) == '10' ) && strlen((string)$ruc_dni)==11 ) {
                            $tipo_documento = 6;
                }else {
                            $tipo_documento = 1;
                }

                $cliente = new Cliente;
                $cliente->idempresa = $empresa;
                $cliente->idusuario = $idusuario;
                $cliente->idvendedor = $idvendedor;
                $cliente->ruc_dni = $ruc_dni;
                $cliente->razon_social = $razon_social;
                $cliente->nombre_comercial = $nombre_comercial;
                $cliente->estado_cliente = 1;
                 if($sec){
                $cliente->secuencia = $sec;
                
                }
                else{
                    $cliente->secuencia = null;
                }

                if($contacto_ruta){
                    $cliente->ruta = $contacto_ruta;
                }
                else{
                    $cliente->ruta = null;
                }

                if($sec && $contacto_ruta ){
                    $cliente->codigo = $codigo;
                }
                else{
                    $cliente->codigo = null;
                }
                $cliente->direccion = $direccion;
                $cliente->direccion_ent = $direccion_ent;
                $cliente->distrito = $distrito;
                $cliente->provincia = $provincia;
                $cliente->departamento = $departamento;
                        
                $cliente->contacto_nombre = $contacto_nombre;
                $cliente->contacto_telefono = $contacto_telefono;
                $cliente->contacto_telefono2 = $contacto_telefono2;
                $cliente->contacto_telefono3 = $contacto_telefono3;
                $cliente->contacto_telefono4 = $contacto_telefono4;
                $cliente->contacto_telefono5 = $contacto_telefono5;
                $cliente->contacto_email = $contacto_email;
                $cliente->dias_credito = $dias_credito;
                $cliente->tipo_emp = $tipo_emp;
                $cliente->tipo_pago = $tipo_pago;
                $cliente->tipo_cliente = $tipo_cliente;
                $cliente->tipo_documento = $tipo_documento;
                $cliente->save();

               
                $clienteubicacion = new ClienteUbicacion;
                $clienteubicacion->ruc_dni = $ruc_dni;
                $clienteubicacion->idcliente =  $cliente->idcliente;

                if( $lat != 0 && $lng != 0 ){
                $clienteubicacion->latitud = $lat;
                $clienteubicacion->longitud = $lng;
                }
                $clienteubicacion->location_type = $location_type;
                $clienteubicacion->direccion = $direccion;
                $clienteubicacion->distrito = $distrito;
                $clienteubicacion->save();
                

                $antiguoclienteubicacion = ClienteUbicacion::where('idcliente',$potencial->idpotencial)->whereNull('ruc_dni')->first();
                $marcaclientes= MarcaCliente::where('idcliente',$potencial->idpotencial)->where('isPotencial',1)->update(['idcliente'=>$cliente->idcliente ,'isPotencial' =>0]);
                $antiguoclienteubicacion->delete();
                $potencial->delete();
                ////////////////////////////VISITAS///////////
                $visit = Visit::where('id_cliente_no_ruc', $idcliente)
                        ->update([
                            'idcliente' =>$cliente->idcliente,
                            'id_cliente_no_ruc' => null                            
                        ]);
////////////////////////////////////////////////////////////
                   

                if($marcas != "vacio"){
                    $marcas1 = explode(",", $marcas);
                    foreach($marcas1 as $marca){                   
                        
                        $marcacliente= new MarcaCliente;
                        $marcacliente->idmarca= $marca;
                        $marcacliente->idcliente=$cliente->idcliente;
                        $marcacliente->isPotencial= 0;
                        $marcacliente->save();
                        
                    }               
                }  
                      

                return json_encode(['mensaje' => 201]);
            }

        }
    }

    public function delete(Request $request){
        $idcliente = $request['idcliente'];
        $cliente = Cliente::find($idcliente);
        $clienteubicacion= ClienteUbicacion::where('idcliente',$idcliente)->whereNotNull('ruc_dni')->first();
        $marcaclientes= MarcaCliente::where('idcliente',$cliente->idcliente)->where('isPotencial',0)->delete();
        $cliente->delete();
        $clienteubicacion->delete();  
        return json_encode(['mensaje' => 200]);
    }

    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $cliente = Cliente::find($id);
        $cliente->estado_entidad = $status;
        $cliente->save();
        return response()->json(['accepted'], 202);
    }

    public function cliente_sinruc(){
        $a = 111111;
        $maximo_num = DB::table('clientes')
                ->where(DB::raw("LEFT(ruc_dni, 6)"),'like','%'.$a.'%')
                ->max('ruc_dni');

        $cliente_sinruc = intval($maximo_num) + 1 ;
        return $cliente_sinruc;
    }

    public function buscar_ruc(Request $request)
    {
        $cookie = array(
            'cookie'    => array(
            'use'       => true,
            'file'      => __DIR__ . "/src/cookie.txt"
            )
        );
        $config = array(
            'representantes_legales'    => true,
            'cookie'                    => $cookie
        );

        $ruc =  $request['nruc'];
        $company = new Sunat( $config );      
        $search1 = $company->consulta( $ruc );    
        return $search1->json( NULL, true );
    }

    public function buscar_reniec(Request $request)
    {
        $dni      = $request['nruc'];
        $person   = new padron();
        $response = $person->consulta($dni);
        return $response->json();
        /*$ruta = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFsZWphbmRyby5kZWxhZ2FsYUB1Y3NwLmVkdS5wZSJ9.SOPat5vsHUGG2UaIcwFQT6tS2GzUwsx3AO3Bc_QVOo8";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //$respuesta  = curl_exec($ch);
        //curl_close($ch);

        $return = new obj(array(
                        'success' => true,
                        'result'  => array(
                            "dni"               => (string) $result->dni,
                            "nombres"           => (string) $result->nombres,
                            "apellidoPaterno"   => (string) $result->apellidoPaterno,
                            "apellidoMaterno"   => (string) $result->apellidoMaterno,
                        ),
                    ));


        return $leer_respuesta->json();*/

    }

    public function llamada(Request $request){
        $idcliente = $request['idcliente'];
        $tipo_llamada = $request['tipo_llamada'];
        $respuesta = $request['respuesta'];
        $potencial = $request['potencial'];
        $latitud = $request['latitud'];
        $longitud = $request['longitud'];
        $tipo = $request['tipo'];

        $idusuario = Auth::user()->id;
        
        $visit = new Visit;
        if($potencial){
            $visit->id_cliente_no_ruc = $idcliente;
        }
        else{
            $visit->idcliente = $idcliente;
        }

        $visit->motivo = $tipo_llamada;
        $visit->respuesta = $respuesta;
        $visit->idusuario = $idusuario;
        $visit->latitud = $latitud;
        $visit->longitud = $longitud;

        #tomamos la hora correcta
        $visit->created_at = Carbon::now();

        if($tipo==1){ //llamada         
            $visit->web_app = 1;
        }
        else{ //visita
            $visit->web_app = 0;
        }
        $visit->save();

        return json_encode(['mensaje' => 200]);
    }

    public function reclamo(Request $request){
        $idcliente = $request['idcliente_reclamo'];
        $reclamo_tmp = $request['reclamo'];
        $idusuario = Auth::user()->id;
        
        $reclamo_new = new Reclamo;
        $reclamo_new->idcliente = $idcliente;
        $reclamo_new->reclamo = $reclamo_tmp;
        $reclamo_new->idusuario = $idusuario;
        $reclamo_new->save();

        return json_encode(['mensaje' => 200]);
    }

    public function cambiar_enproceso(Request $request){
        $idreclamo = $request['idreclamo'];
        $reclamo_tmp = Reclamo::find($idreclamo);
        $reclamo_tmp->estado = 1;
        $reclamo_tmp->save();
        return json_encode(['mensaje' => 200]);
    }

    public function cambiar_solucionado(Request $request){
        $idreclamo = $request['idreclamo'];
        $reclamo_tmp = Reclamo::find($idreclamo);
        $reclamo_tmp->estado = 2;
        $reclamo_tmp->save();
        return json_encode(['mensaje' => 200]);
    }

    public function clientMap(Request $request){
        return view('cliente/clientes_mapa');
    } 

    public function clienteUbicaciones(Request $request){
        $ubicaciones = DB::table('clienteubicacion')
                    ->select('clienteubicacion.idcliubic','clienteubicacion.direccion as sucursal_direccion', 'clientes.direccion as direccion','clienteubicacion.ruc_dni','razon_social','latitud','longitud', 'tipo_empresa.tipoemp_nombre')
                    ->join('clientes','clientes.ruc_dni','=','clienteubicacion.ruc_dni')
                    ->join('tipo_empresa', 'clientes.tipo_emp','=', 'tipo_empresa.id_tipoemp')
                    ->get();
        return response()->json($ubicaciones);
    }
}