<?php

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
    Route::post('/logout', function(){
        auth('api')->logout();
        return sucesso('OK');
    });
});

/**
 * Aplicação de middleware que realiza a validação de tokens informados
 * pelos usuários que irão realizar o login do sistema.
 */
Route::middleware(['jwt.auth'])->group(function(){

    Route::controller(JwtLoginController::class)->group(function(){
        Route::get('/verifica', function(){
            return auth()->user();
        });
    });

    Route::controller(UserController::class)->group(function(){
        Route::prefix('users')->group(function(){
            Route::get('/', 'index')->name('users.index');
        });
    });

    Route::controller(GrupoController::class)->group(function(){
        Route::prefix('grupos')->group(function(){
            Route::get('/', 'index')->name('grupos.index');
        });
        Route::prefix('grupos')->group(function(){
            Route::get('/search', 'search')->name('grupos.search');
        });
        Route::prefix('grupos')->group(function(){
            Route::post('/', 'store')->name('grupos.store');
        });
        Route::prefix('grupos')->group(function(){
            Route::put('/{grupo}', 'update')->name('grupos.update');
        });
    });

    /**
     * Aplicação de rotas agrupadas por entidade.
     */
    // Route::controller(ClienteController::class)->group(function(){
    //     /**
    //      * Rota para leitura de todos os clientes cadastrados
    //      */
    //     Route::prefix('clientes')->group(function(){
    //         /**
    //          * Lista todos os clientes
    //          */
    //         Route::get('/', 'index')
    //             ->name('clientes.index');
    //         /**
    //          * Lista todos os clientes
    //          */
    //         Route::get('/{cliente}', 'find')
    //             ->name('clientes.find');
    //         /**
    //          * Cria um novo cliente
    //          */
    //         Route::post('/', 'store')
    //             ->name('clientes.store');
    //         /**
    //          * Atualiza um cliente
    //          */
    //         Route::put('/{cliente}', 'update')
    //             ->name('clientes.update');
    //         /**
    //          * Apaga um cliente
    //          */
    //         Route::delete('/{cliente}', 'destroy')
    //             ->name('clientes.destroy');
    //         /**
    //          * Atualiza o e-mail do cliente
    //          */
    //         Route::patch('/{cliente}', 'updateEmail')
    //             ->name('clientes.updateEmail');
    //     });
    // });
    
});