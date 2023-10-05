<?php

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
        $dados = [
            'sucesso' =>false,
            'msg' => 'Nenhum resultado encontrado!'
        ];
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