<?php
use App\Http\Controllers\V1\ProductsController;
use App\Http\Controllers\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    //Prefijo V1, todo lo que este dentro de este grupo se accedera escribiendo v1 en el navegador, es decir /api/v1/*
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('products', [ProductsController::class, 'index']);
    Route::post('productscant', [ProductsController::class, 'indexcant']);
    Route::get('productsvendidos', [ProductsController::class, 'productsvendidos']);
    Route::get('ganancia', [ProductsController::class, 'ganancia']);
    Route::get('nostock', [ProductsController::class, 'nostock']);
    Route::group(['middleware' => ['jwt.verify']], function() {
        //Todo lo que este dentro de este grupo requiere verificación de usuario.
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('get-user', [AuthController::class, 'getUser']);
        Route::put('products/{id}', [ProductsController::class, 'updateventas']);
        Route::delete('products/{id}', [ProductsController::class, 'destroy']);
    });
});