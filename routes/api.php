<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/auth')
    ->controller(v1\LoginController::class)
    ->group(function () {
        Route::post('/token', 'authenticate');
        Route::put('/refresh', 'refreshToken');
    });

Route::prefix('/user')
    ->controller(v1\UserController::class)
    ->group(function () {
        Route::post('/register', 'store');
    });

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::prefix('/user')
        ->controller(v1\UserController::class)
        ->group(function () {
            Route::post('/detail', 'storeDetail');
            Route::put('/detail', 'updateDetail');
            Route::put('/', 'update');
            Route::middleware(['role:admin'])->group(function () {
                Route::get('/', 'index');
            });
        });

    Route::prefix('/product')
        ->controller(v1\ProductController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::get('/{product}', 'show');
            Route::get('/{product}/stock', 'stock');
            Route::middleware(['role:admin,moderator'])->group(function () {
                Route::post('/', 'store');
                Route::post('/stock', 'addStock');
                Route::put('/{product}', 'update');
            });
        });

    Route::prefix('/order')
        ->controller(v1\OrderController::class)
        ->group(function () {
            Route::get('/{order}', 'show');
            Route::post('/', 'store');
            Route::middleware(['role:admin,moderator'])->group(function () {
                Route::get('/', 'index');
                Route::middleware(['role:admin'])->group(function () {
                    Route::post('/{order}/approve', 'approve');
                    Route::post('/{order}/reject', 'reject');
                });
            });
        });
});

