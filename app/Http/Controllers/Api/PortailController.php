<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Astronaute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PortailController extends Controller
{
    /**
     * POST /api/portail/inscription
     * Crée un nouveau compte astronaute.
     */
    public function inscription(Request $request)
    {
        // Validation des champs envoyés par le client
        $donnees = $request->validate([
            'nom_complet'    => ['required', 'string', 'max:255'],
            'adresse_email'  => ['required', 'string', 'email', 'max:255', 'unique:astronautes'],
            'mot_de_passe'   => ['required', 'string', 'min:8'],
        ]);

        // Création du nouvel astronaute dans la base
        $nouvelAstronaute = Astronaute::create([
            'nom_complet'   => $donnees['nom_complet'],
            'adresse_email' => $donnees['adresse_email'],
            'mot_de_passe'  => Hash::make($donnees['mot_de_passe']),
        ]);

        // Génération d'un token JWT pour le compte tout juste créé
        $jeton = JWTAuth::fromUser($nouvelAstronaute);

        return response()->json([
            'message'      => 'Compte astronaute créé avec succès',
            'astronaute'   => $nouvelAstronaute,
            'jeton_acces'  => $jeton,
            'type_jeton'   => 'bearer',
            'expiration'   => config('jwt.ttl') * 60,
        ], 201);
    }

    /**
     * POST /api/portail/connexion
     * Authentifie un astronaute et retourne un jeton JWT.
     */
    public function connexion(Request $request)
    {
        // On s'attend à recevoir l'email et le mot de passe
        $identifiants = $request->validate([
            'adresse_email' => ['required', 'email'],
            'mot_de_passe'  => ['required'],
        ]);

        // jwt-auth attend les clés "email" et "password" pour tenter l'authentification
        $credentialsJwt = [
            'adresse_email' => $identifiants['adresse_email'],
            'mot_de_passe'  => $identifiants['mot_de_passe'],
        ];

        if (!$jeton = JWTAuth::attempt($credentialsJwt)) {
            return response()->json([
                'message' => 'Identifiants incorrects, accès refusé.',
            ], 401);
        }

        return response()->json([
            'message'     => 'Connexion réussie — bienvenue à bord !',
            'astronaute'  => auth('api')->user(),
            'jeton_acces' => $jeton,
            'type_jeton'  => 'bearer',
            'expiration'  => config('jwt.ttl') * 60,
        ]);
    }

    /**
     * GET /api/portail/profil
     * Retourne les informations de l'astronaute connecté.
     */
    public function profil()
    {
        $astronauteActuel = auth('api')->user();

        return response()->json([
            'astronaute' => $astronauteActuel,
        ]);
    }

    /**
     * POST /api/portail/deconnexion
     * Invalide le jeton JWT et déconnecte l'astronaute.
     */
    public function deconnexion()
    {
        $jetonActuel = JWTAuth::getToken();

        if ($jetonActuel) {
            JWTAuth::invalidate($jetonActuel);
        }

        return response()->json([
            'message' => 'Déconnexion réussie — à bientôt !',
        ]);
    }

    /**
     * POST /api/portail/renouveler
     * Génère un nouveau jeton JWT à partir de l'ancien.
     */
    public function renouveler()
    {
        $nouveauJeton = JWTAuth::refresh(JWTAuth::getToken());

        return response()->json([
            'jeton_acces' => $nouveauJeton,
            'type_jeton'  => 'bearer',
            'expiration'  => config('jwt.ttl') * 60,
        ]);
    }
}
