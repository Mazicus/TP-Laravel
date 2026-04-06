<?php

namespace Database\Factories;

use App\Models\Astronaute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Astronaute>
 */
class AstroUserFactory extends Factory
{
    // Modèle lié à cette factory
    protected $model = Astronaute::class;

    protected static ?string $motDePasseParDefaut;

    /**
     * Définit l'état par défaut d'un astronaute de test.
     */
    public function definition(): array
    {
        return [
            'nom_complet'        => fake()->name(),
            'adresse_email'      => fake()->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'mot_de_passe'       => static::$motDePasseParDefaut ??= Hash::make('password'),
            'remember_token'     => Str::random(10),
            // Par défaut un pilote (= employé) sans jours de repos
            'rang'               => 'pilote',
            'capital_repos'      => 0,
        ];
    }

    /**
     * État : astronaute dont l'email n'est pas encore vérifié.
     */
    public function nonVerifie(): static
    {
        return $this->state(fn (array $attributs) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * État : astronaute avec le rang commandant (admin).
     */
    public function commandant(): static
    {
        return $this->state(fn (array $attributs) => [
            'rang' => 'commandant',
        ]);
    }
}
