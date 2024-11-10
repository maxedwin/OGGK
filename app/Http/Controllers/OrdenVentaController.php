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
use App\User;
use Carbon\Carbon;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\GuiaRemisionH;
use App\Models\CotizacionH;
use Auth;
use Helper;
use DateTime;

use DB;

class OrdenVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['hoja_ruta_next_day']]);
    }

    public function index()
    {
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];

        if(!isset($query)){
           

        }else{
            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
                ->where('idempresa',$empresa)
                ->where('numeracion','like','%'.$query.'%')
                ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
                ->get();
        }

        return view('orden_venta/listado_orden_venta');
    }

    public function allOV(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1=> 'codigoNB',
            2=> 'created_at',
            3=> 'razon_social',
            4=> 'subtotal',
            5=> 'igv',
            6=> 'total',
            7=> 'f_entrega',
            8=> 'f_cobro',
            9=> 'name',
            10=> 'status_doc',
            11=> 'cliente_extra',
            12=> 'numeracion',
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

        $totalData =DB::table('orden_ventah')->where('orden_ventah.idempresa',$empresa)->count();
            

        if(empty($request->input('search.value'))){
            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
                   
           $totalFiltered = DB::table('orden_ventah')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->count();

        }else{
            $search = $request->input('search.value'); 

            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('orden_ventah.numeracion','like','%'.$search.'%')
                ->orWhere('orden_ventah.codigoNB','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('orden_ventah.total','like','%'.$search.'%')
                ->orWhere('clientes.razon_social','like','%'.$search.'%');
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })    
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
                   
           $totalFiltered = DB::table('orden_ventah')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('orden_ventah.numeracion','like','%'.$search.'%')
                ->orWhere('orden_ventah.codigoNB','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('orden_ventah.total','like','%'.$search.'%')
                ->orWhere('clientes.razon_social','like','%'.$search.'%');
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })    
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->count();
        }


        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($orden_ventas))
        {
            foreach ($orden_ventas as $orden_venta)
            {
                                                 
                $nestedData['numeracion'] = str_pad($orden_venta->numeracion, 6, "0", STR_PAD_LEFT);
                $nestedData['codigoNB'] =$orden_venta->codigoNB;
                $nestedData['created_at']= str_limit($orden_venta->created_at, $limit = 10, $end='');
                $nestedData['razon_social']= $orden_venta->razon_social;

                $nestedData['subtotal']= number_format((float)$orden_venta->subtotal, 2, '.', '');
                $nestedData['igv']= number_format((float)$orden_venta->igv, 2, '.', '');
                $nestedData['total']= number_format((float)$orden_venta->total, 2, '.', '');
                $nestedData['f_entrega']= date_format(date_create_from_format('Y-m-d', $orden_venta->f_entrega), 'd/m/Y');
                $nestedData['f_cobro']= date_format(date_create_from_format('Y-m-d', $orden_venta->f_cobro), 'd/m/Y') ;

                $nestedData['name']= $orden_venta->name.' '.$orden_venta->lastname;
                $nestedData['status_doc']='';    
                            if($orden_venta->status_doc == -1) {
                                if($orden_venta->estado_doc == 0) {
                                    $nestedData['status_doc']='<button id="status" class="btn btn-danger" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="1" >  Pendiente  </button>';
                                }      elseif($orden_venta->estado_doc == 1) {
                                    $nestedData['status_doc']='<button id="status" class="btn btn-success" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="2" > Facturada  </button>';
                                }      elseif($orden_venta->estado_doc == 3) {
                                    $nestedData['status_doc']='<button id="status" class="btn btn-primary" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="2" > Parcial  </button>';
                                }      elseif($orden_venta->estado_doc == 9) {
                                    $nestedData['status_doc']='<button id="status" class="btn btn-info" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="9" > Por Confirmar  </button>';
                                }      else {
                                    $nestedData['status_doc']='<button id="status" class="btn btn-secondary" data-id_orden_ventah="'.$orden_venta->id_orden_ventah.'" data-status="0" > Anulada </button>';
                                }
                            } else {
                                if( $orden_venta->status_cob==3 and  $orden_venta->status_ent ==3 and $orden_venta->status_doc ==3  ){
                                    $nestedData['status_doc']='<button class="btn-sm btn btn-success">VENTA SATISFACTORIA</button>';
                                    if ( $orden_venta->is_ncp ) {
                                        $nestedData['status_doc'].=' <i title="NCP" class="icon-exclamation position-center"></i>';
                                    }
                                }
                                else{ 
                                    $status_doc_ov = Helper::status_doc_ov();  
                                    $status_ent_ov = Helper::status_ent_ov();  
                                    $status_cob_ov = Helper::status_cob_ov();  
                                    $nestedData['status_doc']=$status_doc_ov[$orden_venta->status_doc];
                                    if ( $orden_venta->is_ncp ) {
                                        $nestedData['status_doc'].=' <i title="NCP" class="icon-exclamation position-center"></i>';
                                    }
                                    if( $orden_venta->status_doc !=4 && $orden_venta->status_doc != 0){                                    
                                        if ($orden_venta->status_ent != -1) {
                                            $nestedData['status_doc'].='<br>'.$status_ent_ov[$orden_venta->status_ent];
                                        }
                                        if ($orden_venta->status_cob != -1) {
                                            $nestedData['status_doc'].= '<br>'.$status_cob_ov[$orden_venta->status_cob];
                                        } 
                                    }  
                                }
                            }
                        $nestedData['cliente_extra']=$orden_venta->ruc_dni.' '.$orden_venta->contacto_nombre.' '.$orden_venta->contacto_telefono;
                        $nestedData['acciones'] ='';
                        $nestedData['acciones'] .="<button type='button' class='btn btn-info btn-xs'
                                    id='imprimir' data-id='{$orden_venta->id_orden_ventah}'>
                                <i class='glyphicon glyphicon-print position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-toggle = 'modal'
                                    id='observacion' data-id='{$orden_venta->id_orden_ventah}'
                                    data-numeracion = '{$orden_venta->numeracion } '
                                    data-observacion = '{$orden_venta->comentarios } '>
                                <i class='icon-comments position-center'></i>
                            </button>";
                            /*if($orden_venta->estado_doc == 3) {
                            <button type="button" class="btn btn-success btn-xs" title="Marcar como Facturada"
                                    data-id_orden_ventah   = "{{$orden_venta->id_orden_ventah }}"
                                    data-numeracion      = "{{$orden_venta->numeracion }}"
                                    id="mark_total">
                                <i class="icon-file-check2 position-center"></i>
                            </button>
                             } */
                            if(($orden_venta->status_doc == -1 and $orden_venta->estado_doc == 0) or $orden_venta->status_doc == 1) {
                                $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                        data-id_orden_ventah   = ' {$orden_venta->id_orden_ventah } '
                                        data-numeracion        = ' {$orden_venta->numeracion } '
                                        data-np                = ' {$orden_venta->codigoNB } '                                    
                                        data-toggle            = 'modal'
                                        id='anular'> 
                                    <i class='icon-cancel-square2 position-center'></i>
                                </button>";
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
            header('Content-Disposition: attachment;filename="OrdenesVenta-SolucionesOGGK.xls"');
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
            4=> 'subtotal',
            5=> 'igv',
            6=> 'total',
            7=> 'f_entrega',
            8=> 'f_cobro',
            9=> 'name',
            10=> 'status_doc',
            11=> 'cliente_extra',
            12=> 'numeracion',
                        );  
         $data_array [] = array( 
                            "Correlativo", 
                            "Nota de Pedido",
                            "Fecha de Emisión",
                            "Cliente",
                            "Base Imponible",
                            "IGV",
                            "Total",
                            "Fecha de Entrega",
                            "Fecha de Cobro",
                            "Vendedor",
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
            
        if(empty($request['search'])){
            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->orderBy($order,$dir)
            ->get();
                   
           $totalFiltered = DB::table('orden_ventah')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->count();

        }else{
            $search = $request['search']; 

            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('orden_ventah.numeracion','like','%'.$search.'%')
                ->orWhere('orden_ventah.codigoNB','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('orden_ventah.total','like','%'.$search.'%')
                ->orWhere('clientes.razon_social','like','%'.$search.'%');
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })    
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->orderBy($order,$dir)
            ->get();
                   
           $totalFiltered = DB::table('orden_ventah')
            ->where('orden_ventah.idempresa',$empresa)
            ->where('orden_ventah.created_at', '>=', $f_inicio)
            ->where('orden_ventah.created_at', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('orden_ventah.numeracion','like','%'.$search.'%')
                ->orWhere('orden_ventah.codigoNB','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('orden_ventah.total','like','%'.$search.'%')
                ->orWhere('clientes.razon_social','like','%'.$search.'%');
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })    
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->count();
        }
       
        $formato = 'Y-m-d H:i:s';
        $status_doc_ov=[
                        0 => 'ANULADO',
                        1 => 'GUIA P',
                        2 => 'FACTURA P',
                        3 => 'FACTURADA',
                        4 => 'NC'
                   ];
    
     $status_ent_ov=[
                        1 => ' / ENTREGA P',
                        2 => ' / E PARCIAL',
                        3 => ' / ENTREGADO',
                   ];
    
        $status_cob_ov=[
                        1 => ' / COBRO P',
                        2 => ' / C PARCIAL',
                        3 => ' / FACTURAS PAGADAS'
                   ];


        foreach($orden_ventas as $orden_venta)
        {
            $fecha = DateTime::createFromFormat($formato, $orden_venta->created_at);
            $est;   
            if($orden_venta->status_doc == -1) {
                if($orden_venta->estado_doc == 0) {
                    $est='Pendiente ';
                }      elseif($orden_venta->estado_doc == 1) {
                    $est='Facturada ';
                }      elseif($orden_venta->estado_doc == 3) {
                    $est='Parcial ';
                }      elseif($orden_venta->estado_doc == 9) {
                    $est='Por Confirmar ';
                }      else {
                    $est=' Anulada ';
                }
            } else {
                if( $orden_venta->status_cob==3 and  $orden_venta->status_ent ==3 and $orden_venta->status_doc ==3  ){
                    $est='VENTA SATISFACTORIA ';
                    if ( $orden_venta->is_ncp ) {
                        $est.='NCP ';
                    }
                }
                else{  
                    $est=$status_doc_ov[$orden_venta->status_doc];
                    if ( $orden_venta->is_ncp ) {
                        $est.=' NCP ';
                    }
                    if( $orden_venta->status_doc !=4 && $orden_venta->status_doc != 0){                                    
                        if ($orden_venta->status_ent != -1) {
                            $est.=' '.$status_ent_ov[$orden_venta->status_ent];
                        }
                        if ($orden_venta->status_cob != -1) {
                            $est.= ' '.$status_cob_ov[$orden_venta->status_cob];
                        } 
                    }  
                }
            }

            $data_array[] = array(
                'Correlativo' =>str_pad($orden_venta->numeracion, 6, "0", STR_PAD_LEFT), 
                'Nota de Pedido'=> $orden_venta->codigoNB,              
                'Fecha de Emisión'=>date_format($fecha, 'Y-m-d'),
                'Cliente'=>$orden_venta->razon_social,
                'Base Imponible'=>$orden_venta->subtotal,
                'IGV'=>$orden_venta->igv,
                'Total'=>$orden_venta->total,
                'Fecha de Entrega'=>$orden_venta->f_entrega,
                'Fecha de Cobro'=>$orden_venta->f_cobro,
                'Despachador'=>$orden_venta->name." ".$orden_venta->lastname,
                'Estado'=>$est
            );
        }

        $this->ExportExcel($data_array);
    }
 
    public function index_detallado()
    {
        $empresa = Auth::user()->idempresa;

        $cajas = DB::table('orden_ventah')->select('orden_ventah.*','orden_ventad.*','producto.nombre','clientes.razon_social', 'users.name', 'users.lastname')
            ->join ('orden_ventad', 'orden_ventad.id_orden_ventah', '=', 'orden_ventah.id_orden_ventah')
            ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
            ->join('producto','orden_ventad.idproducto','=','producto.idproducto')
            ->get();

        return view('orden_venta/listado_orden_venta_detallado', ['cajas' => $cajas]);
    }

    public function index_tienda()
    {
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];

        if(!isset($cant))$cant = 1000;
        if(!isset($query)){
            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*', 'users.name', 'users.lastname')
            ->where('orden_ventah.by_client',1)
            ->join ('users', 'users.id', '=', 'orden_ventah.idusuario')
            ->get();

        }else{
            $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*', 'users.name', 'users.lastname')
                ->where('orden_ventah.by_client',1)
                ->where('numeracion','like','%'.$query.'%')
                ->join ('users', 'users.id', '=', 'orden_ventah.idusuario')
                ->paginate($cant);
        }

        return view('orden_venta/listado_orden_venta_tienda', ['orden_ventas' => $orden_ventas]);
    }
    
    public function crear(Request $request)        
    {
        $idcliente = 0;
        $razon_social='';
        if($request['idcliente']!=null){
            $idcliente = $request['idcliente'];
            $tmp = DB::table('clientes')->select('razon_social')->where('idcliente',$idcliente)->first();
            $razon_social = $tmp->razon_social;
        }

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

        return view('orden_venta/orden_venta')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('idclienteagregar',$idcliente)->with('iduser', $id)
        ->with('rsclienteagregar',$razon_social);
    }

    public function hoja_ruta(Request $request)        
    {        
        $empresa = Auth::user()->idempresa;


        // $cant = $request['cant'];
        // $query = $request['query'];
        if(!isset($cant))$cant = 1000;
        if(!isset($query)){
            $guia_remisions = DB::table('guia_remisionh')->select('guia_remisionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np')
            ->where('guia_remisionh.idempresa',$empresa)
            ->where(function ($query) {
                $query->where(function ($query1) {
                    $query1->whereDate('guia_remisionh.f_entrega','=',Carbon::now()->toDateString())
                    ->whereNull('guia_remisionh.f_reprogramar');
                })
                ->orWhereDate('guia_remisionh.f_reprogramar','=',Carbon::now()->toDateString());                  
            })   
            ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
            ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->get();

        }else{
            $guia_remisions = DB::table('guia_remisionh')->select('guia_remisionh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np')
                ->where('idempresa',$empresa)
                ->where(function ($query) {
                    $query->where(function ($query1) {
                        $query1->whereDate('guia_remisionh.f_entrega','=',Carbon::now()->toDateString())
                        ->whereNull('guia_remisionh.f_reprogramar');
                    })
                    ->orWhereDate('guia_remisionh.f_reprogramar','=',Carbon::now()->toDateString());                  
                })                
                ->where('numeracion','like','%'.$query.'%')
                ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                ->paginate($cant);
        }       

        return view('orden_venta/hoja_ruta')->with('guia_remisions',$guia_remisions);
    }

    public function add_hoja_ruta(Request $request)        
    {        
        $ov_select = $request['ov_select'];
        $saved=true;
        DB::beginTransaction(); 
        
        for ($i = 0; $i < count($ov_select); $i++) {
            $ordenventa = GuiaRemisionH::find($ov_select[$i]);            
            if( $ordenventa){
                $ordenventa->hoja_ruta=1;
                $saved=$ordenventa->save();
            }
            else{
                $saved = false;
            }
        }

        if($saved){
            DB::commit();
            return json_encode(['mensaje' => 200]);
        }
        else{
            DB::rollBack();
            return json_encode(['mensaje' => 500]);
        }


    }
    
    public function quitar_hoja_ruta(Request $request) {

        $gr =GuiaRemisionH::find($request['id_orden_ventah']);
        $gr->hoja_ruta = 0;
        $gr->save();

        return json_encode(['mensaje' => 200]);
    }


    public function hoja_ruta_reporte(Request $request){
        $empresa = Auth::user()->idempresa;

        // $cant = $request['cant'];
        // $query = $request['query'];
        $day=Carbon::now()->toDateString();
        
        if($request['day']){
            $day= $request['day'];
        }

        if(!isset($cant))$cant = 1000;
        if(!isset($query)){
            $guia_remisions = GuiaRemisionH::select('guia_remisionh.*',
            'clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre',
            'clientes.contacto_telefono', 'users.name', 'users.lastname', 'ov.codigoNB as np',
            'ov.direccion_entrega as dir', 'fac.codigoNB as facnum', 'fac.total as monto', 
            'ven.name as ven_name', 'ven.lastname as ven_lastname')
            ->where('guia_remisionh.idempresa',$empresa)
            ->where(function ($query) use ($day)  {
                $query->where(function ($query1) use ($day){
                    $query1->whereDate('guia_remisionh.f_entrega','=',$day)
                    ->whereNull('guia_remisionh.f_reprogramar');
                })
                ->orWhereDate('guia_remisionh.f_reprogramar','=',$day);                  
            })
            ->where('guia_remisionh.hoja_ruta', 1)
            ->leftJoin ('cajah as fac', 'fac.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
            ->join ('users as ven', 'ven.id', '=', 'guia_remisionh.idvendedor')
            ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
            ->get();

        }else{
            $guia_remisions =GuiaRemisionH::select('guia_remisionh.*','clientes.razon_social', 
            'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 
            'users.name', 'users.lastname', 'ov.codigoNB as np',
            'ov.direccion_entrega as dir', 'fac.codigoNB as facnum',
            'fac.total as monto', 'ven.name as ven_name', 'ven.lastname as ven_lastname')
                ->where('idempresa',$empresa)
                ->where(function ($query) use ($day)  {
                    $query->where(function ($query1) use ($day){
                        $query1->whereDate('guia_remisionh.f_entrega','=',$day)
                        ->whereNull('guia_remisionh.f_reprogramar');
                    })
                    ->orWhereDate('guia_remisionh.f_reprogramar','=',$day);                  
                })
                ->where('guia_remisionh.hoja_ruta', 1)
                ->where('numeracion','like','%'.$query.'%')
                ->leftJoin ('cajah as fac', 'fac.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                ->join ('clientes', 'guia_remisionh.idcliente', '=', 'clientes.idcliente')
                ->join ('users', 'users.id', '=', 'guia_remisionh.iddespachador')
                ->join ('users as ven', 'ven.id', '=', 'guia_remisionh.idvendedor')
                ->leftJoin ('orden_ventah as ov', 'ov.id_orden_ventah', '=', 'guia_remisionh.id_orden_ventah')
                ->paginate($cant);
        }

        return view('orden_venta/hoja_ruta_reporte', ['guia_remisions' => $guia_remisions])->with('day',$day);
    }

    public function hoja_ruta_next_day(Request $request) {
        if($request['token']=='gec234rw75newv47mnc732mc3herdfsberb435cwe3sdfsdf'){
            $today=Carbon::now()->toDateString();
            $tomorrow=Carbon::tomorrow()->toDateString();
            GuiaRemisionH::whereIn('status_ent', [1,2])->where(function ($query) use ($today)  {
                $query->where(function ($query1) use ($today){
                    $query1->whereDate('guia_remisionh.f_entrega','=',$today)
                    ->whereNull('guia_remisionh.f_reprogramar');
                })
                ->orWhereDate('guia_remisionh.f_reprogramar','=',$today);                  
            })->where(function ($query) {
                $query->where('hoja_ruta', '!=' , 1)->orWhereNull('hoja_ruta');                  
            })->update(['f_reprogramar'=>$tomorrow]);

            
        }
    }



    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria')
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('producto.stock_total','>=',1)
                    ->orwhere('producto.state',1)
                    ->orwhere('idsucursal',$sucursal);
            })
             ->where('producto.barcode', 'like', '%'.$busqueda.'%')
             ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
             ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%')

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->get();

        return json_encode($products);
    }

    public function buscar_clienteOV(Request $request){
        $busqueda = $request['query'];
        $clientes = DB::table('clientes')
            ->where('idcliente',$busqueda)
            ->get();
        return json_encode($clientes);
    }

    public function buscar_coti_numeracion(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('cotizacionh')
            ->select('idcotizacionh','numeracion')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->where('estado_doc','!=',2)
            ->get();
        return json_encode($cotis);
    }

    public function buscar_coti_todo(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('cotizacionh')
            ->select('cotizacionh.*', 'c.razon_social', 'c.contacto_telefono', 'c.contacto_email', 'c.direccion', 'c.distrito', 'c.provincia', 'c.departamento')
            ->join('clientes as c', 'cotizacionh.idcliente', '=', 'c.idcliente')
            ->where('idcotizacionh','=', $busqueda)
            ->first();

        $cotis->detalle = DB::table('cotizaciond')
            ->select('cotizaciond.*', 'p.*')
            ->join('producto as p', 'p.idproducto', '=', 'cotizaciond.idproducto')
            ->where('idcotizacionh','=', $busqueda)
            ->get();

        return json_encode($cotis);
    }


    public function store(Request $request){

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idcliente = $request['idcliente'];
        $idcotizacionh = $request['idcotizacionh'];
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
        $moneda = $request['moneda'];
        $codigoNB = $request['codigoNB'];
        $is_digital = $request['is_digital'];
        $is_igv = $request['is_igv'];
        $direccion_entrega = $request['direccion_entrega'];
        $telefono_entrega = $request['telefono_entrega'];
        $email_entrega = $request['email_entrega'];
        $is_confirm = (int)$request['is_confirm'];
        $observaciones = $request['observaciones'];
            
        $productos_json = $request['productos'];
        $state = 1;
        $status_doc = 1;

        if ( $codigoNB == 0 || $codigoNB == '0' || $codigoNB == null || $codigoNB == '' )
            $bool = false;
        else
            $bool = DB::table('orden_ventah')->where('codigoNB', '=', $codigoNB)->first();

        if( !$bool ){
            $orden_ventah = new OrdenVentaH;
            $orden_ventah->idempresa = $empresa;
            $orden_ventah->idsucursal = $sucursal;
            $orden_ventah->idusuario = $idusuario;
            $orden_ventah->idcliente = $idcliente;
            $orden_ventah->idcotizacionh = $idcotizacionh;
            $orden_ventah->idvendedor = $idvendedor;
            $orden_ventah->is_digital = $is_digital;
            $orden_ventah->is_igv = $is_igv;
            $orden_ventah->direccion_entrega = $direccion_entrega;
            $orden_ventah->telefono_entrega = $telefono_entrega;
            $orden_ventah->email_entrega = $email_entrega;

            $maximo_num = DB::table('orden_ventah')
                            ->where('is_digital', $is_digital)
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('numeracion');

            $orden_ventah->numeracion = intval($maximo_num) + 1 ;

            $orden_ventah->paga = $paga;
            $orden_ventah->igv = $igv;
            $orden_ventah->vuelto = $vuelto;
            $orden_ventah->descuento = $descuento;
            $orden_ventah->subtotal = $subtotal;
            $orden_ventah->total = $total;
            $orden_ventah->comentarios = $comentarios;
            $orden_ventah->comentarios = $observaciones;
            $orden_ventah->f_entrega = $f_entrega;
            $orden_ventah->f_cobro = $f_cobro;
            $orden_ventah->moneda = $moneda;
            $orden_ventah->status_doc = $status_doc;

            if ($is_digital==1)
                $orden_ventah->codigoNB = 'D'.str_pad(intval($maximo_num) + 1, 5, "0", STR_PAD_LEFT);
            else
                $orden_ventah->codigoNB = $codigoNB;
            
            if ($is_confirm == 1)
                $orden_ventah->estado_doc = 9;
            
            $orden_ventah->save();

            if ( $idcotizacionh != 0 || $idcotizacionh != '0' ){
                $coti_state = CotizacionH::find($idcotizacionh);
                $coti_state->estado_doc = 1;
                $coti_state->save();
            }
            
            $productos = json_decode($productos_json);
         

            for($i = 0; $i < count($productos); $i++){
                $orden_ventad = new OrdenVentaD;
                $orden_ventad->id_orden_ventah = $orden_ventah->id_orden_ventah;
                $orden_ventad->idproducto = $productos[$i]->idproducto;
                $orden_ventad->cantidad = $productos[$i]->stock_total;
                $orden_ventad->cantidad_fal = $productos[$i]->stock_total;
                $orden_ventad->precio_unit = $productos[$i]->precio;
                $orden_ventad->precio_total = $productos[$i]->precio * $productos[$i]->stock_total ;
                $orden_ventad->idempresa = $empresa;
                $orden_ventad->save();

                if ($is_confirm != 1) {
                    $product = Producto::find($productos[$i]->idproducto);
                    if ($product->tipo == 1) {
                        $product->stock_imaginario = $product->stock_imaginario - $productos[$i]->stock_total;
                        $saved = $product->save();
                    }
                    $saved = true;
                } else {
                    $saved = true;
                }
            }

            $respuesta = array();
            $respuesta[]= ['created'=> 200];
            $respuesta[] = ['id' => $orden_ventah->id_orden_ventah];

            return json_encode($respuesta);
        }else{
            $respuesta = array();
            $respuesta[]= ['created'=> 999];
            $respuesta[] = ['id' => 999];

            return json_encode($respuesta);
        }

    }
    
    public function show(Request $request){
    	$id = $request['id'];

    	$orden_venta = DB::table('orden_ventah')
                ->where('id_orden_ventah',$id)
                ->first();

    	$orden_ventaD = DB::table('orden_ventad')
                ->select('orden_ventad.*','producto.*')
                ->where('orden_ventad.idempresa','=',$orden_venta->idempresa)
                ->where('orden_ventad.id_orden_ventah','=',$orden_venta->id_orden_ventah)
                ->join('producto','orden_ventad.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$orden_venta->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$orden_venta->idcliente)
                    ->first();

        $cliente_tienda = DB::table('users')
                    ->where('id','=',$orden_venta->idusuario)
                    ->first();                    

        return view('orden_venta/info_orden_venta')
            ->with('orden_venta',$orden_venta)
            ->with('orden_ventaD',$orden_ventaD)
            ->with('sucursal',$sucursal)
            ->with('cliente',$cliente);

    }

    public function show_tienda(Request $request){
        $id = $request['id'];

        $order = DB::table('orden_ventah')
                ->where('id_orden_ventah',$id)
                ->first();

        $orderD = DB::table('orden_ventad')
                ->select('orden_ventad.*','producto.*')
                ->where('orden_ventad.idempresa','=',$order->idempresa)
                ->where('orden_ventad.id_orden_ventah','=',$order->id_orden_ventah)
                ->join('producto','orden_ventad.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$order->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $send_method = DB::table('send_methods')
                    ->where('send_methods.id_sendmethod', '=', $order->tipo_envio)
                    ->first();

        $user = DB::table('users')
                    ->where('id','=',$order->idusuario)
                    ->first();                    

        return view('orden_venta/info_orden_venta_tienda')
            ->with('order',$order)
            ->with('orderD',$orderD)
            ->with('sucursal',$sucursal)
            ->with('user',$user)
            ->with('send_method', $send_method);

    }

   public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $ordenventah = OrdenVentaH::find($id);
        $ordenventah->estado_doc = $status;
        $ordenventah->save();
        return response()->json(['accepted'], 202);
    }

    public function ov_estado(Request $request){
        $idusuario = Auth::user()->id;
        $id_orden_ventah = $request['id_orden_ventah'];
        $orden_ventah = OrdenVentaH::find($id_orden_ventah);

        if($orden_ventah->status_doc==1 || $orden_ventah->estado_doc == 0){ 
    
            $alreadyNullOv = DB::table('orden_ventah')->where([
                ['codigoNB', 'like', $orden_ventah->codigoNB.'%'],
                ['estado_doc', '=', '2']
            ])->orWhere([
                ['codigoNB', 'like', $orden_ventah->codigoNB.'%'],
                ['status_doc', '=', '0']
            ])->orderBy('nulled_at','desc')->first();

            if($alreadyNullOv){
                $orden_ventah->codigoNB = $alreadyNullOv->codigoNB.'-1';
            }
            else{
                $orden_ventah->codigoNB = $orden_ventah->codigoNB.'-1';
            }
            $orden_ventah->nulled_at = date('Y-m-d H:i:s');
            $orden_ventah->estado_doc = 2;
            $orden_ventah->status_doc = 0;
            $orden_ventah->nulled_by = $idusuario;
            $orden_ventah->save();
    
            $productos = DB::table('orden_ventad')
                        ->select('idproducto','cantidad')
                        ->where('id_orden_ventah','=',$id_orden_ventah)
                        ->get();
            if($productos){
                for($i = 0; $i < count($productos); $i++){
                        $product = Producto::find($productos[$i]->idproducto);
                        if ($product->tipo == 1) {
                            $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->cantidad;
                            $saved = $product->save();
                        }
                }
            }
            return json_encode(['mensaje' => 200]);
        }
        else {
            if( $orden_ventah->estado_doc == 2 || $orden_ventah->status_doc == 0){
                return json_encode(['mensaje' => 'already null']);
            }
        }
    

    }

    public function update_vendedor(Request $request){
        $id_orden_ventah = $request['id_orden_ventah'];
        $vendedor = $request['value'];
        $orden = OrdenVentaH::find($id_orden_ventah);
        $orden->idvendedor = $vendedor;
        $orden->save();
        return json_encode(['mensaje' => 200]);
    }

    public function ov_complete_order(Request $request) {

        $id_orden_ventah = $request['id_orden_ventah'];

        $orden_ventah = OrdenVentaH::find($id_orden_ventah);

        if (strval($orden_ventah->created_at) > "2021-02-22 00:00:00") {

            $orden_ventaD = DB::table('orden_ventad')
                    ->where('orden_ventad.id_orden_ventah','=',$id_orden_ventah)
                    ->get();

            $new_subtotal = 0.0;
            foreach ($orden_ventaD as $key => $value) {
                $product = Producto::find($value->idproducto);
                if($product->tipo == 1){
                    $product->stock_imaginario = $product->stock_imaginario + $value->cantidad_fal;
                    $product->save();
                    $orden_ventad = OrdenVentaD::find($value->id_orden_ventad);
                    $orden_ventad->cantidad = $orden_ventad->cantidad - $value->cantidad_fal;
                    $orden_ventad->precio_total = $orden_ventad->cantidad * $orden_ventad->precio_unit;
                    $orden_ventad->save();

                    $new_subtotal += $orden_ventad->precio_total;
                }
            }

            $orden_ventah->subtotal = $new_subtotal;
            $orden_ventah->igv = $orden_ventah->subtotal * 0.18;
            $orden_ventah->total = $orden_ventah->subtotal + $orden_ventah->igv - $orden_ventah->descuento;
            $orden_ventah->estado_doc = 1;
            $orden_ventah->save();

            return json_encode(['mensaje' => 200]);
        } else {
            return json_encode(['mensaje' => 99999]);
        }
    }

    public function ov_edit_comments(Request $request) {

        $orden_ventah = OrdenVentaH::find($request['id_reg']);
        $orden_ventah->comentarios = $request['comments'];
        $orden_ventah->save();

        return json_encode(['mensaje' => 200]);
    }

    public function confirm_ov(Request $request){
        $id_orden_ventah = $request['id_orden_ventah'];
        $orden_ventah = OrdenVentaH::find($id_orden_ventah);

        $orden_ventah->estado_doc = 0;
        $orden_ventah->save();

        $productos = DB::table('orden_ventad')
                    ->select('idproducto','cantidad')
                    ->where('id_orden_ventah','=',$id_orden_ventah)
                    ->get();
        if($productos){
            for($i = 0; $i < count($productos); $i++){
                $product = Producto::find($productos[$i]->idproducto);
                if ($product->tipo == 1) {
                    $product->stock_imaginario = $product->stock_imaginario - $productos[$i]->cantidad;
                    $product->contador_uso = $product->contador_uso + 1;
                    $product->save();
                }
            }
        }

        return json_encode(['mensaje' => 200]);
    }

    public function deny_ov(Request $request) {
        $idusuario = Auth::user()->id;
        $id_orden_ventah = $request['id_orden_ventah'];
        $orden_ventah = OrdenVentaH::find($id_orden_ventah);

        if($orden_ventah->status_doc==1 || $orden_ventah->estado_doc == 0){ 
            $alreadyNullOv = DB::table('orden_ventah')->where([
                ['codigoNB', 'like', $orden_ventah->codigoNB.'%'],
                ['estado_doc', '=', '2']
            ])->orWhere([
                ['codigoNB', 'like', $orden_ventah->codigoNB.'%'],
                ['status_doc', '=', '0']
            ])->orderBy('nulled_at','desc')->first();

            if($alreadyNullOv){
                $orden_ventah->codigoNB = $alreadyNullOv->codigoNB.'-1';
            }
            else{
                $orden_ventah->codigoNB = $orden_ventah->codigoNB.'-1';
            }
            $orden_ventah->nulled_at = date('Y-m-d H:i:s');
            $orden_ventah->estado_doc = 2;
            $orden_ventah->status_doc = 0;
            $orden_ventah->nulled_by = $idusuario;
            $orden_ventah->save();

            return json_encode(['mensaje' => 200]);
        }

        else {
            if( $orden_ventah->estado_doc == 2 || $orden_ventah->status_doc == 0){
                return json_encode(['mensaje' => 'already null']);
            }
        }

    }

    public function por_confirmar()
    {
        $empresa = Auth::user()->idempresa;

        $orden_ventas = DB::table('orden_ventah')->select('orden_ventah.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname')
        ->where('orden_ventah.idempresa',$empresa)
        ->where('orden_ventah.estado_doc',9)
        ->join ('clientes', 'orden_ventah.idcliente', '=', 'clientes.idcliente')
        ->join ('users', 'users.id', '=', 'orden_ventah.idvendedor')
        ->get();

        return view('orden_venta/listado_orden_venta_confirmar', ['orden_ventas' => $orden_ventas]);
    }

}