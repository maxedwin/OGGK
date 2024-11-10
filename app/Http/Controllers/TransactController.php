<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Models\Transacciones;
use App\Models\Producto;
use Dingo\Api\Routing\Helpers;
use App\Models\Categoria;
use App\User;
use Auth;
use DB;


class TransactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        /*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/

        $user = Auth::user()->accountID;

        $transacts = Transacciones::where('accountID',$user)->get();
        $transacts->toArray();

        return response()->json($transacts);
    }

    public function create(Request $request)
    {
    	$user = Auth::user()->accountID;
        $id = $request['id'];
        $title = '';
        $cant = '';
        $color = '';
        $type = null;
        if($request->path() == 'ins'){
            $title = "Nueva Entrada";
            $cant = "Entrante";
            $color = "font-blue-steel";
            $type = 1;

        }else{
            $title = "Nueva Salida";
            $cant = "Saliente";
            $color = "font-red-flamingo";
            $type = 0;
        }
        $product = DB::table('product')->where('productID',$id)->first();
        $category = DB::table('category')->where('categID',$product->categID)->first();
        
        return view('transacts/entradas')->with('product',$product)->with('category',$category)->with('title',$title)->with('cant',$cant)->with('color',$color)->with('type',$type);   
    }

    public function store(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/
        $user = Auth::user()->accountID;    

        $productID = $request['productID'];
        $quantity = $request['quantity'];
        $type = $request['type']; // 0 salida, 1 entrada
        $state = $request['state'];

        $product = Producto::find($productID);
        if($type > 0){            
            $product->quantityTotal = $product->quantityTotal + $quantity;
            $product->save();
        }else{
            if($quantity >$product->quantityTotal ){
               return response()->json(['error_quantity'], 409); 
            }else{
               $product->quantityTotal = $product->quantityTotal - $quantity;
               $product->save(); 
            }
        }

        $transacts = new Transacciones;
        $transacts->productID = $productID;
        $transacts->quantity = $quantity;
        $transacts->type = $type;
        $transacts->state = $state;
        $transacts->accountID = $user;

        $transacts->save();        

        return response()->json(['created'], 201);

    }
    public function show(Request $request){
        $user = Auth::user()->accountID;
    	$id = $request['id'];
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/

        $transact = Transacciones::where('accountID',$user)->where('productID',$id)->get();
        return  response()->json($transact);
    }

    public function edit($id){
    	
    }
    public function update(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/

        $user = Auth::user()->accountID;

        $id = $request['id'];
        $productID = $request['productID'];
        $quantity = $request['quantity'];
        $state = $request['state'];
        $type = $request['type']; // 0 salida, 1 entrada

        $transacts = Transacciones::find($id);
        $product = Producto::find($productID);

        if($transacts->type > 0){            
            $product->quantityTotal = $product->quantityTotal + $quantity;
            $product->save();
        }else{
            if($quantity >$product->quantityTotal ){
               return response()->json(['error_quantity'], 409); 
            }else{
               $product->quantityTotal = $product->quantityTotal - $quantity;
               $product->save(); 
            }
        }

        $transacts = Transacciones::find($id);
        $transacts->productID = $productID;
        $transacts->quantity = $quantity;
        $transacts->state = $state;
        $transacts->accountID = $user;

        $transacts->save();

        return response()->json(['accepted'], 202);
    }
    public function destroy(Request $request){
    	/*$currentUser = JWTAuth::parseToken()->authenticate();
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);*/

        $user = Auth::user()->accountID;
        $id = $request['id'];
        $transacts = Transacciones::find($id);
        $product = Producto::find($productID);

        if($transacts->type > 0){            
            $product->quantityTotal = $product->quantityTotal - $quantity;
            $product->save();
        }else{
            if($quantity >$product->quantityTotal ){
               return response()->json(['error_quantity'], 409); 
            }else{
               $product->quantityTotal = $product->quantityTotal + $quantity;
               $product->save(); 
            }
        }
  
        try {
        	$transacts->delete();
        	return response()->json(['accepted'], 202);
        } catch (Exception $e) {
        	return response()->json(['conflict'], 409);
        }

        
    }

   
}