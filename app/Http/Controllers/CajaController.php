<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
use App\Models\Fe;
use App\Models\CajaH;
use App\Models\CajaD;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\CajaGuia;
use App\Models\GuiaRemisionH;
use App\Models\GuiaRemisionD;
use App\Models\PagoRecibido;
use App\Models\CuotaCaja;
use Auth;
use DB;
use Helper;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Document;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\See;
use DateTime;

use Luecano\NumeroALetras\NumeroALetras;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {     
        $sucu = Auth::user()->idsucursal;      
        $guias = [];
        /*$guias = DB::table('guia_remisionh as gr')
            ->select('gr.id_guia_remisionh', 'gr.numeracion', 'gr.codigoNB')
            ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'gr.id_guia_remisionh')
            ->whereNull('cg.idguia')
            ->get();*/

        $sucursal = DB::table('sucursales')
            ->where('sucursales.idsucursal', '=', $sucu)
            ->join('empresas', 'sucursales.idempresa', '=', 'empresas.idempresa')
            ->first(); 
        return view('caja/listado_caja')->with('guias',$guias)->with('sucursal',$sucursal);
    }

    public function allFB(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1 =>'tipo',
            2=> 'notapedido',
            3=> 'grnum',
            4=> 'codigoNB',
            5=> 'created_at',
            6=> 'razon_social',
            7=> 'clientes.ruc_dni',
            8=> 'subtotal',
            9=> 'igv',
            10=> 'total',
            11=> 'moneda',
            12=> 'name',
            13=> 'cajah.codeG',
            14=> 'status_cob',
            15=> 'numeracion',
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

        //SELECT * from `cajah` left join `clientes` ON`cajah`.idcliente = `clientes`.idcliente left join `users` ON `cajah`.idvendedor = `users`.id left join `cajaguiaventa` as cg ON `cajah`.idcajah = `cg`.idcaja left join `guia_remisionh` as g ON `cg`.idguia = `g`.id_guia_remisionh left join `orden_ventah` as ov2 ON `g`.id_orden_ventah = `ov2`.id_orden_ventah where `cajah`.idempresa=1

     
        $subQuery=DB::table('cajaguiaventa')
        ->select('cajaguiaventa.*','guia_remisionh.codigoNB', 'guia_remisionh.estado_doc')
        ->leftjoin('guia_remisionh', 'cajaguiaventa.idguia' , '=',  'guia_remisionh.id_guia_remisionh')
        ;

        $totalData =DB::table('cajah')->select('cajah.id')->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
       ->where('cajah.idempresa',$empresa)->count();


        $empresa = Auth::user()->idempresa;
        $sucu = Auth::user()->idsucursal;

        // $cant = $request['cant'];
        // $query = $request['query'];
        if(Auth::user()->puesto!=15){
        if(empty($request->input('search.value'))){
            $cajas =DB::table('cajah')->select(
                'cajah.*',
                'clientes.razon_social',
                'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                'cajah.idvendedor',
                'users.name',
                'users.lastname',
                'g.codigoNB as grnum',
                'cajah.codigoNB as cnb',
                'g.estado_doc as g_estado',
                DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                )
                ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
                ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
               
                ->where('cajah.idempresa', $empresa)
                ->where('cajah.created_at', '>=', $f_inicio)
                ->where('cajah.created_at', '<=', $f_fin)
               
                
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalFiltered = DB::table('cajah')->where('cajah.idempresa',$empresa)->where('cajah.created_at', '>=', $f_inicio)->where('cajah.created_at', '<=', $f_fin)
                ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )->count();
        } else {
            $search = $request->input('search.value');
            $cajas =DB::table('cajah')->select(
                'cajah.*',
                'clientes.razon_social',
                'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                'cajah.idvendedor',
                'users.name',
                'users.lastname',
                'g.codigoNB as grnum',
                'cajah.codigoNB as cnb',
                'g.estado_doc as g_estado',
                DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                )
                ->where('cajah.idempresa', $empresa)
                ->where('cajah.created_at', '>=', $f_inicio)
                ->where('cajah.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('cajah.numeracion','like','%'.$search.'%')
                    ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    ->orWhere('g.codigoNB','like','%'.$search.'%')
                    ->orWhere('users.name','like','%'.$search.'%')
                    ->orWhere('cajah.total','like','%'.$search.'%')
                    ->orWhere('clientes.ruc_dni','like','%'.$search.'%')

                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov2.codigoNB','like','%'.$search.'%');
                }) 
                ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalFiltered = DB::table('cajah')->where('cajah.idempresa', $empresa)
                ->where('cajah.created_at', '>=', $f_inicio)
                ->where('cajah.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('cajah.numeracion','like','%'.$search.'%')
                    ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    ->orWhere('g.codigoNB','like','%'.$search.'%')
                    ->orWhere('users.name','like','%'.$search.'%')
                    ->orWhere('cajah.total','like','%'.$search.'%')
                    ->orWhere('clientes.ruc_dni','like','%'.$search.'%')

                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov2.codigoNB','like','%'.$search.'%');
                }) 
                ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )->count(); 
        }
        }else{
            if(empty($request->input('search.value'))){
                $cajas =DB::table('cajah')->select(
                    'cajah.*',
                    'clientes.razon_social',
                    'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                    'cajah.idvendedor',
                    'users.name',
                    'users.lastname',
                    'g.codigoNB as grnum',
                    'cajah.codigoNB as cnb',
                    'g.estado_doc as g_estado',
                    DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                    DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                    DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                )
                    ->where('cajah.idempresa', $empresa)
                    ->where('cajah.correlativoG','>',0)
                    ->where('cajah.created_at', '>=', $f_inicio)
                    ->where('cajah.created_at', '<=', $f_fin)
                    ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                    $totalFiltered = DB::table('cajah')->where('cajah.idempresa',$empresa)->where('cajah.created_at', '>=', $f_inicio)
                    ->where('cajah.created_at', '<=', $f_fin)->where('cajah.correlativoG','>',0)->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                    ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
                    ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
                    ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )->count();
            } else {
                $search = $request->input('search.value');
                $cajas = DB::table('cajah')->select(
                    'cajah.*',
                    'clientes.razon_social',
                    'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                    'cajah.idvendedor',
                    'users.name',
                    'users.lastname',
                    'g.codigoNB as grnum',
                    'cajah.codigoNB as cnb',
                    'g.estado_doc as g_estado',
                    DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                    DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                    DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                    )
                    ->where('cajah.idempresa', $empresa)
                    ->where('cajah.correlativoG','>',0)
                    ->where('cajah.created_at', '>=', $f_inicio)
                    ->where('cajah.created_at', '<=', $f_fin)
                    ->where(function ($query) use ($search)  {
                        $query->where('cajah.numeracion','like','%'.$search.'%')
                        ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                        ->orWhere('clientes.razon_social','like','%'.$search.'%')
                        ->orWhere('g.codigoNB','like','%'.$search.'%')
                        ->orWhere('users.name','like','%'.$search.'%')
                        ->orWhere('cajah.total','like','%'.$search.'%')
                        ->orWhere('clientes.ruc_dni','like','%'.$search.'%')
    
                        //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                        ->orWhere('ov2.codigoNB','like','%'.$search.'%');
                    }) 
                    ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                    ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
                    ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
                    ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                $totalFiltered = DB::table('cajah')->where('cajah.idempresa', $empresa)
                ->where('cajah.correlativoG','>',0)
                ->where('cajah.created_at', '>=', $f_inicio)
                ->where('cajah.created_at', '<=', $f_fin)
                ->where(function ($query) use ($search)  {
                    $query->where('cajah.numeracion','like','%'.$search.'%')
                    ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                    ->orWhere('clientes.razon_social','like','%'.$search.'%')
                    ->orWhere('g.codigoNB','like','%'.$search.'%')
                    ->orWhere('users.name','like','%'.$search.'%')
                    ->orWhere('cajah.total','like','%'.$search.'%')
                    ->orWhere('clientes.ruc_dni','like','%'.$search.'%')

                    //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                    ->orWhere('ov2.codigoNB','like','%'.$search.'%');
                }) 
                ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
                ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
                ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )->count(); 
            }

        }        

        $data = array();
        if(!empty($cajas))
        {
            foreach ($cajas as $caja)
            {          
                $nestedData['numeracion']=str_pad($caja->numeracion, 6, "0", STR_PAD_LEFT) ;
                $nestedData['tipo']= $caja->tipo == 2 ? 'Factura' : 'Boleta';
                            if (!is_null($caja->xml_file) and $caja->xml_file != '') { 
                                $nestedData['tipo'] .=" (<a title='{$caja->descriptionG}' href='".url('greenter',$caja->xml_file)."' download>XML</a>)";
                            } 
                            if (!is_null($caja->cdr_file) and $caja->cdr_file != '') { 
                                $nestedData['tipo'] .=" (<a title='{$caja->descriptionG}' href='".url('greenter',$caja->cdr_file)."' download>CDR</a>)";
                             } 
                             if (!is_null($caja->pdf_file) and $caja->pdf_file != '') {
                                $nestedData['tipo'] .=" (<a title='{$caja->descriptionG}' href='".url('greenter',$caja->pdf_file)."' download>PDF</a>)";
                            }
                $nestedData['np']=$caja->notapedido.' (';
                              if($caja->ov_estado == 0) {
                                $nestedData['np'].= 'Pe)';
                            }      elseif($caja->ov_estado == 1) {
                                $nestedData['np'].= 'F)';
                            }      elseif($caja->ov_estado == 3) {
                                $nestedData['np'].='Pa)';
                            }      else {
                                $nestedData['np'].='A)';
                            }                   
                $nestedData['grnum']=$caja->grnum.' ('; 
                            if($caja->g_estado == 0) {
                                $nestedData['grnum'].='Pe)';
                            }      elseif($caja->g_estado == 1) {
                                $nestedData['grnum'].= 'F)';
                            }      elseif($caja->g_estado == 2) {
                                $nestedData['grnum'].= 'E)';
                            }      elseif($caja->g_estado == 4) {
                                $nestedData['grnum'].= 'R)';
                            }      else{
                                $nestedData['grnum'].= 'A)';
                            }
                    $nestedData['codigoNB']=$caja->codigoNB;
                    $nestedData['created_at']= str_limit($caja->created_at, $limit = 10, $end='');
                    $nestedData['razon_social']=$caja->razon_social;
                    $nestedData['ruc_dni'] =$caja->ruc_dni;
                    $nestedData['subtotal']='';
                    $nestedData['igv']='';
                    $nestedData['total']='';
                    $moneda_tipo;
                        if($caja->moneda == 1){$nestedData['total'].= 'S/ '; $nestedData['subtotal'].= 'S/ '; $nestedData['igv'].= 'S/ '; $moneda_tipo="Soles"; }
                                    else if($caja->moneda == 2){ $nestedData['total'].= '$'; $nestedData['subtotal'].= '$'; $nestedData['igv'].= '$';  $moneda_tipo="Dólares"; } 
                                    else{ $nestedData['total'].= '€'; $nestedData['subtotal'].= '€'; $nestedData['igv'].= '€';  $moneda_tipo="Euros"; }
                                    $nestedData['subtotal'].=number_format((float)$caja->subtotal, 2, '.', '');
                                    $nestedData['igv'].=number_format((float)$caja->igv, 2, '.', '');
                                    $nestedData['total'].=number_format((float)$caja->total, 2, '.', '');
                 $nestedData['moneda']= $moneda_tipo;
                 $nestedData['name']= $caja->name.' '.$caja->lastname;

                 $nestedData['aceptada']='NO';
                 if((int)$caja->correlativoG >0){
                 $nestedData['aceptada']='SI';
                 }

                 $nestedData['status']='';
                            $query='ESTADO: RECHAZADA';
                            if( $caja->codeG > 0 && $caja->codeG < 4000 && $caja->estado_doc==3 ){
                                $nestedData['aceptada']='NO';
                                $nestedData['status'].='<button class="btn" style="background:#000;color:#fff">RECHAZADO</button>';
                            }
                            else{
                                if($caja->status_cob == -1) {
                                    if($caja->estado_doc == 0) {
                                        $nestedData['status'].='<button id="status" class="btn btn-danger" data-idcajah="'.$caja->idcajah.'" data-status="1" >  Pendiente </button>';
                                    }      elseif($caja->estado_doc == 1) {
                                        $nestedData['status'].='<button id="status" class="btn btn-danger" data-idcajah="'.$caja->idcajah.'" data-status="1" >  Pendiente </button>';
                                    }      elseif($caja->estado_doc == 2) {
                                        $nestedData['status'].='<button id="status" class="btn btn-success" data-idcajah="'.$caja->idcajah.'" data-status="3" > Cancelada </button>';
                                    }      elseif($caja->estado_doc == 4) {
                                        $nestedData['status'].='<button id="status" class="btn btn-info" data-idcajah="'.$caja->idcajah.'" data-status="3" > NCP </button>';
                                    }      elseif($caja->estado_doc == 5) {
                                        $nestedData['status'].='<button id="status" class="btn btn-primary" data-idcajah="'.$caja->idcajah.'" data-status="3" > NCT </button>';
                                    }      elseif($caja->estado_doc == 6) {
                                        $nestedData['status'].='<button id="status" class="btn btn-warning" data-idcajah="'.$caja->idcajah.'" data-status="3" > Descuento </button>';
                                    }      else{
                                        $nestedData['status'].='<button id="status" class="btn btn-secondary" data-idcajah="'.$caja->idcajah.'" data-status="0" > Anulada </button>';
                                    }  
                                
                                }
                                else {
                                    $status_cob_fc = Helper::status_cob_fc();
                                    $nestedData['status'].=$status_cob_fc[$caja->status_cob];
                                    if ( $caja->is_ncp ) {
                                        $nestedData['status'].=' <i title="NCP" class="icon-exclamation position-center"></i>';
                                    }                       
                                }
                            }
                            $nestedData['acciones'] ='';
                            if(!($caja->codeG > 0 && $caja->codeG < 4000 && $caja->estado_doc==3)){
                                if(($caja->codeG <=138||$caja->codeG >=4000 )  && (is_null($caja->cdr_file)  || $caja->cdr_file=='') && $caja->correlativoG>0){
                                    $nestedData['acciones'] .="<button type='button' class='btn btn-success btn-xs'
                                        id='actualizar' data-id='{$caja->idcajah}'>
                                        <i class='glyphicon glyphicon-refresh position-center'></i>
                                        </button>";
                                }
                                $nestedData['acciones'] .="<button type='button' class='btn btn-info btn-xs'
                                        id='imprimir' data-id='{$caja->idcajah}'
                                        data-archivo='{$caja->pdf_file}'>
                                    <i class='glyphicon glyphicon-print position-center'></i>
                                </button>
                                <button type='button' class='btn btn-info btn-xs'
                                        data-toggle = 'modal'
                                        id='observacion' data-id='{$caja->idcajah}'
                                        data-numeracion = '{$caja->numeracion }'
                                        data-observacion = '{$caja->comentarios } '
                                        data-tipo           = ' { $caja->tipo } '>
                                    <i class='icon-comments position-center'></i>
                                </button>";
                                
                                if (($caja->status_cob == -1 and $caja->estado_doc != 3) or $caja->correlativo_inside != 0) { 
                                    $nestedData['acciones'] .="<button type='button' class='btn btn-primary btn-xs'
                                        data-idcajah        = ' {$caja->idcajah} '
                                        data-idordenventah    = '{$caja->id_orden_ventah}'
                                        data-tipo           = ' {$caja->tipo} '
                                        data-numeracion     = ' {$caja->numeracion} '
                                        data-toggle         = 'modal'
                                        id='agregar_guia'> 
                                    <i class='glyphicon glyphicon-plus position-center'></i>
                                </button>";
                                } 
                                if(!(($caja->codeG ==0||$caja->codeG >=4000 )  && (is_null($caja->cdr_file)  || $caja->cdr_file=='') && $caja->correlativoG>0)){
                                    if(($caja->status_cob == -1 and $caja->estado_doc != 3) or ($caja->status_cob != 0 and $caja->status_cob != 4 and !$caja->is_ncp)) { 
                                        if($caja->correlativo_inside != 0) { 
                                            $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                                data-idcajah    = '{$caja->idcajah} '
                                                data-numeracion2 = '{$caja->numeracion} '
                                                data-np         = '{$caja->codigoNB} '
                                                data-tipo2           = ' {$caja->tipo } '
                                                data-toggle     = 'modal'
                                                id='anular2' title='Anular operacion o documento'> 
                                            <i class='icon-cancel-square2 position-center'></i>
                                        </button>";
                                   } else { 
                                    $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                            data-idcajah    = '{$caja->idcajah} '
                                            data-numeracion2 = '{$caja->numeracion} '
                                            data-np         = '{$caja->codigoNB} '
                                            data-tipo2           = ' {$caja->tipo} '
                                            data-toggle     = 'modal'
                                            id='anular' title='Nota de credito'> 
                                            <i class='icon-cancel-square' position-center'></i>
                                        </button>";
                                     } 
                             } 
                                 if ($caja->status_cob == 1 && $caja->correlativo_inside == 0) { 
                                    $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                        data-idcajah    = '{$caja->idcajah} '
                                        data-numeracion2 = '{$caja->numeracion} '
                                        data-np         = '{$caja->codigoNB} '
                                        data-tipo2           = ' { $caja->tipo } '
                                        data-toggle     = 'modal'
                                        id='baja' title='Comunicación de baja'> 
                                        <i class='icon-cancel-square position-center'></i>
                                    </button>";
                                 } 
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
            header('Content-Disposition: attachment;filename="FacturasBoletas-SolucionesOGGK.xls"');
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
            1 =>'tipo',
            2=> 'notapedido',
            3=> 'grnum',
            4=> 'codigoNB',
            5=> 'created_at',
            6=> 'razon_social',
            7=> 'clientes.ruc_dni',
            8=> 'subtotal',
            9=> 'igv',
            10=> 'total',
            11=> 'moneda',
            12=> 'name',
            13=> 'cajah.codeG',
            14=> 'status_cob',
            15=> 'numeracion',
        );
         $data_array [] = array( 
                            "Correlativo",
                            "Tipo",
                            "Nota de Pedido",
                            "N° Guia Remision (NF)",
                            "N° NubeFact",
                            "Fecha de Emisión",
                            "Cliente",
                            "RUC/DNI",
                            "Base Imponible",
                            "IGV",
                            "Total",
                            "Tipo de Moneda",
                            "Vendedor",
                            "Aceptada por SUNAT",
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
        
        $subQuery=DB::table('cajaguiaventa')
        ->select('cajaguiaventa.*','guia_remisionh.codigoNB', 'guia_remisionh.estado_doc')
        ->leftjoin('guia_remisionh', 'cajaguiaventa.idguia' , '=',  'guia_remisionh.id_guia_remisionh')
        ;
            
        if(Auth::user()->puesto!=15){
            if(empty($request['search'])){
                $cajas =DB::table('cajah')->select(
                    'cajah.*',
                    'clientes.razon_social',
                    'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                    'cajah.idvendedor',
                    'users.name',
                    'users.lastname',
                    'g.codigoNB as grnum',
                    'cajah.codigoNB as cnb',
                    'g.estado_doc as g_estado',
                    DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                    DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                    DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                    )
                    ->where('cajah.idempresa', $empresa)
                    ->where('cajah.created_at', '>=', $f_inicio)
                    ->where('cajah.created_at', '<=', $f_fin)
                    ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                    ->orderBy($order,$dir)
                    ->get();
                   
            } else {
                $search = $request['search'];
                $cajas =DB::table('cajah')->select(
                    'cajah.*',
                    'clientes.razon_social',
                    'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                    'cajah.idvendedor',
                    'users.name',
                    'users.lastname',
                    'g.codigoNB as grnum',
                    'cajah.codigoNB as cnb',
                    'g.estado_doc as g_estado',
                    DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                    DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                    DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                    )
                    ->where('cajah.idempresa', $empresa)
                    ->where('cajah.created_at', '>=', $f_inicio)
                    ->where('cajah.created_at', '<=', $f_fin)
                    ->where(function ($query) use ($search)  {
                        $query->where('cajah.numeracion','like','%'.$search.'%')
                        ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                        ->orWhere('clientes.razon_social','like','%'.$search.'%')
                        ->orWhere('g.codigoNB','like','%'.$search.'%')
                        ->orWhere('users.name','like','%'.$search.'%')
                        //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                        ->orWhere('ov2.codigoNB','like','%'.$search.'%')
                        ->orWhere('cajah.total','like','%'.$search.'%')
                        ->orWhere('clientes.ruc_dni','like','%'.$search.'%');

                    }) 
                    ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                    ->orderBy($order,$dir)
                    ->get();
    
                }
            }else{
                if(empty($request['search'])){
                    $cajas =DB::table('cajah')->select(
                        'cajah.*',
                        'clientes.razon_social',
                        'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                        'cajah.idvendedor',
                        'users.name',
                        'users.lastname',
                        'g.codigoNB as grnum',
                        'cajah.codigoNB as cnb',
                        'g.estado_doc as g_estado',
                        DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                        DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                        DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                    )
                        ->where('cajah.idempresa', $empresa)
                        ->where('cajah.correlativoG','>',0)
                        ->where('cajah.created_at', '>=', $f_inicio)
                        ->where('cajah.created_at', '<=', $f_fin)
                        ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
                        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
                        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
                        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                        ->orderBy($order,$dir)
                        ->get();
                   
                } else {
                    $search = $request['search'];
                    $cajas = DB::table('cajah')->select(
                        'cajah.*',
                        'clientes.razon_social',
                        'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
                        'cajah.idvendedor',
                        'users.name',
                        'users.lastname',
                        'g.codigoNB as grnum',
                        'cajah.codigoNB as cnb',
                        'g.estado_doc as g_estado',
                        DB::raw("IFNULL(ov2.estado_doc,ov2.estado_doc) as ov_estado"),
                        DB::raw("IFNULL(ov2.codigoNB,ov2.codigoNB) as notapedido"),
                        DB::raw("round(IFNULL(cajah.total_nc,cajah.total),2) as total")
                        )
                        ->where('cajah.idempresa', $empresa)
                        ->where('cajah.correlativoG','>',0)
                        ->where('cajah.created_at', '>=', $f_inicio)
                        ->where('cajah.created_at', '<=', $f_fin)
                        ->where(function ($query) use ($search)  {
                            $query->where('cajah.numeracion','like','%'.$search.'%')
                            ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                            ->orWhere('clientes.razon_social','like','%'.$search.'%')
                        ->orWhere('g.codigoNB','like','%'.$search.'%')
                        ->orWhere('users.name','like','%'.$search.'%')
                        ->orWhere('cajah.total','like','%'.$search.'%')
                        ->orWhere('clientes.ruc_dni','like','%'.$search.'%')

    
                            //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                            ->orWhere('ov2.codigoNB','like','%'.$search.'%');
                        }) 
                        ->leftjoin('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
        ->leftjoin('users', 'cajah.idvendedor', '=', 'users.id' )
        ->leftjoin(DB::raw('(' . $subQuery->toSql() . ') g'), 'cajah.idcajah', '=', 'g.idcaja')
        ->leftjoin('orden_ventah as ov2', 'cajah.id_orden_ventah' , '=', 'ov2.id_orden_ventah' )
                        ->orderBy($order,$dir)
                        ->get();
                }
    
            }      
       
        $formato = 'Y-m-d H:i:s';
        $status_cob_fc =[
            0 => 'ANULADO',
            1 => 'PENDIENTE ENTREGA',
            2 => 'ENTREGA PARCIAL',
            3 => 'ENTREGADO',
            4 => 'NC'
            ];

        foreach($cajas as $caja)
        {
            $aceptada_SUNAT='NO';
            $fecha = DateTime::createFromFormat($formato, $caja->created_at);
            if((int)$caja->correlativoG>0)
                $aceptada_SUNAT='SI';
            $est;
            if( $caja->codeG > 0 && $caja->codeG < 4000 && $caja->estado_doc==3 ){
                $aceptada_SUNAT='NO';
                $est='RECHAZADO';
            }
            else{
                if($caja->status_cob == -1) {
                    if($caja->estado_doc == 0) {
                        $est='Pendiente';
                    }      elseif($caja->estado_doc == 1) {
                        $est=' Pendiente';
                    }      elseif($caja->estado_doc == 2) {
                        $est='Cancelada';
                    }      elseif($caja->estado_doc == 4) {
                        $est='NCP';
                    }      elseif($caja->estado_doc == 5) {
                        $est='NCT';
                    }      elseif($caja->estado_doc == 6) {
                        $est='Descuento';
                    }      else{
                        $est='Anulada';
                    }  
                
                }
                else {
                    $est=$status_cob_fc[$caja->status_cob];
                    if ( $caja->is_ncp ) {
                        $est.='NCP';
                    }                       
                }
            }
            $moneda_tipo;
            if($caja->moneda == 1){$moneda_tipo="Soles"; }
            else if($caja->moneda == 2){ $moneda_tipo="Dólares"; } 
            else{ $moneda_tipo="Euros"; }

            $data_array[] = array(
                'Correlativo' =>str_pad($caja->numeracion, 6, "0", STR_PAD_LEFT), 
                "Tipo" => $caja->tipo == 2? 'Factura' : 'Boleta',
                'Nota de Pedido'=> $caja->notapedido,
                'N° Guia Remision (NF)' =>$caja->grnum,
                'N° NubeFact'=>$caja->codigoNB,               
                'Fecha de Emisión'=>date_format($fecha, 'Y-m-d'),
                'Cliente'=>$caja->razon_social,
                'RUC/DNI'=>$caja->ruc_dni,
                'Base Imponible' => round($caja->subtotal,2),
                'IGV' => round($caja->igv,2),
                'Total' => round($caja->total,2),
                'Tipo de Moneda' => $moneda_tipo,
                'Vendedor'=>$caja->name." ".$caja->lastname,
                'Aceptada por SUNAT' => $aceptada_SUNAT,
                'Estado'=>$est
            );
        }

        $this->ExportExcel($data_array);
    }

    public function index_detallado()
    {
        $empresa = Auth::user()->idempresa;

        $cajas = DB::table('cajah')->select('cajah.*', 'cajad.*', 'producto.nombre', 'clientes.razon_social', 'users.name', 'users.lastname', 'cajah.codigoNB as cnb')
            ->join('cajad', 'cajad.idcajah', '=', 'cajah.idcajah')
            ->join('clientes', 'cajah.idcliente', '=', 'clientes.idcliente')
            ->join('users', 'users.id', '=', 'cajah.idvendedor')
            ->join('producto', 'cajad.idproducto', '=', 'producto.idproducto')
            ->get();

        return view('caja/listado_caja_detallado', ['cajas' => $cajas]);
    }

    public function crear()
    {
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $id = Auth::user()->id;

        $products = Producto::where('idempresa', $empresa)
            ->where('idsucursal', $sucursal)
            ->where('state', 1)
            ->get();

        $usuario = DB::table('users')
            ->where('id', $id)
            ->join('sucursales', 'users.idsucursal', '=', 'sucursales.idsucursal')
            ->join('empresas', 'users.idempresa', '=', 'empresas.idempresa')
            ->first();

        $vendedores = DB::table('users')->where('tienda_user', 0)->get();

        $almacenes = DB::table('almacen')->get();

        /*$guias = DB::table('guia_remisionh as gr')
            ->select('gr.id_guia_remisionh', 'gr.numeracion', 'gr.codigoNB')
            ->leftJoin('cajaguiaventa as cg', 'cg.idguia', '=', 'gr.id_guia_remisionh')
            ->whereNull('cg.idguia')
            ->get();*/
        $guias = [];

        return view('caja/caja')->with('products', $products)->with('usuario', $usuario)->with('vendedores', $vendedores)->with('almacenes', $almacenes)->with('guias', $guias);
    }

    public function buscar_producto(Request $request)
    {
        $busqueda = $request['query'];
        $almacen = $request['almacen'];

        $products = DB::table('almacenlote as a')
            ->select('producto.*', 'categorias.descripcion as categoria', 'a.idalmacen', DB::raw("SUM(l.stock_lote) as stockT"))

            ->leftjoin('lote as l', 'a.idlote', '=', 'l.idlote')
            ->leftjoin('producto', 'l.idproducto', '=', 'producto.idproducto')
            ->where('a.idalmacen', '=', $almacen)
            ->where('l.stock_lote', '>', 0)

            //->Where(function ($query) {
            //$sucursal = Auth::user()->idsucursal;
            //$query->where('producto.stock_total','>=',1)
            //->orwhere('producto.state',1)
            //->orwhere('idsucursal',$sucursal);
            //})

            ->Where(function ($query2) use ($busqueda) {
                $query2->where('producto.barcode', 'like', '%' . $busqueda . '%')
                    ->orwhere('producto.nombre', 'like', '%' . $busqueda . '%')
                    ->orwhere('categorias.descripcion', 'like', '%' . $busqueda . '%');
            })

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->groupBy('producto.idproducto')
            ->get();

        return json_encode($products);
    }

    public function buscar_lote(Request $request)
    {
        $busqueda = $request['query'];
        $almacen = $request['almacen'];
        $lotes = DB::table('lote')
            ->select('lote.idlote', 'lote.codigo', 'lote.stock_lote', 'lote.f_venc')
            ->join('almacenlote', 'almacenlote.idlote', '=', 'lote.idlote')
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('lote.stock_lote', '>=', 1);
            })
            ->where('almacenlote.idalmacen', '=', $almacen)
            ->where('lote.idproducto', '=', $busqueda)
            ->orderBy('f_venc', 'asc')
            ->get();

        return json_encode($lotes);
    }

    public function buscar_cliente(Request $request)
    {
        $busqueda = $request['query'];
        $clientes = DB::table('clientes')
            ->where('razon_social', 'like', '%' . $busqueda . '%')
            ->orwhere('ruc_dni', 'like', '%' . $busqueda . '%')
            // ->orwhere('dni',$busqueda)
            // ->orwhere('ruc',$busqueda)
            ->get();
        return json_encode($clientes);
    }

    public function buscar_gr_todo(Request $request)
    {
        $busqueda = $request['query'];
        $orden_ventas = DB::table('guia_remisionh')
            ->select('ov.*', 'c.razon_social')
            ->join('clientes as c', 'guia_remisionh.idcliente', '=', 'c.idcliente')
            ->join('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->where('guia_remisionh.id_guia_remisionh', '=', $busqueda)
            ->first();

        $orden_ventas->detalle = DB::table('guia_remisionh')
            ->select('orden_ventad.*', 'p.*')
            ->join('orden_ventad', 'orden_ventad.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->join('producto as p', 'p.idproducto', '=', 'orden_ventad.idproducto')
            ->where('guia_remisionh.id_guia_remisionh', '=', $busqueda)
            ->get();

        return json_encode($orden_ventas);
    }

    public function store(Request $request)
    {
        $msg = '';
        DB::beginTransaction(); // <-- first line  

        try {

            $empresa = Auth::user()->idempresa;
            $sucursal = Auth::user()->idsucursal;
            $idusuario = Auth::user()->id;

            $idcliente = $request['idcliente'];
            $id_orden_ventah = $request['id_orden_ventah'];
            $idcajah = $request['idcajah'];
            $subtotal = $request['subtotal'];
            $igv = $request['igv'];
            $total = $request['total'];
            $paga = $request['paga'];
            $vuelto = $request['vuelto'];
            $descuento = $request['descuento'];
            $comentarios = $request['comentarios'];
            $f_entrega = $request['f_entrega'];
            $f_cobro = $request['f_cobro'];
            $idvendedor = $request['idvendedor'];

            $productos_json = $request['productos'];
            $cuotas_json = $request['cuotas'];
            $tipo_recibo = $request['tipo']; // 1 boleta , 2 factura;
            $almacen = $request['almacen'];
            $moneda = $request['moneda'];
            $codigoNB = $request['codigoNB'];
            $fechaNB = $request['fechaNB'];
            $tipo_cambio = $request['tipo_cambio'];
            $forma_pago = $request['forma_pago'];
            $correlativo_inside = 0;
            $state = 1;

            $saved  = false;
            $saved1 = false;
            $saved2 = false;
            $saved3 = false;

            $is_invoice = $request['is_invoice'];
            $xml_file = '';
            $cdr_file = '';
            $pdf_file = '';
            $codeG = '';
            $descriptionG = '';
            $correlativoG = 0;

            if ($is_invoice == '0') {
                
                $resp = $this->generateInvoiceGreen($request);

                if ($resp['created'] == 501) {                                                       
                    //return json_encode([$resp]);
                    $msg = $resp['msg'];
                    $codigoNB = $resp['codigoNB'];
                    $xml_file = $resp['xml_file'];
                    $pdf_file = $resp['pdf_file'];
                    $correlativoG = $resp['correlativoG'];
                    $codeG = $resp['codeG'];
                    $descriptionG = $resp['descriptionG'];

                }else if ($resp['created'] == 502) {                    
                    return json_encode([$resp]);

                } else {
                    $msg = $resp['msg'];
                    $codigoNB = $resp['codigoNB'];
                    $xml_file = $resp['xml_file'];
                    $cdr_file = $resp['cdr_file'];
                    $pdf_file = $resp['pdf_file'];
                    $correlativoG = $resp['correlativoG'];
                    $codeG = $resp['codeG'];
                    $descriptionG = $resp['descriptionG'];
                }
            } else {
                $maximo_num_inside = DB::table('cajah')
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('correlativo_inside');
                $correlativo_inside = intval($maximo_num_inside) + 1;
                $codigoNB = 'VD-'.sprintf('%06d', $correlativo_inside);
            }

            $bool = DB::table('cajah')->where('codigoNB', '=', $codigoNB)->first();

            if (!$bool) {
                $cajah = new CajaH;
                $cajah->idempresa = $empresa;
                $cajah->idsucursal = $sucursal;
                $cajah->idusuario = $idusuario;
                $cajah->idcliente = $idcliente;
                $cajah->idvendedor = $idvendedor;
                $cajah->id_orden_ventah = $id_orden_ventah;

                $maximo_num = DB::table('cajah')
                    ->where('tipo', $tipo_recibo)
                    ->where('idempresa', $empresa)
                    ->where('idsucursal', $sucursal)
                    ->max('numeracion');

                $cajah->numeracion = intval($maximo_num) + 1;

                $cajah->paga = $paga;
                $cajah->igv = $igv;
                $cajah->vuelto = $vuelto;
                $cajah->descuento = $descuento;
                $cajah->subtotal = $subtotal;
                $cajah->total = $total;
                $cajah->tipo = $tipo_recibo;
                $cajah->comentarios = $comentarios;
                $cajah->f_entrega = $f_entrega;
                $cajah->f_cobro = $f_cobro;
                $cajah->moneda = $moneda;
                $cajah->codigoNB = $codigoNB;
                $cajah->fechaNB = $fechaNB;
                $cajah->tipo_cambio = $tipo_cambio;
                $cajah->correlativoG = $correlativoG;
                $cajah->xml_file = $xml_file;
                $cajah->cdr_file = $cdr_file;
                $cajah->pdf_file = $pdf_file;
                $cajah->codeG = $codeG;
                $cajah->descriptionG = $descriptionG;
                $cajah->status_cob=1;
                $cajah->descriptionG = $descriptionG;
                $cajah->correlativo_inside = $correlativo_inside;

                $saved = $cajah->save();
                if ($is_invoice == '0') {
                if ($resp['created'] == 501) { 
                    $festore= new Fe;
                    $festore->tipo= 1;
                    $festore->id_doc=$cajah->idcajah;
                    $festore->request=json_encode($request->all());
                    $festore->save();
                }
                }

                $productos = json_decode($productos_json);

                for ($i = 0; $i < count($productos); $i++) {

                    $cajad = new CajaD;
                    $cajad->idcajah = $cajah->idcajah;
                    $cajad->idproducto = $productos[$i]->idproducto;
                    $cajad->cantidad = $productos[$i]->stock_total;
                    $cajad->precio_unit = $productos[$i]->precio;
                    $cajad->precio_total = $productos[$i]->precio * $productos[$i]->stock_total;
                    $cajad->idempresa = $empresa;
                    $saved1 = $cajad->save();

                    // $transacts = new Transacciones;
                    // $transacts->idproducto = $productos[$i]->idproducto;
                    // $transacts->cantidad = $productos[$i]->stock_total;
                    // $transacts->tipo = 0;
                    // $transacts->state = 1;
                    // $transacts->idsucursal = $sucursal;
                    // $transacts->idempresa = $empresa;
                    // $transacts->idcajah = $cajah->idcajah;
                    // $saved = $transacts->save();

                }

                $cuotas = json_decode($cuotas_json);
                for ($i = 0; $i < count($cuotas); $i++) {
                    $cuota = new CuotaCaja;
                    $cuota->idcajah = $cajah->idcajah;
                    $cuota->monto = $cuotas[$i]->monto;
                    $cuota->f_cuota = $cuotas[$i]->fecha;
                    $cuota->save();
                }

                $idtmp = $cajah->idcajah;
                $guias_select = $request['guias_select'];

                if ($saved && $saved1 && $guias_select != null) {
                    for ($i = 0; $i < count($guias_select); $i++) {
                        $cajaguia = new CajaGuia;
                        $cajaguia->idcaja = $idtmp;
                        $cajaguia->idguia = $guias_select[$i];
                        $cajaguia->state = 1;
                        $saved2 = $cajaguia->save();

                        $gr_state = GuiaRemisionH::find($guias_select[$i]);
                        $gr_state->estado_doc = 1;
                        $saved3 = $gr_state->save();
                    }
                } else if ($saved && $saved1 && $guias_select == null) {
                    $detail_orden_ventad = DB::table('orden_ventad')
                                        ->where('id_orden_ventah',$id_orden_ventah)
                                        ->select('orden_ventad.*', 'producto.tipo')
                                        ->leftJoin('producto', 'producto.idproducto', '=', 'orden_ventad.idproducto')->get();

                    $isServiceOrden = true;
                    foreach ($detail_orden_ventad as $key => $value) {
                        if ($value->tipo != 2) {
                            $isServiceOrden = false;
                        }
                    }
                    if ($isServiceOrden) {
                        $ov_state = OrdenVentaH::find($id_orden_ventah);
                        $ov_state->estado_doc = 1;
                        $ov_state->save();
                    }
                }
            } else {
                $saved = false;
                $saved1 = false;
            }

            if ($saved && $saved1) {
                $saved2 = true;
                $saved3 = true;
            }

            if ($saved && $saved1 && $saved2 && $saved3){
                $childModelSaved=true;
                $ov_state = OrdenVentaH::find($id_orden_ventah);
                if($ov_state->status_doc !=-1){
                    $ov_state->status_doc=3;
                    $ov_state->status_cob=1;
                    $childModelSaved = $childModelSaved && $ov_state->save();
                }
            }
                
            else
                $childModelSaved = false;
        } catch (Exception $e) {
            $childModelSaved = false;
        }

        if ($childModelSaved) {
            DB::commit(); // YES --> finalize it 
            $respuesta = array();
            $respuesta[] = ['created' => 200];
            $respuesta[] = ['id' => $cajah->idcajah];
            $respuesta[] = ['msg' => $msg];
            $respuesta[] = ['pdf' => $cajah->pdf_file];
            $respuesta[] = ['code' =>$codeG];
            if ($is_invoice == '0') {
            if($resp['created']==501){
                if((int)$codeG==0 ||(int)$codeG>=4000){
                    $respuesta[] = ['reenviar' =>'si'];
                }
                else{
                    $respuesta[] = ['reenviar' =>'no'];
                }
            }
            else{
                $respuesta[] = ['reenviar' =>'no'];
            }
            
            
            if($resp['rechazo']){
                $request['idcajah']=$cajah->idcajah;
                $this->anularCajaRechazada($request);
            }
            }
            else{
                $respuesta[] = ['reenviar' =>'no'];
            }
            

            return json_encode($respuesta);
        } else {
            DB::rollBack(); // NO --> error de lotes
            $respuesta = array();
            $respuesta[] = ['created' => 500];
            $respuesta[] = ['id' => 9999];

            return json_encode($respuesta);
        }
    }

    public function anularCajaRechazada(Request $request) {
        $msg = '';
        DB::beginTransaction(); // <-- first line  

        try {

            $caja = CajaH::find($request['idcajah']);
            $caja->nulled_at = date('Y-m-d H:i:s');
            $caja->estado_doc = 3;
            $caja->status_cob = 0;
            $caja->save();   

            $id_ov = $caja->id_orden_ventah;
            $min_status_fact = DB::table('cajah')
                                    ->where('id_orden_ventah', $id_ov)
                                    ->where('status_cob', '!=', 0)
                                    ->where('status_cob', '!=', $request['idcajah'])
                                    ->min('status_cob');

            $ov = OrdenVentaH::find($id_ov);
            if (intval($min_status_fact) == 0) {
                $min_status_fact = -1;
                $ov->status_doc = 2;
            }
            $ov->status_cob = $min_status_fact;
            $ov->save();

            CajaGuia::where('idcaja', $request['idcajah'])->delete();           

            $childModelSaved = true;

        } catch (Exception $e) {
            $childModelSaved = false;
        }

        if ($childModelSaved) {
            DB::commit(); // YES --> finalize it 
            $respuesta = array();
            $respuesta[] = ['created' => 200];
            return json_encode($respuesta);
        } else {
            DB::rollBack(); // NO --> error de lotes
            $respuesta = array();
            $respuesta[] = ['created' => 500];
            return json_encode($respuesta);
        }


    }

    public function checkCDRFB(Request $request){
        $id =$request['id'];

        $dirGreenter = 'greenter/';
        $tipoDocumento='01';
    	$factura =CajaH::find($id);
        $festore=Fe::where('id_doc',$id)->where('tipo',1)->first();
        $rucEmisor= '20600819667';   
        if($festore)
        $request=json_decode($festore->request, true);     

        $request['idcajah']=$id;

        $serie = 'F001';
        if ($factura->tipo == '1' or $factura->tipo == 1) {
            $tipoDocumento = '03';
            $serie = 'B001';
        }
       

        $correlativo=strval($factura->correlativoG);
        $result= parent::checkCDR($rucEmisor,$tipoDocumento,$serie,$correlativo);

        
        if (!$result->isSuccess()) {
            $see = parent::configInvoiceNoteGreen();
            $dirGreenter = 'greenter/';
            //$dirGreenter = base_path().'/public/greenter/';
    
            // Cliente
            $client = parent::getClientGreen($request['idcliente']);
            if ($client->getTipoDoc() == '1' and ($request['tipo'] == '2' or $request['tipo'] == 2)) {
                return ['created' => 502, 'msg' => 'El cliente necesita un RUC para emitir una factura electrónica'];
            }
    
            // Emisor
            $company = parent::getCompanyGreen();    
            
    
            $tipoDoc = '01';
            $serie = 'F001';
            if ($request['tipo'] == '1' or $request['tipo'] == 1) {
                $tipoDoc = '03';
                $serie = 'B001';
            }
            $correlativo = strval($correlativo);
            $codigoNB = $serie.'-'.sprintf('%06d', $correlativo);
            $arrayTipoMoneda = [1 => ['PEN', 'soles'], 2 => ['USD', 'dolares americanos'], 3 => ['EUR', 'euros']];
            $tipoMoneda = $arrayTipoMoneda[(int)$request['moneda']][0];
            $currency = $arrayTipoMoneda[(int)$request['moneda']][1];
            $envio = (float) $request['envio'];
            $envioBI= $envio*100/118; ///Comentar para envio exonerado
            $igvenvio = $envio - $envioBI;    ///Comentar para envio exonerado
            $montoGravada = (float) $request['subtotal'];
            $montoIgv = (float) $request['igv'];
            $subTotal = $montoGravada + $montoIgv+ $envio; 
            $descuento = (float) $request['descuento'];
            $montoImpVenta = $subTotal - $descuento;
            $fechaEmision = $request['fechaNB'];
            $fechaVencimiento = $request['f_cobro'];
            $formaPago = (int)$request['forma_pago'];
    
            // Venta
            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc($tipoDoc) // Factura - Catalog. 01
                ->setSerie($serie)
                ->setCorrelativo($correlativo) // Calcular correlativo
                ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
                ->setFecVencimiento(new DateTime($fechaVencimiento.' 12:00:00-05:00'))
                //->setFormaPago(new FormaPagoContado())
                ->setTipoMoneda($tipoMoneda) // Sol - Catalog. 02
                ->setCompany($company)
                ->setClient($client)               
                //->setMtoOperGravadas($montoGravada) ///Desomentar para envio exonerado
                ->setMtoOperGravadas($montoGravada+$envioBI)
                //->setMtoIGV($montoIgv)
                ->setMtoIGV($montoIgv+$igvenvio)
                //->setTotalImpuestos($montoIgv)
                ->setTotalImpuestos($montoIgv+$igvenvio)
                //->setValorVenta($montoGravada+$envio)
                ->setValorVenta($montoGravada+$envioBI)
                ->setSubTotal($subTotal)
                ->setMtoImpVenta($montoImpVenta);
                


            if($envio>0){
                //$invoice->setMtoOperExoneradas($envio);
                $flete = (new SaleDetail())
                ->setCodProducto('78101802')
                ->setCodProdSunat('78101802')
                ->setUnidad('NIU') // Unidad - Catalog. 03
                ->setDescripcion('ENVIO')
                ->setCantidad(1)
                /*->setMtoValorUnitario($envio)
                ->setMtoValorVenta($envio)
                ->setMtoBaseIgv($envio)
                ->setPorcentajeIgv(0)
                ->setIgv(0)
                ->setTipAfeIgv('20') // Exonerada Op. Onerosa - Catalog. 07
                ->setTotalImpuestos(0)
                ->setMtoPrecioUnitario($envio);*/
                ->setMtoValorUnitario($envioBI)
                ->setMtoValorVenta($envioBI)
                ->setMtoBaseIgv($envioBI)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($igvenvio)
                ->setTipAfeIgv('10') // Exonerada Op. Onerosa - Catalog. 07
                ->setTotalImpuestos($igvenvio)
                ->setMtoPrecioUnitario($envio);
                
            }
                
    
            if ($formaPago == 0) {
                $invoice->setFormaPago(new FormaPagoContado());
            } else {
                $montoCuotas = 0;
                $cuotas = json_decode($request['cuotas']);
                $cuotaItems = [];
                for ($i = 0; $i < count($cuotas); $i++) {
                    $montoCuota = (float)$cuotas[$i]->monto;
                    $fechaCuota = $cuotas[$i]->fecha;
                    $montoCuotas = $montoCuotas + $montoCuota;
                    $cuotaItem = (new Cuota())
                                ->setMonto($montoCuota)
                                ->setFechaPago(new DateTime($fechaCuota.' 12:00:00-05:00'));
                    $cuotaItems[] = $cuotaItem;
                }
                $invoice->setFormaPago(new FormaPagoCredito($montoCuotas))
                        ->setCuotas($cuotaItems);
            }
    
            if ($descuento > 0) {
                $invoice->setDescuentos([
                            (new Charge())
                                ->setCodTipo('03') // Catalog. 53 (03: Descuento global que no afecta la Base Imponible)
                                ->setMontoBase($descuento)
                                ->setFactor(1)
                                ->setMonto($descuento) // Mto Dscto
                        ])
                        ->setSumOtrosDescuentos($descuento); // suma descuentos que no afectan la base (dscto. global)
            }
    
            $productos = json_decode($request['productos']);
            $items = [];
    
            $guias_select = $request['guias_select'];
    
            if ($guias_select != null) {
                $guias = [];
                for ($i = 0; $i < count($guias_select); $i++) {
                    $guiaR = GuiaRemisionH::find($guias_select[$i]);
                    $guia = (new Document())
                            ->setTipoDoc('09')
                            ->setNroDoc($guiaR->codigoNB);
                    $guias[] = $guia;
                }
                $invoice->setGuias($guias);
            }
            
            for ($i = 0; $i < count($productos); $i++) {
    
                $product = Producto::find($productos[$i]->idproducto);
                $description = $product->nombre;
                $codProd = $product->barcode;
                $codSunat = $product->codigo_sunat;
                $cantidad = (int) $productos[$i]->stock_total;
                $montoValorUnit = ((float) $productos[$i]->precio * 100) / 118.00;
                $montoValorVenta = $cantidad * $montoValorUnit;
                $porcentajeIgv = 18.00;
                $igv = ($montoValorVenta * $porcentajeIgv) / 100.00;
                $montoPrecioUnit = $montoValorUnit + ($igv / $cantidad);
    
                $item = (new SaleDetail())
                    ->setCodProducto($codProd)
                    ->setCodProdSunat($codSunat)
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setDescripcion($description)
                    ->setCantidad($cantidad)
                    ->setMtoValorUnitario($montoValorUnit)
                    ->setMtoValorVenta($montoValorVenta)
                    ->setMtoBaseIgv($montoValorVenta)
                    ->setPorcentajeIgv($porcentajeIgv) // 18%
                    ->setIgv($igv)
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos($igv)
                    ->setMtoPrecioUnitario($montoPrecioUnit);
    
                $items[] = $item;
            }
            if($envio>0){
                $items[] = $flete;
            }
    
            $formatter = new NumeroALetras();
    
            $legend = (new Legend())
                ->setCode('1000') // Monto en letras - Catalog. 52
                ->setValue($formatter->toInvoice($montoImpVenta, 2, $currency));
    
            $invoice->setDetails($items)
                ->setLegends([$legend]);
    
            // Envío a SUNAT
            $result = $see->send($invoice);
            
            if (!$result->isSuccess()) {
                $msg = 'Error no se conectó: ';
                ob_start();
                var_dump($result->getError());
                $msg .= ob_get_clean(); 
                return  json_encode(['created' => 500, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]);    
            }           
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
            $this->anularCajaRechazada($request);
        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $msg = 'Excepción';
            $this->anularCajaRechazada($request);
        }

        $msg .= ' '.$cdr->getDescription();

        $factura->cdr_file=$cdrFileName;
        $factura->codeG=0;
        
        $factura->descriptionG= $msg;


        $factura->save();
        //var_dump($cdr);
        $msg = 'El CDR del comprobante '.$serie.'-'.$correlativo.' se recupero correctamente.'.$factura->descriptionG;
        if($festore)
            $festore->delete();
        return  json_encode(['created' => 200, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]); 
        

    }

    public function show(Request $request)
    {
        $id = $request['id'];

        $caja = DB::table('cajah')
            ->where('idcajah', $id)
            ->first();

        $guias = DB::table('guia_remisionh')
            ->select('guia_remisionh.numeracion as correlativo')
            ->join('cajaguiaventa', 'cajaguiaventa.idguia', '=', 'guia_remisionh.id_guia_remisionh')
            ->where('cajaguiaventa.idcaja', '=', $caja->idcajah)
            ->get();

        $cajaD = DB::table('cajad')
            ->select('cajad.*', 'producto.*', DB::raw("SUM(cajad.cantidad) as canti"))
            ->where('cajad.idempresa', '=', $caja->idempresa)
            ->where('cajad.idcajah', '=', $caja->idcajah)
            ->join('producto', 'cajad.idproducto', '=', 'producto.idproducto')
            ->groupBy('cajad.idproducto')
            ->get();

        $sucursal = DB::table('sucursales')
            ->where('sucursales.idsucursal', '=', $caja->idsucursal)
            ->join('empresas', 'sucursales.idempresa', '=', 'empresas.idempresa')
            ->first();

        $cliente = DB::table('clientes')
            ->where('idcliente', '=', $caja->idcliente)
            ->first();

        return view('caja/info_caja')
            ->with('caja', $caja)
            ->with('guias', $guias)
            ->with('cajaD', $cajaD)
            ->with('sucursal', $sucursal)
            ->with('cliente', $cliente);
    }

    public function status(Request $request)
    {
        $id = $request['id'];
        $status = $request['status'];
        $cajah = CajaH::find($id);
        $cajah->estado_doc = $status;
        $cajah->save();
        return response()->json(['accepted'], 202);
    }

    public  function enlazar_guias(Request $request)
    {
        $idcajah = $request['idcajah'];
        $guias_select = $request['guias_select'];

        for ($i = 0; $i < count($guias_select); $i++) {
            $cajaguia = new CajaGuia;
            $cajaguia->idcaja = $idcajah;
            $cajaguia->idguia = $guias_select[$i];
            $cajaguia->state = 1;
            $saved = $cajaguia->save();

            $gr_state = GuiaRemisionH::find($guias_select[$i]);
            $gr_state->estado_doc = 1;
            $saved = $gr_state->save();
        }

        return json_encode(['mensaje' => 200]);
    }

    public function caja_estado(Request $request)
    {
        $idusuario = Auth::user()->id;
        $idcajah = $request['idcajah'];
        $var = "-1";
        $cajah = CajaH::find($idcajah); // elemento a eliminar 
        if($cajah->estado_doc == 3){
            return json_encode(['mensaje' => 200, 'message' => 'already null']);
        }
        $alreadyNullCajah = DB::table('cajah')->where([
            ['codigoNB', 'like', '%'.$cajah->codigoNB.'%'],
            ['estado_doc', '=', '3']
        ])->orderBy('nulled_at','desc')->first(); // elemento previamente eliminado (estado 3), ordenamos por fecha de anulacion 
                                                // (campo nuevo: nulled_at), y obtenemos el   primero
        if($alreadyNullCajah){
            $cajah->codigoNB = $alreadyNullCajah->codigoNB.'-1';
        }
        else{
            $cajah->codigoNB = $cajah->codigoNB.'-1';
        }
        $cajah->nulled_at = date('Y-m-d H:i:s');
        $cajah->estado_doc = 3;
        $cajah->nulled_by = $idusuario;
        if ($cajah->status_cob != -1) {
            $cajah->status_cob = 0;
        }
        $cajah->save();

        //if ($cajah->status_cob != -1) {
        $pagos_recibidos = PagoRecibido::where('idcajah', $idcajah)
                    ->update([
                        'total' => 0,
                        'pagado' => 0,
                        'por_pagar' => 0
                    ]);
        //}

        $id_ov = $cajah->id_orden_ventah;
        $min_status_fact = DB::table('cajah')
                                ->where('id_orden_ventah', $id_ov)
                                ->where('status_cob', '!=', 0)
                                ->min('status_cob');

        $ov = OrdenVentaH::find($id_ov);
        if (intval($min_status_fact) == 0) {
            $min_status_fact = -1;
            $ov->status_doc = 2;
        }
        $ov->status_cob = $min_status_fact;
        $ov->save();

        if ((int)$request['tipo_anulado'] == 1) {

            $empresa = Auth::user()->idempresa;
            $sucursal = Auth::user()->idsucursal;
            $almacen = $cajah->idalmacen;
            $cajaguia = DB::table('cajaguiaventa')->where('idcaja', '=', $idcajah)->get();

            for($i = 0; $i < count($cajaguia); $i++){ 
                $gr = GuiaRemisionH::find($cajaguia[$i]->idguia);
                    if($gr->status_ent==2 || $gr->status_ent==3 ){       //si es una gr con entrega parcial o total
                        $grd= GuiaRemisionD::where('id_guia_remisionh', $cajaguia[$i]->idguia)->get();
                        for($j = 0; $j < count($grd); $j++){   
                            $product= Producto::find($grd[$j]->idproducto);
                            $product->stock_total = $product->stock_total + $grd[$j]->cantidad_ent;   //modifica stock real de productos en las guias de remision entregadas
                            $product->save();  


                            $stockT_suma=0;
                            $stockT_tmp = DB::table('lote')->select(DB::raw("SUM(stock_lote) as stockT"))
                                ->where('idproducto', $product->idproducto)
                                ->groupBy('idproducto')->first();

                            if( $stockT_tmp == null){                   
                                $stockT_suma = 0;
                            }else {
                                $stockT_suma = $stockT_tmp->stockT;
                            }

                            $lote = Lote::find($grd[$j]->idlote);                                   //actualziar lote
                            $lote->stock_lote = $lote->stock_lote + $grd[$j]->cantidad_ent;                
                            $saved2=$lote->save();

                            $transacts = new Transacciones;
                            $transacts->idproducto =  $product->idproducto;                    //realizar la transaccion en kardex
                            $transacts->idempresa = $empresa;
                            $transacts->idsucursal = $sucursal;            
                            $transacts->idusuario = $idusuario;
                            $transacts->idalmacen = $almacen;
                            $transacts->idlote = $grd[$j]->idlote;

                            $transacts->f_emision = date('Y-m-d');
                            $transacts->tipo_documento = 1;
                            $transacts->iddocumento = $cajaguia[$i]->idguia;          
                            $transacts->tipo = 1;                   
                            
                            $transacts->cantidad =$grd[$j]->cantidad_ent;                         
                            $transacts->stockT = $stockT_suma + $grd[$j]->cantidad_ent;

                            $transacts->state = 1;               
                            $saved4 = $transacts->save();

                        }                              

                    }
                               
                $gr->estado_doc = 3;

                //revisamos si la ov tiene otras guias de remision , para no ponerle estado 4
                $gr->status_ent = 0;
                $gr->save();
               
            }
            
            if ( $id_ov != 0 && $id_ov != '0' && $id_ov!=null){
                $ov = OrdenVentaH::find($id_ov);
                $ov->estado_doc = 2;
                $ov->status_doc = 0;
                $ov->save();
            }
        }

        CajaGuia::where('idcaja', $idcajah)->delete();

        return json_encode(['mensaje' => 200]);
    }


    public function update_codigoNB(Request $request)
    {
        $value = $request['value'];
        $idcajah = $request['idcajah'];
        $guiaremisionh = CajaH::find($idcajah);
        $guiaremisionh->codigoNB = $value;
        $guiaremisionh->save();
        return json_encode(['mensaje' => 200]);
    }

    public function update_vendedor(Request $request)
    {
        $idcajah = $request['idcajah'];
        $vendedor = $request['value'];
        $fact = CajaH::find($idcajah);
        $fact->idvendedor = $vendedor;
        $fact->save();
        return json_encode(['mensaje' => 200]);
    }

    public function caja_edit_comments(Request $request) {

        $cajah = CajaH::find($request['id_reg']);
        $cajah->comentarios = $request['comments'];
        $cajah->save();

        return json_encode(['mensaje' => 200]);
    }

    public function exchange(Request $request) {

        $access_key = '6bf2318cd152769b7ea2a727195ba2e2';
        $curli = curl_init();
        curl_setopt($curli, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key='.$access_key.'&symbols=PEN,USD');
        curl_setopt($curli, CURLOPT_RETURNTRANSFER, true);
        $dataLatest = curl_exec($curli);
        if ($dataLatest === FALSE || curl_errno($curli)) {
            $dataUSD = 0;
            $dataEUR = 0;
        } else {
            $dataLatest = json_decode($dataLatest);
            $dataUSD = 0;
            $dataEUR = 0;
            if ($dataLatest->success) {
                $dataEUR = number_format((float)$dataLatest->rates->PEN, 3, '.', '');
                $dataUSD = number_format((float)($dataLatest->rates->PEN/$dataLatest->rates->USD), 3, '.', '');
            }
        }

        return json_encode(['USDExchange' => $dataUSD, 'EURExchange' => $dataEUR]);

    }

    public function generateInvoiceGreen(Request $request) {

        $see = parent::configInvoiceNoteGreen();
        $dirGreenter = 'greenter/';
        //$dirGreenter = base_path().'/public/greenter/';

        // Cliente
        $client = parent::getClientGreen($request['idcliente']);
        if ($client->getTipoDoc() == '1' and ($request['tipo'] == '2' or $request['tipo'] == 2)) {
            return ['created' => 502, 'msg' => 'El cliente necesita un RUC para emitir una factura electrónica'];
        }

        // Emisor
        $company = parent::getCompanyGreen();

        $maxCorrelativo = DB::table('cajah')
                    ->where('tipo', $request['tipo'])
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;

        $tipoDoc = '01';
        $serie = 'F001';
        if ($request['tipo'] == '1' or $request['tipo'] == 1) {
            $tipoDoc = '03';
            $serie = 'B001';
        }
        $correlativo = strval($nextCorrelativo);
        $codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
        $arrayTipoMoneda = [1 => ['PEN', 'soles'], 2 => ['USD', 'dolares americanos'], 3 => ['EUR', 'euros']];
        $tipoMoneda = $arrayTipoMoneda[(int)$request['moneda']][0];
        $currency = $arrayTipoMoneda[(int)$request['moneda']][1];
        $envio = (float) $request['envio'];
        $envioBI= $envio*100/118; ///Comentar para envio exonerado
        $igvenvio = $envio - $envioBI;    ///Comentar para envio exonerado
        $montoGravada = (float) $request['subtotal'];
        $montoIgv = (float) $request['igv'];
        $subTotal = $montoGravada + $montoIgv+ $envio; 
        $descuento = (float) $request['descuento'];
        $montoImpVenta = $subTotal - $descuento;
        $fechaEmision = $request['fechaNB'];
        $fechaVencimiento = $request['f_cobro'];
        $formaPago = (int)$request['forma_pago'];

        // Venta
        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc($tipoDoc) // Factura - Catalog. 01
            ->setSerie($serie)
            ->setCorrelativo($correlativo) // Calcular correlativo
            ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
            ->setFecVencimiento(new DateTime($fechaVencimiento.' 12:00:00-05:00'))
            //->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda($tipoMoneda) // Sol - Catalog. 02
            ->setCompany($company)
            ->setClient($client)            
            //->setMtoOperGravadas($montoGravada) ///Desomentar para envio exonerado
            ->setMtoOperGravadas($montoGravada+$envioBI)
            //->setMtoIGV($montoIgv)
            ->setMtoIGV($montoIgv+$igvenvio)
            //->setTotalImpuestos($montoIgv)
            ->setTotalImpuestos($montoIgv+$igvenvio)
            //->setValorVenta($montoGravada+$envio)
            ->setValorVenta($montoGravada+$envioBI)
            ->setSubTotal($subTotal)
            ->setMtoImpVenta($montoImpVenta);


        if($envio>0){
            //$invoice->setMtoOperExoneradas($envio);
            $flete = (new SaleDetail())
            ->setCodProducto('78101802')
            ->setCodProdSunat('78101802')
            ->setUnidad('NIU') // Unidad - Catalog. 03
            ->setDescripcion('ENVIO')
            ->setCantidad(1)
            /*->setMtoValorUnitario($envio)
            ->setMtoValorVenta($envio)
            ->setMtoBaseIgv($envio)
            ->setPorcentajeIgv(0)
            ->setIgv(0)
            ->setTipAfeIgv('20') // Exonerada Op. Onerosa - Catalog. 07
            ->setTotalImpuestos(0)
            ->setMtoPrecioUnitario($envio);*/
            ->setMtoValorUnitario($envioBI)
            ->setMtoValorVenta($envioBI)
            ->setMtoBaseIgv($envioBI)
            ->setPorcentajeIgv(18.00) // 18%
            ->setIgv($igvenvio)
            ->setTipAfeIgv('10') // Exonerada Op. Onerosa - Catalog. 07
            ->setTotalImpuestos($igvenvio)
            ->setMtoPrecioUnitario($envio);
            
        }

        if ($formaPago == 0) {
            $invoice->setFormaPago(new FormaPagoContado());
        } else {
            $montoCuotas = 0;
            $cuotas = json_decode($request['cuotas']);
            $cuotaItems = [];
            for ($i = 0; $i < count($cuotas); $i++) {
                $montoCuota = (float)$cuotas[$i]->monto;
                $fechaCuota = $cuotas[$i]->fecha;
                $montoCuotas = $montoCuotas + $montoCuota;
                $cuotaItem = (new Cuota())
                            ->setMonto($montoCuota)
                            ->setFechaPago(new DateTime($fechaCuota.' 12:00:00-05:00'));
                $cuotaItems[] = $cuotaItem;
            }
            $invoice->setFormaPago(new FormaPagoCredito($montoCuotas))
                    ->setCuotas($cuotaItems);
        }

        if ($descuento > 0) {
            $invoice->setDescuentos([
                        (new Charge())
                            ->setCodTipo('03') // Catalog. 53 (03: Descuento global que no afecta la Base Imponible)
                            ->setMontoBase($descuento)
                            ->setFactor(1)
                            ->setMonto($descuento) // Mto Dscto
                    ])
                    ->setSumOtrosDescuentos($descuento); // suma descuentos que no afectan la base (dscto. global)
        }

        $productos = json_decode($request['productos']);
        $items = [];

        $guias_select = $request['guias_select'];

        if ($guias_select != null) {
            $guias = [];
            for ($i = 0; $i < count($guias_select); $i++) {
                $guiaR = GuiaRemisionH::find($guias_select[$i]);
                $guia = (new Document())
                        ->setTipoDoc('09')
                        ->setNroDoc($guiaR->codigoNB);
                $guias[] = $guia;
            }
            $invoice->setGuias($guias);
        }
        
        for ($i = 0; $i < count($productos); $i++) {

            $product = Producto::find($productos[$i]->idproducto);
            $description = $product->nombre;
            $codProd = $product->barcode;
            $codSunat = $product->codigo_sunat;
            $cantidad = (int) $productos[$i]->stock_total;
            $montoValorUnit = ((float) $productos[$i]->precio * 100) / 118.00;
            $montoValorVenta = $cantidad * $montoValorUnit;
            $porcentajeIgv = 18.00;
            $igv = ($montoValorVenta * $porcentajeIgv) / 100.00;
            $montoPrecioUnit = $montoValorUnit + ($igv / $cantidad);

            $item = (new SaleDetail())
                ->setCodProducto($codProd)
                ->setCodProdSunat($codSunat)
                ->setUnidad('NIU') // Unidad - Catalog. 03
                ->setDescripcion($description)
                ->setCantidad($cantidad)
                ->setMtoValorUnitario($montoValorUnit)
                ->setMtoValorVenta($montoValorVenta)
                ->setMtoBaseIgv($montoValorVenta)
                ->setPorcentajeIgv($porcentajeIgv) // 18%
                ->setIgv($igv)
                ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                ->setTotalImpuestos($igv)
                ->setMtoPrecioUnitario($montoPrecioUnit);

            $items[] = $item;
        }

        if($envio>0){
            $items[] = $flete;
        }
        


        $formatter = new NumeroALetras();

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($formatter->toInvoice($montoImpVenta, 2, $currency));

        $invoice->setDetails($items)
            ->setLegends([$legend]);

        // Envío a SUNAT
        $result = $see->send($invoice);

        // Guardar XML firmado digitalmente.
        $xmlFileName = $invoice->getName().'.xml';
        file_put_contents($dirGreenter.$xmlFileName, $see->getFactory()->getLastXml());


        $htmlReport = new HtmlReport();

        if($envio>0){
            $htmlReport->setTemplate('invoice-tienda.html.twig');
        }
        else{
            $htmlReport->setTemplate('invoice.html.twig');
        }
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());
        $pdfFileName = $invoice->getName().'.pdf';

        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png'), // Logo de Empresa
                'hash' => parent::getHashXml($dirGreenter.$xmlFileName) // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'extras'     => parent::getLeyendDoc(),
                'link'       => url($dirGreenter.$pdfFileName)
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($invoice, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

        file_put_contents($dirGreenter.$pdfFileName, $pdf);

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            
            // Mostrar error al conectarse a SUNAT.
            $code=(int)$result->getError()->getCode();
            return ['created' => 501, 'msg' => $result->getError()->getCode().' - '.$result->getError()->getMessage(), 'codigoNB' => $codigoNB, 'correlativoG' => $correlativo, 'xml_file' => $xmlFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' =>  $result->getError()->getCode().' - '.$result->getError()->getMessage()];
            //echo 'Codigo Error: '.$result->getError()->getCode();
            //echo 'Mensaje Error: '.$result->getError()->getMessage();
            exit();
        }

        // Guardamos el CDR
        $cdrFileName = 'R-'.$invoice->getName().'.zip';
        file_put_contents($dirGreenter.$cdrFileName, $result->getCdrZip());

        // CDR Resultado
        $cdr = $result->getCdrResponse();

        $code = (int)$cdr->getCode();
        $rechazo=false;
        $msg = '';
        if ($code === 0) {
            $msg = 'ESTADO: ACEPTADA';
            //echo 'ESTADO: ACEPTADA'.PHP_EOL;
        } else if ($code >= 4000) {
            $msg = 'ESTADO: ACEPTADA CON OBSERVACIONES: ';
            ob_start();
            var_dump($cdr->getNotes());
            $msg .= ob_get_clean();
            //echo 'ESTADO: ACEPTADA CON OBSERVACIONES:'.PHP_EOL;
            //var_dump($cdr->getNotes());
        } else if ($code >= 2000 && $code <= 3999) {
            $msg = 'ESTADO: RECHAZADA';
            $rechazo=true;
            //echo 'ESTADO: RECHAZADA'.PHP_EOL;
        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $rechazo=true;
            $msg = 'Excepción';
            //echo 'Excepción';
        }

        $msg .= ' '.$cdr->getDescription();

        
        return ['created' => 200, 'msg' => $msg, 'codigoNB' => $codigoNB, 'correlativoG' => $correlativo, 'xml_file' => $xmlFileName, 'cdr_file' => $cdrFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' => $msg,'rechazo' => $rechazo];
    }

    /* testing greenter */

    



    public function testGreen() {

        $see = parent::configInvoiceNoteGreenTest();
        $dirGreenter = 'greenter/';

        // Cliente
        $client = new Client();
        $client->setTipoDoc('6')
            ->setNumDoc('20000000001')
            ->setRznSocial('EMPRESA X');

        // Emisor
        $address = new Address();
        $address->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion('Av. Villa Nueva 221')
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 de lo contrario.

        $company = new Company();
        $company->setRuc('20123456789')
            ->setRazonSocial('GREEN SAC')
            ->setNombreComercial('GREEN')
            ->setAddress($address);

        // Venta
        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc('01') // Factura - Catalog. 01
            ->setSerie('F001')
            ->setCorrelativo('1')
            ->setFechaEmision(new DateTime('2020-08-24 13:05:00-05:00'))
            ->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda('PEN') // Sol - Catalog. 02
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(100.00)
            ->setMtoIGV(18.00)
            ->setTotalImpuestos(18.00)
            ->setValorVenta(100.00)
            ->setSubTotal(118.00)
            ->setMtoImpVenta(118.00)
        ;

        $item = (new SaleDetail())
            ->setCodProducto('P001')
            ->setUnidad('NIU') // Unidad - Catalog. 03
            ->setCantidad(2)
            ->setDescripcion('PRODUCTO 1')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00) // 18%
            ->setIgv(18.00)
            ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
            ->setTotalImpuestos(18.00)
            ->setMtoValorVenta(100.00)
            ->setMtoValorUnitario(50.00)
            ->setMtoPrecioUnitario(59.00)
        ;

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

        $invoice->setDetails([$item])
            ->setLegends([$legend]);

        // Envío a SUNAT
        $result = $see->send($invoice);

        // Guardar XML firmado digitalmente.
        file_put_contents($dirGreenter.$invoice->getName().'.xml',
            $see->getFactory()->getLastXml());

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: '.$result->getError()->getCode();
            echo 'Mensaje Error: '.$result->getError()->getMessage();
            exit();
        }

        // Guardamos el CDR
        file_put_contents($dirGreenter.'R-'.$invoice->getName().'.zip', $result->getCdrZip());

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
