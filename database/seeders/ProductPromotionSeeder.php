<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductPromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupération des promotions
        $promoCroissant = Promotion::where('name', 'Promo Croissant')->first();
        $promoPain = Promotion::where('name', 'Promo Pain')->first();

        // Récupération des produits
        $baguette = Product::where('name', 'Baguette traditionnelle')->first();
        $painComplet = Product::where('name', 'Pain complet')->first();
        $painGraines = Product::where('name', 'Pain au graines')->first();

        // Vérification avant d'attacher
        if ($promoCroissant) {
            $productsIds = [];
            if ($painComplet) $productsIds[] = $painComplet->id;
            if ($painGraines) $productsIds[] = $painGraines->id;

            if (!empty($productsIds)) {
                $promoCroissant->products()->attach($productsIds);
            }
        }

        if ($promoPain && $baguette) {
            $promoPain->products()->attach([$baguette->id]);
        }
    }
}
