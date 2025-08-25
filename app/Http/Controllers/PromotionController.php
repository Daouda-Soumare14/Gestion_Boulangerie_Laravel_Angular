<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use Illuminate\Http\JsonResponse;

class PromotionController extends Controller
{
    public function index()
    {
        try {
            $promotions = Promotion::with('products')->get();

            return response()->json($promotions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function store(PromotionRequest $request): JsonResponse
    {
        try {
            // Crée la promotion
            $promotion = Promotion::create($request->validated());

            // Lie la promo aux produits si fournis
            if ($request->has('product_ids')) {
                $promotion->products()->sync($request->product_ids);
            }

            return response()->json($promotion->load('products'), 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Promotion $promotion): JsonResponse
    {
        // Charge la promo avec ses produits
        return response()->json($promotion->load('products'));
    }

    public function update(PromotionRequest $request, Promotion $promotion): JsonResponse
    {
        $promotion->update($request->validated());

        if ($request->has('product_ids')) {
            $promotion->products()->sync($request->product_ids);
        }

        return response()->json($promotion->load('products'));
    }

    public function destroy(Promotion $promotion): JsonResponse
    {
        $promotion->delete();
        return response()->json(null, 204);
    }
}
