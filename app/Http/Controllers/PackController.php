<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackRequest;
use App\Models\Pack;
use Illuminate\Http\Request;

class PackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Pack::with('products.promotions')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PackRequest $request)
    {
        try {
            $pack = Pack::create($request->only('name', 'description', 'price'));

            if ($request->has('products')) {
                $syncData = [];
                foreach ($request->products as $prod) {
                    $syncData[$prod['product_id']] = ['quantity' => $prod['quantity']];
                }
                $pack->products()->sync($syncData);
            }

            return response()->json($pack->load('products'), 201);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création du pack: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Pack::with('products')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PackRequest $request, Pack $pack)
    {
        try {
            $pack->update($request->only('name', 'description', 'price'));

            if ($request->has('products')) {
                $syncData = [];
                foreach ($request->products as $prod) {
                    $syncData[$prod['product_id']] = ['quantity' => $prod['quantity']];
                }
                $pack->products()->sync($syncData);
            }

            return response()->json($pack->load('products'));
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la mise à jour du pack: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pack $pack)
    {
        try {
            $pack->delete();
            return response()->json(['status_message' => 'Pack supprimé avec succès.']);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la suppression du produit: ' . $e->getMessage(),
            ], 500);
        }
    }
}
