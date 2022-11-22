<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ApiMiddleware
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
        if (!$request->hasHeader('Authorization')) {
            abort(401, 'Unauthorized');
        }else{
            $header = $request->header('Authorization');
            $user = User::where('access_token', $header)->first();
            if($user) {
                $request->header('Authorization',$user->access_token);
                return $next($request);
            }else {
                return response()->json(['error' => 'Invalid Access Token'], 401);
            }
            
        }
    }
}
