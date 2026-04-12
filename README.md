# Hopital Sud Paris (HSP)

Application web de gestion hospitalière pour l'**Hopital Sud Paris**, développée en PHP natif avec une architecture MVC.

---

## Sommaire

- [Présentation](#présentation)
- [Technologies](#technologies)
- [Architecture du projet](#architecture-du-projet)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Rôles et accès](#rôles-et-accès)
- [Fonctionnalités](#fonctionnalités)

---

## Présentation

HSP est une plateforme complète de gestion hospitalière permettant de gérer les patients, médecins, hospitalisations, stocks, candidatures, événements et bien plus. Elle propose également un forum interne par rôle et une interface publique de présentation de l'hôpital.

---

## Technologies

| Technologie     | Usage                          |
|----------------|-------------------------------|
| PHP 8.x         | Langage principal (back-end)  |
| MySQL           | Base de données               |
| PDO             | Couche d'accès aux données    |
| TailwindCSS (CDN) | Styles et interface         |
| PHPMailer       | Envoi d'emails                |
| Composer        | Gestionnaire de dépendances   |

---

## Architecture du projet

```
hsp/
├── index.php                  # Page d'accueil publique
├── composer.json
├── src/
│   ├── bdd/
│   │   └── Bdd.php            # Connexion PDO à la base de données
│   ├── modele/                # Classes entités (Patient, Medecin, Chambre, etc.)
│   ├── repository/            # Requêtes SQL par entité
│   ├── traitement/            # Logique métier / traitement des formulaires
│   ├── vue/                   # Vues PHP (HTML + PHP)
│   │   ├── Forum/             # Module forum (posts, réponses, ressources)
│   │   ├── Connexion.php
│   │   ├── Inscription.php
│   │   ├── admin.php
│   │   └── ...
│   ├── css/                   # Feuilles de style
│   └── uploads/               # Fichiers uploadés
└── vendor/                    # Dépendances Composer
```

---

## Prérequis

- [WampServer](https://www.wampserver.com/) (ou XAMPP / Laragon)
- PHP >= 8.0
- MySQL >= 5.7
- Composer

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/<votre-repo>/hsp.git
# Placer le dossier dans C:\wamp64\www\hsp
```

### 2. Installer les dépendances

```bash
cd C:\wamp64\www\hsp
composer install
```

### 3. Créer la base de données

- Ouvrir **phpMyAdmin** (http://localhost/phpmyadmin)
- Créer une base de données nommée `hsp`
- Importer le fichier SQL fourni (si disponible)

### 4. Vérifier la configuration BDD

Le fichier `src/bdd/Bdd.php` utilise ces paramètres par défaut :

```php
host     = 'localhost'
dbname   = 'hsp'
username = 'root'
password = ''
```

Modifier si nécessaire.

### 5. Lancer l'application

Accéder à : [http://localhost/hsp](http://localhost/hsp)

---

## Rôles et accès

| Rôle        | Description                                                              |
|-------------|--------------------------------------------------------------------------|
| `admin`     | Accès complet : gestion de tout le système + dashboard admin             |
| `medecin`   | Accès aux patients, dossiers, ordonnances, forum médecins/utilisateurs   |
| `user`      | Accès au forum utilisateurs, candidatures, profil                        |
| `entreprise`| Gestion des offres d'emploi, candidatures, forum entreprises             |

---

## Fonctionnalités

### Page publique
- Présentation de l'hôpital, services, statistiques
- Carrousel d'événements dynamique
- Formulaire de prise de rendez-vous
- Informations de contact et horaires

### Authentification
- Inscription (utilisateur / entreprise)
- Connexion / Déconnexion
- Modification du mot de passe
- Réinitialisation par email (PHPMailer)

### Gestion (admin / medecin)
- Patients, Médecins, Spécialités
- Hôpitaux, Établissements, Chambres
- Hospitalisations, Dossiers de prise en charge
- Ordonnances
- Fournisseurs, Produits, Stock, Demandes de stock
- Contrats, Événements

### Offres et Candidatures
- Les entreprises publient des offres d'emploi
- Les utilisateurs postulent et suivent leurs candidatures
- Les entreprises gèrent les candidatures reçues

### Forum interne (par rôle)
- **Utilisateurs** : questions et discussions
- **Médecins** : échanges professionnels
- **Entreprises** : espace partenaires
- **Admin** : forum interne d'administration
- Possibilité de poster, commenter et partager des ressources

### Profil utilisateur
- Consultation et modification des informations personnelles
