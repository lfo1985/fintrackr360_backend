<?php

namespace App\Http\Filters;

/**
 * @author Luiz Fernando
 * 
 * Classe que recebe todas as regras de filtragem de dados do modelo.
 * A inicial do nome da classe é sempre o nome do model.
 */
class GrupoFilters extends Filters {
    /**
     * Método que recebe as regras para filtragem
     * 
     * @return void
     */
    protected function filter(){
        /**
         * Verifica se existe o nome na requisição
         */
        if($this->has('nome')){
            /**
             * Chama o escopo no modelo
             */
            $this->nome($this->nome);
        }
    }
}