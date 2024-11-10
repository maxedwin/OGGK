<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
    
      public function index()
      {
         return view('search');
      }
   
      public function search(Request $request)
      {
         if($request->ajax())
         {
            $output="";
            $products=DB::table('producto')->where('nombre','LIKE','%'.$request->search."%")->get();
            
            if($products)
            {
               foreach ($products as $key => $product) {
                  $output.='<tr>'.
                  '<td>'.$product->idproducto.'</td>'.
                  '<td>'.$product->nombre.'</td>'.
                  '<td>'.$product->descripcion.'</td>'.
                  '<td>'.$product->precio.'</td>'.
                  '</tr>';
               }
      
            return Response($output);
            }
         }
      }
}