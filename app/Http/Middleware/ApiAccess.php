<?php

namespace App\Http\Middleware;

use Closure;
use Response;

class ApiAccess
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
        //only whitelist
        if( !in_array($request->ip(), config('firewall.whitelist')))
        {
            return response()->json(array(
                                    'error'=>true,
                                    'message'=>'Unauthorized'
                                    ));
        }

        return $next($request);
        
    }
}
