<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Astronaute;
use Illuminate\Http\Request;

class CommandantReposController extends Controller
{
    /**
     * POST /api/commandant/repos/{cible}/crediter
     * Le commandant (admin) ajoute des jours de repos à un astronaute cible.
     */
    public function crediter(Request $request, Astronaute $cible)
    {
        // Validation : le nombre de jours à créditer doit être un entier positif
        $payload = $request->validate([
            'jours_a_crediter' => ['required', 'integer', 'min:1'],
        ]);

        // Ajout des jours au capital de l'astronaute cible
        $cible->capital_repos += $payload['jours_a_crediter'];
        $cible->save();

        return response()->json([
            'message'       => 'Capital crédité avec succès',
            'astronaute'    => $cible->nom_complet,
            'capital_repos' => $cible->capital_repos,
        ]);
    }

    /**
     * POST /api/commandant/repos/{cible}/debiter
     * Le commandant (admin) retire des jours de repos à un astronaute cible.
     *
     * Règles :
     *  - Le capital de la cible ne peut pas passer en dessous de 0
     */
    public function debiter(Request $request, Astronaute $cible)
    {
        // Validation : le nombre de jours à débiter doit être un entier positif
        $payload = $request->validate([
            'jours_a_debiter' => ['required', 'integer', 'min:1'],
        ]);

        $joursRetires = $payload['jours_a_debiter'];

        // On refuse le débit si le solde deviendrait négatif
        if ($cible->capital_repos < $joursRetires) {
            return response()->json([
                'message' => 'Solde insuffisant pour ce débit',
            ], 422);
        }

        // Retrait des jours du capital
        $cible->capital_repos -= $joursRetires;
        $cible->save();

        return response()->json([
            'message'       => 'Capital débité avec succès',
            'astronaute'    => $cible->nom_complet,
            'capital_repos' => $cible->capital_repos,
        ]);
    }
}
