<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Création de la table "astronautes".
     *
     * Colonnes métier ajoutées au-delà du standard Laravel :
     *   - capital_repos : solde de jours de congé (défaut 0)
     *   - rang          : rôle de l'astronaute (pilote = employé, commandant = admin)
     */
    public function up(): void
    {
        Schema::create('astronautes', function (Blueprint $table) {
            $table->id();

            $table->string('nom_complet');
            $table->string('adresse_email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mot_de_passe');

            // Champs métier spécifiques à ce TP
            $table->integer('capital_repos')->default(0);
            $table->enum('rang', ['pilote', 'commandant'])->default('pilote');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Suppression de la table en cas de rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('astronautes');
    }
};
