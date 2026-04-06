# 🚀 Galaxy Congés API — Mazicus

> TP Laravel — Authentification JWT & Gestion de Congés  
> Auteur : **Mazicus** | ENSAM — Département Génie Informatique et IA

---

## Contexte

Une API de gestion de jours de repos pour une base spatiale imaginaire.  
Chaque **pilote** (employé) dispose d'un capital de jours de repos.  
Seul un **commandant** (admin) peut créditer ou débiter ce capital.

---

## Terminologie du projet

| Terme original (TP) | Terme utilisé ici   |
|---------------------|---------------------|
| `users`             | `astronautes`       |
| `employe`           | `pilote`            |
| `admin`             | `commandant`        |
| `solde_conges`      | `capital_repos`     |
| `jours`             | `jours_souhaites` / `jours_a_crediter` / `jours_a_debiter` |
| `AuthController`    | `PortailController` |
| `LeaveController`   | `ReposController`   |
| `AdminLeaveController` | `CommandantReposController` |
| `CheckRole`         | `VerifierRang`      |

---

## Installation

```bash
# 1. Cloner le projet
git clone https://github.com/Mazicus/galaxy-conges-api.git
cd galaxy-conges-api

# 2. Installer les dépendances
composer install

# 3. Copier le fichier d'environnement
cp .env.example .env

# 4. Générer la clé applicative Laravel
php artisan key:generate

# 5. Générer la clé secrète JWT
php artisan jwt:secret

# 6. Créer la base SQLite (ou configurer MySQL dans .env)
touch database/database.sqlite

# 7. Lancer les migrations
php artisan migrate

# 8. (Optionnel) Peupler avec des comptes de test
php artisan db:seed

# 9. Démarrer le serveur
php artisan serve
```

Les comptes seedés par défaut (mot de passe : `password`) :
- **Pilote** : `yuri@galaxie.test`
- **Commandant** : `nova@galaxie.test`

Pour créer un commandant via Tinker :
```bash
php artisan tinker
>>> Astronaute::create(['nom_complet' => 'Nova', 'adresse_email' => 'nova@test.com', 'mot_de_passe' => bcrypt('password'), 'rang' => 'commandant'])
```

---

## Routes API

### Publiques (sans token)

| Méthode | URL                      | Description              |
|---------|--------------------------|--------------------------|
| POST    | `/api/portail/inscription` | Créer un compte pilote |
| POST    | `/api/portail/connexion`   | Se connecter, obtenir un token JWT |

### Protégées — Tous les astronautes authentifiés

| Méthode | URL                      | Description              |
|---------|--------------------------|--------------------------|
| GET     | `/api/portail/profil`    | Voir son profil          |
| POST    | `/api/portail/deconnexion` | Se déconnecter         |
| POST    | `/api/portail/renouveler` | Renouveler le token     |
| GET     | `/api/repos`             | Voir son capital de repos |
| POST    | `/api/repos/demande`     | Soumettre une demande de repos |

### Protégées — Commandants uniquement (`rang = commandant`)

| Méthode | URL                                        | Description                    |
|---------|--------------------------------------------|--------------------------------|
| POST    | `/api/commandant/repos/{cible}/crediter`   | Créditer des jours à un pilote |
| POST    | `/api/commandant/repos/{cible}/debiter`    | Débiter des jours à un pilote  |

---

## Scénarios de test Postman

> Ajouter le header `Authorization: Bearer {token}` pour toutes les routes protégées.

### Scénario 1 — Accès sans token
- `GET /api/repos` sans header `Authorization`
- **Réponse attendue :** `401 Unauthorized`

### Scénario 2 — Inscription et connexion
- `POST /api/portail/inscription` → `{ "nom_complet": "Yuri", "adresse_email": "yuri@test.com", "mot_de_passe": "secret123" }`
- `POST /api/portail/connexion` → récupérer `jeton_acces`
- `GET /api/portail/profil` avec le token → vérifier les infos

### Scénario 3 — Consultation du capital
- `GET /api/repos` avec token
- **Réponse attendue :** `{ "capital_repos": 0 }`

### Scénario 4 — Demande invalide
- `POST /api/repos/demande` → `{ "jours_souhaites": 0 }` → **422** (valeur invalide)
- `POST /api/repos/demande` → `{ "jours_souhaites": 5 }` → **422** "Solde de congés insuffisant"

### Scénario 5 — Crédit par le commandant
- Se connecter en tant que commandant
- `POST /api/commandant/repos/{id_pilote}/crediter` → `{ "jours_a_crediter": 20 }`
- **Réponse attendue :** `{ "capital_repos": 20 }`

### Scénario 6 — Demande valide
- Se reconnecter en tant que pilote
- `POST /api/repos/demande` → `{ "jours_souhaites": 5 }`
- **Réponse attendue :** `{ "capital_repos": 15 }`

### Scénario 7 — Débit au-delà du solde
- Connecté en commandant : `POST /api/commandant/repos/{id_pilote}/debiter` → `{ "jours_a_debiter": 50 }`
- **Réponse attendue :** `422` "Solde insuffisant pour ce débit"

### Scénario 8 — Accès commandant refusé à un pilote
- Connecté en tant que pilote : `POST /api/commandant/repos/{id}/crediter`
- **Réponse attendue :** `403 Forbidden`

---

## Structure du projet

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── PortailController.php       # Auth (inscription, connexion, profil...)
│   │   │   ├── ReposController.php         # Solde + demande de congé
│   │   │   └── CommandantReposController.php # Admin crédit/débit
│   │   └── Controller.php
│   └── Middleware/
│       └── VerifierRang.php                # Vérifie le rang (pilote/commandant)
├── Models/
│   └── Astronaute.php                      # Modèle User (implémente JWTSubject)
database/
├── factories/AstroUserFactory.php
├── migrations/
│   └── ..._create_astronautes_table.php
└── seeders/DatabaseSeeder.php
routes/
└── api.php
```
