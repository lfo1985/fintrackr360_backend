<?php

namespace App\Http\Requests;

/**
 * @author Luiz Fernando
 * 
 * Classe que configura as regras de requisição.
 */
class ContaRequest extends Requests {

    /**
     * Método que recebe as regras para validação de dados
     * @return void
     */
    protected function validate(){
        //
    }

    protected function parseData($request){

        $request->valor = str2dec($request->valor);
        
        return $request;
    }
    
}