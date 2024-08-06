<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BurgerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    //Méthode de connexion et de création de compte
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    //Route de lougout
    Route::post('/logout', [AuthController::class, 'logout']);

    //Méthodes des utilisateurs
    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    //Méthodes accéssible que par les administrateurs
    Route::middleware('Admin')->group(function () {
        //Méthodes des burgers
        Route::get('/burgers/trashed', [BurgerController::class, 'archivedBurger']);
        Route::get('/burgers/trashed/{id}', [BurgerController::class, 'restoreBurger']);
        Route::apiResource('burgers', BurgerController::class);

        //Méthodes des orders
        Route::get('/order/daily', [OrderController::class, 'dailyOrders']);
        Route::get('/order/pending', [OrderController::class, 'pendingOrders']);
        Route::put('/order/{id}/status', [OrderController::class, 'update']);
    });

    //Route concernant les commandes
    Route::post('/order', [\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::get('/order', [\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/order/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::delete('/order/{id}', [\App\Http\Controllers\Api\OrderController::class, 'destroy']);




});
