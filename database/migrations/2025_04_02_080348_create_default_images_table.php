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
        Schema::create('default_images', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Noël, Anniversaire, etc.
            $table->string('path');  // ex: /storage/images/defaults/noel.jpg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_images');
    }
};
