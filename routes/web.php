<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AdminController,
    DashboardController,
    EvenementController,
    InvitationController,
    ParticipationController,
    ProfileController,
    PhotoController,
    LikeController,
    CommentController
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


Route::get('/evenements/map', [EvenementController::class, 'map'])->name('evenements.map');
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

Route::middleware('auth')->group(function() {
    Route::get('/evenements/{evenement}/photos', [PhotoController::class, 'index'])->name('photos.index');
    Route::get('/evenements/{evenement}/photos/create', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('/evenements/{evenement}/photos', [PhotoController::class, 'store'])->name('photos.store');
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


Route::middleware('auth')->group(function () {
    // Formulaire d’édition de profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Mise à jour de profil
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
});





Route::get('/users/{user}', [ProfileController::class, 'show'])
    ->name('users.show');


 


    Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
    Route::post('/photos/{photo}/like', [LikeController::class, 'toggleLike'])->name('photos.like');
    Route::post('/photos/{photo}/comment', [CommentController::class, 'store'])->name('photos.comment');
