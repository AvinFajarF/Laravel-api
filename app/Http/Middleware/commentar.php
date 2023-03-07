<?php

namespace App\Http\Middleware;

use App\Models\Comments;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class commentar
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
        $comment = Comments::findOrFail($request->id);

        if($user->id != $comment->user_id){
            return response()->json(['massage' => "Forbidden"], 403);
        }

        return $next($request);
    }
}
