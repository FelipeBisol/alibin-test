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
    return view('welcome');
});

Route::prefix('v1')->group(function () {
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']);

    Route::post('/auth/token', [\App\Http\Controllers\UserController::class, 'getToken']);

    Route::middleware('auth:sanctum')->prefix('books')->group(function (){
        Route::get('/', [\App\Http\Controllers\BookController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\BookController::class, 'store']);
    });
});
