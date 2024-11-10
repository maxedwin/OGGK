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
use App\Models\NotaCreditoH;
use App\Models\NotaCreditoD;
use App\Models\CajaH;
use App\Models\Fe;
use App\Models\GuiaRemisionH;
use App\Models\GuiaRemisionD;
use App\Models\OrdenVentaH;
use App\Models\CotizacionH;
use App\Models\PagoRecibido;
use App\Models\Lote;
use Auth;
use DB;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\See;
use DateTime;

use Luecano\NumeroALetras\NumeroALetras;

class NotaCreditoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('nota_credito/listado_nota_credito');
    }

    public function allNC(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1 =>'tipo',
            2=> 'fact',
            3=> 'codigoNB',
            4=> 'created_at',
            5=> 'f_devolucion',
            6=> 'razon_social',
            7=> 'ruc_dni',
            8=> 'subtotal',
            9=> 'igv',
            10=> 'total',
            11=> 'moneda',
            12=> 'name',
            13=> 'estado_doc',
            14=> 'cliente_extra',
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
        $cant = 15;      

        $totalData =DB::table('nota_creditoh')->where('nota_creditoh.idempresa',$empresa)->count();
            
        if(empty($request->input('search.value'))){
            $nota_creditos = DB::table('nota_creditoh')->select('nota_creditoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'cajah.codigoNB as fact')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = DB::table('nota_creditoh')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah')->count();

        }else{
            $search = $request->input('search.value'); 
            $nota_creditos = DB::table('nota_creditoh')->select('nota_creditoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'cajah.codigoNB as fact')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where(function ($query) use ($search)  {
                $query->where('nota_creditoh.numeracion','like','%'.$search.'%')
                ->orWhere('nota_creditoh.codigoNB','like','%'.$search.'%')
                ->orWhere('nota_creditoh.total','like','%'.$search.'%')

                ->orWhere('clientes.razon_social','like','%'.$search.'%')
                ->orWhere('clientes.ruc_dni','like','%'.$search.'%')
                ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('clientes.contacto_nombre','like','%'.$search.'%');

                
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();  
                //->paginate($cant);

            $totalFiltered = DB::table('nota_creditoh')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('nota_creditoh.numeracion','like','%'.$search.'%')
                ->orWhere('nota_creditoh.codigoNB','like','%'.$search.'%')
                ->orWhere('clientes.razon_social','like','%'.$search.'%')
                ->orWhere('clientes.ruc_dni','like','%'.$search.'%')
                ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                ->orWhere('nota_creditoh.total','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('clientes.contacto_nombre','like','%'.$search.'%');




                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah') 
            ->count(); 

        }


        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($nota_creditos))
        {
            foreach ($nota_creditos as $nota_credito)
            {
                       
                $nestedData['numeracion'] =str_pad($nota_credito->numeracion, 6, "0", STR_PAD_LEFT);
                $nestedData['tipo']='';
                             if($nota_credito->tipo == 1) {
                                $nestedData['tipo'] .= 'Nota Crédito';
                            } elseif($nota_credito->tipo == 2){
                                $nestedData['tipo'] .= 'Devolución';
                            } else{
                                $nestedData['tipo'] .= '--';
                            }
                             if (!is_null($nota_credito->xml_file) and $nota_credito->xml_file != '') {
                                $nestedData['tipo'] .=" (<a title='{$nota_credito->descriptionG}' href='".url('greenter',$nota_credito->xml_file)."' download>XML</a>)";
                            } 
                             if (!is_null($nota_credito->cdr_file) and $nota_credito->cdr_file != '') { 
                                $nestedData['tipo'] .=" (<a title='{$nota_credito->descriptionG}' href='".url('greenter',$nota_credito->cdr_file)."' download>CDR</a>)";
                             } 
                             if (!is_null($nota_credito->pdf_file) and $nota_credito->pdf_file != '') { 
                                $nestedData['tipo'] .=" (<a title='{$nota_credito->descriptionG}' href='".url('greenter',$nota_credito->pdf_file)."' download>PDF</a>)";
                            } 
                        
                $nestedData['fact']=$nota_credito->fact;                 
                $nestedData['codigoNB']=$nota_credito->codigoNB;
                $nestedData['created_at']=date('d/m/Y', strtotime($nota_credito->created_at));
                $nestedData['f_devolucion'] =date_format(date_create_from_format('Y-m-d', $nota_credito->f_devolucion), 'd/m/Y');
                $nestedData['cliente'] =$nota_credito->razon_social;
                $nestedData['ruc_dni'] =$nota_credito->ruc_dni;
                $nestedData['subtotal']= '';
                $nestedData['igv']= '';
                $nestedData['total']= '';
                $moneda_tipo;
                if($nota_credito->moneda == 1){ $nestedData['total'].= 'S/ '; $nestedData['subtotal'].= 'S/ '; $nestedData['igv'].= 'S/ '; $moneda_tipo="Soles"; }
                            else if($nota_credito->moneda == 2){ $nestedData['total'].= '$'; $nestedData['subtotal'].= '$'; $nestedData['igv'].= '$';  $moneda_tipo="Dólares"; } 
                            else{ $nestedData['total'].= '€'; $nestedData['subtotal'].= '€'; $nestedData['igv'].= '€';  $moneda_tipo="Euros"; }
                            $nestedData['subtotal'].=number_format((float)$nota_credito->subtotal, 2, '.', '');
                            $nestedData['igv'].=number_format((float)$nota_credito->igv, 2, '.', '');
                            $nestedData['total'].=number_format((float)$nota_credito->total, 2, '.', '');

                $nestedData['moneda']= $moneda_tipo;
                        

                $nestedData['name']= $nota_credito->name.' '.$nota_credito->lastname;

                          if($nota_credito->estado_doc == 0) {
                            $nestedData['estado_doc']='<button id="status" class="btn btn-success" data-id_nota_creditoh="'.$nota_credito->id_nota_creditoh.'" data-status="1" >  NCT </button>';
                        }      elseif($nota_credito->estado_doc == 1) {
                            $nestedData['estado_doc']='<button id="status" class="btn btn-primary"  data-status="3" > NCP </button>';
                        }      elseif($nota_credito->estado_doc == 3) {
                            $nestedData['estado_doc']='<button id="status" class="btn btn-info"  data-status="3" > Descuento </button>';
                        } else{
                            $nestedData['estado_doc']= '<button id="status" class="btn btn-secondary" data-id_nota_creditoh="'.$nota_credito->id_nota_creditoh.'" data-status="0" > Anulada </button>';
                        } 
                        
                        $nestedData['cliente_extra']=$nota_credito->ruc_dni.'-'.$nota_credito->contacto_nombre.'-'.$nota_credito->contacto_telefono;
                        $nestedData['acciones']='';
                        if(!($nota_credito->codeG > 0 && $nota_credito->codeG < 4000 && $nota_credito->estado_doc==3)){
                            if(($nota_credito->codeG <=138||$nota_credito->codeG >=4000 )  && (is_null($nota_credito->cdr_file)  || $nota_credito->cdr_file=='') && $nota_credito->correlativoG>0){
                                $nestedData['acciones'].="<button type='button' class='btn btn-success btn-xs'
                                    id='actualizar' data-id='{$nota_credito->id_nota_creditoh}'>
                                <i class='glyphicon glyphicon-refresh position-center'></i>
                            </button>";
                            }
                            $nestedData['acciones'].="<button type='button' class='btn btn-info btn-xs'
                                    id='imprimir' data-id='{$nota_credito->id_nota_creditoh}'
                                    data-archivo='{$nota_credito->pdf_file}'>                                    
                                <i class='glyphicon glyphicon-print position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-toggle = 'modal'
                                    id='observacion' data-id='{$nota_credito->id_nota_creditoh}'
                                    data-numeracion = '{$nota_credito->numeracion } '
                                    data-observacion = '{$nota_credito->comentarios } '>
                                <i class='icon-comments position-center'></i>
                            </button>";
                        }

                    

                $data[] = $nestedData;

            }
        }
        


          
        $json_data =array(
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
            header('Content-Disposition: attachment;filename="NotasCredito-SolucionesOGGK.xls"');
            header('Cache-Control: max-age=0');
            //ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }

/////////////////////////////////////////NO USADA////////////////// solo si piden, falta arreglarla
    function exportData(Request $request){    
        $columns = array( 
            0 =>'numeracion', 
            1 =>'tipo',
            2=> 'fact',
            3=> 'codigoNB',
            4=> 'created_at',
            5=> 'f_devolucion',
            6=> 'razon_social',
            7=> 'ruc_dni',
            8=> 'subtotal',
            9=> 'igv',
            10=> 'total',
            11=> 'moneda',
            12=> 'name',
            13=> 'estado_doc',
            14=> 'cliente_extra',
            15=> 'numeracion',
            );  
         $data_array [] = array( 
                            "Correlativo", 
                            "Tipo",
                            "N° Factura/Boleta (NF)",
                            "N° NubeFact",
                            "Fecha de Emisión",
                            "Fecha de Devolución",
                            "Cliente",
                            "RUC/DNI",
                            "Base Imponible",
                            "IGV",
                            "Total",
                            "Tipo de Moneda",
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
            $nota_creditos = DB::table('nota_creditoh')->select('nota_creditoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'cajah.codigoNB as fact')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah')
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = DB::table('nota_creditoh')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah')->count();

        }else{
            $search = $request['search']; 
            $nota_creditos = DB::table('nota_creditoh')->select('nota_creditoh.*','clientes.razon_social', 'clientes.ruc_dni', 'clientes.contacto_nombre', 'clientes.contacto_telefono', 'users.name', 'users.lastname', 'cajah.codigoNB as fact')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where(function ($query) use ($search)  {
                $query->where('nota_creditoh.numeracion','like','%'.$search.'%')
                ->orWhere('nota_creditoh.codigoNB','like','%'.$search.'%')
                ->orWhere('nota_creditoh.total','like','%'.$search.'%')

                ->orWhere('clientes.razon_social','like','%'.$search.'%')
                ->orWhere('clientes.ruc_dni','like','%'.$search.'%')
                ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('clientes.contacto_nombre','like','%'.$search.'%');

                
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah')
            ->orderBy($order,$dir)
            ->get();  
                //->paginate($cant);

            $totalFiltered = DB::table('nota_creditoh')
            ->where('nota_creditoh.idempresa',$empresa)
            ->where('nota_creditoh.created_at', '>=', $f_inicio)
            ->where('nota_creditoh.created_at', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('nota_creditoh.numeracion','like','%'.$search.'%')
                ->orWhere('nota_creditoh.codigoNB','like','%'.$search.'%')
                ->orWhere('clientes.razon_social','like','%'.$search.'%')
                ->orWhere('clientes.ruc_dni','like','%'.$search.'%')
                ->orWhere('cajah.codigoNB','like','%'.$search.'%')
                ->orWhere('nota_creditoh.total','like','%'.$search.'%')
                ->orWhere('users.name','like','%'.$search.'%')
                ->orWhere('clientes.contacto_nombre','like','%'.$search.'%');




                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join ('cajah', 'cajah.idcajah', '=', 'nota_creditoh.idcajah') 
            ->count(); 

        }
       
        $formato = 'Y-m-d H:i:s';       

        foreach($nota_creditos as $nota_credito)
        {
            $fecha = DateTime::createFromFormat($formato, $nota_credito->created_at);

            $est;
            if($nota_credito->estado_doc == 0) {
                $est='NCT';
            }      elseif($nota_credito->estado_doc == 1) {
                $est='NCP';
            }      elseif($nota_credito->estado_doc == 3) {
                $est='Descuento';
            } else{
                $est= 'Anulada';
            } 
            $type;
            if($nota_credito->tipo == 1) {
                $type= 'Nota Crédito';
            } elseif($nota_credito->tipo == 2){
                $type= 'Devolución';
            } else{
                $type= '--';
            }
            $moneda_tipo;
            if($nota_credito->moneda == 1){$moneda_tipo="Soles"; }
            else if($nota_credito->moneda == 2){ $moneda_tipo="Dólares"; } 
            else{ $moneda_tipo="Euros"; }
            $data_array[] = array(
                'Correlativo' =>str_pad($nota_credito->numeracion, 6, "0", STR_PAD_LEFT), 
                'Tipo'=> $type,
                'N° Factura/Boleta (NF)'=>$nota_credito->fact,
                'N° NubeFact'=>$nota_credito->codigoNB,               
                'Fecha de Emisión'=>date_format($fecha, 'd/m/Y'),
                'Fecha de Devolución'=>date_format(date_create_from_format('Y-m-d', $nota_credito->f_devolucion), 'd/m/Y'),
                'Cliente'=>$nota_credito->razon_social,
                'RUC/DNI' => $nota_credito->ruc_dni,
                'Base Imponible'=>number_format((float)$nota_credito->subtotal, 2, '.', ''),                
                'IGV'=>number_format((float)$nota_credito->igv, 2, '.', ''),                
                'Total'=>number_format((float)$nota_credito->total, 2, '.', ''),                
                'Tipo de Moneda'=> $moneda_tipo,                
                'Vendedor'=>$nota_credito->name.' '.$nota_credito->lastname,
                'Estado'=>$est
            );
        }

        $this->ExportExcel($data_array);
    }


    public function index_detallado()
    {
        $empresa = Auth::user()->idempresa;

        $cajas = DB::table('nota_creditoh')->select('nota_creditoh.*','nota_creditod.*','producto.nombre','clientes.razon_social', 'users.name', 'users.lastname')
            ->join ('nota_creditod', 'nota_creditod.id_nota_creditoh', '=', 'nota_creditoh.id_nota_creditoh')
            ->join ('clientes', 'nota_creditoh.idcliente', '=', 'clientes.idcliente')
            ->join ('users', 'users.id', '=', 'nota_creditoh.idvendedor')
            ->join('producto','nota_creditod.idproducto','=','producto.idproducto')
            ->get();

        return view('nota_credito/listado_nota_credito_detallado', ['cajas' => $cajas]);
    }
    
    public function crear(Request $request)        
    {   
        $idcaja = 0;
        if ($request->id) {
            $idcaja = $request->id;
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
                        
        $vendedores = DB::table('users')->where('tienda_user',0)->get();

        $despachadores = DB::table('users')
                    ->where('puesto', '=', 7)->get();     
                    
        $transportes = DB::table('transporte')->get();      

        $almacenes = DB::table('almacen')->get();                       

        return view('nota_credito/nota_credito')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('despachadores',$despachadores)->with('transportes',$transportes)
            ->with('almacenes',$almacenes)->with('idcaja', $idcaja);
    }

    public function crearsolopdf(Request $request)        
    {   
        $idcaja = 0;
        if ($request->id) {
            $idcaja = $request->id;
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
                        
        $vendedores = DB::table('users')->where('tienda_user',0)->get();

        $despachadores = DB::table('users')
                    ->where('puesto', '=', 7)->get();     
                    
        $transportes = DB::table('transporte')->get();      

        $almacenes = DB::table('almacen')->get();                       

        return view('nota_credito/nota_creditopdf')->with('products',$products)->with('usuario',$usuario)->with('vendedores',$vendedores)->with('despachadores',$despachadores)->with('transportes',$transportes)
            ->with('almacenes',$almacenes)->with('idcaja', $idcaja);
    }

   public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $almacen = $request['almacen'];

        $products = DB::table('almacenlote as a')
            ->select('producto.*','categorias.descripcion as categoria', 'a.idalmacen', DB::raw("SUM(l.stock_lote) as stockT"))

            ->leftjoin('lote as l', 'a.idlote', '=', 'l.idlote')
            ->leftjoin('producto', 'l.idproducto', '=', 'producto.idproducto')
            ->where('a.idalmacen', '=', $almacen)
            
            ->Where(function ($query2) use ($busqueda) {
                $query2->where('producto.barcode', 'like', '%'.$busqueda.'%')
                    ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
                    ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%');
            })
            
            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->groupBy('producto.idproducto')
            ->get();

        return json_encode($products);
    }

    public function buscar_lote(Request $request){
        $busqueda = $request['query'];
        $almacen = $request['almacen'];
        $lotes = DB::table('lote')
            ->select('lote.idlote','lote.codigo')
            ->join('almacenlote', 'almacenlote.idlote', '=', 'lote.idlote')
            ->where('almacenlote.idalmacen', '=', $almacen)
            ->where('lote.idproducto', '=', $busqueda)
            ->get();

        return json_encode($lotes);
    }

    public function buscar_lote_simple(Request $request){
        $busqueda = $request['query'];
        $lotes = DB::table('lote')
            ->select('lote.idlote','lote.codigo')
            ->where('lote.idlote', '=', $busqueda)
            ->first();

        return json_encode($lotes);
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

    public function buscar_ft_numeracion(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('cajah')
            ->select('idcajah','numeracion','tipo')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->where('correlativo_inside',0)
            ->Where(function ($query2) use ($busqueda) {
                $query2->where('estado_doc',0)
                        ->orwhere('estado_doc',2)
                        ->orwhere('estado_doc',1);
            })
            ->whereNotNull('cdr_file')
            ->where('cdr_file','!=',"")
            ->get();
        return json_encode($cotis);
    }

    public function buscar_ft_numeracionpdf(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('cajah')
            ->select('idcajah','numeracion','tipo')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->where('correlativo_inside',0)           
            ->get();
        return json_encode($cotis);
    }

    public function buscar_ft_todo(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('cajah')
            ->select('cajah.*', 'c.razon_social', 'gh.idalmacen')
            ->join('clientes as c', 'cajah.idcliente', '=', 'c.idcliente')
            ->leftjoin( 'cajaguiaventa as cg' , 'cg.idcaja', '=',  'cajah.idcajah')
            ->leftjoin( 'guia_remisionh as gh' , 'gh.id_guia_remisionh', '=',  'cg.idguia')
            ->where('idcajah','=', $busqueda)
            ->first();

        $cotis->detalle = DB::table('cajad')
            ->select('cajad.*', 'p.*',

                DB::raw("case   when 
                                    (SELECT gd.idlote FROM cajaguiaventa cg LEFT JOIN guia_remisiond as gd on gd.id_guia_remisionh = cg.idguia WHERE cg.idcaja=cajad.idcajah and gd.idproducto=cajad.idproducto limit 1) = 0
                                then cajad.idlote
         
                                else (SELECT gd.idlote FROM cajaguiaventa cg LEFT JOIN guia_remisiond as gd on gd.id_guia_remisionh = cg.idguia WHERE cg.idcaja=cajad.idcajah and gd.idproducto=cajad.idproducto limit 1)
                        end as idlote")
            )
            ->join('producto as p', 'p.idproducto', '=', 'cajad.idproducto')            
            ->where('cajad.idcajah','=', $busqueda)
            ->get();

 
        return json_encode($cotis);
    }

    public function buscar_ft_todopdf(Request $request){
        $busqueda = $request['query'];
        $cotis = DB::table('cajah')
            ->select('cajah.*', 'c.razon_social', 'gh.idalmacen')
            ->join('clientes as c', 'cajah.idcliente', '=', 'c.idcliente')
            ->leftjoin( 'cajaguiaventa as cg' , 'cg.idcaja', '=',  'cajah.idcajah')
            ->leftjoin( 'guia_remisionh as gh' , 'gh.id_guia_remisionh', '=',  'cg.idguia')
            ->where('idcajah','=', $busqueda)
            ->first();

        $cotis->detalle = DB::table('cajad')
            ->select('cajad.*', 'p.*',

                DB::raw("case   when 
                                    (SELECT gd.idlote FROM cajaguiaventa cg LEFT JOIN guia_remisiond as gd on gd.id_guia_remisionh = cg.idguia WHERE cg.idcaja=cajad.idcajah and gd.idproducto=cajad.idproducto limit 1) = 0
                                then cajad.idlote
         
                                else (SELECT gd.idlote FROM cajaguiaventa cg LEFT JOIN guia_remisiond as gd on gd.id_guia_remisionh = cg.idguia WHERE cg.idcaja=cajad.idcajah and gd.idproducto=cajad.idproducto limit 1)
                        end as idlote")
            )
            ->join('producto as p', 'p.idproducto', '=', 'cajad.idproducto')            
            ->where('cajad.idcajah','=', $busqueda)
            ->get();

 
        return json_encode($cotis);
    }

    public function store(Request $request){

        $msg = '';
        DB::beginTransaction(); // <-- first line  
        
        try{

            $empresa = Auth::user()->idempresa;
            $sucursal = Auth::user()->idsucursal;
            $idusuario = Auth::user()->id;

            $idcliente = $request['idcliente'];
            $idcajah = $request['idcajah'];
            $subtotal = $request['subtotal'];
            $igv = $request['igv'];
            $total = $request['total'];
            $paga = $request['paga'];
            $vuelto = $request['vuelto'];
            $descuento = $request['descuento'];
            $comentarios = $request['comentarios'];
            $f_devolucion = $request['f_devolucion'];
            $f_cobro = $request['f_cobro'];
            $idvendedor = $request['idvendedor'];
            $razon = $request['razon'];
            $tipo = $request['tipo'];    
            $almacen = $request['almacen'];        
            $moneda = $request['moneda'];
            $codigoNB = $request['codigoNB'];
            $fechaNB = $request['fechaNB'];
            $tipo_descuento = $request['tipo_descuento'];
            $state = 1; 
            $maximo_num = DB::table('nota_creditoh')
                            ->where('tipo', $tipo)
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('numeracion');      

            $bool=true;                            
            $saved=false;
            $saved1=false;
            $saved2=false;
            $saved3=false;
            $saved4=false;
            $saved5=false;
            $sum=0;

            $is_invoice = $request['is_invoice'];
            $xml_file = '';
            $cdr_file = '';
            $pdf_file = '';
            $codeG = '';
            $descriptionG = '';
            $correlativoG = 0;
            $tipo_doc = null;

            //$existente= Fe::where('id_doc',$idcajah)->where('tipo',7)->first();

            if ($is_invoice == '0') {
                /*if($existente){                    
                    $request=json_decode($existente->request, true);
                    $resp=$this->checkCDRNC($request);                    
                }*/

                $resp = $this->generateNoteGreen($request);

                if ($resp['created'] == 997) {
                    return json_encode([$resp]);
                }
                else if ($resp['created'] == 501) {                 
                   
                    //return json_encode([$resp]);

                    $msg = $resp['msg'];
                    $codigoNB = $resp['codigoNB'];
                    $xml_file = $resp['xml_file'];                    
                    $pdf_file = $resp['pdf_file'];
                    $correlativoG = $resp['correlativoG'];
                    $tipo_doc = $resp['tipo_doc'];
                    $codeG = $resp['codeG'];
                    $descriptionG = $resp['descriptionG'];

                    




                } else {
                    $msg = $resp['msg'];
                    $codigoNB = $resp['codigoNB'];
                    $xml_file = $resp['xml_file'];
                    $cdr_file = $resp['cdr_file'];
                    $pdf_file = $resp['pdf_file'];
                    $correlativoG = $resp['correlativoG'];
                    $tipo_doc = $resp['tipo_doc'];
                    $codeG = $resp['codeG'];
                    $descriptionG = $resp['descriptionG'];
                }
            }

            if ( DB::table('nota_creditoh')->where('codigoNB', '=', $codigoNB)->first() )
                $bool = false;            

            if( $bool ){
                $nota_creditoh = new NotaCreditoH;
                $nota_creditoh->idempresa = $empresa;
                $nota_creditoh->idsucursal = $sucursal;
                $nota_creditoh->idusuario = $idusuario;
                $nota_creditoh->idcliente = $idcliente;
                $nota_creditoh->idalmacen = $almacen;
                $nota_creditoh->idcajah = $idcajah;             //ID FACTURA-BOLETA
                $nota_creditoh->idvendedor = $idvendedor;
                $nota_creditoh->razon = $razon;
                $nota_creditoh->numeracion = intval($maximo_num) + 1 ;
                $nota_creditoh->tipo = $tipo;
                $nota_creditoh->paga = $paga;
                $nota_creditoh->igv = $igv;
                $nota_creditoh->vuelto = $vuelto;
                $nota_creditoh->descuento = $descuento;
                $nota_creditoh->subtotal = $subtotal;
                $nota_creditoh->total = $total;
                $nota_creditoh->comentarios = $comentarios;
                $nota_creditoh->f_devolucion = $f_devolucion;
                $nota_creditoh->f_cobro = $f_cobro;
                $nota_creditoh->moneda = $moneda;
                $nota_creditoh->codigoNB = $codigoNB;
                $nota_creditoh->tipo_descuento = $tipo_descuento;
                $nota_creditoh->correlativoG = $correlativoG;
                $nota_creditoh->tipo_doc = $tipo_doc;
                $nota_creditoh->xml_file = $xml_file;
                $nota_creditoh->cdr_file = $cdr_file;
                $nota_creditoh->pdf_file = $pdf_file;
                $nota_creditoh->codeG = $codeG;
                $nota_creditoh->descriptionG = $descriptionG;

                $saved = $nota_creditoh->save();
                if($resp['created'] == 501){

                    $festore= new Fe;
                    $festore->tipo=7 ;
                    $festore->id_doc=$nota_creditoh->id_nota_creditoh;
                    $festore->request=json_encode($request->all());
                    $festore->save();
                } 
                else  if((int)$nota_creditoh->codeG>0 && (int)$nota_creditoh->codeG<4000){
                    $festore= new Fe;
                    $festore->tipo=7 ;
                    $festore->id_doc=$nota_creditoh->id_nota_creditoh;
                    $festore->request=json_encode($request->all());
                    $festore->save();
                }    
            
                $productos_json = $request['productos'];
                $productos = json_decode($productos_json);

                $cants = [];
                $detail_ft = DB::table('cajad')
                                        ->where('idcajah',$idcajah)->get();

                
                foreach ($detail_ft as $key => $value) {
                    $cants[$value->idproducto]['cantidad'] = $value->cantidad;
                }

                for($i = 0; $i < count($productos); $i++){

                    if ($cants[$productos[$i]->idproducto]['cantidad'] < $productos[$i]->stock_total) {
                        DB::rollBack(); // YES --> error de lotes
                        $respuesta = array();
                        $respuesta[] = ['created'=> 997];
                        $respuesta[] = ['id' => 9999999999];
                        return json_encode($respuesta); 
                    }
                    $cants[$productos[$i]->idproducto]['cantidad'] -= $productos[$i]->stock_total;

                    if ($tipo_descuento==1) {

                        $nota_creditod = new NotaCreditoD;
                        $nota_creditod->id_nota_creditoh = $nota_creditoh->id_nota_creditoh;
                        $nota_creditod->idproducto = $productos[$i]->idproducto;
                        $nota_creditod->idlote = $productos[$i]->idlote;
                        $nota_creditod->cantidad = $productos[$i]->stock_total;
                        $nota_creditod->precio_unit = $productos[$i]->precio;
                        $nota_creditod->precio_total = $productos[$i]->precio * $productos[$i]->stock_total ;
                        $nota_creditod->idempresa = $empresa;
                        $saved3=$nota_creditod->save();

                    } else { //si no es descuento

                        $product = Producto::find($productos[$i]->idproducto);
                        if ($product->tipo == 1) {
                            $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->stock_total;                            
                            
                            $saved1 = $product->save();

                            

                        } else {
                            $saved1 = true;
                            $saved2 = true;
                            $saved4 = true;
                        }
                        $nota_creditod = new NotaCreditoD;
                        $nota_creditod->id_nota_creditoh = $nota_creditoh->id_nota_creditoh;
                        $nota_creditod->idproducto = $productos[$i]->idproducto;
                        $nota_creditod->idlote = $productos[$i]->idlote;
                        $nota_creditod->cantidad = $productos[$i]->stock_total;
                        $nota_creditod->precio_unit = $productos[$i]->precio;
                        $nota_creditod->precio_total = $productos[$i]->precio * $productos[$i]->stock_total ;
                        $nota_creditod->idempresa = $empresa;
                        $saved3=$nota_creditod->save();
                    }

                }




                //f ($tipo_descuento==1) {

                    $isTotal = true;
                    foreach ($cants as $key => $value) {
                        if ($value['cantidad'] > 0) {
                            $isTotal = false;
                        }
                    }

                    if ($tipo_descuento==1) {
                        $ft_state = CajaH::find($idcajah);
                        $ft_state->estado_doc = 6;
                        $ft_state->total_nc = $ft_state->total - $total;
                        $ft_state->is_ncp = true;
                        $saved5=$ft_state->save();

                        $nota_creditoh->estado_doc=3;
                        $saved=$nota_creditoh->save();
                        $saved1=true;
                        $saved2=true;
                        $saved4=true;

                        $pagos_recibidos = PagoRecibido::where('idcajah', $idcajah)->orderBY('created_at','asc')->get();
                        $ov_state = OrdenVentaH::find($ft_state->id_orden_ventah);
                        
                        $total_pagado = 0;
                        for($i = 0; $i < count($pagos_recibidos); $i++){  
                            $total_pagado += $pagos_recibidos[$i]->pagado;
                        }
                        if ($total_pagado >= $ft_state->total_nc) {
                            $ft_state->estado_doc = 2;
                            if($ft_state->status_cob != -1) {
                                $ft_state->status_cob = 3;

                                $min_status_fact = DB::table('cajah')
                                                        ->where('id_orden_ventah', $ft_state->id_orden_ventah)
                                                        ->where('idcajah', '!=', $ft_state->idcajah)
                                                        ->where('status_cob', '!=', 0)
                                                        ->min('status_cob');

                                if (intval($min_status_fact) == 0) {
                                    $min_status_fact = $ft_state->status_cob;
                                } else {
                                    $min_status_fact = min(intval($min_status_fact), $ft_state->status_cob);
                                }
                                $ov_state->status_cob = $min_status_fact;
                                //$ov_state->save();
                            }
                            $ft_state->save();
                        }

                        $new_total = $ft_state->total_nc;
                        for($i = 0; $i < count($pagos_recibidos); $i++){
                            $pagos_recibidos[$i]->total = $ft_state->total_nc;
                            if ($new_total < $pagos_recibidos[$i]->pagado) {
                                $pagos_recibidos[$i]->pagado = min($new_total, $pagos_recibidos[$i]->pagado);
                            }
                            $pagos_recibidos[$i]->por_pagar = $new_total - $pagos_recibidos[$i]->pagado;
                            $pagos_recibidos[$i]->save();
                            $new_total -= $pagos_recibidos[$i]->pagado;
                        }

                        $ov_state->is_ncp = true;
                        $ov_state->save();

                        $cajaguia = DB::table('cajaguiaventa')->select('idguia')->where('idcaja',$idcajah)->get();

                        for($i = 0; $i < count($cajaguia); $i++){ 
                            $gr = GuiaRemisionH::find($cajaguia[$i]->idguia);
                            $gr->is_ncp = true;
                            $gr->save();
                        }
                        
                    } else if ($isTotal) {
                        $ft_state = CajaH::find($idcajah);
                        $ft_state->estado_doc = 5;
                        $id_ov = $ft_state->id_orden_ventah;
                        $idcotizacionh=0;
                        $ft_state->status_cob = 4;
                        $saved5=$ft_state->save();

                        $cajaguia = DB::table('cajaguiaventa')->select('idguia')->where('idcaja',$idcajah)->get();
                        $saved2=true;
                        $saved4=true;        
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
                                        $transacts->tipo_documento = 2;
                                        $transacts->iddocumento = $nota_creditoh->id_nota_creditoh;          
                                        $transacts->tipo = 1;                   
                                        
                                        $transacts->cantidad =$grd[$j]->cantidad_ent;                         
                                        $transacts->stockT = $stockT_suma + $grd[$j]->cantidad_ent;

                                        $transacts->state = 1;               
                                        $saved4 = $transacts->save();          





                                    }                              
                                   
                                }

     
                            $gr->estado_doc = 3;

                            //revisamos si la ov tiene otras guias de remision , para no ponerle estado 4
                            $gr->status_ent = 4;
                            $gr->save();
                           
                        }


                        $pagos_recibidos = PagoRecibido::where('idcajah', $idcajah)   //actualizar montos de loas pagos recibidos asociados a esa factura
                        ->update([
                            'total' => 0,
                            'pagado' => 0,
                            'por_pagar' => 0


                        ]);


                        /*for($i = 0; $i < count($pagos_recibidos); $i++){  }*/

                        
                        
                        if ( $id_ov != 0 && $id_ov != '0' && $id_ov!=null){
                            $ov = OrdenVentaH::find($id_ov);    
                            $idcotizacionh = $ov->idcotizacionh;
                            $ov->estado_doc = 2;
                            $ov->status_doc = 4;
                            $ov->save();
                        }

                        if ( $idcotizacionh != 0 && $idcotizacionh != '0' && $idcotizacionh != null ){
                            $coti_state = CotizacionH::find($idcotizacionh);
                            $coti_state->estado_doc = 2;
                            $coti_state->save();
                        }

                    } else {
                        $ft_state = CajaH::find($idcajah);
                        $ft_state->estado_doc = 4;
                        $ft_state->total_nc = $ft_state->total - $total;
                        $ft_state->is_ncp = true;
                        $saved5=$ft_state->save();

                        $nota_creditoh->estado_doc=1;
                        $saved=$nota_creditoh->save();
                        $saved1=true;
                        $saved2=true;
                        $saved4=true;

                        $prodLotes = [];
                        for($i = 0; $i < count($productos); $i++){

                            $product = Producto::find($productos[$i]->idproducto);
                            if ($product->tipo == 1) {
                                if (isset($prodLotes[$productos[$i]->idlote])) {
                                    $prodLotes[$productos[$i]->idlote]['cantidad_nota'] += $productos[$i]->stock_total;
                                } else {
                                    $prodLotes[$productos[$i]->idlote]['idproducto'] = $productos[$i]->idproducto;
                                    $prodLotes[$productos[$i]->idlote]['cantidad_nota'] = $productos[$i]->stock_total;
                                    $prodLotes[$productos[$i]->idlote]['cantidad_entregada'] = 0;
                                    $prodLotes[$productos[$i]->idlote]['cantidad'] = 0;
                                }
                            }
                        }

                        $cajaguia = DB::table('cajaguiaventa')->select('idguia')->where('idcaja',$idcajah)->get();

                        for($i = 0; $i < count($cajaguia); $i++){ 
                            $gr = GuiaRemisionH::find($cajaguia[$i]->idguia);
                            if($gr->status_ent==2 || $gr->status_ent==3 ){
                                $grd= GuiaRemisionD::where('id_guia_remisionh', $cajaguia[$i]->idguia)->get();
                                for($j = 0; $j < count($grd); $j++){
                                    if (isset($prodLotes[$grd[$j]->idlote])) {
                                        $prodLotes[$grd[$j]->idlote]['cantidad_entregada'] += $grd[$j]->cantidad_ent;
                                        $prodLotes[$grd[$j]->idlote]['cantidad'] += $grd[$j]->cantidad;
                                    }
                                }
                            }
                        }

                        foreach ($prodLotes as $key => $value) {
                            $cant_ent = max($value['cantidad_nota'] + $value['cantidad_entregada'] - $value['cantidad'], 0);
                            if ($cant_ent > 0) {
                                $product = Producto::find($value['idproducto']);
                                $product->stock_total = $product->stock_total + $cant_ent;
                                $saved1 = $product->save();

                                $stockT_suma=0;
                                $stockT_tmp = DB::table('lote')->select(DB::raw("SUM(stock_lote) as stockT"))
                                    ->where('idproducto', $value['idproducto'])
                                    ->groupBy('idproducto')->first();

                                if( $stockT_tmp == null){                   
                                    $stockT_suma = 0;
                                }else {
                                    $stockT_suma = $stockT_tmp->stockT;
                                }

                                $lote = Lote::find($key);
                                $lote->stock_lote = $lote->stock_lote + $cant_ent;                
                                $saved2=$lote->save();

                                $transacts = new Transacciones;
                                $transacts->idproducto = $value['idproducto'];
                                $transacts->idempresa = $empresa;
                                $transacts->idsucursal = $sucursal;            
                                $transacts->idusuario = $idusuario;
                                $transacts->idalmacen = $almacen;
                                $transacts->idlote = $key;

                                $transacts->f_emision = date('Y-m-d');
                                $transacts->tipo_documento = 2;
                                $transacts->iddocumento = $nota_creditoh->id_nota_creditoh;          
                                $transacts->tipo = 1;                   
                                
                                $transacts->cantidad = $cant_ent;                         
                                $transacts->stockT = $stockT_suma + $cant_ent;

                                $transacts->state = 1;               
                                $saved4 = $transacts->save();
                            }
                        }

                        $pagos_recibidos = PagoRecibido::where('idcajah', $idcajah)->orderBY('created_at','asc')->get();
                        $ov_state = OrdenVentaH::find($ft_state->id_orden_ventah);
                        
                        $total_pagado = 0;
                        for($i = 0; $i < count($pagos_recibidos); $i++){  
                            $total_pagado += $pagos_recibidos[$i]->pagado;
                        }
                        if ($total_pagado >= $ft_state->total_nc) {
                            $ft_state->estado_doc = 2;
                            if($ft_state->status_cob != -1) {
                                $ft_state->status_cob = 3;

                                $min_status_fact = DB::table('cajah')
                                                        ->where('id_orden_ventah', $ft_state->id_orden_ventah)
                                                        ->where('idcajah', '!=', $ft_state->idcajah)
                                                        ->where('status_cob', '!=', 0)
                                                        ->min('status_cob');

                                if (intval($min_status_fact) == 0) {
                                    $min_status_fact = $ft_state->status_cob;
                                } else {
                                    $min_status_fact = min(intval($min_status_fact), $ft_state->status_cob);
                                }
                                $ov_state->status_cob = $min_status_fact;
                            }
                            $ft_state->save();
                        }

                        $new_total = $ft_state->total_nc;
                        for($i = 0; $i < count($pagos_recibidos); $i++){
                            $pagos_recibidos[$i]->total = $ft_state->total_nc;
                            if ($new_total < $pagos_recibidos[$i]->pagado) {
                                $pagos_recibidos[$i]->pagado = min($new_total, $pagos_recibidos[$i]->pagado);
                            }
                            $pagos_recibidos[$i]->por_pagar = $new_total - $pagos_recibidos[$i]->pagado;
                            $pagos_recibidos[$i]->save();
                            $new_total -= $pagos_recibidos[$i]->pagado;
                        }

                        $ov_state->is_ncp = true;
                        $ov_state->save();

                        //$cajaguia = DB::table('cajaguiaventa')->select('idguia')->where('idcaja',$idcajah)->get();

                        for($i = 0; $i < count($cajaguia); $i++){ 
                            $gr = GuiaRemisionH::find($cajaguia[$i]->idguia);
                            $gr->is_ncp = true;
                            $gr->save();
                        }


                    }
                    //$saved1=true;
                    //$saved2=true;
                    //$saved4=true;

                /*} else {

                    $ft_state = CajaH::find($idcajah);
                    $ft_state->estado_doc = 5;
                    //$ft_state->total_nc = $ft_state->total - $total;
                    $saved5=$ft_state->save();

                    $nota_creditoh->estado_doc=3;
                    $saved=$nota_creditoh->save();

                    $saved1=true;
                    $saved2=true;
                    $saved4=true;

                }*/

                /*for($i = 0; $i < count($productos); $i++){

                    if($tipo_descuento==1){

                        $nota_creditod = new NotaCreditoD;
                        $nota_creditod->id_nota_creditoh = $nota_creditoh->id_nota_creditoh;
                        $nota_creditod->idproducto = $productos[$i]->idproducto;
                        $nota_creditod->idlote = $productos[$i]->idlote;
                        $nota_creditod->cantidad = $productos[$i]->stock_total;
                        $nota_creditod->precio_unit = $productos[$i]->precio;
                        $nota_creditod->precio_total = $productos[$i]->precio * $productos[$i]->stock_total ;
                        $nota_creditod->idempresa = $empresa;
                        $saved3=$nota_creditod->save();

                    }else{

                        $nota_creditod = new NotaCreditoD;
                        $nota_creditod->id_nota_creditoh = $nota_creditoh->id_nota_creditoh;
                        $nota_creditod->idproducto = $productos[$i]->idproducto;
                        $nota_creditod->idlote = $lote->idlote;
                        $nota_creditod->cantidad = $productos[$i]->stock_total;
                        $nota_creditod->precio_unit = $productos[$i]->precio;
                        $nota_creditod->precio_total = $productos[$i]->precio * $productos[$i]->stock_total ;
                        $nota_creditod->idempresa = $empresa;
                        $saved3=$nota_creditod->save();

                        $transacts = new Transacciones;
                        $transacts->idproducto = $productos[$i]->idproducto;
                        $transacts->idempresa = $empresa;
                        $transacts->idsucursal = $sucursal;            
                        $transacts->idusuario = $idusuario;
                        $transacts->idalmacen = $almacen;
                        $transacts->idlote = $productos[$i]->idlote;

                        $transacts->f_emision = date('Y-m-d');
                        $transacts->tipo_documento = 2;
                        $transacts->iddocumento = $nota_creditoh->id_nota_creditoh;          
                        $transacts->tipo = 1;                   
                        
                        $transacts->cantidad = $productos[$i]->stock_total;                         
                        $transacts->stockT = $stockT_suma + $productos[$i]->stock_total;

                        $transacts->state = 1;               
                        $saved4 = $transacts->save();
                        
                        $sum+=$productos[$i]->stock_total;

                    }
                }

                //////CAMBIOS DE ESTADOS ANULADOS

                if($tipo_descuento==1){

                    $ft_state = CajaH::find($idcajah);
                    $ft_state->estado_doc = 6;
                    $ft_state->total_nc = $ft_state->total - $total;
                    $saved5=$ft_state->save();

                    $nota_creditoh->estado_doc=3;
                    $saved=$nota_creditoh->save();

                    $saved1=true;
                    $saved2=true;
                    $saved4=true;

                }else{

                    $id_ov=0;
                    $idcotizacionh=0;
                    if( $sum < round(DB::table('cajad')->where('idcajah',$idcajah)->sum('cantidad'),3) ) {            
                        $ft_state = CajaH::find($idcajah);
                        $ft_state->estado_doc = 4;
                        $ft_state->total_nc = $ft_state->total - $total;
                        $id_ov = $ft_state->id_orden_ventah;
                        $saved5=$ft_state->save();

                        $nota_creditoh->estado_doc=1;
                        $saved=$nota_creditoh->save();
                    }else{
                        $ft_state = CajaH::find($idcajah);
                        $ft_state->estado_doc = 5;
                        $id_ov = $ft_state->id_orden_ventah;
                        $saved5=$ft_state->save();

                        $cajaguia = DB::table('cajaguiaventa')->select('idguia')->where('idcaja',$idcajah)->get();
                        for($i = 0; $i < count($cajaguia); $i++){
                            $gr = GuiaRemisionH::find($cajaguia[$i]->idguia);                
                            $gr->estado_doc = 3;
                            $gr->save();
                        }
                        
                        if ( $id_ov != 0 && $id_ov != '0' && $id_ov!=null){
                            $ov = OrdenVentaH::find($id_ov);    
                            $idcotizacionh = $ov->idcotizacionh;
                            $ov->estado_doc = 2;
                            $ov->save();
                        }

                        if ( $idcotizacionh != 0 && $idcotizacionh != '0' && $idcotizacionh != null ){
                            $coti_state = CotizacionH::find($idcotizacionh);
                            $coti_state->estado_doc = 2;
                            $coti_state->save();
                        }

                    }
                }
                */
                ///******CAMBIOS DE ESTADOS ANULADOS******//////////
            }


            if ( $saved && $saved1 && $saved2 && $saved3 && $saved4 && $saved5 && $bool)
                $childModelSaved = true; 
            else
                $childModelSaved = false;        

        }catch(Exception $e)
            {
                 $childModelSaved = false;
            }
            if ($childModelSaved)
            {
                
                DB::commit(); // YES --> finalize it 
                $respuesta = array();
                $respuesta[] = ['created'=> 200];
                $respuesta[] = ['id' => $nota_creditoh->id_nota_creditoh];
                $respuesta[] = ['msg' => $msg];
                $respuesta[] = ['pdf' =>$nota_creditoh->pdf_file];
                $respuesta[] = ['code' =>$codeG];
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

     
                return json_encode($respuesta);
            }
            elseif( $saved==false && $bool==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999990];
                return json_encode($respuesta);   
            }
            elseif( $saved1==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999991];
                return json_encode($respuesta);   
            }
            elseif( $saved2==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999992];
                return json_encode($respuesta);   
            }
            elseif( $saved3==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999993];
                return json_encode($respuesta);   
            }
            elseif( $saved4==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999994];
                return json_encode($respuesta);   
            }
            elseif( $saved5==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 999];
                $respuesta[] = ['id' => 9999999995];
                return json_encode($respuesta);   
            }
            else
            {
                DB::rollBack(); // NO --> error de lotes
                $respuesta = array();
                $respuesta[]= ['created'=> 999];
                $respuesta[] = ['id' => 999999999];

                return json_encode($respuesta);
            }

    }

    public function checkCDRNC(Request $request){
        $id =$request['id'];
        $dirGreenter = 'greenter/';
        $tipoDocumento='07';
      $NC =NotaCreditoH::find($id);
        $rucEmisor= '20600819667';
        $festore= Fe::where('id_doc',$id)->where('tipo',7)->first();

        if($festore)
        $request=json_decode($festore->request, true);           




        $serie = 'FF01';
        if ($NC->tipo_doc == '1' or $NC->tipo_doc == 1) {
            $serie = 'BB01';
        }
       

        $correlativo=strval($NC->correlativoG);
        $result= parent::checkCDR($rucEmisor,$tipoDocumento,$serie,$correlativo);

        
        if (!$result->isSuccess()) {
            $see = parent::configInvoiceNoteGreen();
            $dirGreenter = 'greenter/';
            //$dirGreenter = base_path().'/public/greenter/';

            // Cliente
            $client = parent::getClientGreen($request['idcliente']);

            // Emisor
            $company = parent::getCompanyGreen();

            $cajah = CajaH::find($request['idcajah']);
        

            $tipoDoc = '07';
            $serie = 'FF01';
            $tipoDocAfectado = '01';
            $tipoDocErp = 2;
            if ($cajah->tipo == 1) {
                $serie = 'BB01';
                $tipoDocAfectado = '03';
                $tipoDocErp = 1;
            }
            $correlativo = strval($correlativo);
            $codigoNB = $serie.'-'.sprintf('%06d', $correlativo);
            $codMotivo = $request['cod_motivo'];
            $descMotivo = $request['desc_motivo'];
            $arrayTipoMoneda = [1 => ['PEN', 'soles'], 2 => ['USD', 'dolares americanos'], 3 => ['EUR', 'euros']];
            $tipoMoneda = $arrayTipoMoneda[(int)$request['moneda']][0];
            $currency = $arrayTipoMoneda[(int)$request['moneda']][1];
            $montoGravada = (float) $request['subtotal'];
            $montoIgv = (float) $request['igv'];
            $montoImpVenta = $montoGravada + $montoIgv;
            $fechaEmision = $request['fechaNB'];
            $fechaVencimiento = $request['f_devolucion'];        

            $note = (new Note())
                ->setUblVersion('2.1')
                ->setTipoDoc($tipoDoc)
                ->setSerie($serie)
                ->setCorrelativo($correlativo)
                ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
                //->setFecVencimiento(new DateTime($fechaVencimiento.' 12:00:00-05:00'))
                ->setTipDocAfectado($tipoDocAfectado) // Tipo Doc: Factura
                ->setNumDocfectado($cajah->codigoNB) // Factura: Serie-Correlativo
                ->setCodMotivo($codMotivo) // Catalogo. 09
                ->setDesMotivo($descMotivo)
                ->setTipoMoneda($tipoMoneda)
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas($montoGravada)
                ->setMtoIGV($montoIgv)
                ->setTotalImpuestos($montoIgv)
                ->setMtoImpVenta($montoImpVenta);

            $productos = json_decode($request['productos']);
            $details = [];

            $cants = [];
            $detail_ft = DB::table('cajad')
                            ->where('idcajah',$request['idcajah'])->get();
            
            foreach ($detail_ft as $key => $value) {
                $cants[$value->idproducto]['cantidad'] = $value->cantidad;
            }

            for ($i = 0; $i < count($productos); $i++) {

                if ($cants[$productos[$i]->idproducto]['cantidad'] < $productos[$i]->stock_total) {
                    return ['created' => 997];
                }
                $cants[$productos[$i]->idproducto]['cantidad'] -= $productos[$i]->stock_total;

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

                $detail = (new SaleDetail())
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

                $details[] = $detail;
            }

            $formatter = new NumeroALetras();

            $legend = (new Legend())
                ->setCode('1000') // Monto en letras - Catalog. 52
                ->setValue($formatter->toInvoice($montoImpVenta, 2, $currency));

            $note->setDetails($details)
                ->setLegends([$legend]);

            // Envio a SUNAT.
            $result = $see->send($note);

            
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
            return json_encode(['created' => 501, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]);
        }
        $arguments = [
            $rucEmisor, //RUC
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
        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $msg = 'Excepción';
        }

        $msg .= ' '.$cdr->getDescription();

        $NC->cdr_file=$cdrFileName;
        $NC->codeG=0;
        $NC->descriptionG= $msg;
        $NC->save();
        //var_dump($cdr);
        $msg = 'El CDR del comprobante '.$serie.'-'.$correlativo.' se recupero correctamente.'.$NC->descriptionG;
        if($festore)
            $festore->delete();
        return json_encode(['created' => 200, 'msg' => $msg,'comprobante'=>$serie.'-'.$correlativo]);
        

    }
     

    public function show(Request $request){
    	$id = $request['id'];

    	$nota_credito = DB::table('nota_creditoh')
                ->where('id_nota_creditoh',$id)
                ->join('users','users.id','=','idvendedor')
                ->first();

    	$nota_creditoD = DB::table('nota_creditod')
                ->select('nota_creditod.*','producto.*',DB::raw("SUM(nota_creditod.cantidad) as canti"))
                ->where('nota_creditod.idempresa','=', $nota_credito->idempresa)
                ->where('nota_creditod.id_nota_creditoh','=',$nota_credito->id_nota_creditoh)
                ->join('producto','nota_creditod.idproducto','=','producto.idproducto')
                ->groupBy('nota_creditod.idproducto')
                ->get();

        $factura = DB::table('cajah')
                    ->select('cajah.numeracion as correlativo')
                    ->join('nota_creditoh','nota_creditoh.idcajah','=','cajah.idcajah')
                    ->where('nota_creditoh.id_nota_creditoh','=',$nota_credito->id_nota_creditoh)   
                    ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$nota_credito->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $cliente = DB::table('clientes')
                    ->where('idcliente','=',$nota_credito->idcliente)
                    ->first();

        return view('nota_credito/info_nota_credito')
            ->with('nota_credito',$nota_credito)
            ->with('nota_creditoD',$nota_creditoD)
            ->with('sucursal',$sucursal)
            ->with('factura',$factura)
            ->with('cliente',$cliente);

    }

    public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $notacreditoh = NotaCreditoH::find($id);
        $notacreditoh->estado_doc = $status;
        $notacreditoh->save();
        return response()->json(['accepted'], 202);
    }

    public function update_codigoNB(Request $request){
        $value = $request['value'];
        $id_nota_creditoh = $request['id_nota_creditoh'];
        $nota = NotaCreditoH::find($id_nota_creditoh);
        $nota->codigoNB = $value;
        $nota->save();
        return json_encode(['mensaje' => 200]);
    }

    public function update_vendedor(Request $request){
        $id_nota_creditoh = $request['id_nota_creditoh'];
        $vendedor = $request['value'];
        $nota = NotaCreditoH::find($id_nota_creditoh);
        $nota->idvendedor = $vendedor;
        $nota->save();
        return json_encode(['mensaje' => 200]);
    }

    public function nota_edit_comments(Request $request) {

        $nota_creditoh = NotaCreditoH::find($request['id_reg']);
        $nota_creditoh->comentarios = $request['comments'];
        $nota_creditoh->save();

        return json_encode(['mensaje' => 200]);
    }

    public function generateNoteGreen(Request $request) {

        $see = parent::configInvoiceNoteGreen();
        $dirGreenter = 'greenter/';
        //$dirGreenter = base_path().'/public/greenter/';

        // Cliente
        $client = parent::getClientGreen($request['idcliente']);

        // Emisor
        $company = parent::getCompanyGreen();

        $cajah = CajaH::find($request['idcajah']);

        $maxCorrelativo = DB::table('nota_creditoh')
                    ->where('tipo_doc', $cajah->tipo)
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;

        $tipoDoc = '07';
        $serie = 'FF01';
        $tipoDocAfectado = '01';
        $tipoDocErp = 2;
        if ($cajah->tipo == 1) {
            $serie = 'BB01';
            $tipoDocAfectado = '03';
            $tipoDocErp = 1;
        }
        $correlativo = strval($nextCorrelativo);
        $codigoNB = $serie.'-'.sprintf('%06d', $nextCorrelativo);
        $codMotivo = $request['cod_motivo'];
        $descMotivo = $request['desc_motivo'];
        $arrayTipoMoneda = [1 => ['PEN', 'soles'], 2 => ['USD', 'dolares americanos'], 3 => ['EUR', 'euros']];
        $tipoMoneda = $arrayTipoMoneda[(int)$request['moneda']][0];
        $currency = $arrayTipoMoneda[(int)$request['moneda']][1];
        $montoGravada = (float) $request['subtotal'];
        $montoIgv = (float) $request['igv'];
        $montoImpVenta = $montoGravada + $montoIgv;
        $fechaEmision = $request['fechaNB'];
        $fechaVencimiento = $request['f_devolucion'];        

        $note = (new Note())
            ->setUblVersion('2.1')
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
            //->setFecVencimiento(new DateTime($fechaVencimiento.' 12:00:00-05:00'))
            ->setTipDocAfectado($tipoDocAfectado) // Tipo Doc: Factura
            ->setNumDocfectado($cajah->codigoNB) // Factura: Serie-Correlativo
            ->setCodMotivo($codMotivo) // Catalogo. 09
            ->setDesMotivo($descMotivo)
            ->setTipoMoneda($tipoMoneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($montoGravada)
            ->setMtoIGV($montoIgv)
            ->setTotalImpuestos($montoIgv)
            ->setMtoImpVenta($montoImpVenta);

        $productos = json_decode($request['productos']);
        $details = [];

        $cants = [];
        $detail_ft = DB::table('cajad')
                        ->where('idcajah',$request['idcajah'])->get();
        
        foreach ($detail_ft as $key => $value) {
            $cants[$value->idproducto]['cantidad'] = $value->cantidad;
        }

        for ($i = 0; $i < count($productos); $i++) {

            if ($cants[$productos[$i]->idproducto]['cantidad'] < $productos[$i]->stock_total) {
                return ['created' => 997];
            }
            $cants[$productos[$i]->idproducto]['cantidad'] -= $productos[$i]->stock_total;

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

            $detail = (new SaleDetail())
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

            $details[] = $detail;
        }

        $formatter = new NumeroALetras();

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($formatter->toInvoice($montoImpVenta, 2, $currency));

        $note->setDetails($details)
            ->setLegends([$legend]);

        // Envio a SUNAT.
        $result = $see->send($note);

        // Guardar XML firmado digitalmente.
        $xmlFileName = $note->getName().'.xml';
        file_put_contents($dirGreenter.$xmlFileName, $see->getFactory()->getLastXml());

        $htmlReport = new HtmlReport();
        $htmlReport->setTemplate('invoice.html.twig');
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());
        $pdfFileName = $note->getName().'.pdf';

        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png'), // Logo de Empresa
                'hash' =>  parent::getHashXml($dirGreenter.$xmlFileName), // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'link'       => url($dirGreenter.$pdfFileName)
                //'extras'     => parent::getLeyendDoc()//,
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($note, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

        file_put_contents($dirGreenter.$pdfFileName, $pdf);

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            $code=(int) $result->getError()->getCode();
            return ['created' => 501, 'msg' => $result->getError()->getCode().' - '.$result->getError()->getMessage(),'codigoNB' => $codigoNB, 'correlativoG' => $correlativo, 'tipo_doc' => $tipoDocErp, 'xml_file' => $xmlFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' => $result->getError()->getCode().' - '.$result->getError()->getMessage()];
            //echo 'Codigo Error: '.$result->getError()->getCode();
            //echo 'Mensaje Error: '.$result->getError()->getMessage();
            exit();
        }

        // Guardamos el CDR
        $cdrFileName = 'R-'.$note->getName().'.zip';
        file_put_contents($dirGreenter.$cdrFileName, $result->getCdrZip());

        // CDR Resultado
        $cdr = $result->getCdrResponse();

        $code = (int)$cdr->getCode();

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
            //echo 'ESTADO: RECHAZADA'.PHP_EOL;
        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $msg = 'Excepción';
            //echo 'Excepción';
        }

        $msg .= ' '.$cdr->getDescription();

        

        return ['created' => 200, 'msg' => $msg, 'codigoNB' => $codigoNB, 'correlativoG' => $correlativo, 'tipo_doc' => $tipoDocErp, 'xml_file' => $xmlFileName, 'cdr_file' => $cdrFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' => $msg];

    }

    public function generatePDF(Request $request) {
        $see = parent::configInvoiceNoteGreen();
        $dirGreenter = 'greenter/';
        //$dirGreenter = base_path().'/public/greenter/';

        // Cliente
        $client = parent::getClientGreen($request['idcliente']);

        // Emisor
        $company = parent::getCompanyGreen();

        $cajah = CajaH::find($request['idcajah']);

        
        

        $tipoDoc = '07';
        $serie = 'FF01';
        $tipoDocAfectado = '01';
        $tipoDocErp = 2;
        if ($cajah->tipo == 1) {
            $serie = 'BB01';
            $tipoDocAfectado = '03';
            $tipoDocErp = 1;
        }
        $correlativo = '24';
        $codMotivo = $request['cod_motivo'];
        $descMotivo = $request['desc_motivo'];
        $arrayTipoMoneda = [1 => ['PEN', 'soles'], 2 => ['USD', 'dolares americanos'], 3 => ['EUR', 'euros']];
        $tipoMoneda = $arrayTipoMoneda[(int)$request['moneda']][0];
        $currency = $arrayTipoMoneda[(int)$request['moneda']][1];
        $montoGravada = (float) $request['subtotal'];
        $montoIgv = (float) $request['igv'];
        $montoImpVenta = $montoGravada + $montoIgv;
        $fechaEmision = $request['fechaNB'];
        $fechaVencimiento = $request['f_devolucion'];        

        $note = (new Note())
            ->setUblVersion('2.1')
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativo)
            ->setFechaEmision(new DateTime($fechaEmision.' 12:00:00-05:00'))
            //->setFecVencimiento(new DateTime($fechaVencimiento.' 12:00:00-05:00'))
            ->setTipDocAfectado($tipoDocAfectado) // Tipo Doc: Factura
            ->setNumDocfectado($cajah->codigoNB) // Factura: Serie-Correlativo
            ->setCodMotivo($codMotivo) // Catalogo. 09
            ->setDesMotivo($descMotivo)
            ->setTipoMoneda($tipoMoneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($montoGravada)
            ->setMtoIGV($montoIgv)
            ->setTotalImpuestos($montoIgv)
            ->setMtoImpVenta($montoImpVenta);

        $productos = json_decode($request['productos']);
        $details = [];

        $cants = [];
        $detail_ft = DB::table('cajad')
                        ->where('idcajah',$request['idcajah'])->get();
        
        foreach ($detail_ft as $key => $value) {
            $cants[$value->idproducto]['cantidad'] = $value->cantidad;
        }

        for ($i = 0; $i < count($productos); $i++) {

            if ($cants[$productos[$i]->idproducto]['cantidad'] < $productos[$i]->stock_total) {
                return ['created' => 997];
            }
            $cants[$productos[$i]->idproducto]['cantidad'] -= $productos[$i]->stock_total;

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

            $detail = (new SaleDetail())
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

            $details[] = $detail;
        }

        $formatter = new NumeroALetras();

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($formatter->toInvoice($montoImpVenta, 2, $currency));

        $note->setDetails($details)
            ->setLegends([$legend]);


        // Guardar XML firmado digitalmente.
        $xmlFileName = $note->getName().'.xml';
        file_put_contents($dirGreenter.$xmlFileName, $see->getXmlSigned($note));

        $htmlReport = new HtmlReport();
        $htmlReport->setTemplate('invoice.html.twig');
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());
        $pdfFileName = $note->getName().'.pdf';

        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png'), // Logo de Empresa
                'hash' =>  parent::getHashXml($dirGreenter.$xmlFileName), // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'link'       => url($dirGreenter.$pdfFileName)
                //'extras'     => parent::getLeyendDoc()//,
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($note, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

        file_put_contents($dirGreenter.$pdfFileName, $pdf);



    }

    /* testing greenter */

    public function testGreen() {

        $see = parent::configInvoiceNoteGreenTest();
        $dirGreenter = 'greenter/';

        // Cliente
        $client = (new Client())
            ->setTipoDoc('6')
            ->setNumDoc('20000000001')
            ->setRznSocial('EMPRESA X');

        // Emisor
        $address = (new Address())
            ->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion('Av. Villa Nueva 221')
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
            ->setRuc('20123456789')
            ->setRazonSocial('GREEN SAC')
            ->setNombreComercial('GREEN')
            ->setAddress($address);

        $note = new Note();
        $note
            ->setUblVersion('2.1')
            ->setTipoDoc('07')
            ->setSerie('FF01')
            ->setCorrelativo('123')
            ->setFechaEmision(new DateTime())
            ->setTipDocAfectado('01') // Tipo Doc: Factura
            ->setNumDocfectado('F001-111') // Factura: Serie-Correlativo
            ->setCodMotivo('07') // Catalogo. 09
            ->setDesMotivo('DEVOLUCION POR ITEM')
            ->setTipoMoneda('PEN')
            ->setGuias([/* Guias (Opcional) */
                (new Document())
                ->setTipoDoc('09')
                ->setNroDoc('0001-213')
            ])
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(200)
            ->setMtoIGV(36)
            ->setTotalImpuestos(36)
            ->setMtoImpVenta(236)
            ;

        $detail1 = new SaleDetail();
        $detail1
            ->setCodProducto('C023')
            ->setUnidad('NIU')
            ->setCantidad(2)
            ->setDescripcion('PROD 1')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00)
            ->setIgv(18)
            ->setTipAfeIgv('10')
            ->setTotalImpuestos(18)
            ->setMtoValorVenta(100)
            ->setMtoValorUnitario(50)
            ->setMtoPrecioUnitario(56);

        $detail2 = new SaleDetail();
        $detail2
            ->setCodProducto('C02')
            ->setUnidad('NIU')
            ->setCantidad(2)
            ->setDescripcion('PROD 2')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00)
            ->setIgv(18)
            ->setTipAfeIgv('10')
            ->setTotalImpuestos(18)
            ->setMtoValorVenta(100)
            ->setMtoValorUnitario(50)
            ->setMtoPrecioUnitario(56);

        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

        $note->setDetails([$detail1, $detail2])
            ->setLegends([$legend]);

        // Envio a SUNAT.
        $res = $see->send($note);
        // Guardar XML firmado digitalmente.
        file_put_contents($dirGreenter.$note->getName().'.xml',
            $see->getFactory()->getLastXml());
        //$util->writeXml($note, $see->getFactory()->getLastXml());

        if (!$res->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: '.$res->getError()->getCode();
            echo 'Mensaje Error: '.$res->getError()->getMessage();
            exit();
        }

        // Guardamos el CDR
        file_put_contents($dirGreenter.'R-'.$note->getName().'.zip', $res->getCdrZip());

        /**@var $res BillResult*/
        $cdr = $res->getCdrResponse();
        //$util->writeCdr($note, $res->getCdrZip());

        //$util->showResponse($note, $cdr);
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
