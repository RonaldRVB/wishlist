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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image'); // chemin ou nom de fichier
            $table->string('purchase_url')->nullable();
            $table->integer('quantity')->nullable(); // Le champ quantity est prévu pour plus tard, donc nullable
            $table->boolean('is_reserved')->default(false);
            $table->foreignId('reserved_by')->nullable()->constrained('users')->nullOnDelete(); //Le champ reserved_by est une FK vers users, nullable
            $table->timestamp('reserved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
