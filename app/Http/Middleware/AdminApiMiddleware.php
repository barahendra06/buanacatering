<?php
namespace App\Http\Middleware;

use Closure;
use Auth;
use JWTAuth;

class AdminApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try 
        {
            $user = JWTAuth::parseToken()->authenticate();
            if($user->isAdmin())
            {
                return $next($request);
            }
            else
            {
                return abort('403')->withMessage('Anda tidak berhak memasuki halaman ini..');
            }
        } 
        catch (\Exception $e) 
        {
            return $e->getCode();
        }
    }
}
