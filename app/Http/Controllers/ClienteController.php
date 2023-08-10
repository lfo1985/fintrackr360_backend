<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClienteCollection;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Repository\ClienteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function index(){
        return new ClienteCollection(ClienteRepository::obterTodos());
    }

    public function search(Request $request){
        $cliente = new Cliente;
        $query = $cliente->query();
        if($request->has('nome')){
            $query->nome($request->nome);
        }
        return new ClienteCollection($query->paginate());
    }

    public function find(Cliente $cliente){
        return new ClienteResource(ClienteRepository::obter($cliente));
    }

    private static function validacao($request){
        if(!$request->nome){
            throw new \Exception("Nome obrigatório!", 1);
        }
        if(!$request->email){
            throw new \Exception("E-mail obrigatório!", 1);
        }
        if(!validaEmail($request->email)){
            throw new \Exception("E-mail incorreto!", 1);
        }
    }

    public function store(Request $request): JsonResponse{
        try {
            self::validacao($request);
            ClienteRepository::cria([
                'nome' => $request->nome,
                'email' => $request->email
            ]);
            return sucesso('Cliente salvo com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }

    public function updateEmail(Request $request, Cliente $cliente): JsonResponse{
        try {
            if(!$request->email){
                throw new \Exception("E-mail obrigatório!", 1);
            }
            if(!validaEmail($request->email)){
                throw new \Exception("E-mail incorreto!", 1);
            }
            ClienteRepository::edita([
                'email' => $request->email
            ], $cliente);
            return sucesso('E-mail cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }

    public function update(Request $request, Cliente $cliente): JsonResponse{
        try {
            self::validacao($request);
            ClienteRepository::edita([
                'nome' => $request->nome,
                'email' => $request->email
            ], $cliente);
            return sucesso('Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }

    public function destroy(Cliente $cliente): JsonResponse{
        try {
            ClienteRepository::apaga($cliente);
            return sucesso('Cliente apagado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }
}
