<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckProfileMiddleware
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
		if(Auth::check())
        {
            $user = Auth::user();
            $member = $user->member;

            // give  access to admin 
            if($user->isAdmin())
            {
                return $next($request);
            }


            $data = [$member->province_id, $member->education_type_id, $member->mobile_phone, $member->school, $member->address, $member->city];

            if(in_array(null, $data))
            {
                if($request->ajax())
                {
                    return response()->json(["message"=>"Please complete your profile first.."],500);
                }
                return redirect()->route('edit-profile')->withMessage('Please complete your profile first..');
            }
        }
        return $next($request);
    }
}
