<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//use Cookie;

class MaintenanceController extends Controller
{
    public function __construct() {

    }

    public function getCookie(Request $request) {
        
        return view('maintenance_cookie_page');
    }
}
