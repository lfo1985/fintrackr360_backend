<?php

namespace App\Http\Controllers;

use App\Http\Resources\RelatorioCollection;
use App\Repository\RelatorioRepository;

class RelatorioController extends Controller{
    
    /**
     * Método responsável por retornar os dados via API
     * 
     * @param int $mes
     * @param int $ano
     * @return RelatorioCollection
     */
    public function index($mes, $ano){
        /**
         * Realiza a consulta e organiza numa collection
         */
        return exibe(RelatorioRepository::selecionar($mes, $ano));
    }
    /**
     * Retorna todos os dados para utilizar no filtro de busca
     * dos dados no relatório.
     * 
     * @return array
     */
    public function dadosFitro(){
        /**
         * Retorna os dados
         */
        return [
            'meses' => meses(),
            'anos' => anos()
        ];
    }

    public function resultado(){

        return exibe(RelatorioRepository::resultado());

    }

}
