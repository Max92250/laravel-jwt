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
    public function handle($request, Closure $next)
    {
        try {
            $token = $request->header('Authorization');

            if (!$token) {
                return response()->json(['error' => 'Authorization token not found'], 401);
            }

            try {
                $parsedToken = JWTAuth::parseToken();
                $user = $parsedToken->authenticate();
            
            } catch (Exception $e) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

        
            $request->headers->set('Authorization', 'Bearer ' . $token);
            
     

            return $next($request);
        } catch (Exception $e) {
            return response()->json(['status' => 'Error processing token'], 500);
        }
    }
}
