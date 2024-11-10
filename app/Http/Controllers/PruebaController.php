<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use App\Book;
use Dingo\Api\Routing\Helpers;
use App\Models\Pokemones;
use App\User;


class PruebaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        return json_encode($currentUser->publicaciones()->get()
        ->toArray());
    }
   
}