<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Astronaute;
use Illuminate\Http\Request;

class ReposController extends Controller
{
    /**
     * GET /api/repos
     * Retourne le capital de repos (solde de congés) de l'astronaute connecté.
     */
    public function consulterCapital()
    {
        // On récupère l'astronaute actuellement authentifié
        $moi = auth('api')->user();

        return response()->json([
            'capital_repos' => $moi->capital_repos,
        ]);
    }

    /**
     * POST /api/repos/demande
     * Soumet une demande de jours de repos et réduit le capital en conséquence.
     *
     * Règles :
     *  - Le champ "jours_souhaites" doit être un entier >= 1
     *  - L'astronaute ne peut pas demander plus que son capital actuel
     */
    public function soumettreDemande(Request $request)
    {
        // Validation : l'astronaute doit demander au moins 1 jour
        $payload = $request->validate([
            'jours_souhaites' => ['required', 'integer', 'min:1'],
        ]);

        /** @var Astronaute $moi */
        $moi            = auth('api')->user();
        $joursVoulus    = $payload['jours_souhaites'];

        // Vérification du solde disponible avant de valider la demande
        if ($moi->capital_repos < $joursVoulus) {
            return response()->json([
                'message' => 'Solde de congés insuffisant',
            ], 422);
        }

        // Déduction des jours demandés du capital
        $moi->capital_repos -= $joursVoulus;
        $moi->save();

        return response()->json([
            'message'       => 'Demande de repos enregistrée avec succès',
            'capital_repos' => $moi->capital_repos,
        ]);
    }
}
