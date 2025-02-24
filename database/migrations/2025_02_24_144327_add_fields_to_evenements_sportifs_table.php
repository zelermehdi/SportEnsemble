<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('evenements_sportifs', function (Blueprint $table) {
            // Niveau de l’événement
            $table->enum('niveau', ['débutant', 'amateur', 'pro'])
                  ->default('amateur')
                  ->after('type_sport');

            // Tags (ex: "indoor", "extérieur", "5 vs 5", etc.)
            // On peut stocker sous forme de texte (ou JSON)
            $table->string('tags')->nullable()->after('niveau');
        });
    }

    public function down()
    {
        Schema::table('evenements_sportifs', function (Blueprint $table) {
            $table->dropColumn(['niveau', 'tags']);
        });
    }
};
