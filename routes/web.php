<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminController,
    DashboardController,
    EvenementController,
    InvitationController,
    ParticipationController
};
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

// Tableau de bord
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile (exemple)
Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Auth routes (Laravel Breeze / Jetstream / etc.)
require __DIR__.'/auth.php';

/**
 * Événements (lecture, création, etc.)
 */
Route::middleware(['auth'])->group(function () {
    // Index + création
    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/creer', [EvenementController::class, 'create'])->name('evenements.create');
    Route::post('/evenements', [EvenementController::class, 'store'])->name('evenements.store');

    // Affichage d’un événement (détails)
    Route::get('/evenements/{evenement}', [EvenementController::class, 'show'])->name('evenements.show');
});

/**
 * Participations
 */
Route::middleware('auth')->group(function () {
    Route::post('/evenements/{evenement}/participer', [ParticipationController::class, 'participer'])
        ->name('participations.participer');
    Route::delete('/evenements/{evenement}/se-retirer', [ParticipationController::class, 'seRetirer'])
        ->name('participations.seRetirer');
});

/**
 * Invitations
 */
Route::middleware('auth')->group(function () {
    Route::post('/evenements/{evenement}/inviter', [InvitationController::class, 'inviter'])
        ->name('invitations.inviter');

    Route::post('/invitations/{invitation}/accepter', [InvitationController::class, 'accepter'])
        ->name('invitations.accepter');

    Route::post('/invitations/{invitation}/refuser', [InvitationController::class, 'refuser'])
        ->name('invitations.refuser');
});

/**
 * Administration (seulement pour Admin)
 */
Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admin/evenements/{evenement}', [AdminController::class, 'supprimerEvenement'])
        ->name('admin.evenements.supprimer');
});


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');