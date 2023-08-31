<?php

namespace App\Http\Controllers;

use App\Http\Filters\GrupoFilters;
use App\Http\Requests\GrupoRequest;
use App\Http\Resources\GrupoCollection;
use App\Http\Resources\GrupoResource;
use App\Models\Grupo;
use App\Repository\GrupoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /**
     * Rota principal
     * 
     * @return GrupoCollection
     */
    public function index(){
        /**
         * Realiza a seleção de todos os regsitros e retorna na collection.
         */
        return new GrupoCollection(GrupoRepository::selecionaTodos());
    }
    /**
     * Rota para pesquisa de dados.
     * 
     * @param Request $request
     * @return GrupoCollection
     */
    public function search(GrupoFilters $grupoFilters){
        /**
         * Retorna a collection de dados filtrados com paginação
         */
        return new GrupoCollection($grupoFilters->get());
    }
    /**
     * Retorna um registro sendo consultado pela chave primária
     * 
     * @param Grupo $grupo
     * @return GrupoResource
     */
    public function find(Grupo $grupo){
        /**
         * Retorna os dados numa resource
         */
        return new GrupoResource(GrupoRepository::seleciona($grupo));
    }
    /**
     * Cria um novo registro
     * 
     * @param GrupoRequest $grupoRequest
     * @return JsonRequest
     */
    public function store(GrupoRequest $grupoRequest): JsonResponse{
        /**
         * Realiza a tentativa de criar um novo registro
         */
        try {
            /**
             * Chama o método para criação do registro
             */
            GrupoRepository::cria([
                'nome' => $grupoRequest->nome
            ]);
            /**
             * Retorna se houve sucesso
             */
            return sucesso('Grupo salvo com sucesso!');
        } catch (\Exception $e) {
            /**
             * Em caso de erro, retorna a exceção
             */
            return erro($e->getMessage());
        }
    }
    /**
     * Atualiza os dados do registro
     * 
     * @param GrupoRequest $grupoRequest
     * @param Grupo $grupo
     * @return JsonResponse
     */
    public function update(GrupoRequest $grupoRequest, Grupo $grupo): JsonResponse{
        /**
         * Realiza a tentaiva de atualizar os dados
         */
        try {
            /**
             * Chama o método para edição de dados
             */
            GrupoRepository::edita([
                'nome' => $grupoRequest->nome
            ], $grupo);
            /**
             * 
             */
            return sucesso('Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }
    /**
     * Apaga o registro
     * 
     * @param Grupo $grupo
     * @return JsonResponse
     */
    public function destroy(Grupo $grupo): JsonResponse{
        /**
         * Realiza a tentativa de apagar o registro
         */
        try {
            /**
             * Chama o método para apagar o registros
             */
            GrupoRepository::apaga($grupo);
            /**
             * Se der tudo certo, retorna sucesso
             */
            return sucesso('Grupo apagado com sucesso!');
        } catch (\Exception $e) {
            /**
             * Em caso de erro, retorna exceção
             */
            return erro($e->getMessage());
        }
    }
}
