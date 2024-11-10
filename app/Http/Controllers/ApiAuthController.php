<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Http\Requests;
use App\Models\Pokemones;

class ApiAuthController extends Controller
{
    public function UserAuth(Request $request){
    	$credencials = $request->only('email','password');
    	$token = null;
    	try {
    		if(!$token = JWTAuth::attempt($credencials)){
    			return response()->json(['error' => 'invalid_credencials'],500);
    		}
    	} catch (Exception $e) {
    		return response()->json(['error' => 'something_went_wrong'],500);
    	}
    	$user = JWTAuth::toUser($token);
    	return response()->json(compact('token','user'));
	}
	
    public function prueba(){
    	try {
	        if (! $user = JWTAuth::parseToken()->authenticate()) {
	            return response()->json(['error' => 'invalid_credencials'],500);
	        }else{
	        	$pokemones = Pokemones::all();
				return response()->json(compact('pokemones'));
	        }

	    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

	        return response()->json(['token_expired'], $e->getStatusCode());

	    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

	        return response()->json(['token_invalid'], $e->getStatusCode());

	    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

	        return response()->json(['token_absent'], $e->getStatusCode());

	    }
		
		
    }

    public function verificar(){
    	try {

	        if (! $user = JWTAuth::parseToken()->authenticate()) {
	            return response()->json(['error' => 'invalid_credencials'],500);
	        }

	    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

	        return response()->json(['token_expired'], $e->getStatusCode());

	    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

	        return response()->json(['token_invalid'], $e->getStatusCode());

	    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

	        return response()->json(['token_absent'], $e->getStatusCode());

	    }

	    // the token is valid and we have found the user via the sub claim
	    return 1;
    }
}
