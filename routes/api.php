<?php

use App\Http\Controllers\ContaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\JwtLoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(JwtLoginController::class)->group(function(){
    /**
     * Realiza o login do usuário
     */
    Route::post('/login', 'login');
    /**
     * Realiza o logout de usuário
     */
    Route::post('/logout', 'logout');
});

/**
 * Aplicação de middleware que realiza a validação de tokens informados
 * pelos usuários que irão realizar o login do sistema.
 */
Route::middleware(['jwt.auth'])->group(function(){
    /**
     * Rotas para gerenciamento dos usuários
     */
    Route::controller(UserController::class)->group(function(){
        /**
         * Usuários
         */
        Route::prefix('users')->group(function(){
            /**
             * Lista todos os usuários
             */
            Route::get('/', 'index')->name('users.index');
        });
    });
    /**
     * Rotas para gerenciamento dos grupos
     */
    Route::controller(GrupoController::class)->group(function(){
        /**
         * Grupos
         */
        Route::prefix('grupos')->group(function(){
            /**
             * Pesquisa utilizando parâmetros
             */
            Route::get('/search', 'search')->name('grupos.search');
            /**
             * Pesquisa utilizando parâmetros
             */
            Route::get('/search-no-paginate', 'searchNoPaginate')->name('conta.search-no-paginate');
            /**
             * Lista todos
             */
            Route::get('/', 'index')->name('grupos.index');
            /**
             * Lista todos
             */
            Route::get('/{grupo}', 'find')->name('grupos.find');
            /**
             * Cria um novo registro
             */
            Route::post('/', 'store')->name('grupos.store');
            /**
             * Atualiza um registro
             */
            Route::put('/{grupo}', 'update')->name('grupos.update');
            /**
             * Apaga um registro
             */
            Route::delete('/{grupo}', 'destroy')->name('grupos.destroy');
        });
    });
        /**
     * Rotas para gerenciamento dos grupos
     */
    Route::controller(ContaController::class)->group(function(){
        /**
         * Contas
         */
        Route::prefix('contas')->group(function(){
            /**
             * Lista todos
             */
            Route::get('/grupo/{grupo}', 'index')->name('conta.grupo.index');
            /**
             * Pesquisa utilizando parâmetros
             */
            Route::get('/search', 'search')->name('conta.search');
            /**
             * Lista todos
             */
            Route::get('/{conta}', 'find')->name('conta.find');
            /**
             * Cria um novo registro
             */
            Route::post('/', 'store')->name('conta.store');
            /**
             * Atualiza um registro
             */
            Route::put('/{conta}', 'update')->name('conta.update');
            /**
             * Apaga um registro
             */
            Route::delete('/{conta}', 'destroy')->name('conta.destroy');
        });
    });

});