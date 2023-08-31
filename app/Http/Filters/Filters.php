<?php
namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Prepara a request para validação de dados
 * 
 * @method mixed filter()
 * @property string model
 */
abstract class Filters {
    /**
     * Recebe o Request com os dados enviados via request.
     * 
     * @var Request
     */
    private $request;
    /**
     * Recebe o build da consulta criada com as regras de
     * negócio do modelo.
     * 
     * @var \Illuminate\Database\Eloquent\Builder $query
     */
    private $query;
    /**
     * Construçao da classe
     * 
     * @param Request
     */
    function __construct(Request $request){
        /**
         * Chama o query para criar a consulta.
         */
        $this->query = $this->model()->query();
        /**
         * Encapsula o request
         */
        $this->request = $request;
        /**
         * Chama a filtragem de dados declarado
         * na subclasse.
         */
        $this->filter();
    }
    /**
     * Pega as propriedadss dinâmicas invocados na subclasse.
     * 
     * @param string $name
     * @return mixed
     */
    function __get($name) {
        return $this->request->$name;
    }
    /**
     * Pega os métodos dinâmicos invocados na subclasse.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    function __call($name, $arguments){
        return $this->query->$name($arguments[0]);
    }
    /**
     * Retorna a instância do modelo.
     * 
     * @return Model
     */
    private function model(): Model{
        /**
         * Quebra o namespace para extrair o nome da subclass
         */
        $parentClassName = array_reverse(explode('\\',get_class($this)));
        /**
         * Remote o "Filters" do nome para obter o nome do model
         */
        $model = str_replace('Filters', '', current($parentClassName));
        /**
         * Define o nome e caminho do modelo
         */
        $class = 'App\\Models\\'.ucfirst($model);
        /**
         * Retorna e instancia o modelo
         */
        return new $class;
    }
    /**
     * Retorna o has() do Request
     * @return bool
     */
    protected function has($field): bool{
        return $this->request->has($field);
    }
    /**
     * Retorna os dados paginados.
     * @return 
     */
    public function get(): LengthAwarePaginator{
        return $this->query->paginate();
    }

}