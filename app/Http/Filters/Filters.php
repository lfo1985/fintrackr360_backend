<?php
namespace App\Http\Filters;

use Illuminate\Http\Request;

/**
 * Prepara a request para validação de dados
 * 
 * @method mixed filter()
 * @property string model
 */
abstract class Filters {

    protected $request;
    protected $query;

    function __construct(Request $request){

        
        $parentClassName = array_reverse(explode('\\',get_class($this)));
        $model = str_replace('Filters', '', current($parentClassName));

        $this->request = $request;
        
        $class = 'App\\Models\\'.ucfirst($model);

        $model = new $class;
        
        $this->query = $model->query();
        
        $this->filter();
    }

    function __get($name) {
        return $this->request->$name;
    }

    function __call($name, $arguments){
        return $this->query->$name($arguments[0]);
    }

    protected function has($field){
        return $this->request->has($field);
    }

    public function get(){
        return $this->query->paginate();
    }

}