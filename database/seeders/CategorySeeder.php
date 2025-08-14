<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pain', 'description' => 'Différents types de pain frais'],
            ['name' => 'Viennoiseries', 'description' => 'Croissants, pains au chocolat, brioches'],
            ['name' => 'Pâtisseries', 'description' => 'Gâteaux, tartes et desserts sucrés'],
            ['name' => 'Boissons', 'description' => 'Cafés, thés et jus'],
            ['name' => 'Épicerie', 'description' => 'Produits complémentaires comme farine, sucre, etc.']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
