<?php

namespace App\Http\Controllers;

use App\Http\Resources\RelatorioCollection;
use App\Models\Periodo;
use App\Repository\PeriodoRepository;
use App\Repository\RelatorioRepository;
use Illuminate\Http\Request;

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

    public function definirStatus(Periodo $periodo, $status){

        try {

            RelatorioRepository::definirStatus($periodo, $status);

            return sucesso('Status atualizado para '.$status);

        } catch (\Exception $e) {

            return erro($e->getMessage());

        }
        
    }

    public function definirStatusTodos(Request $request){

        try {

            RelatorioRepository::definirStatusTodos($request, 'PAGO');

            return sucesso('Status atualizado para PAGO');

        } catch (\Exception $e) {

            return erro($e->getMessage());

        }
        
    }

}
