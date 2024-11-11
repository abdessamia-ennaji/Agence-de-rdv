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
        Schema::create('rdv', function (Blueprint $table) {
            $table->id();
            $table->date('rdv_date');
            $table->time('rdv_time');
            $table->string('prenom');
            $table->string('nom');
            $table->string('telephone');
            $table->string('adresse');
            $table->string('ville');
            $table->text('commentaire')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdv');
    }
};
