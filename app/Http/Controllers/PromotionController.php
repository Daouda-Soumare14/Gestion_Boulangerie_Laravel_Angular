<?php
namespace App\Http\Controllers;

use App\Http\Requests\PromotionFormRequest;
use App\Models\Promotion;
use Illuminate\Http\JsonResponse;

class PromotionController extends Controller
{
    public function index(): JsonResponse
    {
        // Récupère toutes les promos avec leurs produits
        $promotions = Promotion::with('products')->get();
        return response()->json($promotions);
    }

    public function store(PromotionFormRequest $request): JsonResponse
    {
        // Crée la promotion
        $promotion = Promotion::create($request->validated());

        // Lie la promo aux produits si fournis
        if ($request->has('product_ids')) {
            $promotion->products()->sync($request->product_ids);
        }

        return response()->json($promotion->load('products'), 201);
    }

    public function show(Promotion $promotion): JsonResponse
    {
        // Charge la promo avec ses produits
        return response()->json($promotion->load('products'));
    }

    public function update(PromotionFormRequest $request, Promotion $promotion): JsonResponse
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