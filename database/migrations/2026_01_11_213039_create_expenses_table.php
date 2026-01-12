<?php

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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        // Relie le frais au véhicule : si on supprime le véhicule, les frais s'effacent
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        $table->string('label');       // Ex: Changement pneus
        $table->decimal('amount', 10, 2); // Ex: 150.00
        $table->text('notes')->nullable(); // Tes notes cadrées
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
