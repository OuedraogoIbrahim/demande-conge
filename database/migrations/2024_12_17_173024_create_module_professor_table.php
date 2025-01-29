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
        Schema::create('module_professor', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('module_id')->constrained('modules', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('professor_id')->constrained('professors', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_professor');
    }
};
