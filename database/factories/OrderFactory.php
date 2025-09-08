<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // crée aussi un user lié
            'total' => $this->faker->randomFloat(2, 5, 100),
            'payment_mode' => $this->faker->randomElement(['livraison', 'en_ligne']),
            'delivery_status' => 'en_preparation', // adapte si besoin
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
