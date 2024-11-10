<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Route;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $actionName = Route::getCurrentRoute()->getActionName();

        if ($actionName == 'App\Http\Controllers\MaintenanceController@getCookie') {
            $value = '';
            if ($request->hasCookie('mantenimiento')) {
                $value = Cookie::get('mantenimiento');
            } else {
                $token = md5(date("Y-m-dH:i:s") . 'cookie_token');
                $minutes = 10080; // 7 dias
                Cookie::queue(Cookie::make('mantenimiento', $token, $minutes));
                $value = Cookie::get('mantenimiento');
            }
            return $next($request);
        }
        
        $in_maintenance = config('constants.in_maintenance');
        if( $in_maintenance && !$request->hasCookie('mantenimiento') ){
          return redirect('/maintenance');
        }
        return $next($request);
    }
}
