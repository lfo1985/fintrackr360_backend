<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidacaoTokenUsuario
{

    public function handle(Request $request, Closure $next)
    {
        /**
         * Aplicação de uma condição que realiza a validação do token
         * informado pelo usuário. Neste local ainda precisamos informar
         * qual o token que o usuário tá enviando.
         */
        if($request->headers->get('Authorization') != 'aloha'){
            return erro('Não autorizado!', 401);
        } else {
            return $next($request);
        }
    }
}
