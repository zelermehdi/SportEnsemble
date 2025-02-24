<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_sportif_id')->constrained('evenements_sportifs')->onDelete('cascade');
            $table->foreignId('inviteur_id')->constrained('users')->onDelete('cascade'); // Celui qui envoie l'invitation
            $table->foreignId('invite_id')->constrained('users')->onDelete('cascade'); // Celui qui reçoit l'invitation
            $table->enum('statut', ['en_attente', 'accepté', 'refusé'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
    }
};
