<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlaskController;
use App\Http\Controllers\ExpressController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Autenticacion
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

//Flask productos
Route::get("/products", [FlaskController::class, 'index_products']);
Route::post("/products", [FlaskController::class, 'create_products']);
Route::put("/products/{id}", [FlaskController::class, 'update_products']);
Route::delete("/products/{id}", [FlaskController::class, 'delete_products']);


//Express ventas
Route::get("/sales", [ExpressController::class, 'index_sales']);
Route::post("/sales", [ExpressController::class, 'create_sales'])->middleware('auth:api');
Route::put("/sales/{id}", [ExpressController::class, 'update_sales'])->middleware('auth:api');
Route::delete("/sales/{id}", [ExpressController::class, 'delete_sales']);
Route::post("/my_sales", [ExpressController::class, 'my_sales']);