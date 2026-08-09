# Projet — version MVC

Restructuration du dépôt [Miary01/projet](https://github.com/Miary01/projet)
(PHP procédural) en architecture MVC (Modèle / Vue / Contrôleur), avec un
point d'entrée unique et un routeur.

## Installation

1. Copier ce dossier sur ton serveur (ou en local avec `php -S`).
2. Configurer la base de données. Deux options :
   - Créer un fichier `.env` à la racine (à côté de `config/`) :
     ```
     DB_HOST=localhost
     DB_NAME=projet
     DB_USER=admin
     DB_PASSWORD=admin123
     ADMIN_KEY=une_cle_secrete
     ```
   - Ou laisser les valeurs par défaut dans `config/config.php` (identiques
     à celles du projet d'origine).
3. Pointer le **DocumentRoot** de ton serveur web vers le dossier `public/`
   (c'est le seul dossier qui doit être exposé publiquement).
   - Apache : `.htaccess` déjà fourni dans `public/` (nécessite
     `mod_rewrite` activé).
   - Test rapide en local sans Apache :
     ```
     cd public
     php -S localhost:8000 index.php
     ```
     (le routeur PHP interne fera aussi transiter les fichiers statiques,
     c'est un mode dégradé pratique pour tester rapidement — en prod,
     utilise Apache/Nginx avec le `.htaccess` / une règle équivalente pour
     que les fichiers sous `public/assets/` soient servis directement).
4. Le dossier `storage/uploads/exercices/` doit être accessible en écriture
   par le serveur web (`chmod 755` ou `775` selon ton environnement).

## Structure

```
config/
  config.php          Configuration (BDD, chemins, clé admin)
public/
  index.php           Point d'entrée unique + définition des routes
  .htaccess           Réécriture d'URL (Apache)
  assets/css/*.css    Feuilles de style (déplacées depuis les anciens dossiers app/<role>/)
  assets/js/*.js      Scripts (endpoints mis à jour vers les nouvelles routes)
app/
  bootstrap.php       Autoload + connexion BDD + session, exécutés une seule fois
  Core/               Router, Database (PDO singleton), Auth, Controller/Model de base,
                       SessionHandlerDB, Response
  Controllers/        Un contrôleur par domaine métier
  Models/             Tout le SQL, isolé de l'affichage
  Views/              Templates PHP (uniquement de l'affichage, plus de requêtes SQL)
storage/
  uploads/exercices/  Fichiers PDF envoyés par les professeurs (ex app/professeur/uploads/exercices)
```

## Ce qui a changé par rapport à l'ancien code

- **Un seul point d'entrée** (`public/index.php`) au lieu d'un fichier PHP
  par page/action. Toute la logique de connexion BDD + session, dupliquée
  dans chaque ancien fichier, est centralisée dans `app/bootstrap.php`.
- **Tout le SQL est dans `app/Models/`** : les vues et contrôleurs ne
  contiennent plus aucune requête directe.
- **Les 4 anciens `logout.php` identiques** (client, professeur,
  établissement, organisation) sont unifiés en une seule action
  `AuthController::logout` (route `GET /logout`).
- Deux petits bugs de l'ancien code corrigés au passage : le bouton
  « Déconnexion » des pages admin ne faisait rien (pas de lien),
  et cette page chargeait un `home.js` qui n'existait pas dans le
  dépôt d'origine.
- Le rôle « boutique », jamais réellement utilisé dans l'application
  (aucune fonctionnalité, données factices), a été entièrement retiré :
  plus de compte, de route, de champ d'inscription ni de code mort associé.

## Table de correspondance ancien fichier → nouvelle route

| Ancien fichier                                         | Nouvelle route                          |
|----------------------------------------------------------|------------------------------------------|
| `index.php`                                               | `GET /`                                   |
| `login.php`, `temp.php`                                   | `POST /login`                             |
| `signup.php` (GET)                                        | `GET /signup`                             |
| `signup.php` (POST)                                        | `POST /signup`                            |
| `app/<role>/logout.php` (×4, identiques)                   | `GET /logout`                             |
| `search_region.php`                                        | `GET /region/search?q=...`                |
| `app/admin/home.php`                                        | `GET /admin`                              |
| `app/client/home.php`                                       | `GET /client`                             |
| `app/client/api/competitions.php`                           | `GET /client/api/competitions`            |
| `app/client/api/evenements.php`                              | `GET /client/api/evenements`              |
| `app/client/api/inscription.php`                             | `POST /client/api/inscription`            |
| `app/client/api/inscription_evenement.php`                   | `POST /client/api/inscription-evenement`  |
| `app/client/exercices_professeur.php`                        | `GET /client/exercices-professeur`        |
| `app/client/fichier_exercice.php`                            | `GET /client/fichier-exercice`            |
| `app/etablissement/home.php`                                  | `GET /etablissement`                      |
| `app/etablissement/offre_creer.php`                            | `POST /etablissement/offre`               |
| `app/etablissement/candidature_statut.php`                     | `POST /etablissement/candidature`         |
| `app/organisation/home.php` (GET + POST)                        | `GET /organisation`, `POST /organisation` |
| `app/organisation/api/mes_evenements.php`                        | `GET /organisation/api/mes-evenements`    |
| `app/organisation/api/mes_competitions.php`                       | `GET /organisation/api/mes-competitions`  |
| `app/organisation/api/participants.php`                            | `GET /organisation/api/participants`      |
| `app/professeur/home.php`                                            | `GET /professeur`                         |
| `app/professeur/candidater.php`                                       | `POST /professeur/candidater`             |
| `app/professeur/upload_exercice.php`                                    | `POST /professeur/upload-exercice`        |

## Étendre le projet

Pour ajouter une fonctionnalité :
1. Ajoute (ou complète) un modèle dans `app/Models/` avec les requêtes SQL nécessaires.
2. Ajoute une méthode dans le contrôleur concerné (`app/Controllers/`).
3. Déclare la route correspondante dans `public/index.php`.
4. Crée/adapte la vue dans `app/Views/`.

## Interface unifiée, panneau admin et recherche intelligente

En complément de la restructuration MVC, la plateforme a reçu :

- **Une identité visuelle commune** à tous les comptes (`public/assets/css/theme.css`) :
  une seule palette de couleurs (vert `#1F6F5C` en couleur de marque, orange
  `#E08A3E` en accent, plus les couleurs sémantiques succès/erreur/avertissement/info),
  chargée avant chaque feuille de style de compte. Chaque compte garde sa propre
  mise en page, mais toutes partagent désormais les mêmes couleurs et le même
  design (rayons, ombres, typographie).
- **Un panneau admin réellement fonctionnel** (`AdminController`, `app/Models/Statistique.php`) :
  statistiques globales de la plateforme (clients, professeurs, établissements,
  organisations, offres, candidatures, exercices, événements/compétitions),
  répartition des comptes par rôle, et gestion des utilisateurs (liste + suppression
  sécurisée, avec protection contre l'auto-suppression et les erreurs de données liées).
- **Une barre de recherche intelligente** sur chaque compte (`public/assets/js/smart-search.js`) :
  filtre en direct, côté navigateur, les éléments déjà affichés sur le tableau de
  bord (utilisateurs pour l'admin, professeurs/établissements pour le client,
  offres/exercices pour le professeur, professeurs/offres pour l'établissement,
  événements/compétitions/participants pour l'organisation). Aucune requête
  serveur supplémentaire : la recherche porte sur les données déjà chargées.

## Limites connues (héritées du projet d'origine, non corrigées ici)

- Les identifiants de connexion à la base sont en clair par défaut
  (`admin` / `admin123`) — à changer en production via `.env`.
- Pas de protection CSRF sur les formulaires POST.
- Le binaire `duckdb` et les exports CSV présents dans le dépôt d'origine
  n'ont pas été repris ici : ils ne faisaient pas partie de l'application
  PHP elle-même.
