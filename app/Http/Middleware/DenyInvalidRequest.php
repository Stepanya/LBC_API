<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DenyInvalidRequest
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
        
        switch ($request->method()) {

            case 'POST':
                return $next($request);
                break;

            case 'GET':
                if ($request->getRequestUri() != '/api/v1/booking/cbm') {
                    return $next($request);
                } else {
                    return response()->json(['message' => 'GET request method not supported'], 400);
                }
                break;
    
            default:
                return response()->json(['message' => 'Request method not supported'], 400);
                break;
        }
    }
}
