<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class PhotoMiddleware
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
		if(Auth::user()->isAdmin() or Auth::user()->isOperational() or Auth::user()->isEditor())
        {
			return $next($request);
        }
        return abort('403')->withMessage('Anda tidak berhak memasuki halaman ini..');
    }
}
