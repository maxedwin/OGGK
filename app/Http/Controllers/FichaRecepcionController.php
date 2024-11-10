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
use App\Models\AlmacenLote;
use App\User;
use App\Models\FichaRecepcionH;
use App\Models\FichaRecepcionD;
use App\Models\FacturaCompraH;
use App\Models\FactGuiaCompra;
use App\Models\GuiaCompraH;
use App\Models\OrdenCompraH;
use App\Models\OrdenCompraD;
use Auth;
use DB;
use Illuminate\Support\Facades\Validator;
use Helper;
use DateTime;

class FichaRecepcionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        

        return view('ficha_recepcion/listado_ficha_recepcion');
    }


    public function allFR(Request $request)
    {
        
        $columns = array( 
            0 =>'numeracion', 
            1 =>'DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc")',
            2=> 'ficha_recepcionh.serie',
            3=> 'f_recepcion',
            4=> 'razon_social',
            5=> 'flete',
            6=> 'flete_costo',
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

        $totalData =DB::table('orden_comprah')->where('orden_comprah.idempresa',$empresa)->count();
            
        if(empty($request->input('search.value'))){
            $ficha_recepcions = DB::table('ficha_recepcionh')
            ->select('ficha_recepcionh.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', 
                        DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc"), 
                        'ficha_recepcionh.serie as fs', 'gch.serie as gs', 
                        DB::raw("ifnull(ficha_recepcionh.numeracion_guia,gch.numeracion) as gn"),
                        DB::raw("ifnull(prov3.razon_social,prov2.razon_social) as flete"),
                        DB::raw("ifnull(ficha_recepcionh.flete_costo,gch.flete_costo) as flete_costo"))
            
            ->where('ficha_recepcionh.idempresa',$empresa)  
            ->where('ficha_recepcionh.f_recepcion', '>=', $f_inicio)
            ->where('ficha_recepcionh.f_recepcion', '<=', $f_fin)                          
                            
            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'ficha_recepcionh.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')

            ->leftJoin('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
            ->leftJoin('proveedores as prov2', 'gch.flete_trans', '=', 'prov2.idproveedor')
            ->leftJoin('proveedores as prov3', 'ficha_recepcionh.flete_trans', '=', 'prov3.idproveedor')

            
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'ficha_recepcionh.id_orden_comprah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

                //->paginate($cant);    
                                       
            $totalFiltered= DB::table('ficha_recepcionh')           
            
            ->where('ficha_recepcionh.idempresa',$empresa)  
            ->where('ficha_recepcionh.f_recepcion', '>=', $f_inicio)
            ->where('ficha_recepcionh.f_recepcion', '<=', $f_fin)                          
                            
            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'ficha_recepcionh.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')

            ->leftJoin('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
            ->leftJoin('proveedores as prov2', 'gch.flete_trans', '=', 'prov2.idproveedor')
            ->leftJoin('proveedores as prov3', 'ficha_recepcionh.flete_trans', '=', 'prov3.idproveedor')

            
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'ficha_recepcionh.id_orden_comprah')->count();

        }else{
            $search = $request->input('search.value'); 

            $ficha_recepcions = DB::table('ficha_recepcionh')
            ->select('ficha_recepcionh.*','proveedores.razon_social', 'proveedores.ruc_dni', 'proveedores.contacto_nombre', 'proveedores.contacto_telefono', 
                        DB::raw("ifnull(oc2.numeracion,oc.numeracion) as oc"), 
                        'ficha_recepcionh.serie as fs', 'gch.serie as gs', 
                        DB::raw("ifnull(ficha_recepcionh.numeracion_guia,gch.numeracion) as gn"),
                        DB::raw("ifnull(prov3.razon_social,prov2.razon_social) as flete"),
                        DB::raw("ifnull(ficha_recepcionh.flete_costo,gch.flete_costo) as flete_costo"))
            
            ->where('ficha_recepcionh.idempresa',$empresa)  
            ->where('ficha_recepcionh.f_recepcion', '>=', $f_inicio)
            ->where('ficha_recepcionh.f_recepcion', '<=', $f_fin)  
            ->where(function ($query) use ($search)  {
                $query->where('ficha_recepcionh.numeracion','like','%'.$search.'%')
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                ->orWhere('ficha_recepcionh.numeracion_guia','like','%'.$search.'%')
                ->orWhere('gch.numeracion','like','%'.$search.'%')
                ->orWhere('oc.numeracion','like','%'.$search.'%')
                ->orWhere('oc2.numeracion','like','%'.$search.'%')
                ->orWhere('proveedores.ruc_dni','like','%'.$search.'%')
                ->orWhere('proveedores.razon_social','like','%'.$search.'%')
                ->orWhere('prov3.razon_social','like','%'.$search.'%')
                ->orWhere('prov2.razon_social','like','%'.$search.'%')
                ->orWhere('ficha_recepcionh.flete_costo','like','%'.$search.'%')
                ->orWhere('ficha_recepcionh.serie','like','%'.$search.'%')
                ->orWhere('gch.serie','like','%'.$search.'%')
                ->orWhere('gch.flete_costo','like','%'.$search.'%');



            })                          
                            
            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'ficha_recepcionh.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')

            ->leftJoin('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
            ->leftJoin('proveedores as prov2', 'gch.flete_trans', '=', 'prov2.idproveedor')
            ->leftJoin('proveedores as prov3', 'ficha_recepcionh.flete_trans', '=', 'prov3.idproveedor')

            
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'ficha_recepcionh.id_orden_comprah')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
                //->paginate($cant);

            $totalFiltered = DB::table('ficha_recepcionh')
            ->where('ficha_recepcionh.idempresa',$empresa)  
            ->where('ficha_recepcionh.f_recepcion', '>=', $f_inicio)
            ->where('ficha_recepcionh.f_recepcion', '<=', $f_fin)  
            ->where(function ($query) use ($search)  {
                $query->where('ficha_recepcionh.numeracion','like','%'.$search.'%')
                //->orWhere('clientes.contacto_nombre','like','%'.$search.'%')
                ->orWhere('ficha_recepcionh.numeracion_guia','like','%'.$search.'%')
                ->orWhere('gch.numeracion','like','%'.$search.'%')
                ->orWhere('oc.numeracion','like','%'.$search.'%')
                ->orWhere('oc2.numeracion','like','%'.$search.'%')
                ->orWhere('proveedores.ruc_dni','like','%'.$search.'%')
                ->orWhere('proveedores.razon_social','like','%'.$search.'%')
                ->orWhere('prov3.razon_social','like','%'.$search.'%')
                ->orWhere('prov2.razon_social','like','%'.$search.'%')
                ->orWhere('ficha_recepcionh.flete_costo','like','%'.$search.'%')
                ->orWhere('ficha_recepcionh.serie','like','%'.$search.'%')
                ->orWhere('gch.serie','like','%'.$search.'%')
                ->orWhere('gch.flete_costo','like','%'.$search.'%');



            })                             
                            
            ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'ficha_recepcionh.id_factura_comprah')
            ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')

            ->leftJoin('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
            ->leftJoin('proveedores as prov2', 'gch.flete_trans', '=', 'prov2.idproveedor')
            ->leftJoin('proveedores as prov3', 'ficha_recepcionh.flete_trans', '=', 'prov3.idproveedor')

            
            ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'gch.id_orden_comprah')
            ->leftJoin('orden_comprah as oc2', 'oc2.id_orden_comprah', '=', 'ficha_recepcionh.id_orden_comprah')->count(); 

        }


        //$totalData =count($guia_remisions);
            
        

        

           // $guia_remisions = $guia_remisions;
                            

           // $totalFiltered = count($guia_remisions);
        

        $data = array();
        if(!empty($ficha_recepcions))
        {
            foreach ($ficha_recepcions as $ficha_recepcion)
            {
                        
                $nestedData['numeracion'] = str_pad($ficha_recepcion->numeracion, 6, "0", STR_PAD_LEFT) ;
                $nestedData['oc'] = "OC-{$ficha_recepcion->oc}";
                        if($ficha_recepcion->fs == 'FR' ) {
                            $nestedData['gc']=$ficha_recepcion->gs.'-'.$ficha_recepcion->gn;
                        }      else{
                            $nestedData['gc']=$ficha_recepcion->fs.'-'.$ficha_recepcion->gn;
                        } 
                        $nestedData['f_recepcion']=date_format(date_create_from_format('Y-m-d', $ficha_recepcion->f_recepcion), 'd/m/Y');  
                        $nestedData['razon_social']=$ficha_recepcion->razon_social;
                        $nestedData['flete']= $ficha_recepcion->flete;
                        $nestedData['flete_costo']="S/".number_format((float)$ficha_recepcion->flete_costo, 2, '.', '');

                        if($ficha_recepcion->estado_doc == 0) {
                            $nestedData['estado_doc']='<td><button id="status" class="btn btn-success" data-id_ficha_recepcionh="'.$ficha_recepcion->id_ficha_recepcionh.'" data-status="1" >  RecibidaT </button></td>';
                        }      elseif($ficha_recepcion->estado_doc == 1) {
                            $nestedData['estado_doc']='<td><button id="status" class="btn btn-primary"  data-status="2" > RecibidaP </button></td>';
                        }      else{
                            $nestedData['estado_doc']='<td><button id="status" class="btn btn-secondary" data-id_ficha_recepcionh="'.$ficha_recepcion->id_ficha_recepcionh.'" data-status="0" > Anulada </button></td>';
                        }  

                        $nestedData['cliente_extra']="{$ficha_recepcion->ruc_dni} - {$ficha_recepcion->contacto_nombre}-{$ficha_recepcion->contacto_telefono}";
                        $nestedData['acciones']="
                            <button type='button' class='btn btn-info btn-xs'
                                    id='imprimir' data-id='{$ficha_recepcion->id_ficha_recepcionh}'>
                                <i class='glyphicon glyphicon-print position-center'></i>
                            </button>
                            <button type='button' class='btn btn-info btn-xs'
                                    data-toggle = 'modal'
                                    id='observacion' data-id='{$ficha_recepcion->id_ficha_recepcionh}'
                                    data-numeracion = '{$ficha_recepcion->numeracion } '
                                    data-observacion = '{$ficha_recepcion->comentarios } '>
                                <i class='icon-comments position-center'></i>
                            </button>";
                            if($ficha_recepcion->estado_doc != 2) {
                                $nestedData['acciones'].="<button type='button' class='btn btn-danger btn-xs'
                                    data-id_orden_ventah   = '{$ficha_recepcion->id_ficha_recepcionh} '
                                    data-numeracion      = '{$ficha_recepcion->numeracion} '
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

        $cajas = DB::table('ficha_recepcionh')->select('ficha_recepcionh.*','ficha_recepciond.*','producto.nombre','proveedores.razon_social')
            ->join ('ficha_recepciond', 'ficha_recepciond.id_ficha_recepcionh', '=', 'ficha_recepcionh.id_ficha_recepcionh')
            ->join ('proveedores', 'ficha_recepcionh.idproveedor', '=', 'proveedores.idproveedor')
            ->join('producto','ficha_recepciond.idproducto','=','producto.idproducto')
            ->get();

        return view('ficha_recepcion/listado_ficha_recepcion_detallado', ['cajas' => $cajas]);
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

        $almacenes = DB::table('almacen')->get();                    
                        

        return view('ficha_recepcion/ficha_recepcion')->with('products',$products)->with('usuario',$usuario)->with('almacenes',$almacenes);
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

     public function buscar_fc_numeracion(Request $request){
        $busqueda = $request['query'];
        $facturas = DB::table('factura_comprah')
            ->select('id_factura_comprah','numeracion')
            ->where('numeracion','like','%'.$busqueda.'%')
            ->get();
        return json_encode($facturas);
    }

    public function buscar_fc_todo(Request $request){
        $busqueda = $request['query'];
        $facturas = DB::table('factura_comprah')
            ->select('factura_comprah.*', 'p.razon_social')
            ->join('proveedores as p', 'factura_comprah.idproveedor', '=', 'p.idproveedor')
            ->where('id_factura_comprah','=', $busqueda)
            ->first();

        $facturas->detalle = DB::table('factura_comprad')
            ->select('factura_comprad.*', 'p.*')
            ->join('producto as p', 'p.idproducto', '=', 'factura_comprad.idproducto')
            ->where('id_factura_comprah','=', $busqueda)
            ->get();

        return json_encode($facturas);
    }

    public function store(Request $request){
        
        DB::beginTransaction(); // <-- first line  
    
        try{

            $empresa = Auth::user()->idempresa;
            $sucursal = Auth::user()->idsucursal;
            $idusuario = Auth::user()->id;

            $idproveedor = $request['idproveedor'];
            $id_orden_comprah = $request['id_orden_comprah'];
            $almacen = $request['almacen'];
            $comentarios = $request['comentarios'];
            $f_emision = $request['f_emision'];
            $f_recepcion = $request['f_recepcion'];
            $serie = $request['serie'];
            $numeracion = $request['numeracion'];
            $flete_costo = $request['flete_costo'];
            $flete_trans = $request['flete_trans'];
                
                
            $productos_json = $request['productos'];

            $maximo_num = DB::table('ficha_recepcionh')
                            ->where('idempresa',$empresa)
                            ->where('idsucursal',$sucursal)
                            ->max('numeracion');
        
            $productos = json_decode($productos_json);

            $boolLote=false;
            $saved=false;
            $saved1=false;
            $saved2=false;
            $saved3=false;           
            $saved4=false;           
            $saved5=false;  
            $saved6=false;    

            $detail_orden_comprad = DB::table('orden_comprad')
                                    ->where('id_orden_comprah',$id_orden_comprah)->get();
            $cants = [];
            foreach ($detail_orden_comprad as $key => $value) {
                $cants[$value->idproducto]['id_orden_comprad'] = $value->id_orden_comprad;
                $cants[$value->idproducto]['cantidad_fal'] = $value->cantidad_fal;
            }

            $ficha_recepcionh = new FichaRecepcionH;
            $ficha_recepcionh->idempresa = $empresa;
            $ficha_recepcionh->idsucursal = $sucursal;
            $ficha_recepcionh->idusuario = $idusuario;
            $ficha_recepcionh->idalmacen = $almacen;
            $ficha_recepcionh->id_orden_comprah = $id_orden_comprah;
            $ficha_recepcionh->idproveedor = $idproveedor;
            $ficha_recepcionh->serie = $serie;
            $ficha_recepcionh->numeracion_guia = $numeracion ;
            $ficha_recepcionh->numeracion = intval($maximo_num) + 1 ;
            $ficha_recepcionh->comentarios = $comentarios;
            $ficha_recepcionh->f_emision = $f_emision;
            $ficha_recepcionh->f_recepcion = $f_recepcion;  
            $ficha_recepcionh->flete_costo = $flete_costo;
            $ficha_recepcionh->flete_trans = $flete_trans;  
            $saved = $ficha_recepcionh->save();

            $cantidades_orden_comprah = true;
            
            for($i = 0; $i < count($productos); $i++){

                $boolLote = DB::table('lote')
                        ->where('codigo', '=', $productos[$i]->lote)
                        ->where('idproducto', $productos[$i]->idproducto)
                        ->first();

                $stockT_suma=0;
                $stockT_tmp = DB::table('lote')->select(DB::raw("SUM(stock_lote) as stockT"))
                                ->where('idproducto', $productos[$i]->idproducto)
                                ->groupBy('idproducto')->first();

                if( $stockT_tmp == null){                   
                    $stockT_suma = 0;
                }else {
                    $stockT_suma = $stockT_tmp->stockT;
                }

                if (isset($cants[$productos[$i]->idproducto]) and $cants[$productos[$i]->idproducto]['cantidad_fal'] < $productos[$i]->stock_total) {
                    $cantidades_orden_comprah = false;
                    DB::rollBack(); // YES --> el lote no pertenece al producto ingresado
                    $respuesta = array();
                    $respuesta[] = ['created'=> 500];
                    $respuesta[] = ['id' => 9999999997];
                    $respuesta[] = ['msg' => 'La cantidad de los productos en relacion a la orden de orden de compra es mayor'];
                    return json_encode($respuesta);
                    break;
                } else if (isset($cants[$productos[$i]->idproducto])) {
                    $orden_comprad = OrdenCompraD::find($cants[$productos[$i]->idproducto]['id_orden_comprad']);
                    $orden_comprad->cantidad_fal = $orden_comprad->cantidad_fal - $productos[$i]->stock_total;
                    $orden_comprad->save();
                    $cants[$productos[$i]->idproducto]['cantidad_fal'] = $orden_comprad->cantidad_fal;
                }
                            
                if ($boolLote){

                    if ((int)$productos[$i]->idproducto != $boolLote->idproducto) {
                        DB::rollBack(); // YES --> el lote no pertenece al producto ingresado
                        $respuesta = array();
                        $respuesta[] = ['created'=> 500];
                        $respuesta[] = ['id' => 9999999997];
                        $respuesta[] = ['msg' => 'El lote ' . $productos[$i]->lote . ' no tiene relación con el producto'];
                        return json_encode($respuesta);
                    } 

                    $product = Producto::find($productos[$i]->idproducto);
                    $product->stock_total = $product->stock_total + $productos[$i]->stock_total;
                    if (!isset($cants[$productos[$i]->idproducto])) {
                        $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->stock_total;
                    }
                    $saved1 = $product->save();

                    $lote = Lote::find($boolLote->idlote);
                    $lote->stock_lote = $lote->stock_lote + $productos[$i]->stock_total;                
                    $saved2=$lote->save();

                    $ficha_recepciond = new FichaRecepcionD;
                    $ficha_recepciond->id_ficha_recepcionh = $ficha_recepcionh->id_ficha_recepcionh;
                    $ficha_recepciond->idproducto = $productos[$i]->idproducto;
                    $ficha_recepciond->cantidad = $productos[$i]->stock_total;
                    $ficha_recepciond->idempresa = $empresa;
                    $ficha_recepciond->idlote = $lote->idlote;
                    $saved3=$ficha_recepciond->save();

                    $transacts = new Transacciones;
                    $transacts->idproducto = $productos[$i]->idproducto;
                    $transacts->idempresa = $empresa;
                    $transacts->idsucursal = $sucursal;            
                    $transacts->idusuario = $idusuario;
                    $transacts->idalmacen = $almacen;
                    $transacts->idlote = $lote->idlote;

                    $transacts->f_emision = date('Y-m-d');
                    $transacts->tipo_documento = 3;
                    $transacts->iddocumento = $ficha_recepcionh->id_ficha_recepcionh;          
                    $transacts->tipo = 1;                   
                    
                    $transacts->cantidad = $productos[$i]->stock_total;                         
                    $transacts->stockT = $stockT_suma + $productos[$i]->stock_total;

                    $transacts->state = 1;               
                    $saved6 = $transacts->save();
                    $saved4 = true;

                }else{

                    $product = Producto::find($productos[$i]->idproducto);
                    $product->stock_total = $product->stock_total + $productos[$i]->stock_total;
                    if (!isset($cants[$productos[$i]->idproducto])) {
                        $product->stock_imaginario = $product->stock_imaginario + $productos[$i]->stock_total;
                    }
                    $saved1 = $product->save();
                    
                    $lote = new Lote;
                    $lote->idproducto = $productos[$i]->idproducto;
                    $lote->codigo = $productos[$i]->lote;
                    $lote->f_venc = $productos[$i]->f_vencimiento;
                    $lote->state = 1;
                    $lote->stock_lote = $lote->stock_lote + $productos[$i]->stock_total;  
                    $saved2=$lote->save();

                    $ficha_recepciond = new FichaRecepcionD;
                    $ficha_recepciond->id_ficha_recepcionh = $ficha_recepcionh->id_ficha_recepcionh;
                    $ficha_recepciond->idproducto = $productos[$i]->idproducto;
                    $ficha_recepciond->cantidad = $productos[$i]->stock_total;
                    $ficha_recepciond->idempresa = $empresa;
                    $ficha_recepciond->idlote = $lote->idlote;
                    $saved3=$ficha_recepciond->save();

                    $almacenlote = new AlmacenLote;
                    $almacenlote->idlote = $lote->idlote;
                    $almacenlote->idalmacen = $almacen;
                    $almacenlote->state = 1;
                    $saved4=$almacenlote->save();  

                    $transacts = new Transacciones;
                    $transacts->idproducto = $productos[$i]->idproducto;
                    $transacts->idempresa = $empresa;
                    $transacts->idsucursal = $sucursal;            
                    $transacts->idusuario = $idusuario;
                    $transacts->idalmacen = $almacen;
                    $transacts->idlote = $lote->idlote;

                    $transacts->f_emision = date('Y-m-d');
                    $transacts->tipo_documento = 3;
                    $transacts->iddocumento = $ficha_recepcionh->id_ficha_recepcionh;          
                    $transacts->tipo = 1;                   
                    
                    $transacts->cantidad = $productos[$i]->stock_total;                         
                    $transacts->stockT = $stockT_suma + $productos[$i]->stock_total;

                    $transacts->state = 1;               
                    $saved6 = $transacts->save();     
                }
            }

            if( $id_orden_comprah != '' ){
                if ($cantidades_orden_comprah) {
                    $isTotal = true;
                    foreach ($cants as $key => $value) {
                        if ($value['cantidad_fal'] > 0) {
                            $isTotal = false;
                        }
                    }
                    $oc_state = OrdenCompraH::find($id_orden_comprah);
                    if ($isTotal) {
                        $oc_state->estado_doc = 1;
                    } else {
                        $oc_state->estado_doc = 3;
                        $ficha_recepcionh->estado_doc=1;
                        $ficha_recepcionh->save();
                    }
                    $saved5 = $oc_state->save();
                } else {
                    $saved5=false;
                }  
            }else{
                $saved5=false;
            }


            if($saved && $saved1 && $saved2 && $saved3 && $saved4 && $saved5 && $saved6)
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
                $respuesta[] = ['id' => $ficha_recepcionh->id_ficha_recepcionh];

                return json_encode($respuesta);

            }elseif( $saved==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999990];
                return json_encode($respuesta);   
            }
            elseif( $saved1==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999991];
                return json_encode($respuesta);   
            }
            elseif( $saved2==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999992];
                return json_encode($respuesta);   
            }
            elseif( $saved3==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999993];
                return json_encode($respuesta);   
            }
            elseif( $saved4==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999994];
                return json_encode($respuesta);   
            }
            elseif( $saved5==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999995];
                return json_encode($respuesta);   
            }
            elseif( $saved6==false && $childModelSaved==false )
            {
                DB::rollBack(); // YES --> error de lotes
                $respuesta = array();
                $respuesta[] = ['created'=> 500];
                $respuesta[] = ['id' => 9999999996];
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

        $ficha_recepcion = DB::table('ficha_recepcionh')
                ->select('ficha_recepcionh.*', 'oc.numeracion as oc', 'gch.serie as gs', 'gch.numeracion as gn')
                ->where('id_ficha_recepcionh',$id)
                ->leftJoin('factguiacompra as fgc', 'fgc.idfact', '=', 'ficha_recepcionh.id_factura_comprah')
                ->leftJoin('guia_comprah as gch', 'gch.id_guia_comprah', '=', 'fgc.idguia')
                ->leftJoin('orden_comprah as oc', 'oc.id_orden_comprah', '=', 'ficha_recepcionh.id_orden_comprah')
                ->first();

        $ficha_recepcionD = DB::table('ficha_recepciond')
                ->select('ficha_recepciond.*','producto.*','lote.*')
                ->where('ficha_recepciond.idempresa','=',$ficha_recepcion->idempresa)
                ->where('ficha_recepciond.id_ficha_recepcionh','=',$ficha_recepcion->id_ficha_recepcionh)
                ->join('producto','ficha_recepciond.idproducto','=','producto.idproducto')
                ->leftJoin('lote','lote.idlote', '=', 'ficha_recepciond.idlote')
                ->get();

        $sucursal = DB::table('sucursales')
                    ->where('sucursales.idsucursal','=',$ficha_recepcion->idsucursal)
                    ->join('empresas','sucursales.idempresa','=','empresas.idempresa')
                    ->first();

        $proveedor = DB::table('proveedores')
                    ->where('idproveedor','=',$ficha_recepcion->idproveedor)
                    ->first();

        return view('ficha_recepcion/info_ficha_recepcion')
            ->with('ficha_recepcion',$ficha_recepcion)
            ->with('ficha_recepcionD',$ficha_recepcionD)
            ->with('sucursal',$sucursal)
            ->with('proveedor',$proveedor);

    }


     public function status(Request $request){
        $id = $request['id'];
        $status = $request['status'];
        $ficharecepcion = FichaRecepcionH::find($id);
        $ficharecepcion->estado_doc = $status;
        $ficharecepcion->save();
        return response()->json(['accepted'], 202);
    }

    public function fr_estado(Request $request){
        $empresa = Auth::user()->idempresa;
        $sucursal = Auth::user()->idsucursal;
        $idusuario = Auth::user()->id;

        $idalmacen = '';
        $f_emision = date('Y-m-d');
        $idficha = $request['id_orden_ventah'];

        $saved = false;
        $saved1 = false;
        $saved2 = false;  
        $saved3 = false;    

        $ficha = FichaRecepcionH::find($idficha);

        if( $ficha->estado_doc != 2 ){
            $ficha->estado_doc = 2;
            $idalmacen = $ficha->idalmacen;
            $saved = $ficha->save();

            $detail_orden_comprad = DB::table('orden_comprad')
                                    ->where('id_orden_comprah',$ficha->id_orden_comprah)->get();
            $cants = [];
            foreach ($detail_orden_comprad as $key => $value) {
                $cants[$value->idproducto]['id_orden_comprad'] = $value->id_orden_comprad;
                $cants[$value->idproducto]['cantidad'] = $value->cantidad;
                $cants[$value->idproducto]['cantidad_fal'] = $value->cantidad_fal;
            }

            $productos = DB::table('ficha_recepciond')->where('id_ficha_recepcionh',$idficha)->get();

            for($i = 0; $i < count($productos); $i++){
                $product = Producto::find($productos[$i]->idproducto);
                $product->stock_total = $product->stock_total - $productos[$i]->cantidad;
                if (!isset($cants[$productos[$i]->idproducto])) {
                    $product->stock_imaginario = $product->stock_imaginario - $productos[$i]->cantidad;
                }
                $saved1 = $product->save();

                if (isset($cants[$productos[$i]->idproducto])) {
                    $orden_comprad = OrdenCompraD::find($cants[$productos[$i]->idproducto]['id_orden_comprad']);
                    $orden_comprad->cantidad_fal = $orden_comprad->cantidad_fal + $productos[$i]->cantidad;
                    $orden_comprad->save();
                    $cants[$productos[$i]->idproducto]['cantidad_fal'] = $orden_comprad->cantidad_fal;
                }

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
                $lote->stock_lote = $lote->stock_lote - $productos[$i]->cantidad;                
                $saved2=$lote->save();

                $transacts = new Transacciones;
                $transacts->idproducto = $productos[$i]->idproducto;
                $transacts->idempresa = $empresa;
                $transacts->idsucursal = $sucursal;            
                $transacts->idusuario = $idusuario;
                $transacts->idalmacen = $idalmacen;
                $transacts->idlote = $lote->idlote;

                $transacts->f_emision = date('Y-m-d');
                $transacts->tipo_documento = 3;
                $transacts->iddocumento = $idficha;          
                $transacts->tipo = 0;                   
                    
                $transacts->cantidad = $productos[$i]->cantidad;                         
                $transacts->stockT = $stockT_suma - $productos[$i]->cantidad;

                $transacts->state = 1;               
                $saved3 = $transacts->save();     
            }

            $isTotal = true;
            foreach ($cants as $key => $value) {
                if ($value['cantidad_fal'] < $value['cantidad']) {
                    $isTotal = false;
                }
            }
            $oc_state = OrdenCompraH::find($ficha->id_orden_comprah);
            if ($isTotal) {
                $oc_state->estado_doc = 0;
            } else {
                $oc_state->estado_doc = 3;
            }
            $oc_state->save();

        }else{
            $saved=false;
        }
     
        if($saved && $saved1 && $saved2 && $saved3)            
            return json_encode(['mensaje' => 200]);
        else
            return json_encode(['mensaje' => 500]);

    }

    public function fr_edit_comments(Request $request) {

        $ficha_recepcionh = FichaRecepcionH::find($request['id_reg']);
        $ficha_recepcionh->comentarios = $request['comments'];
        $ficha_recepcionh->save();

        return json_encode(['mensaje' => 200]);
    }

}