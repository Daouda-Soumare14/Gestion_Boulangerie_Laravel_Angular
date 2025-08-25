<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with('category', 'promotions', 'packs');

            // Filtrer par nom et description
            if ($search = $request->search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                        ->orwhere('content', 'LIKE', '%' . $search . '%');
                });
            }

            // Filtrer par catégorie si présent
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Filtrer par prix minimum si présent
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            // Filtrer par prix maximum si présent
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // Exécution de la requête filtrée avec pagination
            $products = $query->latest()->paginate(30);

            return response()->json($products);

        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($this->extractData($request, new Product()));
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with('category', 'promotions')->findOrFail($id);
            return response()->json(['data' => $product]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 404,
                'status_message' => 'Produit non trouvé: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $product->update($this->extractData($request, $product));
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['status_message' => 'Produit supprimé avec succès.']);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la suppression du produit: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function heroProducts()
    {
        // Récupère les deux derniers produits ajoutés ou avec une condition spécifique
        $products = Product::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        return response()->json($products);
    }


    public function extractData(ProductRequest $request, Product $product): array
    {
        $data = $request->validated();

        /** @var UploadedFile|null $photo */
        $photo = $request->file('photo');

        if (!$photo || !$photo->isValid()) {
            return $data;
        }

        // Supprimer l'ancienne image si elle existe
        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        // Stocker la nouvelle image dans 'storage/app/public/products'
        $data['photo'] = $photo->store('products', 'public');

        return $data;
    }
}
