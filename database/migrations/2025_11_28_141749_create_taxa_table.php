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
        Schema::create('taxa', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kolom Nama
            $table->string('rank'); // Kolom Rank (Kingdom, Genus, dll)
            $table->foreignId('parent_id')->nullable()->constrained('taxa')->onDelete('cascade'); // Relasi ke dirinya sendiri (Induk)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke User pembuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxa');
    }
};