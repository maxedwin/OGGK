<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Regions;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index()
    {
        $categories = Regions::all();
        return view('form', compact('regions'));
    }
}
