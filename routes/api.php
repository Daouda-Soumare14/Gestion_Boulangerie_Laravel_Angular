<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Routes publiques
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);


// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('me', [UserController::class, 'me']);
});


// Promotions
Route::apiResource('promotions', PromotionController::class);

// Hero product 
Route::get('hero-products', [ProductController::class, 'heroProducts']);

// Pack
Route::apiResource('packs', PackController::class);

// Routes protégées par authentification
Route::middleware(['auth:sanctum'])->group(function () {

    // Catégories
    Route::apiResource('categories', CategoryController::class);

    // Produits
    Route::apiResource('products', ProductController::class);

    // Commandes
    Route::apiResource('orders', OrderController::class);

    // Items de commande
    Route::apiResource('order-items', OrderItemController::class);

    // status de la livraison
    Route::put('/orders/{order}/delivery-status', [OrderController::class, 'updateDeliveryStatus']);

    // status de la commande
    Route::put('/orders/{order}/order-status', [OrderController::class, 'updateOrderStatus']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/sync', [CartController::class, 'syncCart']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
