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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // Type de notification (classe Laravel)
            $table->morphs('notifiable'); // Ajoute notifiable_type et notifiable_id
            $table->json('data'); // Stockage des données de la notification
            $table->timestamp('read_at')->nullable(); // Indique si la notification a été lue
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
