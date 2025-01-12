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
use App\Models\AlmacenLote;
use App\User;
use App\Models\GuiaRemisionH;
use App\Models\Fe;
use App\Models\GuiaRemisionD;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\CajaH;
use Carbon\Carbon;
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

class GuiaRemisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('guia_remision/listado_guia_remision', );
    }
    public function allGR(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1 =>'np',
            2=> 'codigoNB',
            3=> 'created_at',
            4=> 'razon_social',
            5=> 'f_entrega',
            6=> 'f_entregado',
            7=> 'name',
            8=> 'status_ent',
            9=> 'cliente_extra',
            10=> 'numeracion',
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

        $totalData =DB::table('guia_remisionh')->where('guia_remisionh.idempresa',$empresa)->count();
            
        if(!isset($cant))$cant = 1000;
        if(empty($request->input('search.value'))){
            $guia_remisions = DB::table('guia_remisionh')->select('guia_remisionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np')
            ->where('guia_remisionh.idempresa',$empresa)
            ->where('guia_remisionh.created_at', '>=', $f_inicio)
            ->where('guia_remisionh.created_at', '<=', $f_fin)
            ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
            ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();                                 
            $totalFiltered = DB::table('guia_remisionh')->where('guia_remisionh.idempresa',$empresa) ->where('guia_remisionh.created_at', '>=', $f_inicio)
            ->where('guia_remisionh.created_at', '<=', $f_fin)->count();

        }else{
            $search = $request->input('search.value'); 
            $guia_remisions = DB::table('guia_remisionh')->select('guia_remisionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np')
                ->where('guia_remisionh.idempresa',$empresa)
                ->where('guia_remisionh.created_at', '>=', $f_inicio)
                ->where('guia_remisionh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_remisionh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_remisionh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov.codigoNB','like','%'.$search.'%');
                })                
                ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('guia_remisionh')->where('guia_remisionh.idempresa',$empresa)
                ->where('guia_remisionh.idempresa',$empresa)->where('guia_remisionh.created_at', '>=', $f_inicio)
                ->where('guia_remisionh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_remisionh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_remisionh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov.codigoNB','like','%'.$search.'%');
                })->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')->count(); 

        }


        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($guia_remisions))
        {
            foreach ($guia_remisions as $guia_remision)
            {
                $nestedData['numeracion'] =str_pad($guia_remision->numeracion, 6, "0", STR_PAD_LEFT);
                 if (!is_null($guia_remision->xml_file) and $guia_remision->xml_file != '') { 
                    $nestedData['numeracion'] .=" (<a title='{$guia_remision->descriptionG}' href='".url('greenter',$guia_remision->xml_file)."' download>XML</a>)";
                } 
                if (!is_null($guia_remision->cdr_file) and $guia_remision->cdr_file != '') { 
                    $nestedData['numeracion'] .=" (<a title='{$guia_remision->descriptionG}' href='".url('greenter',$guia_remision->cdr_file)."' download>CDR</a>)";
                 } 
                 if (!is_null($guia_remision->pdf_file) and $guia_remision->pdf_file != '') {
                    $nestedData['numeracion'] .=" (<a title='{$guia_remision->descriptionG}' href='".url('greenter',$guia_remision->pdf_file)."' download>PDF</a>)";
                }
                $nestedData['np'] = $guia_remision->np;
                $nestedData['codigoNB'] =  $guia_remision->codigoNB;
                $formato = 'Y-m-d H:i:s';
                $fecha = DateTime::createFromFormat($formato, $guia_remision->created_at);
                $nestedData['created_at'] =date_format($fecha, 'Y-m-d');
                $nestedData['razon_social'] = $guia_remision->razon_social;
                $nestedData['f_entrega'] = $guia_remision->f_entrega;
                $nestedData['f_entregado'] =$guia_remision->f_entregado;
                $nestedData['name'] = $guia_remision->name." ".$guia_remision->lastname;

                if( $guia_remision->codeG > 138 && $guia_remision->codeG < 4000 && $guia_remision->estado_doc==3 ){
                    $nestedData['status_ent_gr'] = '<button class="btn" style="background:#000;color:#fff">RECHAZADO</button>';
                }
                else{
                    if($guia_remision->status_ent == -1) {
                        if($guia_remision->estado_doc == 0) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-danger" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="1" >  Pendiente </button>';
                        }      elseif($guia_remision->estado_doc == 1) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-primary" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="2" > Facturada </button>';
                        }      elseif($guia_remision->estado_doc == 2) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-success" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="3" > Entregada </button>';
                        }      elseif($guia_remision->estado_doc == 4) {
                            $nestedData['status_ent_gr'] = '<button id="status" class="btn btn-info" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="3" > Reprogramada </button>';
                        }      else{
                            $nestedData['status_ent_gr'] ='<button id="status" class="btn btn-secondary" data-id_guia_remisionh="'.$guia_remision->id_guia_remisionh.'" data-status="0" > Anulada </button>';
                        }  
                    } else {
                        $status_ent_gr = Helper::status_ent_gr();
                        $nestedData['status_ent_gr'] = $status_ent_gr[$guia_remision->status_ent];
                        if ( $guia_remision->is_ncp ) {
                            $nestedData['status_ent_gr'] .= ' <i title="NCP" class="icon-exclamation position-center"></i>';
                        }
                    }
                }

                $nestedData['cliente_extra'] =  $guia_remision->ruc_dni.$guia_remision->contacto_nombre.$guia_remision->contacto_telefono;

                $nestedData['acciones'] ='';

                if(!($guia_remision->codeG > 138 && $guia_remision->codeG < 4000 && $guia_remision->estado_doc==3)){
                            if(($guia_remision->codeG <= 138||$guia_remision->codeG >=4000 )  && (is_null($guia_remision->cdr_file)  || $guia_remision->cdr_file=='') && $guia_remision->correlativoG>0 )
                            $nestedData['acciones'] .="<button type='button' class='btn btn-success btn-xs'
                                        id='actualizar' data-id='{$guia_remision->id_guia_remisionh}'>
                                    <i class='glyphicon glyphicon-refresh position-center'></i>
                                </button>";
                            
                                $nestedData['acciones'] .="<button type='button' class='btn btn-info btn-xs'
                                    id='imprimir' data-id='{$guia_remision->id_guia_remisionh}'
                                    data-archivo='{$guia_remision->pdf_file}'>
                                <i class='glyphicon glyphicon-print position-center'></i>
                            </button>
                            <button type='button' class='btn btn-light btn-xs'
                                    id='lotes' data-id='{$guia_remision->id_guia_remisionh}'  >                                 
                                <i class='glyphicon glyphicon-eye-open position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-toggle = 'modal'
                                    id='observacion' data-id='{$guia_remision->id_guia_remisionh}'
                                    data-numeracion = '{$guia_remision->numeracion } '
                                    data-observacion = '{$guia_remision->comentarios } '>
                                <i class='icon-comments position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-id_guia_remisionh   = ' {$guia_remision->id_guia_remisionh} '                                    
                                    data-correlativo         = ' {$guia_remision->numeracion} '
                                    data-codigo              = ' {$guia_remision->codigoNB} '
                                    data-toggle              = 'modal'
                                    id='history'> 
                                <i class='glyphicon glyphicon-time position-center'></i>
                            </button>";
                            if($guia_remision->estado_doc == 0) {
                                $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                    data-id_orden_ventah   = ' {$guia_remision->id_guia_remisionh} '
                                    data-numeracion        = ' {$guia_remision->numeracion} '
                                    data-np                = ' {$guia_remision->codigoNB} '                                    
                                    data-toggle            = 'modal'
                                    id='anular'> 
                                <i class='icon-cancel-square2 position-center'></i>
                            </button>";
                            } 
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
            header('Content-Disposition: attachment;filename="GuiasRemision-SolucionesOGGK.xls"');
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
            0=> 'numeracion',
            1=> 'np',
            2=> 'codigoNB',
            3=> 'created_at',
            4=> 'razon_social',
            5=> 'f_entrega',
            6=> 'f_entregado',
            7=> 'name',
            8=> 'status_ent_gr',
            9=> 'cliente_extra',
            10=> 'numeracion',
        );   
         $data_array [] = array( 
                            "Correlativo", 
                            "Nota de Pedido",
                            "N° NubeFact",
                            "Fecha de Emisión",
                            "Cliente",
                            "Fecha de Entrega",
                            "Fecha de Entregado",
                            "Despachador",
                            "Estado",
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

        $totalData =DB::table('guia_remisionh')->where('guia_remisionh.idempresa',$empresa)->count();
            
        if(!isset($cant))$cant = 1000;
        if(empty($request['search'])){
            $guia_remisions = DB::table('guia_remisionh')->select('guia_remisionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np')
            ->where('guia_remisionh.idempresa',$empresa)
            ->where('guia_remisionh.created_at', '>=', $f_inicio)
            ->where('guia_remisionh.created_at', '<=', $f_fin)
            ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
            ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->orderBy($order,$dir)
            ->get();                                 
            $totalFiltered = DB::table('guia_remisionh')->where('guia_remisionh.idempresa',$empresa)->where('guia_remisionh.created_at', '>=', $f_inicio)->where('guia_remisionh.created_at', '<=', $f_fin)->count();

        }else{
            $search = $request['search']; 
            $guia_remisions = DB::table('guia_remisionh')->select('guia_remisionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np')
                ->where('guia_remisionh.idempresa',$empresa)
                ->where('guia_remisionh.created_at', '>=', $f_inicio)
                ->where('guia_remisionh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_remisionh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_remisionh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov.codigoNB','like','%'.$search.'%');
                })                
                ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                ->orderBy($order,$dir)
                ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('guia_remisionh')->where('guia_remisionh.idempresa',$empresa)->where('guia_remisionh.created_at', '>=', $f_inicio)
            ->where('guia_remisionh.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('guia_remisionh.numeracion','like','%'.$search.'%')
                    ->orWhere('guia_remisionh.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov.codigoNB','like','%'.$search.'%');
                })->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')->count(); 

        }
       
        $formato = 'Y-m-d H:i:s';
        $status_ent_gr =[
            0 => 'ANULADO',
            1 => 'PENDIENTE ENTREGA',
            2 => 'ENTREGA PARCIAL',
            3 => 'ENTREGADO',
            4 => 'NC'
            ];

        foreach($guia_remisions as $guia_remision)
        {
            $fecha = DateTime::createFromFormat($formato, $guia_remision->created_at);
            $est;
            if( $guia_remision->codeG > 138 && $guia_remision->codeG < 4000 && $guia_remision->estado_doc==3 ){
               $est='RECHAZADO';
            }
            else{
                if($guia_remision->status_ent == -1) {
                    if($guia_remision->estado_doc == 0) {
                        $est='Pendiente';
                    }      elseif($guia_remision->estado_doc == 1) {
                        $est='Facturada';
                    }      elseif($guia_remision->estado_doc == 2) {
                        $est='Entregada';
                    }      elseif($guia_remision->estado_doc == 4) {
                        $est='Reprogramada';
                    }      else{
                        $est='Anulada';
                    }  
                } else {
                    
                    $est=$status_ent_gr[$guia_remision->status_ent];
                    if ( $guia_remision->is_ncp ) {
                        $est .= ' NCP';
                    }
                }
            }

            $data_array[] = array(
                'Correlativo' =>str_pad($guia_remision->numeracion, 6, "0", STR_PAD_LEFT), 
                'Nota de Pedido'=> $guia_remision->np,
                'N° NubeFact'=>$guia_remision->codigoNB,               
                'Fecha de Emisión'=>date_format($fecha, 'Y-m-d'),
                'Cliente'=>$guia_remision->razon_social,
                'Fecha de Entrega'=>$guia_remision->f_entrega,
                'Fecha de Entregado'=>$guia_remision->f_entregado,
                'Despachador'=>$guia_remision->name." ".$guia_remision->lastname,
                'Estado'=>$est
            );
        }

        $this->ExportExcel($data_array);
    }

    public function index_detallado()
    {
        $empresa = Auth::user()->idempresa;

        $cajas = DB::table('guia_remisionh')->select('guia_remisionh.*','guia_remisiond.*','producto.nombre','clientes.razon_social', 'users.name', 'users.lastname')
            ->join ('guia_remisiond', 'guia_remisiond.id_guia_remisionh', '=', 'guia_remisionh.id_guia_remisionh')
            ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_remisionh.idvendedor')
            ->join('producto','guia_remisiond.idproducto','=','producto.idproducto')
            ->get();

        return view('guia_remision/listado_guia_remision_detallado', ['cajas' => $cajas]);
    }

    public function guias_pendientes()
    {
        $sucu = Auth::user()->idsucursal;
        $gr_pendientes = DB::table('guia_remisionh')
                    ->select('guia_remisionh.*', DB::raw("IFNULL(guia_remisionh.f_reprogramar,'0000-00-00') as f_reprogramar"),'clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'guia_remisionh.codigoNB', 'ov.codigoNB as np', 'ov.numeracion as numov', 'caja.codigoNB as caja', 'caja.tipo as cajatipo')
                    ->where('guia_remisionh.estado_doc','=',0)
                    ->orWhere('guia_remisionh.estado_doc','=',1)
                    ->orWhere('guia_remisionh.estado_doc','=',4)
                    ->leftjoin ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                    ->leftjoin ('users', 'users.id', '=', 'guia_remisionh.idvendedor')
                    ->leftJoin('cajaguiaventa as cg','cg.idguia','=','guia_remisionh.id_guia_remisionh')
                    ->leftJoin('cajah as caja','caja.idcajah','=','cg.idcaja')
                    ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                    ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$sucu)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        return view('guia_remision/listado_GRpendientes', ['gr_pendientes' => $gr_pendientes])->with('sucursal',$sucursal);
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

        return view('guia_remision/guia_remision')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('despachadores',$despachadores)->with('transportes',$transportes)->with('almacenes',$almacenes)->with('ubigeo', $ubigeo)->with('iduser',$id);
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
                        
        $vendedores = DB::table('users')->where('activated',1)->get();

        $despachadores = DB::table('users')->where('activated',1)->whereIn('puesto', [1,4,6,8,9])->get();     
                    
        $transportes = DB::table('transporte')->get();    

        $almacenes = DB::table('almacen')->get();   

        $ubigeo = DB::table('distritos')
                    ->select('distritos.*', 'provincias.provincia_name', 'departamentos.departamento_name')
                    ->leftJoin('provincias', 'provincias.id', '=', 'distritos.id_provi')
                    ->leftJoin('departamentos', 'departamentos.id', '=', 'distritos.id_depa')
                    ->get();   

        return view('guia_remision/guia_remisionpdf')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('despachadores',$despachadores)->with('transportes',$transportes)->with('almacenes',$almacenes)->with('ubigeo', $ubigeo)->with('iduser',$id);
    }

    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria', DB::raw("SUM(lote.stock_lote) as stockT"))
            
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('producto.state',1)
                    ->orwhere('idsucursal',$sucursal);
            })

            ->Where(function ($query2) use ($busqueda) {
                $query2->where('producto.barcode', 'like', '%'.$busqueda.'%')
                    ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
                    ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%');
            })

            ->leftjoin('lote', 'lote.idproducto', '=', 'producto.idproducto')

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->where('lote.stock_lote', '>', 0)

            ->groupBy('producto.idproducto')
            ->get();

        return json_encode($products);
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

        $orden_ventas->guias = DB::table('guia_remisionh as gr')
            ->select('gr.id_guia_remisionh', 'gr.numeracion', 'gr.codigoNB', 'gr.estado_doc')
            ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'gr.id_guia_remisionh')
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
        $guias = DB::table('guia_remisionh as gr')
            ->select('gr.id_guia_remisionh', 'gr.numeracion', 'gr.codigoNB', 'gr.estado_doc')
            ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'gr.id_guia_remisionh')
            ->where('gr.id_orden_ventah','=', $busqueda)
            ->where('gr.estado_doc','!=',3)
            ->whereNull('cg.idguia')
            ->get();

        return json_encode($guias);
    }

    public function update_codigoNB(Request $request){
        $value = $request['value'];
        $id_guia_remisionh = $request['id_guia_remisionh'];
        $guiaremisionh = GuiaRemisionH::find($id_guia_remisionh);
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
            $almacen_llegada = $request['almacen_llegada'];   
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
                $maximo_num_inside = DB::table('guia_remisionh')
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('correlativo_inside');
                $correlativo_inside = intval($maximo_num_inside) + 1;
                $codigoNB = 'SP-'.sprintf('%06d', $correlativo_inside);
            }
            
            $bool=false;
            $leer_respuesta = [];
            if ( DB::table('guia_remisionh')->where('codigoNB', '=', $codigoNB)->first() ){
                $bool = true;
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 992];
                $respuesta[] = ['id' => 9999999992];
                $respuesta[] = ['leer_respuesta' => $leer_respuesta];
     
                return json_encode($respuesta);   
            }

            $guia_remisionh = new GuiaRemisionH;
            $guia_remisionh->idempresa = $empresa;
            $guia_remisionh->idsucursal = $sucursal;
            $guia_remisionh->motivo_traslado = $motivo;
            $guia_remisionh->idusuario = $idusuario;
            $guia_remisionh->idcliente = $idcliente;
            $guia_remisionh->id_orden_ventah = (((int)$motivo) == 1 ? $id_orden_ventah : 0);
            $guia_remisionh->idvendedor = $idvendedor;
            $guia_remisionh->iddespachador = $iddespachador;
            $guia_remisionh->idtransporte = $idtransporte;
            $guia_remisionh->idalmacen = $almacen;
            /*$guia_remisionh->numero_de_bultos = $num_bultos;
            $guia_remisionh->ubigeo = "tmp";*/

            $maximo_num = DB::table('guia_remisionh')
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('numeracion');

            $guia_remisionh->numeracion = intval($maximo_num) + 1 ;

            $guia_remisionh->paga = $paga;
            $guia_remisionh->vuelto = $vuelto;
            $guia_remisionh->comentarios = $comentarios;
            $guia_remisionh->f_entrega = $f_entrega;
            $guia_remisionh->f_cobro = $f_cobro;
            $guia_remisionh->peso_total = $peso_total;
            $guia_remisionh->codigoNB = $codigoNB;
            $guia_remisionh->fechaNB = $fechaNB;
            $guia_remisionh->correlativoG = 0;
            $guia_remisionh->correlativo_inside = $correlativo_inside;
            $saved = $guia_remisionh->save();

            $productos = json_decode($productos_json);

            $saved1 = false;
            $saved2 = true;
            $saved4 = false;
            $saved5 = false;

            $cants = [];
            if (((int)$motivo) == 1) {
                $detail_orden_ventad = DB::table('orden_ventad')
                                        ->where('id_orden_ventah',$id_orden_ventah)->get();
                
                foreach ($detail_orden_ventad as $key => $value) {
                    $cants[$value->idproducto]['id_orden_ventad'] = $value->id_orden_ventad;
                    $cants[$value->idproducto]['cantidad_fal'] = $value->cantidad_fal;
                }
            }

            $cantidades_orden_ventah = true;
            
            for($i = 0; $i < count($productos); $i++){

                $stockT_suma=0;
                $stockT_tmp = DB::table('lote')->select(DB::raw("SUM(stock_lote) as stockT"))
                    ->where('idproducto', $productos[$i]->idproducto)
                    ->groupBy('idproducto')->first();

                if( $stockT_tmp == null){                   
                    $stockT_suma = 0;
                }else {
                    $stockT_suma = $stockT_tmp->stockT;
                }

                $lote = Lote::find($productos[$i]->idlote);
                if($productos[$i]->stock_total > $lote->stock_lote){
                    DB::rollBack(); // YES --> error de lotes
                    $respuesta = array();
                    $respuesta[] = ['created'=> 999];
                    $respuesta[] = ['id' => 9999999999];
                    $respuesta[] = ['leer_respuesta' => $leer_respuesta];
                    return json_encode($respuesta); 
                }
                //$lote->stock_lote = $lote->stock_lote - $productos[$i]->stock_total;
                //$saved1 = $lote->save();
                $saved1 = true;

                $product = Producto::find($productos[$i]->idproducto);
                //$product->stock_total = $product->stock_total - $productos[$i]->stock_total;
                if (((int)$motivo) == 1) {
                    if (!isset($cants[$productos[$i]->idproducto])) {
                        DB::rollBack(); // YES --> error de lotes
                        $respuesta = array();
                        $respuesta[] = ['created'=> 998];
                        $respuesta[] = ['id' => 9999999999];
                        $respuesta[] = ['leer_respuesta' => $leer_respuesta];
                        return json_encode($respuesta);
                    } else if ($cants[$productos[$i]->idproducto]['cantidad_fal'] < $productos[$i]->stock_total) {
                        DB::rollBack(); // YES --> error de lotes
                        $respuesta = array();
                        $respuesta[] = ['created'=> 997];
                        $respuesta[] = ['id' => 9999999999];
                        $respuesta[] = ['leer_respuesta' => $leer_respuesta];
                        return json_encode($respuesta); 
                        $cantidades_orden_ventah = false;
                        break;
                    } else {
                        $orden_ventad = OrdenVentaD::find($cants[$productos[$i]->idproducto]['id_orden_ventad']);
                        $orden_ventad->cantidad_fal = $orden_ventad->cantidad_fal - $productos[$i]->stock_total;
                        $orden_ventad->save();
                        $cants[$productos[$i]->idproducto]['cantidad_fal'] = $orden_ventad->cantidad_fal;
                    }
                    $saved2 = true;
                } else {
                    $saved2=true;
                    if( ((int)$motivo) != 5 ){
                        $product->stock_imaginario = $product->stock_imaginario - $productos[$i]->stock_total;
                        $saved2 = $product->save(); 
                    }   
                    else{

                        ///salida lote

                        $transacts = new Transacciones;
                        $transacts->idproducto = $productos[$i]->idproducto;
                        $transacts->idempresa = $empresa;
                        $transacts->idsucursal = $sucursal;            
                        $transacts->idusuario = $idusuario;
                        $transacts->idalmacen = $almacen;
                        $transacts->idlote = $productos[$i]->idlote;

                        $transacts->f_emision = date('Y-m-d');
                        $transacts->tipo_documento = 1;
                        $transacts->iddocumento = $guia_remisionh->id_guia_remisionh;          
                        $transacts->tipo = 0;                   
                        
                        $transacts->cantidad = $productos[$i]->stock_total;                         
                        $transacts->stockT = $stockT_suma - $productos[$i]->stock_total;

                        $transacts->tipo_movimiento = $motivo;
                        $transacts->state = 1;        
                        $saved2 = $transacts->save();

                        $lote->stock_lote=  $lote->stock_lote - $productos[$i]->stock_total;

                        $saved2 =  $saved2 && $lote->save();

                        ///entrada nuevo lote
                        $loten = new Lote;
                        $loten->idproducto = $productos[$i]->idproducto;
                        $loten->codigo = strval($lote->codigo)."-".strval($almacen_llegada)."-".Carbon::now()->format('Y-m-d H:i:s');
                        $loten->f_venc = $lote->f_vencimiento;
                        $loten->state = 1;
                        $loten->stock_lote = $productos[$i]->stock_total;  
                        $saved2=$saved2 && $loten->save();
    
    
                        $almacenlote = new AlmacenLote;
                        $almacenlote->idlote = $loten->idlote;
                        $almacenlote->idalmacen = $almacen_llegada;
                        $almacenlote->state = 1;
                        $saved2=$saved2 && $almacenlote->save();  
    
                        $transacts = new Transacciones;
                        $transacts->idproducto = $productos[$i]->idproducto;
                        $transacts->idempresa = $empresa;
                        $transacts->idsucursal = $sucursal;            
                        $transacts->idusuario = $idusuario;
                        $transacts->idalmacen = $almacen_llegada;
                        $transacts->idlote = $loten->idlote;
    
                        $transacts->f_emision = date('Y-m-d');
                        $transacts->tipo_documento = 1;
                        $transacts->iddocumento = $guia_remisionh->id_guia_remisionh;    
                        $transacts->tipo = 1;                   
                        
                        $transacts->cantidad = $productos[$i]->stock_total;                         
                        $transacts->stockT = $productos[$i]->stock_total;
    
                        $transacts->state = 1;               
                        $saved2 = $saved2 && $transacts->save();   

                        $guia_remisionh->status_ent = 3;
                        $guia_remisionh->estado_doc = 2;
                        $saved2 = $saved2 && $guia_remisionh->save(); 
                    }            

                }
                 //$saved2 = $product->save();                
                    
                $guia_remisiond = new GuiaRemisionD;
                $guia_remisiond->id_guia_remisionh = $guia_remisionh->id_guia_remisionh;
                $guia_remisiond->idproducto = $productos[$i]->idproducto;
                $guia_remisiond->cantidad = $productos[$i]->stock_total;
                $guia_remisiond->idlote = $productos[$i]->idlote;
                $guia_remisiond->peso_unit = $productos[$i]->peso;
                $guia_remisiond->peso_total = $productos[$i]->peso * $productos[$i]->stock_total ;
                $guia_remisiond->peso_und = $productos[$i]->peso_und;
                $guia_remisiond->idempresa = $empresa;
                $saved = $guia_remisiond->save();

                /*$transacts = new Transacciones;
                $transacts->idproducto = $productos[$i]->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;            
                $transacts->idusuario = $idusuario;
                $transacts->idalmacen = $almacen;
                $transacts->idlote = $productos[$i]->idlote;

                $transacts->f_emision = date('Y-m-d');
                $transacts->tipo_documento = 1;
                $transacts->iddocumento = $guia_remisionh->id_guia_remisionh;          
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
                    $ov_state = OrdenVentaH::find($id_orden_ventah);
                    if ($isTotal) {
                        $ov_state->estado_doc = 1;
                    } else {
                        $ov_state->estado_doc = 3;
                    }
                    if ($ov_state->status_doc != -1) {
                        $guia_remisionh->status_ent = 1;
                        $ov_state->status_doc = 2;
                        //$ov_state->status_ent = max($ov_state->status_ent, 1);
                        $ov_state->status_ent = 1;
                        /*if ($isTotal) {
                            $ov_state->status_ent = 3;
                        }*/
                        $guia_remisionh->save();
                    }

                    $saved5 = $ov_state->save();
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
                    $request['numeracion'] = $guia_remisionh->numeracion;
                    $request['id_doc'] = $guia_remisionh->id_guia_remisionh;
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

                $guia_remisionh->correlativoG = $correlativoG;
                $guia_remisionh->xml_file = $xml_file;
                if($cdr_file !=''){
                    $guia_remisionh->cdr_file = $cdr_file;
                }                
                $guia_remisionh->pdf_file = $pdf_file;
                $guia_remisionh->codeG = $codeG;
                $guia_remisionh->descriptionG = $descriptionG;
                $guia_remisionh->save();

                DB::commit(); // YES --> finalize it 
                if((int)$codeG>138 &&(int)$codeG<4000){
                    $request['id_orden_ventah']=$guia_remisionh->id_guia_remisionh;
                    $this->gr_estado_anulado($request);
                }
    
                $respuesta = array();
                $respuesta[] = ['created'=> 200];
                $respuesta[] = ['id' => $guia_remisionh->id_guia_remisionh];
                $respuesta[] = ['msg' => $msg];
                $respuesta[] = ['pdf' =>$guia_remisionh->pdf_file];
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
    	$guia_remision =GuiaRemisionH::find($id);
        $festore=Fe::where('id_doc',$id)->where('tipo',9)->first();
        $rucEmisor= '20600819667';        
        if($festore)
            $request=json_decode($festore->request, true);


        $serie='T001';
        $correlativo=strval($guia_remision->correlativoG);
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

            /*$maxCorrelativo = DB::table('guia_remisionh')
                        ->max('correlativoG');
            $nextCorrelativo = intval($maxCorrelativo) + 1;*/

            $tipoDoc = '09';
            $serie = 'T001';
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



        $guia_remision->cdr_file=$cdrFileName;
        $guia_remision->codeG=0;
        $guia_remision->descriptionG= $msg;
        $guia_remision->save();
        //var_dump($cdr);
        $msg = 'El CDR del comprobante '.$serie.'-'.$correlativo.' se recupero correctamente.'.$guia_remision->descriptionG;
        if($festore)
            $festore->delete();
        return  json_encode(['created' => 200, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]); 
        

    }

    public function show(Request $request){
    	$id = $request['id'];

    	$guia_remision = DB::table('guia_remisionh as grh')
                ->select('grh.*','users.name as name','users.lastname as lastname','users.dni as dni','transporte.nombre_trans as nombre_trans','transporte.marca as marca','transporte.placa as placa', 'ov.direccion_entrega', 'ov.email_entrega', 'ov.telefono_entrega')
                ->where('grh.id_guia_remisionh',$id)
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'grh.id_orden_ventah')
                ->join('users','users.id','=','grh.iddespachador')
                ->join('transporte','transporte.idtransporte','=','grh.idtransporte')
                ->first();

    	$guia_remisionD = DB::table('guia_remisiond')
                ->select('guia_remisiond.*','producto.nombre as nn','producto.medida_venta')
                ->where('guia_remisiond.idempresa','=',$guia_remision->idempresa)
                ->where('guia_remisiond.id_guia_remisionh','=',$guia_remision->id_guia_remisionh)
                ->join('producto','guia_remisiond.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$guia_remision->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$guia_remision->idcliente)
                    ->first();

        return view('guia_remision/info_guia_remision')
             ->with('guia_remision',$guia_remision)
             ->with('guia_remisionD',$guia_remisionD)
             ->with('sucursal',$sucursal)
             ->with('cliente',$cliente);

    }
    public function lotes(Request $request){
    	$id = $request['id'];

    	$guia_remision = DB::table('guia_remisionh as grh')
                ->select('grh.*','users.name as name','users.lastname as lastname','users.dni as dni','transporte.nombre_trans as nombre_trans','transporte.marca as marca','transporte.placa as placa', 'ov.direccion_entrega', 'ov.email_entrega', 'ov.telefono_entrega')
                ->where('grh.id_guia_remisionh',$id)
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'grh.id_orden_ventah')
                ->join('users','users.id','=','grh.iddespachador')
                ->join('transporte','transporte.idtransporte','=','grh.idtransporte')
                ->first();

    	$guia_remisionD = DB::table('guia_remisiond')
                ->select('guia_remisiond.*','producto.nombre as nn','producto.medida_venta','lote.codigo','lote.f_venc')
                ->where('guia_remisiond.idempresa','=',$guia_remision->idempresa)
                ->where('guia_remisiond.id_guia_remisionh','=',$guia_remision->id_guia_remisionh)
                ->join('producto','guia_remisiond.idproducto','=','producto.idproducto')
                ->join('lote', 'lote.idlote','=','guia_remisiond.idlote')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$guia_remision->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$guia_remision->idcliente)
                    ->first();

        

        return view('guia_remision/lotes_guia_remision')
             ->with('guia_remision',$guia_remision)
             ->with('guia_remisionD',$guia_remisionD)
             ->with('sucursal',$sucursal)
             ->with('cliente',$cliente);

    }

     public function gr_estado(Request $request){
        $idusuario = Auth::user()->id;
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;

        $id_guia_remisionh = $request['id_guia_remisionh'];
        $guiaremisionh = GuiaRemisionH::find($id_guia_remisionh);
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

            $productos = DB::table('guia_remisiond')->where('id_guia_remisionh',$id_guia_remisionh)->get();
            $idProds = [];
            $prods = json_decode($request['productos']);

            for($i = 0; $i < count($prods); $i++){
                $guia_remisiond = GuiaRemisionD::find($prods[$i]->id);
                $guia_remisiond->cantidad_ent += $prods[$i]->cantidad_entregada;
                $guia_remisiond->save();
                $idProds[$prods[$i]->id] = $prods[$i]->cantidad_entregada;
            }

            for($i = 0; $i < count($productos); $i++){

                $product = Producto::find($productos[$i]->idproducto);
                //$product->stock_total = $product->stock_total - $productos[$i]->cantidad;
                $product->stock_total = $product->stock_total - $idProds[$productos[$i]->id_guia_remisiond];
                $product->save();

                $stockT_suma=0;
                $stockT_tmp = DB::table('lote')->select(DB::raw("SUM(stock_lote) as stockT"))
                    ->where('idproducto', $productos[$i]->idproducto)
                    ->groupBy('idproducto')->first();

                if( $stockT_tmp == null){                   
                    $stockT_suma = 0;
                }else {
                    $stockT_suma = $stockT_tmp->stockT;
                }

                $lote = Lote::find($productos[$i]->idlote);
                //$lote->stock_lote = $lote->stock_lote - $productos[$i]->cantidad;
                $lote->stock_lote = $lote->stock_lote - $idProds[$productos[$i]->id_guia_remisiond];
                $lote->save();                    

                if ($idProds[$productos[$i]->id_guia_remisiond] > 0) {
                    $transacts = new Transacciones;
                    $transacts->idproducto = $productos[$i]->idproducto;
                    $transacts->idempresa = $empresa;
                    $transacts->idsucursal = $sucursal;            
                    $transacts->idusuario = $idusuario;
                    $transacts->idalmacen = $idalmacen;
                    $transacts->idlote = $productos[$i]->idlote;

                    $transacts->f_emision = date('Y-m-d');
                    $transacts->tipo_documento = 1;
                    $transacts->iddocumento = $id_guia_remisionh;          
                    $transacts->tipo = 0;                   
                    
                    //$transacts->cantidad = $productos[$i]->cantidad;
                    $transacts->cantidad = $idProds[$productos[$i]->id_guia_remisiond];
                    $transacts->stockT = $stockT_suma - $idProds[$productos[$i]->id_guia_remisiond];

                    $transacts->tipo_movimiento = $tipo_movimiento;
                    $transacts->state = 1;               
                    $transacts->save();
                }

            }

            if ($guiaremisionh->status_ent != -1) {
                $min_status_guias = DB::table('guia_remisionh')
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

        $id_guia_remisionh = $request['id_guia_remisionh'];
        $guiaremisionh = GuiaRemisionH::find($id_guia_remisionh);
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

        $guia = GuiaRemisionH::find($idguia);

        if( $guia->estado_doc != 3 )
        {
            $guia->estado_doc = 3;
            if ($guia->status_ent != -1) {
                $guia->status_ent = 0;
            }

            $idalmacen = $guia->idalmacen;
            $tipo_movimiento = $guia->tipo_movimiento;

            $alreadyNullGr = DB::table('guia_remisionh')->where([
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
                $detail_orden_ventad = DB::table('orden_ventad')
                                        ->where('id_orden_ventah',$guia->id_orden_ventah)->get();
                
                foreach ($detail_orden_ventad as $key => $value) {
                    $cants[$value->idproducto]['id_orden_ventad'] = $value->id_orden_ventad;
                    $cants[$value->idproducto]['cantidad'] = $value->cantidad;
                    $cants[$value->idproducto]['cantidad_fal'] = $value->cantidad_fal;
                }
            }

            $productos = DB::table('guia_remisiond')->where('id_guia_remisionh',$idguia)->get();

            for($i = 0; $i < count($productos); $i++){

                $product = Producto::find($productos[$i]->idproducto);
                //$product->stock_total = $product->stock_total + $productos[$i]->cantidad;
                if ($guia->motivo_traslado == 1) {
                    $orden_ventad = OrdenVentaD::find($cants[$productos[$i]->idproducto]['id_orden_ventad']);
                    $orden_ventad->cantidad_fal = $orden_ventad->cantidad_fal + $productos[$i]->cantidad;
                    $orden_ventad->save();
                    $cants[$productos[$i]->idproducto]['cantidad_fal'] = $orden_ventad->cantidad_fal;
                    $saved1 = true;
                } else {
                    $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->cantidad;
                    $saved1 = $product->save();
                }

                /*$stockT_suma=0;
                $stockT_tmp = DB::table('lote')->select(DB::raw("SUM(stock_lote) as stockT"))
                    ->where('idproducto', $productos[$i]->idproducto)
                    ->groupBy('idproducto')->first();

                if( $stockT_tmp == null){                   
                    $stockT_suma = 0;
                }else {
                    $stockT_suma = $stockT_tmp->stockT;
                }

                $lote = Lote::find($productos[$i]->idlote);

                $lote->stock_lote = $lote->stock_lote + $productos[$i]->cantidad;
                $saved1 = $lote->save();                    

                $transacts = new Transacciones;
                $transacts->idproducto = $productos[$i]->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;            
                $transacts->idusuario = $idusuario;
                $transacts->idalmacen = $idalmacen;
                $transacts->idlote = $productos[$i]->idlote;

                $transacts->f_emision = date('Y-m-d');
                $transacts->tipo_documento = 1;
                $transacts->iddocumento = $idguia;          
                $transacts->tipo = 1;                   
                
                $transacts->cantidad = $productos[$i]->cantidad;                         
                $transacts->stockT = $stockT_suma + $productos[$i]->cantidad;

                $transacts->tipo_movimiento = $tipo_movimiento;
                $transacts->state = 1;               
                $saved2 = $transacts->save();*/
                $saved2 = true;
            }

            if ($guia->motivo_traslado == 1) {
                $isTotal = true;
                foreach ($cants as $key => $value) {
                    if ($value['cantidad_fal'] < $value['cantidad']) {
                        $isTotal = false;
                    }
                }
                $ov_state = OrdenVentaH::find($guia->id_orden_ventah);
                if ($isTotal) {
                    $ov_state->estado_doc = 0;
                } else {
                    $ov_state->estado_doc = 3;
                }
                if ($guia->status_ent != -1) {
                    $max_status_guias = DB::table('guia_remisionh')->where('id_orden_ventah', $guia->id_orden_ventah)->max('status_ent');
                    if ($max_status_guias == 0) { //controla las guias que existen y los estados de la OV al anular todas las guias (añadir lo de las facturas)
                        $ov_state->status_ent = -1;
                        $ov_state->status_doc = 1;
                    } else {
                        $ov_state->status_ent = $max_status_guias;
                    }
                }
                $ov_state->save();
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

        $guia_remisionh = GuiaRemisionH::find($request['id_reg']);
        $guia_remisionh->comentarios = $request['comments'];
        $guia_remisionh->save();

        return json_encode(['mensaje' => 200]);
    }

    public function gr_detail(Request $request) {

        $detail = DB::table('guia_remisiond')
                        ->where('id_guia_remisionh',$request['id_guia_remisionh'])
                        ->select('guia_remisiond.*', 'producto.nombre', 'producto.barcode')
                        ->leftJoin ('producto', 'producto.idproducto', '=', 'guia_remisiond.idproducto')
                        ->get();

        return json_encode($detail);
    }

    public function gr_history(Request $request) {

        $transacts = DB::table('transacciones')
                    ->where('transacciones.tipo_documento', 1)
                    ->where('transacciones.tipo', 0)
                    ->where('transacciones.iddocumento', $request['id_guia_remisionh'])
                    ->select('transacciones.*', 'producto.nombre', 'producto.barcode')
                    ->leftJoin('producto', 'producto.idproducto', '=', 'transacciones.idproducto')
                    ->orderBy('transacciones.f_emision', 'desc')
                    ->get();

        return json_encode($transacts);
    }

    public function nextCorrelativoAndCode() {
        $maxCorrelativo = DB::table('guia_remisionh')
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;

        return ['correlativoG' => strval($nextCorrelativo), 'codigoNB' => 'T001-'.sprintf('%06d', $nextCorrelativo)];
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

        /*$maxCorrelativo = DB::table('guia_remisionh')
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;*/

        $tipoDoc = '09';
        $serie = 'T001';
        //$correlativo = strval($nextCorrelativo);
        //$codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
        $correlativo = $request['correlativo'];
        $fechaEmision = $request['fechaNB'];
        $observacion = $request['observacion'];

        

        $motivo = $request['idmotivo'];
        if(!(int)$envioCodTraslado==13 && !(int)$envioCodTraslado==4){
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

        /*$maxCorrelativo = DB::table('guia_remisionh')
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;*/

        $tipoDoc = '09';
        $serie = 'T001';
        //$correlativo = strval($nextCorrelativo);
        //$codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
        $correlativo = '44';
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
            ->setSerie('T001')
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
