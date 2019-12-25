<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserAndOperator
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
        if (!$request->user()->isOfType('o')  && !$request->user()->isOfType('u')) {
            return response()->json(["msg" => "Only users of type Operator and User can access this"], 401);
        }
        return $next($request);
    }
}
