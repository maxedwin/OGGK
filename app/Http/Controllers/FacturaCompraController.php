<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Models\Transacciones;
use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\Models\Producto;
use App\User;
use App\Models\GuiaCompraH;
use App\Models\FacturaCompraH;
use App\Models\FacturaCompraD;
use App\Models\FactGuiaCompra;
use Auth;
use DB;

class FacturaCompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {     

        return view('factura_compra/listado_factura_compra');
    }

    public function allFC(Request $request)
    {
        
        $columns = array( 
            0 =>'oc2.numeracion', 
            1 =>'numeracion',
            2=> 'f_emision',
            3=> 'f_vencimiento',
            4=> 'razon_social',
            5=> 'moneda',
            6=> 'total',
            7=> 'estado_doc',
            8=> 'cliente_extra',
            9=> 'numeracion',
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

        $totalData =DB::table('factura_comprah')->where('factura_comprah.idempresa',$empresa)->count();
            
        if(empty($request->input('search.value'))){
             $factura_compras = DB::table('factura_comprah')
            ->select('factura_comprah.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc") )
            ->where('factura_comprah.idempresa',$empresa)
            ->where('factura_comprah.f_emision', '>=', $f_inicio)
            ->where('factura_comprah.f_emision', '<=', $f_fin)
            ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')

            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'factura_comprah.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'factura_comprah.id_guia_comprah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
                //->paginate($cant);    
                                       
            $totalFiltered =DB::table('factura_comprah')
            ->select('factura_comprah.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc") )
            ->where('factura_comprah.idempresa',$empresa)
            ->where('factura_comprah.f_emision', '>=', $f_inicio)
            ->where('factura_comprah.f_emision', '<=', $f_fin)
            ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')

            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'factura_comprah.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'factura_comprah.id_guia_comprah')
            ->count();

        }else{
            $search = $request->input('search.value'); 
            $factura_compras = DB::table('factura_comprah')
            ->select('factura_comprah.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc") )
            ->where('factura_comprah.idempresa',$empresa)
            ->where('factura_comprah.f_emision', '>=', $f_inicio)
            ->where('factura_comprah.f_emision', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('factura_comprah.numeracion','like','%'.$search.'%')
                ->orWhere('proveedores.razon_social','like','%'.$search.'%')
                ->orWhere('proveedores.ruc_dni','like','%'.$search.'%')
                ->orWhere('oc.numeracion','like','%'.$search.'%')
                ->orWhere('oc2.numeracion','like','%'.$search.'%')
                ->orWhere('factura_comprah.total','like','%'.$search.'%');
                //->orWhere('clientes.razon_social','like','%'.$search.'%')
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')

            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'factura_comprah.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'factura_comprah.id_guia_comprah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('factura_comprah')
            ->select('factura_comprah.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc") )
            ->where('factura_comprah.idempresa',$empresa)
            ->where('factura_comprah.f_emision', '>=', $f_inicio)
            ->where('factura_comprah.f_emision', '<=', $f_fin)
            ->where(function ($query) use ($search)  {
                $query->where('factura_comprah.numeracion','like','%'.$search.'%')
                ->orWhere('proveedores.razon_social','like','%'.$search.'%')
                ->orWhere('proveedores.ruc_dni','like','%'.$search.'%')
                ->orWhere('oc.numeracion','like','%'.$search.'%')
                ->orWhere('oc2.numeracion','like','%'.$search.'%')
                ->orWhere('factura_comprah.total','like','%'.$search.'%');
                //->orWhere('clientes.razon_social','like','%'.$search.'%')
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                //->orWhere('ov.codigoNB','like','%'.$search.'%');
            })
            ->join ('proveedores', 'factura_comprah.idproveedor', '=', 'proveedores.idproveedor')

            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'factura_comprah.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'factura_comprah.id_guia_comprah')->count(); 

        }


        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($factura_compras))
        {
            foreach ($factura_compras as $factura_compra)
            {
                $nestedData['oc']="OC-".$factura_compra->oc;
                $nestedData['numeracion']=$factura_compra->serie."-".str_pad($factura_compra->numeracion, 6, "0", STR_PAD_LEFT);
                $nestedData['f_emision']= date_format(date_create_from_format('Y-m-d', $factura_compra->f_emision), 'd/m/Y');
                $nestedData['f_vencimiento']= date_format(date_create_from_format('Y-m-d', $factura_compra->f_vencimiento), 'd/m/Y');                          
                $nestedData['razon_social']= $factura_compra->razon_social;
                if($factura_compra->moneda == 1){ $nestedData['moneda']='Soles'; }else if($factura_compra->moneda == 2){  $nestedData['moneda']='Dólares'; } else{ $nestedData['moneda']='Euros'; }
                $nestedData['total']= number_format((float)$factura_compra->total, 2, '.', '');
                
                if($factura_compra->estado_doc == 0) {
                    $nestedData['estado_doc']= '<td><button id="status" class="btn btn-danger" data-id_factura_comprah="'.$factura_compra->id_factura_comprah.'" data-status="1" >  Pendiente </button></td>';
                }      elseif($factura_compra->estado_doc == 1) {
                    $nestedData['estado_doc']= '<td><button id="status" class="btn btn-danger" data-id_factura_comprah="'.$factura_compra->id_factura_comprah.'" data-status="1" >  Pendiente </button></td>';
                }      elseif($factura_compra->estado_doc == 2) {
                    $nestedData['estado_doc']='<td><button id="status" class="btn btn-success" data-id_factura_comprah="'.$factura_compra->id_factura_comprah.'" data-status="2" > Cancelada </button></td>';
                }      else{
                    $nestedData['estado_doc']= '<td><button id="status" class="btn btn-secondary" data-id_factura_comprah="'.$factura_compra->id_factura_comprah.'" data-status="0" > Anulada </button></td>';
                }

                $nestedData['cliente_extra']=$factura_compra->ruc_dni.'-'.$factura_compra->contacto_nombre.'-'.$factura_compra->contacto_telefono;
                $nestedData['acciones']='';
                    if ($factura_compra->estado_doc != 3) { 
                        $nestedData['acciones'].="<button type='button' class='btn btn-danger btn-xs'
                            data-id_orden_ventah   = '{$factura_compra->id_factura_comprah} '
                            data-numeracion      = '{$factura_compra->numeracion } '
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

        $guias = DB::table('guia_comprah as gc')
                    ->select('gc.id_guia_comprah','gc.numeracion')
                    ->leftJoin('factguiacompra as fg','fg.idguia', '=', 'gc.id_guia_comprah')
                    ->whereNull('fg.idguia')
                    ->where('gc.estado_doc','!=',3)
                    ->get();
                        

        return view('factura_compra/factura_compra')->with('products',$products)->with('usuario',$usuario)->with('guias',$guias);
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

    public function buscar_GuiaCompra(Request $request){
        $busqueda = $request['query'];
        $guias = DB::table('guia_comprah')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->get();
        return json_encode($guias);
    }

    public function buscar_gc_todo(Request $request){
        $busqueda = $request['query'];
        $orden_compras = DB::table('guia_comprah')
            ->select('oc.*', 'p.razon_social')
            ->join('proveedores as p', 'guia_comprah.idproveedor', '=', 'p.idproveedor')
            ->join('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'guia_comprah.id_orden_comprah')
            ->where('guia_comprah.id_guia_comprah','=', $busqueda)
            ->first();

        $orden_compras->detalle = DB::table('guia_comprah')
            ->select('orden_comprad.*', 'p.*')
            ->join('orden_comprad','orden_comprad.id_orden_comprah', '=', 'guia_comprah.id_orden_comprah')
            ->join('producto as p', 'p.idproducto', '=', 'orden_comprad.idproducto')
            ->where('guia_comprah.id_guia_comprah','=', $busqueda)
            ->get();

        return json_encode($orden_compras);
    }

    public function store(Request $request){

    DB::beginTransaction(); // <-- first line  
    
    try{        

        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idproveedor = $request['idproveedor'];
        $id_guia_comprah = $request['id_orden_comprah'];
        $subtotal = $request['subtotal'];
        $igv = $request['igv'];
        $total = $request['total'];
        $paga = $request['paga'];
        $vuelto = $request['vuelto'];
        $descuento = $request['descuento'];
        $comentarios = $request['comentarios'];
        $f_emision = $request['f_emision'];
        $f_vencimiento = $request['f_vencimiento'];
        $serie = $request['serie'];
        $numeracion = $request['numeracion'];
        $moneda = $request['moneda'];
            
        $productos_json = $request['productos'];
        //$state = 1;


        $factura_comprah = new FacturaCompraH;
        $factura_comprah->idempresa = $empresa;
        $factura_comprah->idsucursal = $sucursal;
        $factura_comprah->idusuario = $idusuario;
        $factura_comprah->id_guia_comprah = $id_guia_comprah;
        $factura_comprah->idproveedor = $idproveedor;
        $factura_comprah->serie = $serie;
        $factura_comprah->numeracion = $numeracion;
        $factura_comprah->moneda = $moneda;


        $factura_comprah->paga = $paga;
        $factura_comprah->igv = $igv;
        $factura_comprah->vuelto = $vuelto;
        $factura_comprah->descuento = $descuento;
        $factura_comprah->subtotal = $subtotal;
        $factura_comprah->total = $total;
        $factura_comprah->comentarios = $comentarios;
        $factura_comprah->f_emision = $f_emision;
        $factura_comprah->f_vencimiento = $f_vencimiento;

        $saved = $factura_comprah->save();

        $productos = json_decode($productos_json);

        for($i = 0; $i < count($productos); $i++){
            $factura_comprad = new FacturaCompraD;
            $factura_comprad->id_factura_comprah = $factura_comprah->id_factura_comprah;
            $factura_comprad->idproducto = $productos[$i]->idproducto;
            $factura_comprad->cantidad = $productos[$i]->stock_total;
            $factura_comprad->costo_unit = $productos[$i]->costo;
            $factura_comprad->costo_total = $productos[$i]->costo * $productos[$i]->stock_total ;
            $factura_comprad->idempresa = $empresa;
            $saved = $factura_comprad->save();

        }

        if($saved)
            $childModelSaved = true; 
        else
            $childModelSaved = false; 

    }catch(Exception $e)
        {
             $childModelSaved = false;
        }

        if($childModelSaved)
        {
            DB::commit(); // YES --> finalize it 
            $respuesta = array();
            $respuesta[]= ['created'=> 200];
            $respuesta[] = ['id' => $factura_comprah->id_factura_comprah];

            return json_encode($respuesta);

        }
        else
        {
            DB::rollBack(); // NO --> some error has occurred undo the whole thing
            $respuesta = array();
            $respuesta[]= ['created'=> 500];
            $respuesta[] = ['id' => 9999];

            return json_encode($respuesta);
        }
    }
    
    public function show(Request $request){
    	$id = $request['id'];

    	$factura_compra = DB::table('factura_comprah')
                ->where('id_factura_comprah',$id)
                ->first();

    	$factura_compraD = DB::table('factura_comprad')
                ->select('factura_comprad.*','producto.*')
                ->where('factura_comprad.idempresa','=',$factura_compra->idempresa)
                ->where('factura_comprad.id_factura_comprah','=',$factura_compra->id_factura_comprah)
                ->join('producto','factura_comprad.idproducto','=','producto.idproducto')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$factura_compra->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $proveedor = DB::table('proveedores')
                    ->where('idproveedor','=',$factura_compra->idproveedor)
                    ->first();

        return view('factura_compra/info_factura_compra')
            ->with('factura_compra',$factura_compra)
            ->with('factura_compraD',$factura_compraD)
            ->with('sucursal',$sucursal)
            ->with('proveedor',$proveedor);

    }


     public function status(Request $request){
        $id = $request['id_orden_ventah'];
        $facturacompra = FacturaCompraH::find($id);
        $facturacompra->estado_doc = 3;
        $facturacompra->save();
        return json_encode(['mensaje' => 200]);
    }



}