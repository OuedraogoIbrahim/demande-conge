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
        Schema::create('pending_students', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->foreignUuid('establishment_id')->constrained('establishments', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('registration_id')->constrained('registrations', 'id')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_students');
    }
};
