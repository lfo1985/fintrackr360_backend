<?php

namespace App\Http\Controllers;

use App\Http\Filters\ContaFilters;
use App\Http\Requests\ContaRequest;
use App\Http\Resources\ContaCollection;
use App\Http\Resources\ContaResource;
use App\Models\Conta;
use App\Repository\ContaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    /**
     * Rota principal
     * 
     * @return ContaCollection
     */
    public function index(){
        /**
         * Realiza a seleção de todos os regsitros e retorna na collection.
         */
        return new ContaCollection(ContaRepository::selecionaTodos());
    }
    /**
     * Rota para pesquisa de dados.
     * 
     * @param Request $request
     * @return ContaCollection
     */
    public function search(ContaFilters $contaFilters){
        /**
         * Retorna a collection de dados filtrados com paginação
         */
        return new ContaCollection($contaFilters->get());
    }
    /**
     * Retorna um registro sendo consultado pela chave primária
     * 
     * @param Conta $conta
     * @return ContaResource
     */
    public function find(Conta $conta){
        /**
         * Retorna os dados numa resource
         */
        return new ContaResource(ContaRepository::seleciona($conta));
    }
    /**
     * Cria um novo registro
     * 
     * @param ContaRequest $contaRequest
     * @return JsonRequest
     */
    public function store(ContaRequest $contaRequest): JsonResponse{
        /**
         * Realiza a tentativa de criar um novo registro
         */
        try {
            /**
             * Chama o método para criação do registro
             */
            ContaRepository::cria([
                'id_grupo' => $contaRequest->id_grupo,
                'titulo' => $contaRequest->titulo,
                'natureza' => $contaRequest->natureza,
                'descricao' => $contaRequest->descricao,
                'valor' => $contaRequest->valor
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
     * @param ContaRequest $contaRequest
     * @param Conta $conta
     * @return JsonResponse
     */
    public function update(ContaRequest $contaRequest, Conta $conta): JsonResponse{
        /**
         * Realiza a tentaiva de atualizar os dados
         */
        try {
            /**
             * Chama o método para edição de dados
             */
            ContaRepository::edita([
                'titulo' => $contaRequest->titulo,
                'natureza' => $contaRequest->natureza,
                'descricao' => $contaRequest->descricao,
                'valor' => $contaRequest->valor
            ], $conta);
            /**
             * Retorno de sucesso
             */
            return sucesso('Conta atualizado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }
    /**
     * Apaga o registro
     * 
     * @param Conta $conta
     * @return JsonResponse
     */
    public function destroy(Conta $conta): JsonResponse{
        /**
         * Realiza a tentativa de apagar o registro
         */
        try {
            /**
             * Chama o método para apagar o registros
             */
            ContaRepository::apaga($conta);
            /**
             * Se der tudo certo, retorna sucesso
             */
            return sucesso('Conta apagada com sucesso!');
        } catch (\Exception $e) {
            /**
             * Em caso de erro, retorna exceção
             */
            return erro($e->getMessage());
        }
    }
}
