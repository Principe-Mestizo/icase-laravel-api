<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

    Route::get('/proveedores/combo', [SupplierController::class, 'combo'])
    ->name('proveedores.combo');
    Route::apiResource('/proveedores', SupplierController::class);


    Route::apiResource("/clientes", CustomerController::class);
    Route::apiResource("/usuarios", UserController::class);

    Route::apiResource("/ventas", SaleController::class);
    Route::apiResource("/detalle-venta", SaleDetailController::class);
    Route::apiResource("/compras", PurchaseController::class);
    Route::apiResource("/detalle-compra", PurchaseDetailController::class);

    Route::apiResource("/movimientos", InventoryMovementController::class);


    Route::get('/totals', [SalesController::class, 'getTotals']);

});

