<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class ParseTokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $tokens = $request->header('Authorization');

            if (!$tokens) {
                return response()->json(['error' => 'Authorization token not found'], 401);
            }
    
            try {
                $parsedToken = JWTAuth::parseToken();
          
            $user = $parsedToken->authenticate();
            } catch (Exception $e) {
                return response()->json(['error' => 'Invalid token'], 401);
            }
    
            $token = $parsedToken->getToken();
            $request->headers->set('Authorization', 'Bearer ' . $token);
           
            return $next($request);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
    
}
