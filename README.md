
# 🎬 Slim Auth & Anime API

Cette API a été développée avec le **micro-framework PHP Slim**.
Elle gère l’authentification des utilisateurs (inscription, connexion, profil, déconnexion) ainsi que la récupération de données multimédias comme des mangas, animes, films et épisodes.

---

## 🚀 Fonctionnalités principales

### 🔐 Authentification

* **POST** `/auth/register` → Créer un nouveau compte utilisateur.
* **POST** `/auth/login` → Se connecter avec email et mot de passe.
* **GET** `/auth/profile` → Récupérer les informations de l’utilisateur connecté (via la session).
* **GET** `/auth/logout` → Déconnexion (destruction de la session).

### 📺 Contenu multimédia

* **GET** `/auth/manga` → Récupère la liste des mangas.
* **GET** `/auth/anime?page={num}` → Récupère la liste des animes (pagination optionnelle).
* **GET** `/auth/movies?page={num}` → Récupère la liste des films.
* **POST** `/auth/episodes` → Récupère les épisodes d’un anime en fonction du titre.
* **GET** `/auth/random` → Retourne un élément aléatoire (anime/manga/film).

### 🧩 Autres

* **GET** `/auth/hello` → Route de test simple ("hello world").

---

## 🛠️ Installation et configuration

### 1️⃣ Cloner le projet

```bash
git clone https://github.com/ton-utilisateur/ton-projet.git
cd ton-projet
```

### 2️⃣ Installer les dépendances

Assure-toi d’avoir **Composer** installé :

```bash
composer install
```

### 3️⃣ Configurer la base de données

Le projet utilise une classe `database` (dans `App/database.php`) et un modèle `User` (dans `App/Models/User.php`).
👉 Vérifie ta configuration (host, nom de base, utilisateur, mot de passe) dans le fichier concerné.

### 4️⃣ Lancer le serveur de développement Slim

```bash
php -S localhost:8080 -t public
```

Ensuite, tu peux accéder à ton API à l’adresse :
👉 [http://localhost:8080](http://localhost:8080)

---

## 📦 Exemple de requêtes

### 🔸 Inscription

```bash
POST /auth/register
Content-Type: application/json

{
  "name": "Joan",
  "email": "joan@example.com",
  "password": "secret123"
}
```

**Réponse :**

```json
{
  "message": "User account created!"
}
```

---

### 🔸 Connexion

```bash
POST /auth/login
Content-Type: application/json

{
  "email": "joan@example.com",
  "password": "secret123"
}
```

**Réponse :**

```json
{
  "message": "Login successful",
  "token": "session_id",
  "data": {
    "IDUSER": 1,
    "NAME": "Joan",
    "EMAIL": "joan@example.com"
  }
}
```

---

### 🔸 Profil utilisateur

```bash
GET /auth/profile
```

**Réponse :**

```json
{
  "IDUSER": 1,
  "NAME": "Joan",
  "EMAIL": "joan@example.com"
}
```

---

### 🔸 Déconnexion

```bash
GET /auth/logout
```

**Réponse :**

```json
{
  "message": "Logout successful"
}
```

---

## ⚙️ Stack technique

* **Langage :** PHP 8+
* **Framework :** Slim Framework
* **Base de données :** MySQL
* **Gestion des sessions :** PHP native (`$_SESSION`)
* **Sécurité :** Hashage des mots de passe avec `password_hash()` et `password_verify()`

---

## ✨ À propos

Développé par **Joan Kekeli** 🧠
Projet personnel d’apprentissage du framework **Slim**, incluant gestion d’authentification, routes RESTful et manipulation de données dynamiques (mangas, animes, films).


