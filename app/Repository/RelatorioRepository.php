<?php

namespace App\Repository;

use App\Models\Grupo;
use App\Models\Periodo;
use Illuminate\Database\Eloquent\Builder;
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

        $dados = Grupo::with(['conta', 'conta.periodo' => function($query) use($data1, $data2){
            $query->where('data_vencimento', '>=', $data1);
            $query->where('data_vencimento', '<=', $data2);
        }])->get();

        /**
         * Retorna os dados
         */
        return $dados;
    }

    public static function resultado(){

        $dados = Periodo::with(['conta'])->get();

        $resultados = [];
        
        if($dados){
            foreach ($dados as $item) {

                $ano = date('Y', strtotime($item->data_vencimento));
                $mes = date('m', strtotime($item->data_vencimento));
                
                $resultados[$ano.'-'.$mes][$item->conta->natureza][] = $item->conta->natureza == 'D' ? 0-$item->valor : (float) $item->valor;

            }
        }

        $dadosSomados = [];

        foreach ($resultados as $mesAno => $dadosMesAno) {

            foreach ($dadosMesAno as $natureza => $valores) {
                $dadosSomados[$mesAno][$natureza] = array_sum($valores);
            }

        }

        $dadosComSaldo = [];

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
            

        }

        return $dadosComSaldoFormatados;

    }
}