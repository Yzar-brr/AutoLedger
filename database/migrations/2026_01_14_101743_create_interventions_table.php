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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('technician'); // Nom du technicien
            $table->decimal('price', 10, 2); // Prix
            $table->string('duration'); // Temps passé (ex: "2h30")
            $table->text('description'); // Le contenu de la note
            $table->json('photos')->nullable(); // Pour stocker les chemins des images
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
