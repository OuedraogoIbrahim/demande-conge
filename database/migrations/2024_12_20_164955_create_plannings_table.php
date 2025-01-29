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
        Schema::create('plannings', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('title');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('statut', ['en attente', 'terminer', 'annuler']);
            $table->enum('type', ['cours', 'devoir', 'autre']);

            $table->foreignUuid('filiere_id')->constrained('filieres', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('module_id')->constrained('modules', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('classe_id')->constrained('classes', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('establishment_id')->constrained('establishments', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
