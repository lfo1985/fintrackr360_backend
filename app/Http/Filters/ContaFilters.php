<?php

namespace App\Http\Filters;

/**
 * @author Luiz Fernando
 * 
 * Classe que recebe todas as regras de filtragem de dados do modelo.
 * A inicial do nome da classe é sempre o nome do model.
 */
class ContaFilters extends Filters {
    /**
     * Método que recebe as regras para filtragem
     * 
     * @return void
     */
    protected function filter(){

        if($this->has('titulo')){
            $this->porTitulo($this->titulo);
        }
        
    }
}