<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'name' => 'Promo Croissant',
            'description' => 'Offre spéciale sur tous les croissants',
            'photo' => 'promo_croissant.jpg', // placer l’image dans storage/app/public
            'discount_type' => 'pourcentage',
            'discount_value' => 20,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(7),
        ]);

        Promotion::create([
            'name' => 'Promo Pain',
            'description' => 'Achetez 1 pain, recevez -5 Fcfa',
            'photo' => 'promo_pain.jpg',
            'discount_type' => 'montant',
            'discount_value' => 5,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(5),
        ]);
    }
}
