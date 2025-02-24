<?php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Photo de profil (URL ou chemin)
            $table->string('avatar')->nullable()->after('password');
            
            // Brève biographie
            $table->text('bio')->nullable()->after('avatar');
            
            // Ville / localisation
            $table->string('ville')->nullable()->after('bio');
            
            // Sports préférés (liste simple, ou vous pouvez faire autrement)
            $table->string('sports_favoris')->nullable()->after('ville');

            // Niveau ou type, si besoin (ex: 'débutant','amateur','pro')
            // $table->enum('niveau', ['débutant','amateur','pro'])->default('amateur')->after('sports_favoris');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'bio', 'ville', 'sports_favoris']);
            // $table->dropColumn('niveau'); // si vous l’aviez rajouté
        });
    }
};
