<?php

namespace Database\Seeders;

use App\Models\Astronaute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Peuple la base avec des données de test.
     *
     * Crée un pilote (employé) et un commandant (admin) par défaut.
     * Utile pour tester rapidement l'API sans passer par Tinker.
     */
    public function run(): void
    {
        // Pilote de test — rang : pilote (employé), capital : 0
        Astronaute::factory()->create([
            'nom_complet'   => 'Yuri Stellaris',
            'adresse_email' => 'yuri@galaxie.test',
        ]);

        // Commandant de test — rang : commandant (admin), capital : 0
        Astronaute::factory()->commandant()->create([
            'nom_complet'   => 'Nova Commandante',
            'adresse_email' => 'nova@galaxie.test',
        ]);
    }
}
