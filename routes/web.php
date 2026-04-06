<?php

use Illuminate\Support\Facades\Route;

// Pas de routes web dans ce projet — c'est une API pure.
Route::get('/', function () {
    return response()->json([
        'projet' => 'Galaxy Congés API',
        'auteur' => 'Mazicus',
        'note'   => 'Utilisez les routes /api/portail pour commencer.',
    ]);
});
