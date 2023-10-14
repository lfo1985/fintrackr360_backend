<?php

use Carbon\Carbon;

function sucesso($msg, $options = []){
    $sucesso = [
        'sucesso' => true, 
        'msg' => $msg
    ];
    if(count($options) > 0){
        $sucesso = $sucesso + $options;
    }
    return response()->json($sucesso, 200);
}

function erro($msg, $codigo = 400){
    return response()->json(['sucesso' => false, 'msg' => $msg], $codigo);
}

function exibe($dados){
    if(count($dados) == 0){
        $dados = [];
    }
    return \response()->json($dados);
}

function excecao($msg){
    throw new Exception($msg, 1);
}

function validaEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function str2dec($valor){
    if($valor){
        return (float) str_replace(',', '.', str_replace('.', '', $valor));
    } else {
        return null;
    }
}

function dec2str($valor, $casasDecimais = 2){
    if($valor){
        return (string) number_format($valor, $casasDecimais, ',', '.');
    } else {
        return null;
    }
}

function dataBR2Date($data){
    if($data){
        return Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
    } else {
        return null;
    }
}

function date2DataBR($data){
    if($data){
        return Carbon::createFromFormat('Y-m-d', $data)->format('d/m/Y');
    } else {
        return null;
    }
}

function adMeses($data, $mes){
    if($data){
        return Carbon::parse($data)->addMonths($mes)->format('Y-m-d');
    } else {
        return null;
    }
}

function meses(){

    $meses = [];
    
    for ($i = 1; $i <= 12 ; $i++) {
        
        $timestamp = mktime(
                        0, 
                        0, 
                        0, 
                        str_pad($i, 2, 0, STR_PAD_LEFT), 
                        1, 
                        date('Y')
                    );
        
        $meses[] = [
            'numero' => date('m', $timestamp),
            'nome' => date('F', $timestamp)
        ];

    }

    return $meses;
}

function anos(){
    
    $anoInicio = (int) date('Y', strtotime('-2 Years'));
    $anoFim = (int) date('Y', strtotime('+3 years'));
    $anos = [];
    
    for ($ano = $anoInicio; $ano <= $anoFim ; $ano++) { 
        $anos[] = $ano;
    }

    return $anos;

}