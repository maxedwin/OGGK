<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Requests;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Response;
use JWTAuth;

use App\Models\Transacciones;
use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Lote;
use App\User;
use App\Models\GuiaTrasladoH;
use App\Models\Fe;
use App\Models\GuiaTrasladoD;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\CajaH;
use Auth;
use DB;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Document;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;
use Greenter\See;
use Helper;
use DateTime;

class GuiaTrasladoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('guia_traslado/listado_guia_traslado', );
    }
    public function allGR(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1=> 'codigoNB',
            2=> 'created_at',
            3=> 'razon_social',
            4=> 'f_entrega',
            //5=> 'f_entregado',
            5=> 'name',
           // 6=> 'status_ent',
            6=> 'cliente_extra',
            7=> 'numeracion',
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
        if($request['f_inicio']){        
        $f_inicio = $request['f_inicio'];
        }
        if($request['f_fin']){
        $date_fin = new DateTime($request['f_fin']);
        $date_fin->modify('+1 day');       
        $f_fin = $date_fin->format('Y-m-d');
        }
        $cant = 15;

        $totalData =DB::table('guia_trasladoh')->where('guia_trasladoh.idempresa',$empresa)->count();
            
        if(!isset($cant))$cant = 1000;
        if(empty($request->input('search.value'))){
            $guia_traslados = DB::table('guia_trasladoh')->select('guia_trasladoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('guia_trasladoh.idempresa',$empresa)
            ->where('guia_trasladoh.created_at', '>=', $f_inicio)
            ->where('guia_trasladoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_trasladoh.iddespachador')
            
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();                                 
            $totalFiltered = DB::table('guia_trasladoh')->where('guia_trasladoh.idempresa',$empresa) ->where('guia_trasladoh.created_at', '>=', $f_inicio)
            ->where('guia_trasladoh.created_at', '<=', $f_fin)->count();

        }else{
            $search = $request->input('search.value'); 
            $guia_traslados = DB::table('guia_trasladoh')->select('guia_trasladoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
                ->where('guia_trasladoh.idempresa',$empresa)
                ->where('guia_trasladoh.created_at', '>=', $f_inicio)
                ->where('guia_trasladoh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_trasladoh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_trasladoh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%');
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')

                })                
                ->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_trasladoh.iddespachador')
                
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('guia_trasladoh')->where('guia_trasladoh.idempresa',$empresa)
                ->where('guia_trasladoh.idempresa',$empresa)->where('guia_trasladoh.created_at', '>=', $f_inicio)
                ->where('guia_trasladoh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_trasladoh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_trasladoh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%');
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                })->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_trasladoh.iddespachador')
                ->count(); 

        }


        //$totalData =count($guia_traslados);
            
        

        

           // $guia_traslados = $guia_traslados;
                            

           // $totalFiltered = count($guia_traslados);
        

        $data = array();
        if(!empty($guia_traslados))
        {
            foreach ($guia_traslados as $guia_traslado)
            {
                $nestedData['numeracion'] =str_pad($guia_traslado->numeracion, 6, "0", STR_PAD_LEFT);
                 if (!is_null($guia_traslado->xml_file) and $guia_traslado->xml_file != '') { 
                    $nestedData['numeracion'] .=" (<a title='{$guia_traslado->descriptionG}' href='".url('greenter',$guia_traslado->xml_file)."' download>XML</a>)";
                } 
                if (!is_null($guia_traslado->cdr_file) and $guia_traslado->cdr_file != '') { 
                    $nestedData['numeracion'] .=" (<a title='{$guia_traslado->descriptionG}' href='".url('greenter',$guia_traslado->cdr_file)."' download>CDR</a>)";
                 } 
                 if (!is_null($guia_traslado->pdf_file) and $guia_traslado->pdf_file != '') {
                    $nestedData['numeracion'] .=" (<a title='{$guia_traslado->descriptionG}' href='".url('greenter',$guia_traslado->pdf_file)."' download>PDF</a>)";
                }
                $nestedData['codigoNB'] =  $guia_traslado->codigoNB;
                $formato = 'Y-m-d H:i:s';
                $fecha = DateTime::createFromFormat($formato, $guia_traslado->created_at);
                $nestedData['created_at'] =date_format($fecha, 'Y-m-d');
                $nestedData['razon_social'] = $guia_traslado->razon_social;
                $nestedData['f_entrega'] = $guia_traslado->f_entrega;
                //$nestedData['f_entregado'] =$guia_traslado->f_entregado;
                $nestedData['name'] = $guia_traslado->name." ".$guia_traslado->lastname;

                /*if( $guia_traslado->codeG > 138 && $guia_traslado->codeG < 4000 && $guia_traslado->estado_doc==3 ){
                    $nestedData['status_ent_gr'] = '<button class="btn" style="background:#000;color:#fff">RECHAZADO</button>';
                }
                else{
                    if($guia_traslado->status_ent == -1) {
                        if($guia_traslado->estado_doc == 0) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-danger" data-id_guia_trasladoh="'.$guia_traslado->id_guia_trasladoh.'" data-status="1" >  Pendiente </button>';
                        }      elseif($guia_traslado->estado_doc == 1) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-primary" data-id_guia_trasladoh="'.$guia_traslado->id_guia_trasladoh.'" data-status="2" > Facturada </button>';
                        }      elseif($guia_traslado->estado_doc == 2) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-success" data-id_guia_trasladoh="'.$guia_traslado->id_guia_trasladoh.'" data-status="3" > Entregada </button>';
                        }      elseif($guia_traslado->estado_doc == 4) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-info" data-id_guia_trasladoh="'.$guia_traslado->id_guia_trasladoh.'" data-status="3" > Reprogramada </button>';
                        }      else{
                            $nestedData['status_ent_gr'] ='<button id="status" class="btn btn-secondary" data-id_guia_trasladoh="'.$guia_traslado->id_guia_trasladoh.'" data-status="0" > Anulada </button>';
                        }  
                    } else {
                        $status_ent_gr = Helper::status_ent_gr();
                        $nestedData['status_ent_gr'] = $status_ent_gr[$guia_traslado->status_ent];
                        if ( $guia_traslado->is_ncp ) {
                            $nestedData['status_ent_gr'] .= ' <i title="NCP" class="icon-exclamation position-center"></i>';
                        }
                    }
                }*/

                $nestedData['cliente_extra'] =  $guia_traslado->ruc_dni.$guia_traslado->contacto_nombre.$guia_traslado->contacto_telefono;

                $nestedData['acciones'] ='';

                if(!($guia_traslado->codeG > 0 && $guia_traslado->codeG < 4000 && $guia_traslado->estado_doc==3)){
                            if(($guia_traslado->codeG <= 0||$guia_traslado->codeG >=4000 )  && (is_null($guia_traslado->cdr_file)  || $guia_traslado->cdr_file=='') && $guia_traslado->correlativoG>0 )
                            $nestedData['acciones'] .="<button type='button' class='btn btn-success btn-xs'
                                        id='actualizar' data-id='{$guia_traslado->id_guia_trasladoh}'>
                                    <i class='glyphicon glyphicon-refresh position-center'></i>
                                </button>";
                            
                                $nestedData['acciones'] .="<button type='button' class='btn btn-info btn-xs'
                                    id='imprimir' data-id='{$guia_traslado->id_guia_trasladoh}'
                                    data-archivo='{$guia_traslado->pdf_file}'>
                                <i class='glyphicon glyphicon-print position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-toggle = 'modal'
                                    id='observacion' data-id='{$guia_traslado->id_guia_trasladoh}'
                                    data-numeracion = '{$guia_traslado->numeracion } '
                                    data-observacion = '{$guia_traslado->comentarios } '>
                                <i class='icon-comments position-center'></i>
                            </button>
                            ";
                            /*if($guia_traslado->estado_doc == 0) {
                                $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                    data-id_orden_ventah   = ' {$guia_traslado->id_guia_trasladoh} '
                                    data-numeracion        = ' {$guia_traslado->numeracion} '
                                    data-np                = ' {$guia_traslado->codigoNB} '                                    
                                    data-toggle            = 'modal'
                                    id='anular'> 
                                <i class='icon-cancel-square2 position-center'></i>
                            </button>";
                            } */
                        }


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

    public function ExportExcel($customer_data){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($customer_data);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="GuiasTraslado-SolucionesOGGK.xls"');
            header('Cache-Control: max-age=0');
            //ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }


    function exportData(Request $request){    
        $columns = array( 
            0 =>'numeracion', 
            1=> 'codigoNB',
            2=> 'created_at',
            3=> 'razon_social',
            4=> 'f_entrega',
            //5=> 'f_entregado',
            5=> 'name',
           // 6=> 'status_ent',
            6=> 'cliente_extra',
            7=> 'numeracion',
        );   
         $data_array [] = array( 
                            "Correlativo", 
                            "N° NubeFact",
                            "Fecha de Emisión",
                            "Cliente",
                            "Fecha de Entrega",
                           // "Fecha de Entregado",
                            "Despachador",
                            //"Estado",
                        );
  
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];
        $order =$columns[$request['order']];
        $dir = $request['dir'];
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
        $cant = 15;

        $totalData =DB::table('guia_trasladoh')->where('guia_trasladoh.idempresa',$empresa)->count();
            
        if(!isset($cant))$cant = 1000;
        if(empty($request['search'])){
            $guia_traslados = DB::table('guia_trasladoh')->select('guia_trasladoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('guia_trasladoh.idempresa',$empresa)
            ->where('guia_trasladoh.created_at', '>=', $f_inicio)
            ->where('guia_trasladoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_trasladoh.iddespachador')
            
            ->orderBy($order,$dir)
            ->get();                                 
            $totalFiltered = DB::table('guia_trasladoh')->where('guia_trasladoh.idempresa',$empresa)->where('guia_trasladoh.created_at', '>=', $f_inicio)->where('guia_trasladoh.created_at', '<=', $f_fin)->count();

        }else{
            $search = $request['search']; 
            $guia_traslados = DB::table('guia_trasladoh')->select('guia_trasladoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
                ->where('guia_trasladoh.idempresa',$empresa)
                ->where('guia_trasladoh.created_at', '>=', $f_inicio)
                ->where('guia_trasladoh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_trasladoh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_trasladoh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%');
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                })                
                ->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_trasladoh.iddespachador')
                
                ->orderBy($order,$dir)
                ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('guia_trasladoh')->where('guia_trasladoh.idempresa',$empresa)->where('guia_trasladoh.created_at', '>=', $f_inicio)
            ->where('guia_trasladoh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_trasladoh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_trasladoh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%');
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                })->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_trasladoh.iddespachador')
                ->count(); 

        }
       
        $formato = 'Y-m-d H:i:s';
        $status_ent_gr =[
            0 => 'ANULADO',
            1 => 'PENDIENTE ENTREGA',
            2 => 'ENTREGA PARCIAL',
            3 => 'ENTREGADO',
            4 => 'NC'
            ];

        foreach($guia_traslados as $guia_traslado)
        {
            $fecha = DateTime::createFromFormat($formato, $guia_traslado->created_at);
            $est;
            if( $guia_traslado->codeG > 138 && $guia_traslado->codeG < 4000 && $guia_traslado->estado_doc==3 ){
               $est='RECHAZADO';
            }
            else{
                if($guia_traslado->status_ent == -1) {
                    if($guia_traslado->estado_doc == 0) {
                        $est='Pendiente';
                    }      elseif($guia_traslado->estado_doc == 1) {
                        $est='Facturada';
                    }      elseif($guia_traslado->estado_doc == 2) {
                        $est='Entregada';
                    }      elseif($guia_traslado->estado_doc == 4) {
                        $est='Reprogramada';
                    }      else{
                        $est='Anulada';
                    }  
                } else {
                    
                    $est=$status_ent_gr[$guia_traslado->status_ent];
                    if ( $guia_traslado->is_ncp ) {
                        $est .= ' NCP';
                    }
                }
            }

            $data_array[] = array(
                'Correlativo' =>str_pad($guia_traslado->numeracion, 6, "0", STR_PAD_LEFT), 
                'N° NubeFact'=>$guia_traslado->codigoNB,               
                'Fecha de Emisión'=>date_format($fecha, 'Y-m-d'),
                'Cliente'=>$guia_traslado->razon_social,
                'Fecha de Entrega'=>$guia_traslado->f_entrega,
                //'Fecha de Entregado'=>$guia_traslado->f_entregado,
                'Despachador'=>$guia_traslado->name." ".$guia_traslado->lastname,
                //'Estado'=>$est
            );
        }

        $this->ExportExcel($data_array);
    }

    public function index_detallado()
    {
        $empresa = Auth::user()->idempresa;

        $cajas = DB::table('guia_trasladoh')->select('guia_trasladoh.*','guia_trasladod.*','producto.nombre','clientes.razon_social', 'users.name', 'users.lastname')
            ->join ('guia_trasladod', 'guia_trasladod.id_guia_trasladoh', '=', 'guia_trasladoh.id_guia_trasladoh')
            ->join ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_trasladoh.idvendedor')
            ->join('producto','guia_trasladod.idproducto','=','producto.idproducto')
            ->get();

        return view('guia_traslado/listado_guia_traslado_detallado', ['cajas' => $cajas]);
    }

    public function guias_pendientes()
    {
        $sucu = Auth::user()->idsucursal;
        $gr_pendientes = DB::table('guia_trasladoh')
                    ->select('guia_trasladoh.*', DB::raw("IFNULL(guia_trasladoh.f_reprogramar,'0000-00-00') as f_reprogramar"),'clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'guia_trasladoh.codigoNB', 'ov.codigoNB as np', 'ov.numeracion as numov', 'caja.codigoNB as caja', 'caja.tipo as cajatipo')
                    ->where('guia_trasladoh.estado_doc','=',0)
                    ->orWhere('guia_trasladoh.estado_doc','=',1)
                    ->orWhere('guia_trasladoh.estado_doc','=',4)
                    ->leftjoin ('clientes', 'guia_trasladoh.idcliente', '=', 'clientes.idcliente')
                    ->leftjoin ('users', 'users.id', '=', 'guia_trasladoh.idvendedor')
                    ->leftJoin('cajaguiaventa as cg','cg.idguia','=','guia_trasladoh.id_guia_trasladoh')
                    ->leftJoin('cajah as caja','caja.idcajah','=','cg.idcaja')
                    
                    ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$sucu)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        return view('guia_traslado/listado_GRpendientes', ['gr_pendientes' => $gr_pendientes])->with('sucursal',$sucursal);
    }

    public function crear()        
    {
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $id = Auth::user()->id;

        $products = Producto::where('idempresa',$empresa)
                    ->where('idsucursal',$sucursal)
                    ->where('state',1)
                    ->where('stock_total','>',0)->get();

        $usuario = DB::table('users')
                    ->where('id',$id)
                    ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
                    ->join('empresas','users.idempresa','=','empresas.idempresa')
                    ->first();
                        
        $vendedores = DB::table('users')->where('tienda_user',0)->get();

        $despachadores = DB::table('users')->where('tienda_user',0)->whereIn('puesto', [1,4,6,8,9])->get();     
                    
        $transportes = DB::table('transporte')->get();    

        $almacenes = DB::table('almacen')->get();   

        $ubigeo = DB::table('distritos')
                    ->select('distritos.*', 'provincias.provincia_name', 'departamentos.departamento_name')
                    ->leftJoin('provincias', 'provincias.id', '=', 'distritos.id_provi')
                    ->leftJoin('departamentos', 'departamentos.id', '=', 'distritos.id_depa')
                    ->get();   

        return view('guia_traslado/guia_traslado')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('despachadores',$despachadores)->with('transportes',$transportes)->with('almacenes',$almacenes)->with('ubigeo', $ubigeo)->with('iduser',$id);
    }

    public function crearsolopdf()        
    {
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $id = Auth::user()->id;

        $products = Producto::where('idempresa',$empresa)
                    ->where('idsucursal',$sucursal)
                    ->where('state',1)
                    ->where('stock_total','>',0)->get();

        $usuario = DB::table('users')
                    ->where('id',$id)
                    ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
                    ->join('empresas','users.idempresa','=','empresas.idempresa')
                    ->first();
                        
        $vendedores = DB::table('users')->where('tienda_user',0)->get();

        $despachadores = DB::table('users')->where('tienda_user',0)->whereIn('puesto', [1,4,6,8,9])->get();     
                    
        $transportes = DB::table('transporte')->get();    

        $almacenes = DB::table('almacen')->get();   

        $ubigeo = DB::table('distritos')
                    ->select('distritos.*', 'provincias.provincia_name', 'departamentos.departamento_name')
                    ->leftJoin('provincias', 'provincias.id', '=', 'distritos.id_provi')
                    ->leftJoin('departamentos', 'departamentos.id', '=', 'distritos.id_depa')
                    ->get();   

        return view('guia_traslado/guia_trasladopdf')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('despachadores',$despachadores)->with('transportes',$transportes)->with('almacenes',$almacenes)->with('ubigeo', $ubigeo)->with('iduser',$id);
    }

    public function buscar_cliente(Request $request){
        $busqueda = $request['query'];
        $clientes = DB::table('clientes')
            ->where('razon_social','like','%'.$busqueda.'%')
            ->orwhere('ruc_dni','like','%'.$busqueda.'%')
            // ->orwhere('dni',$busqueda)
            // ->orwhere('ruc',$busqueda)
            ->get();
        return json_encode($clientes);
    }

    public function buscar_ov_numeracion(Request $request){
        $busqueda = $request['query'];
        $orden_ventas = DB::table('orden_ventah')
            ->select('id_orden_ventah','numeracion','is_digital','codigoNB')
            ->where('estado_doc','!=',2)
            ->where('estado_doc','!=',9)
            ->Where(function ($query2) use ($busqueda) {
                $query2 ->where('numeracion','like','%'.$busqueda.'%')
                        ->orwhere('codigoNB','like','%'.$busqueda.'%');
            })                        
            ->get();
        return json_encode($orden_ventas);
    }

    public function buscar_ov_numeracion_inc(Request $request){
        $busqueda = $request['query'];
        $orden_ventas = DB::table('orden_ventah')
            ->select('id_orden_ventah','numeracion','is_digital','codigoNB')
            ->where('estado_doc','!=',2)
            ->where('estado_doc','!=',1)
            ->where('estado_doc','!=',9)
            ->Where(function ($query2) use ($busqueda) {
                $query2 ->where('numeracion','like','%'.$busqueda.'%')
                        ->orwhere('codigoNB','like','%'.$busqueda.'%');
            })                        
            ->get();
        return json_encode($orden_ventas);
    }

    public function buscar_ov_pdf(Request $request){
        $busqueda = $request['query'];
        $orden_ventas = DB::table('orden_ventah')
            ->select('id_orden_ventah','numeracion','is_digital','codigoNB')
            ->Where(function ($query2) use ($busqueda) {
                $query2 ->where('numeracion','like','%'.$busqueda.'%')
                        ->orwhere('codigoNB','like','%'.$busqueda.'%');
            })                        
            ->get();
        return json_encode($orden_ventas);
    }

    public function buscar_ov_todo(Request $request){
        $busqueda = $request['query'];
        $orden_venta = DB::table('orden_ventah')
            ->select('orden_ventah.*', 'c.razon_social', 'c.direccion', 'c.distrito', 'c.departamento', 'c.provincia')
            ->leftJoin('clientes as c', 'orden_ventah.idcliente', '=', 'c.idcliente')
            ->where('id_orden_ventah','=', $busqueda)
            ->first();

        $orden_venta->ubigeo = '';

        $provincia = DB::table('provincias')->where('provincia_name', $orden_venta->provincia)->first();
        if ($provincia) {
            $distrito = DB::table('distritos')->where('id_provi', $provincia->id)->where('distrito_name', $orden_venta->distrito)->first();
            if ($distrito) {
                $orden_venta->ubigeo = $distrito->id;
            }
        }

        $orden_venta->detalle = DB::table('orden_ventad')
            ->select('orden_ventad.*', 'p.*')
            ->join('producto as p', 'p.idproducto', '=', 'orden_ventad.idproducto')
            ->where('id_orden_ventah','=', $busqueda)
            ->get();

        return json_encode($orden_venta);
    }

    public function buscar_ov_todo_guia(Request $request){
        $busqueda = $request['query'];
        $orden_ventas = DB::table('orden_ventah')
            ->select('orden_ventah.*', 'c.razon_social')
            ->leftJoin('clientes as c', 'orden_ventah.idcliente', '=', 'c.idcliente')
            ->where('id_orden_ventah','=', $busqueda)
            ->first();

        $orden_ventas->detalle = DB::table('orden_ventad')
            ->select('orden_ventad.*', 'p.*')
            ->join('producto as p', 'p.idproducto', '=', 'orden_ventad.idproducto')
            ->where('id_orden_ventah','=', $busqueda)
            ->get();

        $orden_ventas->guias = DB::table('guia_trasladoh as gr')
            ->select('gr.id_guia_trasladoh', 'gr.numeracion', 'gr.codigoNB', 'gr.estado_doc')
            ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'gr.id_guia_trasladoh')
            ->where('gr.id_orden_ventah','=', $busqueda)
            ->where('gr.estado_doc','!=',3)
            //->whereNotNull('gr.cdr_file')
            //->where('gr.cdr_file','!=',"")
            ->whereNull('cg.idguia')
            ->get();

        return json_encode($orden_ventas);
    }

    public function buscar_guias(Request $request){
        $busqueda = $request['query'];
        $guias = DB::table('guia_trasladoh as gr')
            ->select('gr.id_guia_trasladoh', 'gr.numeracion', 'gr.codigoNB', 'gr.estado_doc')
            ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'gr.id_guia_trasladoh')
            ->where('gr.id_orden_ventah','=', $busqueda)
            ->where('gr.estado_doc','!=',3)
            ->whereNull('cg.idguia')
            ->get();

        return json_encode($guias);
    }

    public function update_codigoNB(Request $request){
        $value = $request['value'];
        $id_guia_trasladoh = $request['id_guia_trasladoh'];
        $guiaremisionh = GuiaTrasladoH::find($id_guia_trasladoh);
        $guiaremisionh->codigoNB = $value;
        $guiaremisionh->save();
        return json_encode(['mensaje' => 200]);
    }

    public function store(Request $request){

        DB::beginTransaction(); // <-- first line  
    
        try{
            $empresa = Auth::user()->idempresa;
            $sucursal = Auth::user()->idsucursal;
            $idusuario = Auth::user()->id;

            $idcliente = $request['idcliente'];
            $id_orden_ventah = $request['id_orden_ventah'];
            $paga = $request['paga'];
            $vuelto = $request['vuelto'];
            $comentarios = $request['comentarios'];
            $f_entrega = $request['f_entrega'];
            $f_cobro = $request['f_cobro'];
            $idvendedor = $request['idvendedor'];
            $iddespachador = $request['iddespachador'];
            $idtransporte = $request['idtransporte'];
            $peso_total = $request['peso_total'];
            $almacen = $request['almacen'];   
            //$num_bultos = $request['num_bultos'];   
            //$ubigeo = $request['ubigeo'];   
            $motivo = $request['idmotivo'];
            $codigoNB = $request['codigoNB']; 
            $fechaNB = $request['fechaNB'];
            $correlativo_inside = 0;
                
            $productos_json = $request['productos'];
            $state = 1;

            if ($request['is_invoice'] == '0') {
                $nextCoAndCode = $this->nextCorrelativoAndCode();
                $codigoNB = $nextCoAndCode['codigoNB'];
                $request['correlativo'] = $nextCoAndCode['correlativoG'];
            } else {
                $maximo_num_inside = DB::table('guia_trasladoh')
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('correlativo_inside');
                $correlativo_inside = intval($maximo_num_inside) + 1;
                $codigoNB = 'SP-'.sprintf('%06d', $correlativo_inside);
            }
            
            $bool=false;
            $leer_respuesta = [];
            if ( DB::table('guia_trasladoh')->where('codigoNB', '=', $codigoNB)->first() ){
                $bool = true;
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 992];
                $respuesta[] = ['id' => 9999999992];
                $respuesta[] = ['leer_respuesta' => $leer_respuesta];
     
                return json_encode($respuesta);   
            }

            $guia_trasladoh = new GuiaTrasladoH;
            $guia_trasladoh->idempresa = $empresa;
            $guia_trasladoh->idsucursal = $sucursal;
            $guia_trasladoh->motivo_traslado = $motivo;
            $guia_trasladoh->idusuario = $idusuario;
            $guia_trasladoh->idcliente = $idcliente;
            $guia_trasladoh->idvendedor = $idvendedor;
            $guia_trasladoh->iddespachador = $iddespachador;
            $guia_trasladoh->idtransporte = $idtransporte;
            $guia_trasladoh->idalmacen = $almacen;
            /*$guia_trasladoh->numero_de_bultos = $num_bultos;
            $guia_trasladoh->ubigeo = "tmp";*/

            $maximo_num = DB::table('guia_trasladoh')
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('numeracion');

            $guia_trasladoh->numeracion = intval($maximo_num) + 1 ;

            $guia_trasladoh->paga = $paga;
            $guia_trasladoh->vuelto = $vuelto;
            $guia_trasladoh->comentarios = $comentarios;
            $guia_trasladoh->f_entrega = $f_entrega;
            $guia_trasladoh->f_cobro = $f_cobro;
            $guia_trasladoh->peso_total = $peso_total;
            $guia_trasladoh->codigoNB = $codigoNB;
            $guia_trasladoh->fechaNB = $fechaNB;
            $guia_trasladoh->correlativoG = 0;
            $guia_trasladoh->correlativo_inside = $correlativo_inside;
            $saved = $guia_trasladoh->save();

            $productos = json_decode($productos_json);

            $saved1 = false;
            $saved2 = true;
            $saved4 = false;
            $saved5 = false;

            $cants = [];

            $cantidades_orden_ventah = true;
            
            for($i = 0; $i < count($productos); $i++){

                $saved1=true;
                    
                $guia_trasladod = new GuiaTrasladoD;
                $guia_trasladod->id_guia_trasladoh = $guia_trasladoh->id_guia_trasladoh;
                $guia_trasladod->idproducto = $productos[$i]->idproducto;
                $guia_trasladod->cantidad = $productos[$i]->stock_total;
                //$guia_trasladod->idlote = $productos[$i]->idlote;
                $guia_trasladod->peso_unit = $productos[$i]->peso;
                $guia_trasladod->peso_total = $productos[$i]->peso * $productos[$i]->stock_total ;
                $guia_trasladod->peso_und = $productos[$i]->peso_und;
                $guia_trasladod->idempresa = $empresa;
                $saved = $guia_trasladod->save();

                /*$transacts = new Transacciones;
                $transacts->idproducto = $productos[$i]->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;            
                $transacts->idusuario = $idusuario;
                $transacts->idalmacen = $almacen;
                $transacts->idlote = $productos[$i]->idlote;

                $transacts->f_emision = date('Y-m-d');
                $transacts->tipo_documento = 1;
                $transacts->iddocumento = $guia_trasladoh->id_guia_trasladoh;          
                $transacts->tipo = 0;                   
                
                $transacts->cantidad = $productos[$i]->stock_total;                         
                $transacts->stockT = $stockT_suma - $productos[$i]->stock_total;

                $transacts->tipo_movimiento = $motivo;
                $transacts->state = 1;        
                $saved4 = $transacts->save();*/
                $saved4 = true;
            }

            if( ((int)$motivo) == 1 ){
                if ($cantidades_orden_ventah) {
                    $isTotal = true;
                    foreach ($cants as $key => $value) {
                        if ($value['cantidad_fal'] > 0) {
                            $isTotal = false;
                        }
                    }
                   

                    $saved5 = true;
                } else {
                    $saved5=false;
                }             
            }else{
                $saved5=true;
            }      

            if ($saved && $saved1 && $saved2 && $saved4 && $saved5 && $bool==false)
                $childModelSaved = true; 
            else
                $childModelSaved = false;        

        }catch(Exception $e)
            {
                 $childModelSaved = false;
            }

            if ($childModelSaved)
            {
                $msg = '';
                $xml_file = '';
                $cdr_file = '';
                $pdf_file = '';
                $codeG = '';
                $descriptionG = '';
                $correlativoG = 0;

                if ($request['is_invoice'] == '0') {
                    $request['numeracion'] = $guia_trasladoh->numeracion;
                    $request['id_doc'] = $guia_trasladoh->id_guia_trasladoh;
                    $resp = $this->generateDespatchGreen($request);

                    if ($resp['created'] == 501) {                                                 
                            $msg  = "ESTADO: ESPERANDO RESPUESTA DE SUNAT";
                            $xml_file = $resp['xml_file'];
                            $pdf_file = $resp['pdf_file'];
                            $correlativoG = $resp['correlativoG'];
                            $codeG = $resp['codeG'];
                            $descriptionG = $resp['descriptionG'];
                         
                       
                    } else {
                        $msg = $resp['msg'];                        
                        $xml_file = $resp['xml_file'];
                        $cdr_file = $resp['cdr_file'];
                        $pdf_file = $resp['pdf_file'];
                        $correlativoG = $resp['correlativoG'];
                        $codeG = $resp['codeG'];
                        $descriptionG = $resp['descriptionG'];
                    }
                }

                $guia_trasladoh->correlativoG = $correlativoG;
                $guia_trasladoh->xml_file = $xml_file;
                if($cdr_file !=''){
                    $guia_trasladoh->cdr_file = $cdr_file;
                }                
                $guia_trasladoh->pdf_file = $pdf_file;
                $guia_trasladoh->codeG = $codeG;
                $guia_trasladoh->descriptionG = $descriptionG;
                $guia_trasladoh->save();

                DB::commit(); // YES --> finalize it 
                if((int)$codeG>138 &&(int)$codeG<4000){
                    $request['id_orden_ventah']=$guia_trasladoh->id_guia_trasladoh;
                    $this->gr_estado_anulado($request);
                }
    
                $respuesta = array();
                $respuesta[] = ['created'=> 200];
                $respuesta[] = ['id' => $guia_trasladoh->id_guia_trasladoh];
                $respuesta[] = ['msg' => $msg];
                $respuesta[] = ['pdf' =>$guia_trasladoh->pdf_file];
                $respuesta[] = ['leer_respuesta' => $leer_respuesta];
                $respuesta[] = ['codeG' => $codeG];
                if($request['is_invoice'] == '0'){
                $respuesta[] = ['cdr' => $resp['cdrStatus']];
                }


     
                return json_encode($respuesta);
            }
            elseif( $bool && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 992];
                $respuesta[] = ['id' => 9999999992];
                $respuesta[] = ['leer_respuesta' => $leer_respuesta];
     
                return json_encode($respuesta);   
            }
            elseif( $saved2==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999999];
                $respuesta[] = ['leer_respuesta' => $leer_respuesta];
     
                return json_encode($respuesta);   
            }
            else
            {
                DB::rollBack(); // NO --> error de nubefact
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999999];
                $respuesta[] = ['leer_respuesta' => $leer_respuesta];

                return json_encode($respuesta);
            }

    }

    public function checkCDRGR(Request $request){
        $id =$request['id'];
        $request['id_orden_ventah']=$id;

        $dirGreenter = 'greenter/';
        $tipoDocumento='09';
    	$guia_traslado =GuiaTrasladoH::find($id);
        $festore=Fe::where('id_doc',$id)->where('tipo',9)->first();
        $rucEmisor= '20600819667';        
        if($festore)
            $request=json_decode($festore->request, true);


        $serie='T002';
        $correlativo=strval($guia_traslado->correlativoG);
        $result= parent::checkCDR($rucEmisor,$tipoDocumento,$serie,$correlativo);

        
        if (!$result->isSuccess()) {

            $see = parent::configDespatchGreen();
            $dirGreenter = 'greenter/';

            $client = parent::getClientGreen($request['idcliente']);

            $company = parent::getCompanyGreen();

            $relTipoDoc = '02';
            $relNroDoc = strval($request['numeracion']);

            $transpTipoDoc = $request['transporte_tipo_doc'];
            $transNumDoc = $request['transporte_num_doc'];
            $transRznSocial = $request['transporte_denominacion'];
            $transPlaca = $request['transporte_placa'];
            $transChoferTipoDoc = $request['transporte_chofer_tipo_doc'];
            $transChoferDoc = $request['transporte_chofer_num_doc'];

            $envioCodTraslado = $request['envio_cod_traslado'];
            $envioDescTraslado = $request['envio_desc_traslado'];
            $envioModTraslado = $request['envio_mod_traslado'];
            $envioFecTraslado = $request['envio_fec_traslado'];
            $envioPesoTotal = (float) $request['envio_peso_total'];

            $partidaUbigeo = $request['partida_ubigeo'];
            $partidaDireccion = strtoupper($request['partida_direccion']);

            $llegadaUbigeo = $request['llegada_ubigeo'];
            $llegadaDireccion = strtoupper($request['llegada_direccion']);

            /*$maxCorrelativo = DB::table('guia_trasladoh')
                        ->max('correlativoG');
            $nextCorrelativo = intval($maxCorrelativo) + 1;*/

            $tipoDoc = '09';
            $serie = 'T002';
            //$correlativo = strval($nextCorrelativo);
            //$codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
            $correlativo = $request['correlativo'];
            $fechaEmision = $request['fechaNB'];
            $observacion = $request['observacion'];

            

            $motivo = $request['idmotivo'];
            if(!(int)$envioCodTraslado==13){
                $rel = (new Document())
                ->setTipoDoc($relTipoDoc) // Tipo: Numero de Orden de Entrega
                ->setNroDoc($relNroDoc);
            }

            $transp = (new Transportist())
                ->setTipoDoc($transpTipoDoc)
                ->setNumDoc($transNumDoc)
                ->setRznSocial($transRznSocial)
                ->setPlaca($transPlaca)
                ->setChoferTipoDoc($transChoferTipoDoc)
                ->setChoferDoc($transChoferDoc);

            $envio = (new Shipment())
                ->setCodTraslado($envioCodTraslado) // Cat.20
                ->setDesTraslado($envioDescTraslado)
                ->setModTraslado($envioModTraslado) // Cat.18
                ->setFecTraslado(new DateTime($envioFecTraslado.' 12:00:00-05:00'))
                ->setCodPuerto('000')
                ->setIndTransbordo(false)
                ->setPesoTotal($envioPesoTotal)
                ->setUndPesoTotal('KGM')
                ->setNumContenedor('C-000')
                ->setLlegada(new Direction($llegadaUbigeo, $llegadaDireccion))
                ->setPartida(new Direction($partidaUbigeo, $partidaDireccion))
                ->setTransportista($transp);

            $despatch = (new Despatch())
                ->setTipoDoc($tipoDoc)
                ->setSerie($serie)
                ->setCorrelativo($correlativo)
                ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
                ->setCompany($company)
                ->setDestinatario($client)
                ->setObservacion($observacion)
                ->setEnvio($envio);

            if(!(int)$envioCodTraslado==13){
                $despatch->setRelDoc($rel);
            }


            $productos = json_decode($request['productos']);
            $details = [];
            
            for ($i = 0; $i < count($productos); $i++) {

                $product = Producto::find($productos[$i]->idproducto);
                $description = $product->nombre;
                $codProd = $product->barcode;
                $codSunat = $product->codigo_sunat;
                $cantidad = (int) $productos[$i]->stock_total;

                $detail = (new DespatchDetail())
                    ->setCantidad($cantidad)
                    ->setUnidad('NIU')
                    ->setDescripcion($description)
                    ->setCodigo($codProd)
                    ->setCodProdSunat($codSunat);

                $details[] = $detail;
            }

            $despatch->setDetails($details);

            // Envío a SUNAT
            $result = $see->send($despatch);

            if (!$result->isSuccess()) {
                $msg = 'Error no se conectó: ';
                ob_start();
                var_dump($result->getError());
                $msg .= ob_get_clean(); 
                return  json_encode(['created' => 500, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]);    
            }


///////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////      
        }

        $cdr = $result->getCdrResponse();
        if ($cdr === null) {
            $msg = 'CDR no encontrado, el comprobante '.$serie.'-'.$correlativo.' no ha sido comunicado a SUNAT.';
            return  json_encode(['created' => 501, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]); 
        }
        $arguments = [
            $rucEmisor,  //RUC
            $tipoDocumento, //TIPO DOCUMENTO
            $serie, //SERIE
            intval($correlativo) //CORRELATIVO
        ];
        // Guardamos el CDR
        $cdrFileName = 'R-'.implode('-', $arguments).'.zip';
        file_put_contents($dirGreenter.$cdrFileName, $result->getCdrZip()); 
        $code = (int)$cdr->getCode();
        $cdrStatus= $code;
        $msg = '';
        if ($code === 0) {
            $msg = 'ESTADO: ACEPTADA';
        } 
        else if($code === 98 || $code==109 || ($code<=138 && $code>=130)){
            $msg = 'ESTADO: ESPERANDO RESPUESTA DE SUNAT';
        }
        else if ($code >= 4000) {
            $msg = 'ESTADO: ACEPTADA CON OBSERVACIONES: ';
            ob_start();
            var_dump($cdr->getNotes());
            $msg .= ob_get_clean();
        } else if ($code >= 2000 && $code <= 3999) {
            $msg = 'ESTADO: RECHAZADA';
            $this->gr_estado_anulado($request);
        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $msg = 'Excepción';
            $this->gr_estado_anulado($request);
            
        }

        $msg .= ' '.$cdr->getDescription();



        $guia_traslado->cdr_file=$cdrFileName;
        $guia_traslado->codeG=0;
        $guia_traslado->descriptionG= $msg;
        $guia_traslado->save();
        //var_dump($cdr);
        $msg = 'El CDR del comprobante '.$serie.'-'.$correlativo.' se recupero correctamente.'.$guia_traslado->descriptionG;
        if($festore)
            $festore->delete();
        return  json_encode(['created' => 200, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]); 
        

    }

    public function show(Request $request){
    	$id = $request['id'];

    	$guia_traslado = DB::table('guia_trasladoh as grh')
                ->select('grh.*','users.name as name','users.lastname as lastname','users.dni as dni','transporte.nombre_trans as nombre_trans','transporte.marca as marca','transporte.placa as placa', 'ov.direccion_entrega', 'ov.email_entrega', 'ov.telefono_entrega')
                ->where('grh.id_guia_trasladoh',$id)
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'grh.id_orden_ventah')
                ->join('users','users.id','=','grh.iddespachador')
                ->join('transporte','transporte.idtransporte','=','grh.idtransporte')
                ->first();

    	$guia_trasladoD = DB::table('guia_trasladod')
                ->select('guia_trasladod.*','producto.nombre as nn','producto.medida_venta')
                ->where('guia_trasladod.idempresa','=',$guia_traslado->idempresa)
                ->where('guia_trasladod.id_guia_trasladoh','=',$guia_traslado->id_guia_trasladoh)
                ->join('producto','guia_trasladod.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$guia_traslado->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$guia_traslado->idcliente)
                    ->first();

        return view('guia_traslado/info_guia_traslado')
             ->with('guia_traslado',$guia_traslado)
             ->with('guia_trasladoD',$guia_trasladoD)
             ->with('sucursal',$sucursal)
             ->with('cliente',$cliente);

    }

     public function gr_estado(Request $request){
        $idusuario = Auth::user()->id;
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;

        $id_guia_trasladoh = $request['id_guia_trasladoh'];
        $guiaremisionh = GuiaTrasladoH::find($id_guia_trasladoh);
        //$guiaremisionh->estado_doc = 2;
        $guiaremisionh->f_entregado = date("Y/m/d");
        $guiaremisionh->id_usuario_despachador = $idusuario;
        $guiaremisionh->save();

        if (strval($guiaremisionh->created_at) > "2021-06-28 00:00:00") {

            if ($guiaremisionh->status_ent != -1) {
                $guiaremisionh->status_ent = 2;

                if ($request['is_total']) {
                    $guiaremisionh->status_ent = 3;
                    $guiaremisionh->estado_doc = 2;
                }
            } else {
                $guiaremisionh->estado_doc = 2;
            }
            $guiaremisionh->save();

            $idalmacen = $guiaremisionh->idalmacen;
            $tipo_movimiento = $guiaremisionh->tipo_movimiento;

            /*$cants = [];
            if (((int)$motivo) == 1) {
                $detail_orden_ventad = DB::table('orden_ventad')
                                        ->where('id_orden_ventah',$guiaremisionh->id_orden_ventah)->get();
                
                foreach ($detail_orden_ventad as $key => $value) {
                    $cants[$value->idproducto]['id_orden_ventad'] = $value->id_orden_ventad;
                    $cants[$value->idproducto]['cantidad_fal'] = $value->cantidad_fal;
                }
            }*/

            $productos = DB::table('guia_trasladod')->where('id_guia_trasladoh',$id_guia_trasladoh)->get();
            $idProds = [];
            $prods = json_decode($request['productos']);

            for($i = 0; $i < count($prods); $i++){
                $guia_trasladod = GuiaTrasladoD::find($prods[$i]->id);
                $guia_trasladod->cantidad_ent += $prods[$i]->cantidad_entregada;
                $guia_trasladod->save();
                $idProds[$prods[$i]->id] = $prods[$i]->cantidad_entregada;
            }


            if ($guiaremisionh->status_ent != -1) {
                $min_status_guias = DB::table('guia_trasladoh')
                                        ->where('id_orden_ventah', $guiaremisionh->id_orden_ventah)
                                        ->where('status_ent', '!=', 0)
                                        ->min('status_ent');

                $ov_state = OrdenVentaH::find($guiaremisionh->id_orden_ventah);
                $ov_state->status_ent = $min_status_guias;
                $ov_state->save();
            }

        }

        return json_encode(['mensaje' => 200]);
    }

    public function gr_estado_reprogramar(Request $request){
        $idusuario = Auth::user()->id;

        $id_guia_trasladoh = $request['id_guia_trasladoh'];
        $guiaremisionh = GuiaTrasladoH::find($id_guia_trasladoh);
        $guiaremisionh->estado_doc = 4;
        $guiaremisionh->f_reprogramar = $request['f_reprogramar'];
        $guiaremisionh->id_usuario_despachador = $idusuario;
        $guiaremisionh->save();    

        return json_encode(['mensaje' => 200]);
    }

    public function gr_estado_anulado(Request $request){
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idalmacen = '';
        $f_emision = date('Y-m-d');
        $idguia = $request['id_orden_ventah'];
        $tipo_movimiento = '';
        $saved = false;
        $saved1 = false;
        $saved2 = false;  
        $saved3 = false;    

        $guia = GuiaTrasladoH::find($idguia);

        if( $guia->estado_doc != 3 )
        {
            $guia->estado_doc = 3;
            if ($guia->status_ent != -1) {
                $guia->status_ent = 0;
            }

            $idalmacen = $guia->idalmacen;
            $tipo_movimiento = $guia->tipo_movimiento;

            $alreadyNullGr = DB::table('guia_trasladoh')->where([
                ['codigoNB', 'like', $guia->codigoNB.'-%'],
                ['estado_doc', '=', '3']
            ])->orderBy('created_at','desc')->first();
            if($alreadyNullGr){
                $guia->codigoNB = $alreadyNullGr->codigoNB.'-1';
            }
            else{
                $guia->codigoNB = $guia->codigoNB.'-1';
            }

            $saved = $guia->save();

            $cants = [];
            

            if ($guia->motivo_traslado == 1) {
                $isTotal = true;
                foreach ($cants as $key => $value) {
                    if ($value['cantidad_fal'] < $value['cantidad']) {
                        $isTotal = false;
                    }
                }
                
            }

        }else{
            $saved=false;
        }


        if($saved && $saved1 && $saved2)            
            return json_encode(['mensaje' => 200]);
        else
            return json_encode(['mensaje' => 500]);
    }

    public function gr_edit_comments(Request $request) {

        $guia_trasladoh = GuiaTrasladoH::find($request['id_reg']);
        $guia_trasladoh->comentarios = $request['comments'];
        $guia_trasladoh->save();

        return json_encode(['mensaje' => 200]);
    }

    public function gr_detail(Request $request) {

        $detail = DB::table('guia_trasladod')
                        ->where('id_guia_trasladoh',$request['id_guia_trasladoh'])
                        ->select('guia_trasladod.*', 'producto.nombre', 'producto.barcode')
                        ->leftJoin ('producto', 'producto.idproducto', '=', 'guia_trasladod.idproducto')
                        ->get();

        return json_encode($detail);
    }



    public function nextCorrelativoAndCode() {
        $maxCorrelativo = DB::table('guia_trasladoh')
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;

        return ['correlativoG' => strval($nextCorrelativo), 'codigoNB' => 'T002-'.sprintf('%06d', $nextCorrelativo)];
    }

    public function generateDespatchGreen(Request $request) {

        $see = parent::configDespatchGreen();
        $dirGreenter = 'greenter/';

        $client = parent::getClientGreen($request['idcliente']);

        $company = parent::getCompanyGreen();

        $relTipoDoc = '02';
        $relNroDoc = strval($request['numeracion']);

        $transpTipoDoc = $request['transporte_tipo_doc'];
        $transNumDoc = $request['transporte_num_doc'];
        $transRznSocial = $request['transporte_denominacion'];
        $transPlaca = $request['transporte_placa'];
        $transChoferTipoDoc = $request['transporte_chofer_tipo_doc'];
        $transChoferDoc = $request['transporte_chofer_num_doc'];

        $envioCodTraslado = $request['envio_cod_traslado'];
        $envioDescTraslado = $request['envio_desc_traslado'];
        $envioModTraslado = $request['envio_mod_traslado'];
        $envioFecTraslado = $request['envio_fec_traslado'];
        $envioPesoTotal = (float) $request['envio_peso_total'];

        $partidaUbigeo = $request['partida_ubigeo'];
        $texto_llegada_ubigeo= $request['texto_llegada_ubigeo'];
        $texto_partida_ubigeo= $request['texto_partida_ubigeo'];
        $partidaDireccion = strtoupper($request['partida_direccion']);

        $llegadaUbigeo = $request['llegada_ubigeo'];
        $llegadaDireccion = strtoupper($request['llegada_direccion']);

        /*$maxCorrelativo = DB::table('guia_trasladoh')
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;*/

        $tipoDoc = '09';
        $serie = 'T002';
        //$correlativo = strval($nextCorrelativo);
        //$codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
        $correlativo = $request['correlativo'];
        $fechaEmision = $request['fechaNB'];
        $observacion = $request['observacion'];

        

        $motivo = $request['idmotivo'];
        if(!(int)$envioCodTraslado==13){
            $rel = (new Document())
            ->setTipoDoc($relTipoDoc) // Tipo: Numero de Orden de Entrega
            ->setNroDoc($relNroDoc);
        }

        $transp = (new Transportist())
            ->setTipoDoc($transpTipoDoc)
            ->setNumDoc($transNumDoc)
            ->setRznSocial($transRznSocial)
            ->setPlaca($transPlaca)
            ->setChoferTipoDoc($transChoferTipoDoc)
            ->setChoferDoc($transChoferDoc);

        $envio = (new Shipment())
            ->setCodTraslado($envioCodTraslado) // Cat.20
            ->setDesTraslado($envioDescTraslado)
            ->setModTraslado($envioModTraslado) // Cat.18
            ->setFecTraslado(new DateTime($envioFecTraslado.' 12:00:00-05:00'))
            ->setCodPuerto('000')
            ->setIndTransbordo(false)
            ->setPesoTotal($envioPesoTotal)
            ->setUndPesoTotal('KGM')
            ->setNumContenedor('C-000')
            ->setLlegada(new Direction($llegadaUbigeo, $llegadaDireccion))
            ->setPartida(new Direction($partidaUbigeo, $partidaDireccion))
            ->setTransportista($transp);

        $despatch = (new Despatch())
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
            ->setCompany($company)
            ->setDestinatario($client)
            ->setObservacion($observacion)
            ->setEnvio($envio);

        if(!(int)$envioCodTraslado==13){
            $despatch->setRelDoc($rel);
        }


        $productos = json_decode($request['productos']);
        $details = [];
        
        for ($i = 0; $i < count($productos); $i++) {

            $product = Producto::find($productos[$i]->idproducto);
            $description = $product->nombre;
            $codProd = $product->barcode;
            $codSunat = $product->codigo_sunat;
            $cantidad = (int) $productos[$i]->stock_total;

            $detail = (new DespatchDetail())
                ->setCantidad($cantidad)
                ->setUnidad('NIU')
                ->setDescripcion($description)
                ->setCodigo($codProd)
                ->setCodProdSunat($codSunat);

            $details[] = $detail;
        }

        $despatch->setDetails($details);

        // Envío a SUNAT
        $result = $see->send($despatch);

        // Guardar XML firmado digitalmente.
        $xmlFileName = $despatch->getName().'.xml';
        file_put_contents($dirGreenter.$xmlFileName, $see->getFactory()->getLastXml());


        

        $htmlReport = new HtmlReport();
        $htmlReport->setTemplate('despatch.html.twig');
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());
        $pdfFileName = $despatch->getName().'.pdf';

        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png')//, // Logo de Empresa
                //'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'extras'     => parent::getLeyendDoc(),//,
                'link'       => url($dirGreenter.$pdfFileName),
                'ubigeo_llegada'    => $texto_llegada_ubigeo,
                'ubigeo_partida'    => $texto_partida_ubigeo
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($despatch, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

       
        file_put_contents($dirGreenter.$pdfFileName, $pdf);

        // CDR Resultado
        
        
        $cdr = $result->getCdrResponse();
        $cdrStatus=888888888;
        // Verificamos que la conexión con SUNAT fue exitosa.
       
        
       
        if (!$result->isSuccess()) {

            $festore= new Fe;
            $festore->tipo= 9;
            $festore->id_doc=$request['id_doc'];
            $festore->request=json_encode($request->all());
            $festore->save();
            // Mostrar error al conectarse a SUNAT.
            $code=(int)$result->getError()->getCode();
           
            return ['created' => 501, 'msg' => $result->getError()->getCode().' - '.$result->getError()->getMessage(), 'codeG' => strval($code), 'cdrStatus' => $cdrStatus,'xml_file' => $xmlFileName, 'pdf_file' => $pdfFileName,'correlativoG' => $correlativo, 'descriptionG' =>  $result->getError()->getCode().' - '.$result->getError()->getMessage()];
            exit();
        }

        // Guardamos el CDR
        $cdrFileName = 'R-'.$despatch->getName().'.zip';
        file_put_contents($dirGreenter.$cdrFileName, $result->getCdrZip());

        $code = (int)$cdr->getCode();
        $cdrStatus= $code;
        $rechazo=false;
        $msg = '';
        if ($code === 0) {
            $msg = 'ESTADO: ACEPTADA';
        } 
        else if($code === 98){
            $msg = 'ESTADO: ESPERANDO RESPUESTA DE SUNAT';
        }
        else if ($code >= 4000) {
            $msg = 'ESTADO: ACEPTADA CON OBSERVACIONES: ';
            ob_start();
            var_dump($cdr->getNotes());
            $msg .= ob_get_clean();
        } else if ($code >= 2000 && $code <= 3999) {
            $msg = 'ESTADO: RECHAZADA';
            $rechazo=true;

        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $msg = 'Excepción';
            $rechazo=true;
        }

        $msg .= ' '.$cdr->getDescription();

        

        return ['created' => 200, 'msg' => $msg, 'correlativoG' => $correlativo, 'xml_file' => $xmlFileName, 'cdr_file' => $cdrFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' => $msg, 'cdrStatus' => $cdrStatus,'rechazo' => $rechazo];

    }

    public function generatePDF(Request $request) {

        $see = parent::configDespatchGreen();
        $dirGreenter = 'greenter/';

        $client = parent::getClientGreen($request['idcliente']);

        $company = parent::getCompanyGreen();

        $relTipoDoc = '02';
        $relNroDoc = strval($request['numeracion']);

        $transpTipoDoc = $request['transporte_tipo_doc'];
        $transNumDoc = $request['transporte_num_doc'];
        $transRznSocial = $request['transporte_denominacion'];
        $transPlaca = $request['transporte_placa'];
        $transChoferTipoDoc = $request['transporte_chofer_tipo_doc'];
        $transChoferDoc = $request['transporte_chofer_num_doc'];

        $envioCodTraslado = $request['envio_cod_traslado'];
        $envioDescTraslado = $request['envio_desc_traslado'];
        $envioModTraslado = $request['envio_mod_traslado'];
        $envioFecTraslado = $request['envio_fec_traslado'];
        $envioPesoTotal = (float) $request['envio_peso_total'];

        $partidaUbigeo = $request['partida_ubigeo'];
        $partidaDireccion = strtoupper($request['partida_direccion']);
        $texto_llegada_ubigeo= $request['texto_llegada_ubigeo'];
        $texto_partida_ubigeo= $request['texto_partida_ubigeo'];

        $llegadaUbigeo = $request['llegada_ubigeo'];
        $llegadaDireccion = strtoupper($request['llegada_direccion']);

        /*$maxCorrelativo = DB::table('guia_trasladoh')
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;*/

        $tipoDoc = '09';
        $serie = 'T002';
        //$correlativo = strval($nextCorrelativo);
        //$codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
        $correlativo = '13';
        $fechaEmision = $request['fechaNB'];
        $observacion = $request['observacion'];

        $rel = (new Document())
            ->setTipoDoc($relTipoDoc) // Tipo: Numero de Orden de Entrega
            ->setNroDoc($relNroDoc);

        $transp = (new Transportist())
            ->setTipoDoc($transpTipoDoc)
            ->setNumDoc($transNumDoc)
            ->setRznSocial($transRznSocial)
            ->setPlaca($transPlaca)
            ->setChoferTipoDoc($transChoferTipoDoc)
            ->setChoferDoc($transChoferDoc);

        $envio = (new Shipment())
            ->setCodTraslado($envioCodTraslado) // Cat.20
            ->setDesTraslado($envioDescTraslado)
            ->setModTraslado($envioModTraslado) // Cat.18
            ->setFecTraslado(new DateTime($envioFecTraslado.' 12:00:00-05:00'))
            ->setCodPuerto('000')
            ->setIndTransbordo(false)
            ->setPesoTotal($envioPesoTotal)
            ->setUndPesoTotal('KGM')
            ->setNumContenedor('C-000')
            ->setLlegada(new Direction($llegadaUbigeo, $llegadaDireccion))
            ->setPartida(new Direction($partidaUbigeo, $partidaDireccion))
            ->setTransportista($transp);

        $despatch = (new Despatch())
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
            ->setCompany($company)
            ->setDestinatario($client)
            ->setObservacion($observacion)
            ->setRelDoc($rel)
            ->setEnvio($envio);

        $productos = json_decode($request['productos']);
        $details = [];
        
        for ($i = 0; $i < count($productos); $i++) {

            $product = Producto::find($productos[$i]->idproducto);
            $description = $product->nombre;
            $codProd = $product->barcode;
            $codSunat = $product->codigo_sunat;
            $cantidad = (int) $productos[$i]->stock_total;

            $detail = (new DespatchDetail())
                ->setCantidad($cantidad)
                ->setUnidad('NIU')
                ->setDescripcion($description)
                ->setCodigo($codProd)
                ->setCodProdSunat($codSunat);

            $details[] = $detail;
        }

        $despatch->setDetails($details);

        $xmlFileName = $despatch->getName().'.xml';
        file_put_contents($dirGreenter.$xmlFileName, $see->getFactory()->getLastXml());

        

        $htmlReport = new HtmlReport();
        $htmlReport->setTemplate('despatch.html.twig');
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());
        $pdfFileName = $despatch->getName().'.pdf';
        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png')//, // Logo de Empresa
                //'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'extras'     => parent::getLeyendDoc(),
                'link'       => url($dirGreenter.$pdfFileName),
                'ubigeo_llegada'    => $texto_llegada_ubigeo,
                'ubigeo_partida'    => $texto_partida_ubigeo
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($despatch, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }


        file_put_contents($dirGreenter.$pdfFileName, $pdf);

    }

    /* testing greenter */

    public function testGreen() {

        $see = parent::configDespatchGreenTest();
        $dirGreenter = 'greenter/';

        // Cliente
        $client = new Client();
        $client->setTipoDoc('1')
            ->setNumDoc('45634913')
            ->setRznSocial('EMPRESA X');

        // Emisor
        $address = (new Address())
            ->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion('Av. Villa Nueva 221')
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 de lo contrario.

        $company = (new Company())
            ->setRuc('20123456789')
            ->setRazonSocial('GREEN SAC')
            ->setNombreComercial('GREEN')
            ->setAddress($address);

        $rel = (new Document())
            ->setTipoDoc('02') // Tipo: Numero de Orden de Entrega
            ->setNroDoc('213123');

        $transp = (new Transportist())
            ->setTipoDoc('6')
            ->setNumDoc('20000000002')
            ->setRznSocial('TRANSPORTES S.A.C')
            ->setPlaca('ABI-453')
            ->setChoferTipoDoc('1')
            ->setChoferDoc('40003344');

        $envio = (new Shipment())
            ->setCodTraslado('01') // Cat.20
            ->setDesTraslado('VENTA')
            ->setModTraslado('01') // Cat.18
            ->setFecTraslado(new DateTime())
            ->setCodPuerto('123')
            ->setIndTransbordo(false)
            ->setPesoTotal(12.5)
            ->setUndPesoTotal('KGM')
            ->setNumContenedor('XD-2232')
            ->setLlegada(new Direction('150101', 'AV LIMA'))
            ->setPartida(new Direction('150203', 'AV ITALIA'))
            ->setTransportista($transp);

        $despatch = (new Despatch())
            ->setTipoDoc('09')
            ->setSerie('T002')
            ->setCorrelativo('123')
            ->setFechaEmision(new DateTime())
            ->setCompany($company)
            ->setDestinatario($client)
            ->setTercero((new Client())
                ->setTipoDoc('6')
                ->setNumDoc('20000000003')
                ->setRznSocial('EMPRESA SA'))
            ->setObservacion('NOTA GUIA')
            ->setRelDoc($rel)
            ->setEnvio($envio);

        $detail = (new DespatchDetail())
            ->setCantidad(2)
            ->setUnidad('ZZ')
            ->setDescripcion('PROD 1')
            ->setCodigo('PROD1')
            ->setCodProdSunat('P001');

        $despatch->setDetails([$detail]);

        // Envío a SUNAT
        $result = $see->send($despatch);

        // Guardar XML firmado digitalmente.
        file_put_contents($dirGreenter.$despatch->getName().'.xml',
            $see->getFactory()->getLastXml());

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: '.$result->getError()->getCode();
            echo 'Mensaje Error: '.$result->getError()->getMessage();
            exit();
        }

        // Guardamos el CDR
        file_put_contents($dirGreenter.'R-'.$despatch->getName().'.zip', $result->getCdrZip());

        // CDR Resultado
        $cdr = $result->getCdrResponse();

        $code = (int)$cdr->getCode();

        if ($code === 0) {
            echo 'ESTADO: ACEPTADA'.PHP_EOL;
        } else if ($code >= 4000) {
            echo 'ESTADO: ACEPTADA CON OBSERVACIONES:'.PHP_EOL;
            var_dump($cdr->getNotes());
        } else if ($code >= 2000 && $code <= 3999) {
            echo 'ESTADO: RECHAZADA'.PHP_EOL;
        } else {
            /* Esto no debería darse */
            /*code: 0100 a 1999 */
            echo 'Excepción';
        }

        echo $cdr->getDescription().PHP_EOL;

    }
}
