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
        Schema::create('rdv_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rdv_id')->constrained('rdv')->onDelete('cascade'); // This links to the 'rdv' table
            $table->enum('status', ['Confirmé', 'En attente', 'Annulé'])->default('En attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdv_statuses');
    }
};
