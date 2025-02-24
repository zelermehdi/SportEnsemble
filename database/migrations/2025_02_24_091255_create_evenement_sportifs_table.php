<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('evenements_sportifs', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('type_sport', ['foot', 'course', 'basket', 'autre']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('lieu');
            $table->dateTime('date');
            $table->integer('max_participants')->nullable();
            $table->enum('statut', ['ouvert', 'fermé', 'complet'])->default('ouvert');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenement_sportifs');
    }
};
