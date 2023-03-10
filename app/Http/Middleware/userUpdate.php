<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class userUpdate
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

        $userId = Auth::user()->id;
        $requestId = $request->id;

        if($requestId != $userId){
            return response()->json([
                "status" => "Error",
                "massage" => "Anda tidak dapat mengedit profile orang lain"
            ], 401);
        }


        return $next($request);
    }
}
