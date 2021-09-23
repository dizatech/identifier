<?php

namespace Dizatech\Identifier\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentifierGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( Auth::check() ){
            if( $request->format() == 'json' ){
                return response('', 409);
            }
            else{
                return response('', 302)
                    ->header( 'Location', config('app.url') );
            }
        }

        return $next($request);
    }
}
