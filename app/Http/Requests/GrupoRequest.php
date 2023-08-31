<?php

namespace App\Http\Requests;

/**
 * @author Luiz Fernando
 * 
 * Classe que configura as regras de requisição.
 */
class GrupoRequest extends Requests {

    /**
     * Método que recebe as regras para validação de dados
     * @return void
     */
    protected function validate(){
        /**
         * Valida o nome se foi informado
         */
        if(!$this->nome){
            /**
             * Retorna exceção
             */
            excecao("Nome é obrigatório");
        }
    }
    
}