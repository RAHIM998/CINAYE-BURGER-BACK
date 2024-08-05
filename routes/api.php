<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BurgerController;
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
    Route::get('user', [UserController::class, 'index'])->name('index');
    Route::get('user/{id}', [UserController::class, 'show'])->name('show');
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    //Méthodes des burgers
    Route::middleware('Admin')->group(function () {
        Route::get('/burgers/trashed', [BurgerController::class, 'archivedBurger']);
        Route::get('/burgers/trashed/{id}', [BurgerController::class, 'restoreBurger']);
        Route::apiResource('burgers', BurgerController::class);
    });


});
