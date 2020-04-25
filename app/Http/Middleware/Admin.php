<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class Admin
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
//        // admin session이 있는 경우만 request를 진행한다.
//        if (Auth::guard('admin')->check()) {
        if(auth()->user() ==null){
            return redirect('admin/login');
        }

        if (auth()->user()->is_admin ==1) {
            return $next($request);
        }

        Auth::logout();
        return redirect('admin/login');
    }
}
