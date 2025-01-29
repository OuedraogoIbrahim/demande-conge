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
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('nom');
            $table->longText('description')->nullable();
            $table->integer('coefficient');
            $table->integer('nombre_heures');
            $table->integer('heures_utilisees');
            $table->foreignUuid('filiere_id')->constrained('filieres', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('niveau_id')->constrained('niveaux', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
