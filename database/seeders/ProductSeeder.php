<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Baguette Tradition',
                'description' => 'Baguette artisanale croustillante',
                'price' => 1.20,
                'stock' => 50,
                'photo' => 'baguette.jpg',
                'allergens' => 'Gluten'
            ],
            [
                'category_id' => 2,
                'name' => 'Croissant au Beurre',
                'description' => 'Croissant frais et feuilleté',
                'price' => 1.50,
                'stock' => 40,
                'photo' => 'croissant.jpg',
                'allergens' => 'Gluten, Lait'
            ],
            [
                'category_id' => 3,
                'name' => 'Éclair au Chocolat',
                'description' => 'Pâtisserie traditionnelle au chocolat',
                'price' => 2.50,
                'stock' => 30,
                'photo' => 'eclair.jpg',
                'allergens' => 'Gluten, Lait, Œufs'
            ],
            [
                'category_id' => 4,
                'name' => 'Jus d’Orange',
                'description' => 'Pur jus d’orange frais',
                'price' => 2.00,
                'stock' => 25,
                'photo' => 'jus_orange.jpg',
                'allergens' => null
            ],
            [
                'category_id' => 5,
                'name' => 'Farine de Blé',
                'description' => 'Farine pour pâtisserie et pain',
                'price' => 1.80,
                'stock' => 100,
                'photo' => 'farine.jpg',
                'allergens' => 'Gluten'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
