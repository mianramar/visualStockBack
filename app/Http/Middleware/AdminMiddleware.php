<?php

/*

    Título: Entrega Final

    Autor: Miguel Ángel Rama Martínez.

    Data modificación: 17/06/2024

    Versión 1.0

*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
//Midleware para controlar el acceso en caso de que el usuario no sea "admin"
class AdminMiddleware
{
   public function handle(Request $request, Closure $next) {
    if ($request->user()->rol !== 'administrador') { //Comproba o rol de usuario
        return redirect()->back();
    }
    return $next($request);
   }
}
