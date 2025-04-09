<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Session;

class appcheckauth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


       
        if (Session::has('AppUserData') )
        {
            $is_logged= false;
            if (Session::has('AppUserData')) {
                $appUserData = Session::get('AppUserData');
                $is_logged= $appUserData['Login'];
            }

            if($is_logged){
                return redirect()->route('ap.dashboard');
            }
        

        }
        
        return $next($request);
    }
}
