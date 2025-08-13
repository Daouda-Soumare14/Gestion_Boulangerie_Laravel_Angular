<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderFormRequest;
use App\Http\Requests\OrderItemRequest;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index(): JsonResponse
    {
        // Liste tous les items avec produit et commande associés
        $items = OrderItem::with(['product', 'order'])->get();
        return response()->json($items);
    }

    public function store(OrderItemRequest $request): JsonResponse
    {
        $item = OrderItem::create($request->validated());

        return response()->json($item->load(['product', 'order']), 201);
    }

    public function show(OrderItem $orderItem): JsonResponse
    {
        return response()->json($orderItem->load(['product', 'order']));
    }

    public function update(OrderItemRequest $request, OrderItem $orderItem): JsonResponse
    {

        $orderItem->update($request->validated());
        return response()->json($orderItem->load(['product', 'order']));
    }

    public function destroy(OrderItem $orderItem): JsonResponse
    {
        $orderItem->delete();
        return response()->json(null, 204);
    }
}