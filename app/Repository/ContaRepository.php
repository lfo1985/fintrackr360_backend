<?php

namespace App\Repository;

use App\Models\Conta;
use Illuminate\Support\Facades\DB;

class ContaRepository {

    /**
     * Obtem todos os registros da tabela
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function selecionaTodos(): \Illuminate\Pagination\LengthAwarePaginator{
        /**
         * Retorna todos os registros com paginação
         */
        return Conta::paginate();
    }

    /**
     * Obtem apenas um registro de acordo com o ID informado
     * 
     * @param Conta
     * @return Conta $conta
     */
    public static function seleciona(Conta $conta): Conta{
        /**
         * Retorna o objeto da entidade selecionada a partir do id
         * informado via parâmetro.
         */
        return $conta;
    }

    /**
     * Cria o registro no banco de dados
     * 
     * @param array $dados
     * @param void
     */
    public static function cria(array $dados): void{
        /**
         * Realiza a tentativa de criar o registro no banco de dados.
         */
        try {
            /**
             * Inicia a transaction para instanciar a requisição
             * de modo que se der qualquer problema seja possível
             * desfazer a ação dando um rollback.
             */
            DB::beginTransaction();
            /**
             * Recebe os dados do registro que será criado na tabela.
             * 
             * Neste momento os dados são preparados para gravação.
             */
            $conta = new Conta($dados);
            /**
             * Após a definição dos dados, realiza a gravação. Caso ocorra
             * qualquer Exceção, a requisição não será executada no banco.
             */
            $conta->created_by = auth()->user()->id;
            $conta->save();
            /**
             * Ocorrendo tudo certo na instância da requisição,
             * realiza o commit para executar de fato a ação no
             * banco de dados.
             */
            DB::commit();
        } catch (\PDOException $e) {
            /**
             * Se ocorrer qualquer exceção, realiza o rollBack()
             * cancelando a requisição.
             */
            DB::rollBack();
            /**
             * Dispara a exceção.
             */
            excecao($e->getMessage(), 1);
        }
    }
    /**
     * Edita o registro da entidade
     * 
     * @param array $dados
     * @param Cliente $cliente
     * @return void
     */
    public static function edita(array $dados, Conta $conta): void{
        /**
         * Realiza a tentativa de editar os dados
         */
        try {
            /**
             * Inicia a transaction para preparo da rotina de atualização.
             */
            DB::beginTransaction();
            /**
             * Realiza uma leitura de todos os dados que estão vindo
             * via parâmetro e setando cada posição numa propriedade
             * da entidade.
             */
            foreach ($dados as $campo => $valor) {
                $conta->$campo = $valor;
            }
            /**
             * Após a definição dos dados na instância, define a gravação
             * dos dados.
             */
            $conta->save();
            /**
             * Commita todo o preparo realizado acima.
             */
            DB::commit();
        } catch (\PDOException $e) {
            /**
             * Em caso de problema durante a preparação dos dados,
             * o sistema realiza um rollback e dispara uma exceção.
             */
            DB::rollBack();
            /**
             * dispara a exceção.
             */
            excecao($e->getMessage());
        }
    }
    /**
     * Realiza a remoção do registro da entidade.
     * 
     * @param Conta $conta
     * @return void
     */
    public static function apaga(Conta $conta): void{
        /**
         * Realiza a tentativa de apagar o registro.
         */
        try {
            /**
             * Inicia a transaction.
             */
            DB::beginTransaction();
            /**
             * Prepara a deleção do registro informado via
             * parâmetro.
             */
            $conta->delete();
            /**
             * Commita as ações que foram preparadas acima.
             */
            DB::commit();
        } catch (\PDOException $e) {
            /**
             * Em caso de problema, dispara a exceção e executa
             * o rollback.
             */
            DB::rollBack();
            /**
             * dispara a exceção.
             */
            excecao($e->getMessage());
        }
    }

}