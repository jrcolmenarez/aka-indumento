<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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

       /* return $next($request)
        //Url a la que se le dará acceso en las peticiones, puedes poner un  "*" para aceptar todas los origenes
        ->header("Access-Control-Allow-Origin", "http://urlfronted.example")
        //Métodos que a los que se da acceso, agregas los que necesites
        ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE")
        //Headers de la petición
        ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");*/
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                                     ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type');
        }
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application, application/x-www-form-urlencoded');

        return $response;

    }
}
