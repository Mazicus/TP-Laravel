# Galaxy Congés API

Une petite API créée pour un TP Laravel.  
Elle sert à gérer des jours de repos pour des “astronautes”.

---

## Idée du projet

- Les pilotes ont un nombre de jours de repos.  
- Les commandants peuvent ajouter ou retirer des jours.  
- L’API utilise JWT pour l’authentification.

---

## Installation

```bash
git clone https://github.com/Mazicus/galaxy-conges-api.git
cd galaxy-conges-api

composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Base SQLite (sinon config MySQL dans .env)
touch database/database.sqlite

php artisan migrate
php artisan db:seed   # optionnel

php artisan serve

Comptes créés automatiquement (mot de passe : password) :

Pilote : yuri@galaxie.test

Commandant : nova@galaxie.test



---

Routes principales

Sans authentification

POST /api/portail/inscription — créer un compte pilote

POST /api/portail/connexion — obtenir un token


Avec token

GET /api/portail/profil

POST /api/portail/deconnexion

POST /api/portail/renouveler

GET /api/repos — voir son solde

POST /api/repos/demande — demander des jours


Commandants seulement

POST /api/commandant/repos/{id}/crediter

POST /api/commandant/repos/{id}/debiter



---

Tests rapides

Sans token → 401

S'inscrire → se connecter → consulter le profil

Solde initial → 0

Demande invalide → 422

Un commandant peut créditer/débiter

Un pilote ne peut pas accéder aux routes commandant → 403



---

Structure du projet

app/
  Controllers/
  Middleware/
  Models/
database/
routes/

