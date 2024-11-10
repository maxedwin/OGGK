<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use JWTAuth;
use Dingo\Api\Routing\Helpers;
use App\Models\Slide;
use DB;
use App\User;
use Auth;
use Image;

class SlideController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cant = $request['cant'];
        $query = $request['query'];

        if(!isset($cant))$cant = 100;
        if(!isset($query)){
            $slides = DB::table('slides')->get();
        }else{
        	$slides = DB::table('slides')
        		->where('alias','like', '%'.$query.'%')
        		->get();
        }

        return view('slide/listado_slide', ['slides' => $slides]);

    }

    public function store(Request $request)
    {
    	$slide = new Slide;
        $slide->alias = $request['alias'];

        if($request->file('image')){
            $image=$request->file('image');
            if($image->isValid()){
                $fileName=time().'-'.str_slug($request['alias'],"-").'.'.$image->getClientOriginalExtension();
                Image::make($image->getRealPath())->save('./images/large/'. $fileName);
                $slide->image=$fileName;
            }
        }
        $slide->save();

        return redirect()->action('SlideController@index');
    }

    public function delete(Request $request){
        $idslide = $request['idslide'];
        $slide = Slide::find($idslide);
        $image='./images/large/'.$slide->image;
        unlink($image);
        $slide->delete();
        return json_encode(['mensaje' => 200]);
    }

    public function active(Request $request){
        $idslide = $request['idslide'];
        $slide = Slide::find($idslide);
        $slide->active = !$slide->active;
        $slide->save();
        return response()->json(['accepted'], 202);
    }
}
