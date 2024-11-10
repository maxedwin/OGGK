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
use App\Models\OrdenCompraH;
use App\Models\OrdenCompraD;
use App\Models\FichaRecepcionH;
use Auth;
use DB;
use Helper;
use DateTime;

class OrdenCompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       
        // $cant = $request['cant'];
        // $query = $request['query'];



        return view('orden_compra/listado_orden_compra');
    }


    public function allOC(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1 =>'f_emision',
            2=> 'razon_social',
            3=> 'orden_comprah.moneda',
            4=> 'total',
            5=> 'estado_doc',
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

        $totalData =DB::table('orden_comprah')->where('orden_comprah.idempresa',$empresa)->count();
            
        if(empty($request->input('search.value'))){
                $orden_compras = DB::table('orden_comprah')
                ->where('orden_comprah.idempresa',$empresa)
                ->where('orden_comprah.f_emision', '>=', $f_inicio)
                ->where('orden_comprah.f_emision', '<=', $f_fin)
                ->join ('proveedores', 'orden_comprah.idproveedor', '=', 'proveedores.idproveedor')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
                //->paginate($cant);    
                                       
            $totalFiltered =DB::table('orden_comprah')
            ->where('orden_comprah.idempresa',$empresa)
            ->where('orden_comprah.f_emision', '>=', $f_inicio)
            ->where('orden_comprah.f_emision', '<=', $f_fin)
            ->join ('proveedores', 'orden_comprah.idproveedor', '=', 'proveedores.idproveedor')->count();

        }else{
            $search = $request->input('search.value'); 
            $orden_compras = DB::table('orden_comprah')
            ->where('orden_comprah.idempresa',$empresa)
            ->where('orden_comprah.f_emision', '>=', $f_inicio)
            ->where('orden_comprah.f_emision', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('orden_comprah.numeracion','like','%'.$search.'%')
                //->orWhere('orden_comprah.codigoNB','like','%'.$search.'%')
                ->orWhere('razon_social','like','%'.$search.'%');

                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->join ('proveedores', 'orden_comprah.idproveedor', '=', 'proveedores.idproveedor')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('orden_comprah')
            ->where('orden_comprah.idempresa',$empresa)
            ->where('orden_comprah.f_emision', '>=', $f_inicio)
            ->where('orden_comprah.f_emision', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('orden_comprah.numeracion','like','%'.$search.'%')
                //->orWhere('orden_comprah.codigoNB','like','%'.$search.'%')
                ->orWhere('razon_social','like','%'.$search.'%');
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->join ('proveedores', 'orden_comprah.idproveedor', '=', 'proveedores.idproveedor')->count(); 

        }


        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($orden_compras))
        {
            foreach ($orden_compras as $orden_compra)
            {

                        
                        $nestedData['numeracion'] = str_pad($orden_compra->numeracion, 6, "0", STR_PAD_LEFT);
                        $nestedData['f_emision'] = date_format(date_create_from_format('Y-m-d', $orden_compra->f_emision), 'd/m/Y') ;  

                        $nestedData['razon_social'] =  $orden_compra->razon_social;
                        if($orden_compra->moneda == 1){ $nestedData['moneda'] ='Soles'; }else if($orden_compra->moneda == 2){ $nestedData['moneda'] ='Dólares'; } else{ $nestedData['moneda'] ='Euros'; }
                        $nestedData['total'] = number_format((float)$orden_compra->total, 2, '.', '') ;

                         if($orden_compra->estado_doc == 0) {
                            $nestedData['estado_doc'] = '<td><button id="status" class="btn btn-danger" data-id_orden_comprah="'.$orden_compra->id_orden_comprah.'" data-status="1" >  Pendiente </button></td>';
                        }      elseif($orden_compra->estado_doc == 1) {
                            $nestedData['estado_doc'] = '<td><button id="status" class="btn btn-success" data-id_orden_comprah="'.$orden_compra->id_orden_comprah.'" data-status="2" > RecibidaT </button></td>';
                        }      elseif($orden_compra->estado_doc == 3) {
                            $nestedData['estado_doc'] = '<td><button id="status" class="btn btn-primary" data-id_orden_comprah="'.$orden_compra->id_orden_comprah.'" data-status="2" > RecibidaP </button></td>';
                        }      else{
                            $nestedData['estado_doc'] = '<td><button id="status" class="btn btn-secondary" data-id_orden_comprah="'.$orden_compra->id_orden_comprah.'" data-status="0" > Anulada </button></td>';
                        }  

                        $nestedData['cliente_extra'] = $orden_compra->ruc_dni.'-'.$orden_compra->contacto_nombre.' '.$orden_compra->contacto_telefono;
                        $nestedData['acciones'] ="
                            <button type='button' class='btn btn-info btn-xs'
                                    id='imprimir' data-id='{$orden_compra->id_orden_comprah}'>
                                <i class='glyphicon glyphicon-print position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-toggle = 'modal'
                                    id='observacion' data-id='{$orden_compra->id_orden_comprah}'
                                    data-numeracion = '{$orden_compra->numeracion } '
                                    data-observacion = '{$orden_compra->comentarios } '>
                                <i class='icon-comments position-center'></i>
                            </button>";
                            if($orden_compra->estado_doc == 3) { 
                                $nestedData['acciones'] .="<button type='button' class='btn btn-success btn-xs' title='Marcar como RecibidoT'
                                    data-id_orden_ventah   = '{$orden_compra->id_orden_comprah }'
                                    data-numeracion      = '{$orden_compra->numeracion }'
                                    id='mark_total'>
                                <i class='icon-file-check2 position-center'></i>
                            </button>";
                            } 
                            if($orden_compra->estado_doc != 0 and $orden_compra->descuento > 0) {
                                $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs' title='Anular descuento'
                                    data-id_orden_ventah   = '{$orden_compra->id_orden_comprah } '
                                    data-numeracion      = '{$orden_compra->numeracion } '
                                    data-descuento      = '{$orden_compra->descuento } '
                                    data-toggle          = 'modal'
                                    id='anular_desc'>
                                <i class='icon-percent position-center'></i>
                            </button>";
                             }
                            if($orden_compra->estado_doc == 0) { 
                                $nestedData['acciones'] .="<button type='button' class='btn btn-danger btn-xs'
                                    data-id_orden_ventah   = '{$orden_compra->id_orden_comprah } '
                                    data-numeracion      = '{$orden_compra->numeracion } '
                                    data-toggle          = 'modal'
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
            header('Content-Disposition: attachment;filename="GuiasRemision-SolucionesOGGK.xls"');
            header('Cache-Control: max-age=0');
            //ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }

////////////////////////////NO SE USA//////////////////// FAALTA ARMAR
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
            if( $guia_remision->codeG > 0 && $guia_remision->codeG < 4000 && $guia_remision->estado_doc==3 ){
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

        $cajas = DB::table('orden_comprah')->select('orden_comprah.*','orden_comprad.*','producto.nombre','prov.razon_social')
            ->join ('orden_comprad', 'orden_comprad.id_orden_comprah', '=', 'orden_comprah.id_orden_comprah')
            ->join ('proveedores as prov', 'prov.idproveedor', '=', 'orden_comprah.idproveedor')
            ->join('producto','orden_comprad.idproducto','=','producto.idproducto')
            ->get();

        return view('orden_compra/listado_orden_compra_detallado', ['cajas' => $cajas]);
    }
    
    public function crear()        
    {
        $empresa = Auth::user()->idempresa;
        $idsucursal = Auth::user()->idsucursal;
        $id = Auth::user()->id;

        $products = [];
        /*$products = Producto::where('idempresa',$empresa)
                    ->where('idsucursal',$sucursal)
                    ->where('state',1)
                    ->where('stock_total','>',0)->get();*/

        $usuario = DB::table('users')
                    ->where('id',$id)
                    ->join('sucursales','users.idsucursal','=','sucursales.idsucursal')
                    ->join('empresas','users.idempresa','=','empresas.idempresa')
                    ->first();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$idsucursal)
                    ->first();

        return view('orden_compra/orden_compra')->with('products',$products)->with('usuario',$usuario)->with('sucursal', $sucursal);
    }

    public function buscar_producto(Request $request){
        $busqueda = $request['query'];
        $products = DB::table('producto')
            ->select('producto.*','categorias.descripcion as categoria')
            ->Where(function ($query) {
                $sucursal = Auth::user()->idsucursal;
                $query->where('producto.state',1)
                    ->orwhere('idsucursal',$sucursal);
            })
             ->where('producto.barcode', 'like', '%'.$busqueda.'%')
             ->orwhere('producto.nombre', 'like', '%'.$busqueda.'%')
             ->orwhere('categorias.descripcion', 'like', '%'.$busqueda.'%')

            ->leftJoin('categorias', 'producto.idcategoria', '=', 'categorias.idcategoria')
            ->get();

        return json_encode($products);
    }

    public function buscar_proveedor(Request $request){
        $busqueda = $request['query'];
        $proveedores = DB::table('proveedores')
            ->where('razon_social','like','%'.$busqueda.'%')
            ->orwhere('ruc_dni','like','%'.$busqueda.'%')
            // ->orwhere('dni',$busqueda)
            // ->orwhere('ruc',$busqueda)
            ->get();
        return json_encode($proveedores);
    }

    public function store(Request $request){

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idproveedor = $request['idproveedor'];
        $subtotal = $request['subtotal'];
        $igv = $request['igv'];
        $total = $request['total'];
        $paga = $request['paga'];
        $vuelto = $request['vuelto'];
        $descuento = $request['descuento'];
        $comentarios = $request['comentarios'];
        $f_emision = $request['f_emision'];
        $moneda = $request['moneda'];
        $lugar_entrega = $request['lugar_entrega'];
        $observaciones = $request['observaciones'];
            
        $productos_json = $request['productos'];
        $state = 1;


        $orden_comprah = new OrdenCompraH;
        $orden_comprah->idempresa = $empresa;
        $orden_comprah->idsucursal = $sucursal;
        $orden_comprah->idusuario = $idusuario;
        $orden_comprah->idproveedor = $idproveedor;

        $maximo_num = DB::table('orden_comprah')
                        ->where('idempresa',$empresa)
                        ->where('idsucursal',$sucursal)
                        ->max('numeracion');

        $orden_comprah->numeracion = intval($maximo_num) + 1 ;

        $orden_comprah->paga = $paga;
        $orden_comprah->igv = $igv;
        $orden_comprah->vuelto = $vuelto;
        $orden_comprah->descuento = $descuento;
        $orden_comprah->subtotal = $subtotal;
        $orden_comprah->total = $total;
        $orden_comprah->comentarios = $comentarios;
        $orden_comprah->f_emision = $f_emision;
        $orden_comprah->moneda = $moneda;
        $orden_comprah->lugar_entrega = $lugar_entrega;
        $orden_comprah->observaciones = $observaciones;
        $orden_comprah->save();


        $productos = json_decode($productos_json);

        for($i = 0; $i < count($productos); $i++){
            // $product = Producto::find($productos[$i]->idproducto);
            // if($product->tipo == 1){
            //             $product->stock_total = $product->stock_total - $productos[$i]->stock_total;
            //             $product->save();
            // }

            // AFTDB
                $product = Producto::find($productos[$i]->idproducto);
                if($product->tipo == 1){
                    $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->stock_total;
                    $product->save();
                }
            // AFTDB

            $orden_comprad = new OrdenCompraD;
            $orden_comprad->id_orden_comprah = $orden_comprah->id_orden_comprah;
            $orden_comprad->idproducto = $productos[$i]->idproducto;
            $orden_comprad->cantidad = $productos[$i]->stock_total;
            // AFTDB
            $orden_comprad->cantidad_fal = $productos[$i]->stock_total;
            // AFTDB
            $orden_comprad->costo_unit = $productos[$i]->costo;
            $orden_comprad->costo_total = $productos[$i]->costo * $productos[$i]->stock_total ;
            $orden_comprad->idempresa = $empresa;
            $orden_comprad->save();

        }

        $respuesta = array();
        $respuesta[]= ['created'=> 200];
        $respuesta[] = ['id' => $orden_comprah->id_orden_comprah];

        return json_encode($respuesta);

    }
    
    public function show(Request $request){
    	$id = $request['id'];

    	$orden_compra = DB::table('orden_comprah')
                ->where('id_orden_comprah',$id)
                ->first();

    	$orden_compraD = DB::table('orden_comprad')
                ->select('orden_comprad.*','producto.*')
                ->where('orden_comprad.idempresa','=',$orden_compra->idempresa)
                ->where('orden_comprad.id_orden_comprah','=',$orden_compra->id_orden_comprah)
                ->join('producto','orden_comprad.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$orden_compra->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $proveedor = DB::table('proveedores')
                    ->where('idproveedor','=',$orden_compra->idproveedor)
                    ->first();

        return view('orden_compra/info_orden_compra')
            ->with('orden_compra',$orden_compra)
            ->with('orden_compraD',$orden_compraD)
            ->with('sucursal',$sucursal)
            ->with('proveedor',$proveedor);

    }


     public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $ordencomprah = OrdenCompraH::find($id);
        $ordencomprah->estado_doc = $status;
        $ordencomprah->save();
        return response()->json(['accepted'], 202);
    }

    public function oc_estado(Request $request){
        $id_orden_ventah = $request['id_orden_ventah'];
        $orden_ventah = OrdenCompraH::find($id_orden_ventah);
        $orden_ventah->estado_doc = 2;
        $orden_ventah->save();

        // AFTDB 
            $orden_compraD = DB::table('orden_comprad')
                ->select('orden_comprad.*')
                ->where('orden_comprad.id_orden_comprah','=',$id_orden_ventah)
                ->get();

            foreach ($orden_compraD as $key => $value) {
                $product = Producto::find($value->idproducto);
                if($product->tipo == 1){
                    $product->stock_imaginario = $product->stock_imaginario - $value->cantidad;
                    $product->save();
                }
            }
        // AFTDB

        return json_encode(['mensaje' => 200]);
    }

    public function oc_anular_descuento(Request $request){
        $orden_comprah = OrdenCompraH::find($request['id_orden_ventah']);
        $orden_comprah->total = $orden_comprah->total + $orden_comprah->descuento;
        $orden_comprah->descuento = 0;
        $orden_comprah->save();

        return json_encode(['mensaje' => 200]);
    }

    public function oc_complete_order(Request $request) {

        $id_orden_comprah = $request['id_orden_comprah'];

        $orden_comprah = OrdenCompraH::find($id_orden_comprah);

        if (strval($orden_comprah->created_at) > "2021-02-22 00:00:00") {

            $orden_compraD = DB::table('orden_comprad')
                    ->where('orden_comprad.id_orden_comprah','=',$id_orden_comprah)
                    ->get();

            $new_subtotal = 0.0;
            foreach ($orden_compraD as $key => $value) {
                $product = Producto::find($value->idproducto);
                if($product->tipo == 1){
                    $product->stock_imaginario = $product->stock_imaginario - $value->cantidad_fal;
                    $product->save();
                    $orden_comprad = OrdenCompraD::find($value->id_orden_comprad);
                    $orden_comprad->cantidad = $orden_comprad->cantidad - $value->cantidad_fal;
                    $orden_comprad->costo_total = $orden_comprad->cantidad * $orden_comprad->costo_unit;
                    $orden_comprad->save();

                    $new_subtotal += $orden_comprad->costo_total;
                }
            }
            
            $orden_comprah->subtotal = $new_subtotal;
            $orden_comprah->igv = $orden_comprah->subtotal * 0.18;
            $orden_comprah->total = $orden_comprah->subtotal + $orden_comprah->igv - $orden_comprah->descuento;
            $orden_comprah->estado_doc = 1;
            $orden_comprah->save();

            $ficha_recepcionh = DB::table('ficha_recepcionh')
                    ->where('ficha_recepcionh.id_orden_comprah','=',$id_orden_comprah)
                    ->where('ficha_recepcionh.estado_doc','=',1)
                    ->orderBy('ficha_recepcionh.created_at', 'desc')
                    ->first();

            $ficha_recepcionh = FichaRecepcionH::find($ficha_recepcionh->id_ficha_recepcionh);
            $ficha_recepcionh->estado_doc = 0;
            $ficha_recepcionh->save();

            return json_encode(['mensaje' => 200]);
        } else {
            return json_encode(['mensaje' => 99999]);
        }
    }

    public function oc_edit_comments(Request $request) {

        $orden_comprah = OrdenCompraH::find($request['id_reg']);
        $orden_comprah->comentarios = $request['comments'];
        $orden_comprah->save();

        return json_encode(['mensaje' => 200]);
    }


}