<?php

namespace App\Repository;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class ClienteRepository {

    public static function obterTodos(): \Illuminate\Pagination\LengthAwarePaginator{
        /**
         * Ao usar o paginate ele limita a exibição de registros e
         * aplica os dados de paginação na resposta em JSON.
         * 
         * Caso queira exibir todos, utilize o all();
         */
        return Cliente::paginate();
    }

    public static function obter(Cliente $cliente): Cliente{
        return $cliente;
    }

    public static function cria(array $dados): void{
        try {
            DB::beginTransaction();
            $cliente = new Cliente($dados);
            $cliente->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage(), 1);
        }
    }

    public static function edita(array $dados, Cliente $cliente): void{
        try {
            DB::beginTransaction();
            foreach ($dados as $campo => $valor) {
                $cliente->$campo = $valor;
            }
            $cliente->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    public static function apaga(Cliente $cliente): void{
        try {
            DB::beginTransaction();
            $cliente->delete();
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}