<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class JwtLoginController extends Controller {

    public function login(Request $request){
        
        try {
            /**
             * Realiza a tentativa de login utilizando as credenciais
             * enviadas via POST pelo cliente.
             */
            $token = auth('api')->attempt($request->all(['email', 'password']));
            /**
             * Valida se o token foi definido.
             */
            if(!$token){
                /**
                 * Se não, dispara uma exceção de acesso não autorizado
                 */
                throw new UnauthorizedException("Login invalido.");
            }
            /**
             * Ocorrendo tudo certo, responde o token para o usuário
             */
            return sucesso('OK', ['token' => $token, 'usuario' => auth('api')->user()]);
        } catch (\Illuminate\Validation\UnauthorizedException $e) {
            /**
             * Em caso de erro, dispara o erro 401 com a mensagem da exceação.
             */
            return erro($e->getMessage(), 401);
        }

    }

    public function logout(){
        /**
         * Chama o logout para quebrar o token
         */
        auth('api')->logout();
        /**
         * Retorna sucesso
         */
        return sucesso('OK');
    }

    public function logged(){
        return auth()->user();
    }

}
