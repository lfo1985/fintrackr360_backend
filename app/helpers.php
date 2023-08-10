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

function validaEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}