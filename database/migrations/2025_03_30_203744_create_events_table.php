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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique(); // URL lisible (anniversaire-mamy)
            $table->foreignId('status_event_id')->constrained('status_events'); // “en préparation”, “actif”, “clôturé”
            $table->boolean('is_collaborative')->default(false);
            $table->string('custom_image')->nullable(); // Image personnalisée
            $table->foreignId('default_image_id')->nullable()->constrained('default_images')->nullOnDelete(); // Image par défaut
            $table->boolean('is_public')->default(false);
            $table->date('event_date');
            $table->date('end_date')->nullable(); // Champ optionnel pour date de clôture
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
