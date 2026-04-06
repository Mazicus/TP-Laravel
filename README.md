# 🚀 Galaxy Congés API

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](#license)
[![Build Status](https://img.shields.io/github/workflow/status/Mazicus/TP-Laravel/CI)](../../actions)

> **Une API Laravel pour gérer les jours de congé des "astronautes"**  
> _A simple TP project showcasing JWT authentication, roles, and leave management._

---

## 🌟 Objectif du projet

- **Pilotes** : disposent d'un nombre de jours de repos.
- **Commandants** : peuvent ajouter/enlever des jours aux pilotes.
- **Authentification** : via JWT.

---

## 🚀 Installation rapide

```bash
git clone https://github.com/Mazicus/TP-Laravel.git
cd TP-Laravel

composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Base SQLite (par défaut), sinon configurez MySQL dans .env
touch database/database.sqlite

php artisan migrate
php artisan db:seed   # optionnel

php artisan serve
```

### Comptes de test

- **Pilote** : `yuri@galaxie.test`  
- **Commandant** : `nova@galaxie.test`  
**Mot de passe par défaut** : `password`

---

## 🛣️ Routes principales

### Sans authentification
- `POST /api/portail/inscription` — Créer un compte pilote
- `POST /api/portail/connexion` — Obtenir un token

### Avec token (JWT)
- `GET /api/portail/profil` — Voir son profil
- `POST /api/portail/deconnexion` — Déconnexion
- `POST /api/portail/renouveler` — Renouveler le token
- `GET /api/repos` — Voir son solde de repos
- `POST /api/repos/demande` — Demander des jours

### Réservé au commandant
- `POST /api/commandant/repos/{id}/crediter` — Crédite des jours
- `POST /api/commandant/repos/{id}/debiter` — Débite des jours

---

## ⚡ Exemples à tester

- Accès sans token → **401**
- S'inscrire → se connecter → consulter le profil
- Solde initial = **0**
- Demande invalide → **422**
- Le commandant peut créditer/débiter un pilote
- Un pilote ne peut pas accéder aux routes commandant → **403**

---

## 📦 Structure du projet

```
app/
  Controllers/
  Middleware/
  Models/
database/
routes/
```

---

## 🛠️ Contribuer

Les contributions sont les bienvenues !  
Pour toute suggestion, veuillez ouvrir une _issue_ ou une _pull request_.

---

## 📃 License

MIT — voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 👤 Contact

Créé par [Mazicus](https://github.com/Mazicus).  
N'hésitez pas à me contacter pour toute question !

---

> _English version available upon request._
