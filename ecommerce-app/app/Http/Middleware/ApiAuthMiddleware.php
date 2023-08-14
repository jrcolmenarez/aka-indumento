<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checktoken = $jwtAuth->checkToken($token);

        if ($checktoken){
            return $next($request);
        }else {
            $data = array (
                'code' => 200,
                'status' => 'error',
                'message' => 'Usuario no ha iniciado sesion'
             );
            return response()->json($data, $data['code']);
        }
    }
}
