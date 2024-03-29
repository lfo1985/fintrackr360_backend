<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Exception;

/**
 * Prepara a request para validação de dados
 * 
 * @method mixed validate()
 * @method mixed parseData()
 */
abstract class Requests {
    /**
     * Recebe os dados e recurso da requisição
     * 
     * @var Request
     */
    protected $request;
    /**
     * Construção da classe
     * 
     * @return Request $request
     */
    function __construct(Request $request) {
        /**
         * Encaapsula o Request
         */
        $this->request = $request;
        /**
         * Chama o validate que foi invocado na subclasse
         */
        $this->validate();
        /**
         * Realiza a conversão de dados para enviar ao banco de dados
         */
        $parsedData = $this->parseData($this->request);
        /**
         * Valida se houveram dados definidos
         */
        if($parsedData){
            /**
             * Redefine a request
             */
            $this->request = $parsedData;
        }
    }
    /**
     * Pega as propriedades que são invocadas na subclasse
     * 
     * @param string $name
     * @return mixed
     */
    function __get($name) {
        /**
         * retorna os dados que vem na requisição conforme
         * as propriedades dinâmicas são chamadas na subclasse.
         */
        return $this->request->$name;
    }    
}