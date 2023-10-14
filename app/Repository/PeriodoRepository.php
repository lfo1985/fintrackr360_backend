<?php

namespace App\Repository;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoRepository {
    /**
     * Obtem todos os registros da tabela
     * 
     * @param int $id_conta
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function selecionaTodos($id_conta): \Illuminate\Database\Eloquent\Collection{
        /**
         * Retorna todos os registros com paginação
         */
        return Periodo::where('id_conta', $id_conta)
            ->where('created_by', auth()
            ->user()->id)
            ->get();
    }
    /**
     * Cria o registro no banco de dados
     * 
     * @param array $dados
     * @param void
     */
    public static function cria(array $dados): void{
        /**
         * Realiza a tentativa de executar a rotina
         */
        try {
            /**
             * Recebe os dados do registro que será criado na tabela.
             * 
             * Neste momento os dados são preparados para gravação.
             */
            $periodo = new Periodo($dados);
            /**
             * Após a definição dos dados, realiza a gravação. Caso ocorra
             * qualquer Exceção, a requisição não será executada no banco.
             */
            $periodo->created_by = auth()->user()->id;
            $periodo->save();

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
    public static function edita(array $dados, Periodo $periodo): void{
        /**
         * Realiza a tentativa de edição dos dados.
         */
        try {
            /**
             * Realiza uma leitura de todos os dados que estão vindo
             * via parâmetro e setando cada posição numa propriedade
             * da entidade.
             */
            foreach ($dados as $campo => $valor) {
                $periodo->$campo = $valor;
            }
            /**
             * Após a definição dos dados na instância, define a gravação
             * dos dados.
             */
            $periodo->save();

        } catch (\Exception $e) {
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
     * Realiza a remoção do registro da entidade.
     * 
     * @param Periodo $periodo
     * @return void
     */
    public static function apaga(Periodo $periodo): void{
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
            $periodo->delete();
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

    /**
     * Gera ou edita os períodos conforme atualiza a conta.
     * 
     * @param ContaRequest $request
     * @param int $id_conta
     * @param float $valor
     * @param string $tipoGravacao
     * 
     * @return void
     */
    public static function salvarPeriodos($request, $id_conta, $valor, $tipoGravacao){
        /**
         * Cria os períodos
         * 
         * Recebe o valor que está vindo pelo reuqest
         */
        $valor = $request->valor;
        /**
         * Caso seja parcelado, o sistema automaticamente divide o valor
         * que foi informado pela quantidade de períodos.
         */
        if($request->tipo == 'PARCELADO'){
            $valor = ($request->valor / $request->periodos);
        }
        
        if($tipoGravacao == 'ADICIONAR'){
            /**
             * Criam as parcelas
             */
            for ($i=1; $i <= $request->periodos ; $i++) {
                /**
                 * Em caso de criação das parcelas, chama o método de criação
                 */
                $dados = [
                    'id_conta' => $id_conta,
                    'valor' => $valor,
                    'data_vencimento' => adMeses($request->data_vencimento, $i - 1)
                ];
                /**
                 * Verifica se o tipo de conta é recorrente ou a vista; neste cenário
                 * a conta é registrado com total de periodos e numero igial a 1.
                 */
                if($request->tipo == 'RECORRENTE' || $request->tipo == 'A_VISTA'){
                    /**
                     * Adiciona no array
                     */
                    $dados = $dados + [
                        'numero' => '1',
                        'total' => '1'
                    ];

                } else if($request->tipo == 'PARCELADO'){
                    /**
                     * Adiciona no array
                     */
                    $dados = $dados + [
                        'numero' => $i,
                        'total' => $request->periodos,
                    ];

                }
                self::cria($dados);

            }

        } else if($tipoGravacao == 'EDITAR'){
            /**
             * Seleciona todos os períodos com base no ID da conta
             */
            $periodos = self::selecionaTodos($id_conta);
            /**
             * Valida se existem dados
             */
            if($periodos){
                /**
                 * Realiza a leitura de todos os dados
                 */
                foreach ($periodos as $i => $periodo) {
                    /**
                     * Atualiza os dados.
                     */
                    if($periodo->status != 'PAGO'){
                        /**
                         * Sendo o período diferente de pago, executa a edição
                         */
                        self::edita([
                            'valor' => $valor,
                            'data_vencimento' => adMeses($request->data_vencimento, $i)
                        ], $periodo);
                    }

                }

            }

        }   
    }
    /**
     * Define o período como pago.
     */
    public static function definirStatus(Periodo $periodo, $status){
        
        if($status == Periodo::PAGO){
            $periodo->status = Periodo::PAGO;
        } else {
            $periodo->status = Periodo::PENDENTE;
        }
        
        return $periodo->save();
    }
    
    public static function definirStatusTodos(Request $request, $status){

        foreach ($request->periodos as $idPeriodo) {
            
            $statusDefinido = '';
            if($status == Periodo::PAGO){
                $statusDefinido = Periodo::PAGO;
            } else {
                $statusDefinido = Periodo::PENDENTE;
            }
            
            Periodo::where('id', $idPeriodo)->update([
                'status' => $statusDefinido
            ]);

        }

        return true;

    }
}