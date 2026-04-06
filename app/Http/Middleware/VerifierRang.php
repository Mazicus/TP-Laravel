<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifierRang
{
    /**
     * Vérifie que l'astronaute authentifié possède bien le rang requis.
     *
     * Si le rang ne correspond pas, on retourne une erreur 403 (accès interdit).
     */
    public function handle(Request $request, Closure $next, string $rangRequis): Response
    {
        // Récupération de l'astronaute connecté via le guard api
        $astronaute = auth('api')->user();

        // Vérification : l'astronaute doit exister ET avoir le bon rang
        if (!$astronaute || $astronaute->rang !== $rangRequis) {
            return response()->json([
                'message' => 'Accès interdit — rang insuffisant',
            ], 403);
        }

        // Tout est bon, on laisse passer la requête
        return $next($request);
    }
}
