<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\PayMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use DB;
use Image;

class PayMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Listar metodos de pago
    public function index()
    {
        $pay_methods = DB::table('pay_methods')
                    ->select('pay_methods.*')
                    ->get();
                    return view('pay_method/pay_methods')->with('pay_methods',$pay_methods);
    }

    //Vista para crear nuevo metodo de pago
    public function create()
    {
        return view('pay_method/nuevo_paymethod');
    }

    //Busqueda por nombre
    public function get_ID(Request $request){
        $nombre = $request['nombre'];
        $pay_method =DB::table('PayMethod')->where('nombre','like','%'.$nombre.'%')->first();
        return response()->json($pay_method);
    }

    //Vista para editar metodo de pago
    public function update(Request $request){
        $id_paymethod = $request['id_paymethod'];
        $pay_method = PayMethod::find($id_paymethod);
        return view('pay_method/editar_paymethod',['mensaje' => '200'])->with('pay_method', $pay_method);
    }

    //Busqueda por id
    public function get(Request $request){
        $id_paymethod = $request['id_paymethod'];
        if(!empty($id_paymethod)){
            $pay_method = DB::table('pay_methods')
                ->where('id_paymethod',$id_paymethod)
                ->first();
                return response()->json($pay_method, 202);
        }
    }

    //Guardar nuevo metodo de pago en la base de datos
    public function store(Request $request){
        $pay_method = new PayMethod();
        $pay_method->nombre = $request['nombre'];
        $pay_method->descripcion_pre = $request['descripcion_pre'];
        $pay_method->descripcion_det = $request['descripcion_det'];
        if($request->file('imagen')){
            $imagen=$request->file('imagen');
            if($imagen->isValid()){
                $fileName=time().'-'.str_slug($request['nombre'],"-").'.'.$imagen->getClientOriginalExtension();
                Image::make($imagen->getRealPath())->save('./images/large/'. $fileName);
                $pay_method->imagen=$fileName;
            }
        }
        $pay_method->save();
        return redirect()->action('PayMethodController@index');
    }

    //Busqueda por id
    public function show(Request $request){
    	$id_paymethod = $request['id_paymethod'];
    	$pay_method = PayMethod::where('id_paymethod',$id_paymethod)->first();      
        return  response()->json($pay_method);
    }

    //Guardar en la base de datos los cambios realizados en un metodo de pago
    public function store_update(Request $request){
        $id_paymethod = $request['id_paymethod'];
        $nombre = $request['nombre'];
        $descripcion_pre = $request['descripcion_pre'];
        $descripcion_det = $request['descripcion_det'];
        $pay_method = PayMethod::find($id_paymethod);
        $pay_method->nombre = $nombre;
        $pay_method->descripcion_pre = $descripcion_pre;
        $pay_method->descripcion_det = $descripcion_det;
        if($request->file('imagen')){
            $imagen=$request->file('imagen');
            if($imagen->isValid()){
                if ($pay_method->imagen != ''){
                    $imagenactual='./images/large/'.$pay_method->imagen;
                    unlink($imagenactual);
                }                
                $fileName=time().'-'.str_slug($request['nombre'],"-").'.'.$imagen->getClientOriginalExtension();
                Image::make($imagen->getRealPath())->save('./images/large/'. $fileName);
                $pay_method->imagen=$fileName;
            }
        } 
        $pay_method->save();
        return redirect()->action('PayMethodController@index');
    }

    //Eliminar metodo de pago, no se puede eliminar si solo queda 1
    public function delete(Request $request){
        $num_pay_methods = PayMethod::all()->count();
        if ( $num_pay_methods <= 1 )
        {
            return json_encode(['mensaje' => 900]);
        }
        $id_paymethod = $request['id_paymethod'];
        $pay_method = PayMethod::find($id_paymethod);
        $imagen='./images/large/'.$pay_method->imagen;
        unlink($imagen);
        $pay_method->delete();
        return json_encode(['mensaje' => 200]);
    }   
}