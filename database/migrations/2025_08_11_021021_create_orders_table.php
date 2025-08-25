<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            // Statut de la commande (validée, annulée, etc.)
            $table->enum('order_status', ['validee', 'annulee'])->default('validee');

            // Statut de la livraison
            $table->enum('delivery_status', [
                'en_preparation',
                'prete',
                'en_livraison',
                'livree'
            ])->default('en_preparation');

            $table->enum('payment_mode', ['livraison', 'en_ligne']);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
