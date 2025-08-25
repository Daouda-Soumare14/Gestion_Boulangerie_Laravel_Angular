<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // GET /api/cart
    public function getCart(Request $request)
    {
        try {
            $user = $request->user();
            $cartItems = $user->cartItems()->with('product')->get();

            return response()->json($cartItems);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la recuperation du panier',
            ]);
        }
    }

    // POST /api/cart/sync
    public function syncCart(Request $request)
    {
        try {
            $user = $request->user();
            $items = $request->input('items');

            // supprime l'ancien panier
            $user->cartItems()->delete();

            // ajoute les nouveaux elements
            foreach ($items as $item) {
                $user->cartItems()->create([
                    'product_id' => $item['product']['id'],
                    'quantity' => $item['quantity']
                ]);
            }

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Panier synchronisé avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la recuperation du panier' . $e->getMessage(),
            ]);
        }
    }
}

