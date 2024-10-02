<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group([
    //    'auth:api' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});
Route::middleware('auth:api',)->prefix('admin')->group(function () {

    Route::resource("/categorias", CategorieController::class);
    Route::post("/categorias/{id}", [CategorieController::class, "update"]);

    Route::resource("/productos", ProductController::class);
    Route::post("/productos/{id}", [ProductController::class, "update"]);


    Route::get('/totals', [SalesController::class, 'getTotals']);
});


Route::prefix('ecommerce')->group(function () {

    Route::resource("/categorias", CategorieController::class);

    Route::resource("/productos", ProductController::class);
});
