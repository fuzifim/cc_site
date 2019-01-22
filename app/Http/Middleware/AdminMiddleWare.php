<?php

namespace App\Http\Middleware;
use Illuminate\Routing\Redirector;
use Closure;
use Session;

class AdminMiddleWare
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
       if($request->has('username') && $request->has('password')):
            return $next($request);
        endif;
        if (Session::has('admin')):

            return $next($request);

        endif;
        return view('adminwp.login.login');
    }
}
