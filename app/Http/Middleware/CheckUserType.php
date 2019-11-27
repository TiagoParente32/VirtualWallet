<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if (!$request->user()->isOfType($type)) {
            return response()->json(["msg" => "Only users of type $type can access this"], 401);
        }
        return $next($request);
    }
}
