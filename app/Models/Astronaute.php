<?php

namespace App\Models;

use Database\Factories\AstroUserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

// Champs autorisés à être remplis en masse
#[Fillable(['nom_complet', 'adresse_email', 'mot_de_passe', 'rang', 'capital_repos'])]
#[Hidden(['mot_de_passe', 'remember_token'])]

class Astronaute extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<AstroUserFactory> */
    use HasFactory, Notifiable;

    // Nom de la table dans la base de données
    protected $table = 'astronautes';

    // Correspondance entre les attributs du modèle et les colonnes DB
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mot_de_passe'      => 'hashed',
    ];

    // --- Interface JWTSubject ---

    // Retourne l'identifiant unique utilisé dans le token JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Retourne des données supplémentaires à inclure dans le payload JWT
    public function getJWTCustomClaims()
    {
        return [
            'rang' => $this->rang,
        ];
    }
}
