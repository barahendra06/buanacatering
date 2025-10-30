<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ConversationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return $next($request);
        }
        return abort('403')->withMessage('Anda tidak berhak memasuki halaman ini..');
    }
}
