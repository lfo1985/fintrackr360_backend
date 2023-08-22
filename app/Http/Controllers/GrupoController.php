<?php

namespace App\Http\Controllers;

use App\Http\Resources\GrupoCollection;
use App\Http\Resources\GrupoResource;
use App\Models\Grupo;
use App\Repository\GrupoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /**
     * Rota principal
     * 
     * @return GrupoCollection
     */
    public function index(){
        /**
         * Realiza a seleção de todos os regsitros e retorna na collection.
         */
        return new GrupoCollection(GrupoRepository::selecionaTodos());
    }
    /**
     * Rota para pesquisa de dados.
     */
    public function search(Request $request){
        $grupo = new Grupo;
        $query = $grupo->query();
        if($request->has('nome')){
            $query->nome($request->nome);
        }
        return new GrupoCollection($query->paginate());
    }

    public function find(Grupo $grupo){
        return new GrupoResource(GrupoRepository::seleciona($grupo));
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
            GrupoRepository::cria([
                'nome' => $request->nome,
                'email' => $request->email
            ]);
            return sucesso('Grupo salvo com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }

    public function updateEmail(Request $request, Grupo $grupo): JsonResponse{
        try {
            if(!$request->email){
                throw new \Exception("E-mail obrigatório!", 1);
            }
            if(!validaEmail($request->email)){
                throw new \Exception("E-mail incorreto!", 1);
            }
            GrupoRepository::edita([
                'email' => $request->email
            ], $grupo);
            return sucesso('E-mail Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }

    public function update(Request $request, Grupo $grupo): JsonResponse{
        try {
            self::validacao($request);
            GrupoRepository::edita([
                'nome' => $request->nome,
                'email' => $request->email
            ], $grupo);
            return sucesso('Grupo atualizado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }

    public function destroy(Grupo $grupo): JsonResponse{
        try {
            GrupoRepository::apaga($grupo);
            return sucesso('Grupo apagado com sucesso!');
        } catch (\Exception $e) {
            return erro($e->getMessage());
        }
    }
}
