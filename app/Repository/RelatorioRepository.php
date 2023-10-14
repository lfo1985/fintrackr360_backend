<?php

namespace App\Repository;

use App\Models\Grupo;
use App\Models\Periodo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class RelatorioRepository {
    /**
     * Obtem todos os registros da tabela
     * 
     * @param int $mes
     * @param int $ano
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function selecionar($mes, $ano){
        /**
         * Configura o invervalo de data conforme os parâmetros
         */
        $data1 = $ano.'-'.$mes.'-01';
        $data2 = $ano.'-'.$mes.'-31';
        /**
         * Faz a consulta
         */
        $dados = [];

        $dados = DB::table('grupos')
            ->join('contas', 'grupos.id', '=', 'contas.id_grupo')
            ->join('periodos', 'contas.id', '=', 'periodos.id_conta')
            ->select([
                'grupos.id',
                'grupos.nome AS nome_grupo',
                'contas.titulo',
                'contas.natureza',
                'contas.descricao',
                'contas.tipo',
                'periodos.id_conta',
                'periodos.id AS id_periodo',
                'periodos.numero',
                'periodos.total',
                'periodos.valor',
                'periodos.data_vencimento',
                'periodos.status',
            ])
            ->where('data_vencimento', '>=', $data1)
            ->where('data_vencimento', '<=', $data2)
            ->where('grupos.created_by', '=', auth()->user()->id)
            ->get();

        $resultado = [];

        foreach ($dados as $i => $itens) {

            $resultado[$itens->id]['id'] = $itens->id;
            $resultado[$itens->id]['nome'] = $itens->nome_grupo;

            $conta = [
                'id' => $itens->id_conta,
                'titulo' => $itens->titulo,
                'natureza' => $itens->natureza,
                'descricao' => $itens->descricao,
                'tipo' => $itens->tipo,
                'periodo' => [
                    'id' => $itens->id_periodo,
                    'numero' => $itens->numero,
                    'total' => $itens->total,
                    'valor' => $itens->valor,
                    'valor_formatado' => $itens->natureza == 'D' ? dec2str(0-$itens->valor) : dec2str($itens->valor),
                    'data_vencimento' => $itens->data_vencimento,
                    'status' => $itens->status,
                ]
            ];

            $resultado[$itens->id]['conta'][] = $conta;
        }
        
        sort($resultado);

        /**
         * Retorna os dados
         */
        return $resultado;
    }

    public static function resultado(){

        $dados = Periodo::with(['conta'])
            ->where('created_by', auth()->user()->id)
            ->get();

        $resultados = [];
        $resultadosPagosRecebidos = [];
        
        if($dados){

            $creditoRecebido = 1;
            $debitoPago = 1;
            
            foreach ($dados as $item) {

                $ano = date('Y', strtotime($item->data_vencimento));
                $mes = date('m', strtotime($item->data_vencimento));
                
                $resultados[$ano.'-'.$mes][$item->conta->natureza][] = $item->conta->natureza == 'D' ? 0-$item->valor : (float) $item->valor;
                
                if($item->status == Periodo::PAGO){
                
                    if($item->conta->natureza == 'D'){
                        $resultadosPagosRecebidos[$ano.'-'.$mes]['D']['PAGOS'][] = Periodo::PAGO;
                        $creditoRecebido++;
                    }
                    
                    if($item->conta->natureza == 'C'){
                        $resultadosPagosRecebidos[$ano.'-'.$mes]['C']['PAGOS'][] = Periodo::PAGO;
                        $debitoPago++;
                    }
                    
                }
                if($item->conta->natureza == 'D'){
                    $resultadosPagosRecebidos[$ano.'-'.$mes]['D']['TOTAL'][] = 1;
                }
                if($item->conta->natureza == 'C'){
                    $resultadosPagosRecebidos[$ano.'-'.$mes]['C']['TOTAL'][] = 1;
                }
            }

        }

        $dadosSomados = [];

        foreach ($resultados as $mesAno => $dadosMesAno) {
            
            foreach ($dadosMesAno as $natureza => $valores) {
                
                $dadosSomados[$mesAno][$natureza] = array_sum($valores);

            }

        }

        $dadosComSaldo = [];
        $dadosComSaldoFormatados = [];

        if(count($dadosSomados) > 0){
            foreach ($dadosSomados as $mesAno => $natureza) {
    
                $credito = data_get($natureza, 'C', 0);
                $debito = abs(data_get($natureza, 'D', 0));
                $saldo = $credito - $debito;
                
                $dadosComSaldo[$mesAno]['C'] = $credito;
                $dadosComSaldo[$mesAno]['D'] = $debito;
                $dadosComSaldo[$mesAno]['SALDO'] = $saldo;
    
                $mesAnterior = date('Y-m', strtotime($mesAno.'-01 -1 months'));
    
                $acumulado = data_get($dadosComSaldo, $mesAnterior.'.SALDO_ACUMULADO') + $saldo;
    
                $dadosComSaldo[$mesAno]['SALDO_ACUMULADO'] = $acumulado;
    
                $dadosComSaldoFormatados[$mesAno]['C'] = dec2str($credito);
                $dadosComSaldoFormatados[$mesAno]['D'] = dec2str(0-$debito);
                $dadosComSaldoFormatados[$mesAno]['SALDO'] = dec2str($saldo);
                $dadosComSaldoFormatados[$mesAno]['SALDO_ACUMULADO'] = dec2str($acumulado);
                $dadosComSaldoFormatados[$mesAno]['CLASSE_ACUMULADO'] = $acumulado < 0 ? 'danger' : 'success';
                $dadosComSaldoFormatados[$mesAno]['CLASSE_SALDO'] = $saldo < 0 ? 'danger' : 'success';
    
                $TOTAL_RECEBIDO_CREDITO = array_count_values(data_get($resultadosPagosRecebidos, $mesAno.'.C.PAGOS', []));
                $TOTAL_PAGO_DEBITO = array_count_values(data_get($resultadosPagosRecebidos, $mesAno.'.D.PAGOS', []));
                $TOTAL_LANCAMENTOS_CREDITO = array_sum(data_get($resultadosPagosRecebidos, $mesAno.'.C.TOTAL', []));
                $TOTAL_LANCAMENTOS_DEBITO = array_sum(data_get($resultadosPagosRecebidos, $mesAno.'.D.TOTAL', []));
    
                $PERCENTUAL_CREDITO_RECEBIDO = 0;
                $PERCENTUAL_DEBITO_PAGO = 0;
    
                if($TOTAL_LANCAMENTOS_CREDITO > 0){
                    $PERCENTUAL_CREDITO_RECEBIDO = (data_get($TOTAL_RECEBIDO_CREDITO, 'PAGO', 0) / $TOTAL_LANCAMENTOS_CREDITO) * 100;
                }
    
                if($TOTAL_LANCAMENTOS_DEBITO > 0){
                    $PERCENTUAL_DEBITO_PAGO = (data_get($TOTAL_PAGO_DEBITO, 'PAGO', 0) / $TOTAL_LANCAMENTOS_DEBITO) * 100;
                }
    
                $dadosComSaldoFormatados[$mesAno]['TOTAL_RECEBIDO_CREDITO'] = data_get($TOTAL_RECEBIDO_CREDITO, 'PAGO', 0);
                $dadosComSaldoFormatados[$mesAno]['TOTAL_PAGO_DEBITO'] = data_get($TOTAL_PAGO_DEBITO, 'PAGO', 0);
                $dadosComSaldoFormatados[$mesAno]['TOTAL_LANCAMENTOS_CREDITO'] = $TOTAL_LANCAMENTOS_CREDITO;
                $dadosComSaldoFormatados[$mesAno]['TOTAL_LANCAMENTOS_DEBITO'] = $TOTAL_LANCAMENTOS_DEBITO;
                $dadosComSaldoFormatados[$mesAno]['PERCENTUAL_CREDITO_RECEBIDO_STRING'] = dec2str($PERCENTUAL_CREDITO_RECEBIDO, 0) ?? '0';
                $dadosComSaldoFormatados[$mesAno]['PERCENTUAL_DEBITO_PAGO_STRING'] = dec2str($PERCENTUAL_DEBITO_PAGO, 0) ?? '0';
                $dadosComSaldoFormatados[$mesAno]['PERCENTUAL_CREDITO_RECEBIDO_FLOAT'] = $PERCENTUAL_CREDITO_RECEBIDO;
                $dadosComSaldoFormatados[$mesAno]['PERCENTUAL_DEBITO_PAGO_FLOAT'] = $PERCENTUAL_DEBITO_PAGO;
                $dadosComSaldoFormatados[$mesAno]['MES_EXTENSO'] = date('F/Y', strtotime($mesAno.'-01'));
            }
        }
        

        return $dadosComSaldoFormatados;

    }

    public static function definirStatus(Periodo $periodo, $status){

        return PeriodoRepository::definirStatus($periodo, $status);
        
    }

    public static function definirStatusTodos(Request $request, $status){

        return PeriodoRepository::definirStatusTodos($request, $status);

    }
}