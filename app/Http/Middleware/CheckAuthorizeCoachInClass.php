<?php

namespace App\Http\Middleware;

use App\classroom;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthorizeCoachInClass
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $classroom = classroom::query()->find($request->route('classroom'));
        if ($classroom) {
            if (Auth::user()->isCoach() && $classroom->coaches->where('user_id', Auth::user()->id)->count() === 0)
            {
                return abort(403);
            }
        }

        return $next($request);
    }
}
