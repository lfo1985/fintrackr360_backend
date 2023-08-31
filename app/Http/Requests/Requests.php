<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Exception;

/**
 * Prepara a request para validação de dados
 * 
 * @method mixed validate()
 */
abstract class Requests {

    protected $request;
    
    function __construct(Request $request) {
        $this->request = $request;
        $this->validate();
    }

    function __get($name) {
        return $this->request->$name;
    }

    protected static function exception($msg){
        throw new Exception($msg, 1);
    }
    
}