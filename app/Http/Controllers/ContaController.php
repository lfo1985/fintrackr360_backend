<?php

namespace App\Http\Controllers;

use App\Http\Filters\ContaFilters;
use App\Http\Requests\ContaRequest;
use App\Http\Resources\ContaCollection;
use App\Http\Resources\ContaResource;
use App\Models\Conta;
use App\Models\Grupo;
use App\Repository\ContaRepository;
use App\Repository\GrupoRepository;
use App\Repository\PeriodoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    /**
     * Rota principal
     * 
     * @return ContaCollection
     */
    public function index(Grupo $grupo){
        /**
         * Realiza a seleção de todos os regsitros e retorna na collection.
         */
        return new ContaCollection(ContaRepository::selecionaTodos($grupo));
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
            $conta = ContaRepository::cria([
                'id_grupo' => $contaRequest->id_grupo,
                'titulo' => $contaRequest->titulo,
                'natureza' => $contaRequest->natureza,
                'descricao' => $contaRequest->descricao,
                'tipo' => $contaRequest->tipo,
                'valor' => $contaRequest->valor
            ]);
            
            PeriodoRepository::salvarPeriodos(
                $contaRequest, 
                $conta->id, 
                $contaRequest->data_vencimento,
                'ADICIONAR'
            );

            /**
             * Retorna se houve sucesso
             */
            return sucesso('Conta salva com sucesso!');
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

            PeriodoRepository::salvarPeriodos(
                $contaRequest, 
                $conta->id, 
                $contaRequest->data_vencimento,
                'EDITAR'
            );

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

    /**
     * Retorn os tipos configurados para as contas
     * 
     * @return array
     */
    public function dependencias(): array{
        /**
         * Retorna todos os tipos configurados para as contas
         */
        $keysNatureza = array_keys(ContaRepository::getNaturezas());
        $valuesNatureza = array_values(ContaRepository::getNaturezas());

        $naturezas = [];

        foreach ($keysNatureza as $i => $key) {
            $naturezas[] = [
                'id' => $key,
                'nome' => $valuesNatureza[$i]
            ];
        }
        
        $keysTipos = array_keys(ContaRepository::getTipos());
        $valuesTipos = array_values(ContaRepository::getTipos());

        $tipos = [];

        foreach ($keysTipos as $i => $key) {
            $tipos[] = [
                'id' => $key,
                'nome' => $valuesTipos[$i]
            ];
        }

        return [
            'tipos' => $tipos,
            'natureza' => $naturezas,
            'grupos' => GrupoRepository::selecionaTodosSemPaginacao()
        ];
    }

}
