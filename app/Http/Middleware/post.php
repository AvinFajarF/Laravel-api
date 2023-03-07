<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post as posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class post
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
        $user = Auth::user();
        $post = posts::findOrFail($request->id);

        if($user->id != $post->created_by){
            return response()->json(['massage' => "Forbidden"], 403);
        }

        return $next($request);
    }
}
