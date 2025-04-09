<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Gatekeepers;
use Illuminate\Http\Request;
use App\Models\Agent;
use Symfony\Component\HttpFoundation\Response;
use Session;

class AppCheckOtherDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Session::has('AppUserData')) {
            $id   = Session::get('AppUserData')['UID'];


                $AdminSql = Agent::where(['agent_id' =>$id])->first();
            


            if($AdminSql != null && Session::get('AppUserData')['Login']){
                return $next($request);
            }
            else{
                return redirect()->route('ap.logout');
            }
        }
        else{
            return redirect()->route('ap.logout');
        }
        // return $next($request);
    }
}
