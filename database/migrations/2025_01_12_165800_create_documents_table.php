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
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('chemin');
            $table->foreignUuid('establishment_id')->constrained('establishments', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('filiere_id')->constrained('filieres', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('niveau_id')->constrained('niveaux', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('module_id')->constrained('modules', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
