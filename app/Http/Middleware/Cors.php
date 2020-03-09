<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    //A ideia é evitar o erro de Cross-Origin ao adicionar o localhost no Access-Control-Allow-Origin
    //Como estou usando um Authorization para pesquisar na API, o Access-Control-Allow-Origin não pode ser igual a * pois gera erro
    public function handle($request, Closure $next)
    {
        return $next($request)
      ->header('Access-Control-Allow-Origin', 'http://localhost')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
      ->header('Access-Control-Allow-Headers', 'Origin, Authorization');
    }
}
