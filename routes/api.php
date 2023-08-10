<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\JwtLoginController;
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
    /**
     * Aplicação de rotas agrupadas por entidade.
     */
    Route::controller(ClienteController::class)->group(function(){
        /**
         * Rota para leitura de todos os clientes cadastrados
         */
        Route::prefix('clientes')->group(function(){
            /**
             * Lista todos os clientes
             */
            Route::get('/', 'index')
                ->name('clientes.index');
            /**
             * Lista todos os clientes
             */
            Route::get('/{cliente}', 'find')
                ->name('clientes.find');
            /**
             * Cria um novo cliente
             */
            Route::post('/', 'store')
                ->name('clientes.store');
            /**
             * Atualiza um cliente
             */
            Route::put('/{cliente}', 'update')
                ->name('clientes.update');
            /**
             * Apaga um cliente
             */
            Route::delete('/{cliente}', 'destroy')
                ->name('clientes.destroy');
            /**
             * Atualiza o e-mail do cliente
             */
            Route::patch('/{cliente}', 'updateEmail')
                ->name('clientes.updateEmail');
        });
    });
    
});

Route::get('jwt', function(){

    $header = [
        'typ' => 'JWT',
        'alg' => 'HS256'
    ];

    $payload = [
        'exp' => time(),
        'uid' => 1,
        'email' => 'lfo1985@gmail.com'
    ];

    $header = json_encode($header);
    $payload = json_encode($payload);

    $header = base64_encode($header);
    $payload = base64_encode($payload);

    $sign = hash_hmac('SHA256', $header.'.'.$payload, '123456', true);
    $sign = base64_encode($sign);

    echo $header.'.'.$payload.'.'.$sign;

});