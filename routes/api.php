<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
/*
|-------------------------------------------------------------------------
| API Routes
|-------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/menus', [MenuController::class, 'index']); // Get all menus
Route::post('/menus', [MenuController::class, 'store']); // Create menu
Route::get('/menus/{id}', [MenuController::class, 'show']); // Get one menu
Route::post('/menus/{id}', [MenuController::class, 'update']); // Update menu
Route::delete('/menus/{id}', [MenuController::class, 'destroy']); // Delete menu
Route::middleware('auth:api')->post('orders', [OrderController::class, 'placeOrder']);
// Route::middleware('auth:api')->group(function () {
//     Route::post('/orders', [OrderController::class, 'store']); // Place an order
//     Route::get('/orders', [OrderController::class, 'index']); // Fetch orders 
//     Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']); // Update order status (admin only)
// });
Route::middleware('auth:api')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']); // Place an order
    Route::get('/orders', [OrderController::class, 'index']); // Fetch orders 
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']); // Update order status (admin only)
});