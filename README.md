# Harmonia

**Harmonia** est une application web de streaming musical développée avec **Symfony 7**, dans le cadre d'un projet de formation. Elle permet aux utilisateurs de découvrir des albums, des artistes et des genres musicaux, de gérer leurs favoris et leurs playlists, et aux administrateurs de gérer l'ensemble du contenu via un tableau de bord dédié.

---

## Captures d'écran

| Page d'accueil | Liste des albums | Dashboard admin |
|---|---|---|
| ![Accueil](screenshots/home.png) | ![Albums](screenshots/albums.png) | ![Admin](screenshots/admin.png) |

---

## Technologies utilisées

- **PHP 8.2** / **Symfony 7**
- **Twig** — moteur de templates
- **Doctrine ORM** — gestion de la base de données
- **MariaDB** (via **Docker**) — base de données relationnelle
- **Bootstrap 5** + **Bootstrap Icons** — interface utilisateur
- **Extension Twig personnalisée** — injection des genres dans la navbar
- **Docker** — conteneurisation de la base de données (MariaDB, port 3506)
- **Serveur Ubuntu** — environnement de déploiement

---

## Fonctionnalités

### Côté utilisateur
- Parcourir les albums, artistes et genres musicaux
- Écouter et comptabiliser les écoutes par morceau
- Ajouter/retirer des morceaux en favoris
- Créer et gérer ses playlists
- Ajouter des morceaux à une playlist depuis la page d'un album
- Consulter son profil (favoris, historique d'écoute, playlists)

### Côté administrateur
- Tableau de bord avec statistiques (artistes, albums, morceaux, playlists)
- Gestion complète (CRUD) : Artistes, Albums, Genres, Tracks, Playlists, Utilisateurs
- Pagination sur les listes longues (albums, tracks)
- Sidebar de navigation dédiée

---

## Structure du projet

```
src/
├── Controller/
│   ├── AdminController.php
│   ├── AlbumController.php
│   ├── ArtistController.php
│   ├── FavoriteController.php
│   ├── GenreController.php
│   ├── HomeController.php
│   ├── PlaylistController.php
│   ├── TrackController.php
│   └── UserController.php
├── Entity/
│   ├── Album.php
│   ├── Artist.php
│   ├── Favorite.php
│   ├── Genre.php
│   ├── Playlist.php
│   ├── Track.php
│   └── User.php
├── Form/
│   ├── AlbumType.php
│   ├── ArtistType.php
│   ├── GenreType.php
│   ├── PlaylistType.php
│   └── TrackType.php
├── Repository/
└── Twig/
    └── AppExtension.php
templates/
├── admin/
│   ├── _sidebar.html.twig
│   ├── index.html.twig
│   ├── tracks.html.twig
│   ├── users.html.twig
│   ├── albums/index.html.twig
│   ├── artists/index.html.twig
│   ├── genres/index.html.twig
│   └── playlists/index.html.twig
├── album/
├── artist/
├── component/
├── genre/
├── playlist/
└── base.html.twig
```

---

## Rôles utilisateur

| Rôle | Accès |
|---|---|
| Visiteur | Accueil, Albums, Artistes, Genres |
| `ROLE_USER` | Favoris, Playlists, Profil, Écoutes |
| `ROLE_ADMIN` | Dashboard admin + tout le CRUD |

---

## Principales routes

| Route | URL | Description |
|---|---|---|
| `app_home` | `/` | Page d'accueil |
| `app_albums` | `/albums` | Liste des albums |
| `app_album_show` | `/album/{id}` | Détail d'un album |
| `app_artist_list` | `/artists` | Liste des artistes |
| `app_genre_list` | `/admin/genres` | Liste des genres |
| `app_profil` | `/profil` | Profil utilisateur |
| `app_handle_favorite` | `/handle-favorite/{id}` | Ajouter/retirer un favori |
| `app_playlist_add_track` | `/playlist/{id}/add-track/{track_id}` | Ajouter un morceau à une playlist |
| `app_admin_dashboard` | `/admin` | Tableau de bord admin |
| `app_admin_artists` | `/admin/artists` | Gestion artistes |
| `app_admin_albums` | `/admin/albums` | Gestion albums |
| `app_admin_genres` | `/admin/genres` | Gestion genres |
| `app_admin_tracks` | `/admin/tracks` | Gestion tracks |
| `app_admin_playlists` | `/admin/playlists` | Gestion playlists |
| `app_admin_users` | `/admin/users` | Gestion utilisateurs |

---

## Installation

### Prérequis
- **Serveur Ubuntu** (ou environnement Linux)
- **Docker** + Docker Compose
- PHP 8.2+
- Composer
- Symfony CLI

### Étapes

```bash
# 1. Cloner le projet
git clone https://github.com/ton-pseudo/harmonia.git
cd harmonia

# 2. Lancer la base de données MariaDB via Docker là ou le fichier compose.yaml existe dans un dossier
docker compose up -d

# 3. Installer les dépendances PHP
composer install

# 4. Configurer la base de données dans .env
# MariaDB tourne sur le port 3506 via Docker
DATABASE_URL="mysql://user:password@127.0.0.1:3506/harmonia"

# 5. Créer la base de données et les tables
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate

# 6. Charger les fixtures (données de test)
symfony console doctrine:fixtures:load

# 7. Lancer le serveur
symfony serve

# 8. Arrêter le serveur
symfony server:stop
```

---

## Auteur

Développé par **Fanny** — Formation Conceptrice Développeuse Application 2026

---

## Licence

Projet réalisé dans le cadre d'une formation — usage éducatif uniquement.
