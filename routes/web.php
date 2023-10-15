<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    $qtdMes = 0;
    for ($i = 1; $i <= 36 ; $i++) { 
        echo date('Y-m-15', strtotime('+'.$qtdMes.' months')).'<br>';
        $qtdMes++;
    }
});
