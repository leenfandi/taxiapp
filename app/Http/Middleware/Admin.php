<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
class Admin  extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected function authenticate($request, array $guards)
    {
        try{


            if ($this->auth->guard('admin')->check()) {
                return $this->auth->shouldUse('admin');
            }


        $this->unauthenticated($request, ['admin']);
        }
        catch (\Exception $e){
            return  response()->json(['msg'=>'Unauthenticated user']);
        }catch (\Exception $e)
        {
            return  response()->json(['msg'=>'token_invaled',$e ->getMessage()]);
        }
    }
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            return route('login');
        }
    }
}
