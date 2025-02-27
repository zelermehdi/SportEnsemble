<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['auth']]); // ✅ Active les WebSockets pour les utilisateurs connectés
        require base_path('routes/channels.php'); // ✅ Charge les canaux définis
    }
    
}
