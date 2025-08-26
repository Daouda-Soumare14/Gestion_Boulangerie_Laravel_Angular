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


// Routes publiques pour consultation

// Produits publics
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Categories publiques
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// Hero product publics
Route::get('hero-products', [ProductController::class, 'heroProducts']);

// Packs publics
Route::get('packs', [PackController::class, 'index']);
Route::get('packs/{id}', [PackController::class, 'show']);

// Promotions publiques
Route::get('promotions', [PromotionController::class, 'index']);
Route::get('promotions/{id}', [PromotionController::class, 'show']);



// Routes protégées par authentification
Route::middleware(['auth:sanctum'])->group(function () {

    // Produits
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Catégories
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    // Packs
    Route::post('packs', [PackController::class, 'store']);
    Route::put('packs/{id}', [PackController::class, 'update']);
    Route::delete('packs/{id}', [PackController::class, 'destroy']);

    // Promotions
    Route::post('promotions', [PromotionController::class, 'store']);
    Route::put('promotions/{id}', [PromotionController::class, 'update']);
    Route::delete('promotions/{id}', [PromotionController::class, 'destroy']);

    // Commandes
    Route::apiResource('orders', OrderController::class);

    // Items de commande
    Route::apiResource('order-items', OrderItemController::class);

    // status de la livraison
    Route::put('/orders/{order}/delivery-status', [OrderController::class, 'updateDeliveryStatus']);

    // status de la commande
    Route::put('/orders/{order}/order-status', [OrderController::class, 'updateOrderStatus']);

    // cart
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/sync', [CartController::class, 'syncCart']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
