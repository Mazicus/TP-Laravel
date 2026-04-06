<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortailController;
use App\Http\Controllers\Api\ReposController;
use App\Http\Controllers\Api\CommandantReposController;

/*
|--------------------------------------------------------------------------
| Routes API — Galaxy Congés (Mazicus)
|--------------------------------------------------------------------------
|
| Routes publiques : inscription et connexion (pas besoin de jeton)
| Routes protégées : tout le reste nécessite un jeton JWT valide
|
*/

// --- Routes publiques (sans authentification) ---
Route::prefix('portail')->group(function () {
    Route::post('/inscription', [PortailController::class, 'inscription']);
    Route::post('/connexion',   [PortailController::class, 'connexion']);
});

// --- Routes protégées par le jeton JWT ---
Route::middleware('auth:api')->group(function () {

    // Gestion du compte astronaute
    Route::prefix('portail')->group(function () {
        Route::get('/profil',       [PortailController::class, 'profil']);
        Route::post('/deconnexion', [PortailController::class, 'deconnexion']);
        Route::post('/renouveler',  [PortailController::class, 'renouveler']);
    });

    // Gestion du capital de repos (pour l'astronaute lui-même)
    Route::get('/repos',            [ReposController::class, 'consulterCapital']);
    Route::post('/repos/demande',   [ReposController::class, 'soumettreDemande']);

    // Gestion admin du capital (réservé au rang "commandant")
    Route::prefix('commandant/repos')->middleware('verif.rang:commandant')->group(function () {
        Route::post('/{cible}/crediter', [CommandantReposController::class, 'crediter']);
        Route::post('/{cible}/debiter',  [CommandantReposController::class, 'debiter']);
    });
});
