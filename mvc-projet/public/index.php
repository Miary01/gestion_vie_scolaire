<?php

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\EtablissementController;
use App\Controllers\OrganisationController;
use App\Controllers\ProfesseurController;
use App\Controllers\RegionController;
use App\Core\Router;

$router = new Router();

// --- Authentification (ex index.php, login.php, signup.php, logout communs) ---
$router->get('/', AuthController::class, 'showLogin');
$router->post('/login', AuthController::class, 'login');
$router->get('/signup', AuthController::class, 'showSignup');
$router->post('/signup', AuthController::class, 'signup');
$router->get('/logout', AuthController::class, 'logout');

// --- Autocomplétion région (ex search_region.php) ---
$router->get('/region/search', RegionController::class, 'search');

// --- Admin (ex app/admin/home.php) ---
$router->get('/admin', AdminController::class, 'home');
$router->post('/admin/utilisateur/supprimer', AdminController::class, 'supprimerUtilisateur');

// --- Client (ex app/client/*) ---
$router->get('/client', ClientController::class, 'home');
$router->get('/client/api/competitions', ClientController::class, 'apiCompetitions');
$router->get('/client/api/evenements', ClientController::class, 'apiEvenements');
$router->post('/client/api/inscription', ClientController::class, 'apiInscription');
$router->post('/client/api/inscription-evenement', ClientController::class, 'apiInscriptionEvenement');
$router->get('/client/exercices-professeur', ClientController::class, 'exercicesProfesseur');
$router->get('/client/fichier-exercice', ClientController::class, 'fichierExercice');

// --- Établissement (ex app/etablissement/*) ---
$router->get('/etablissement', EtablissementController::class, 'home');
$router->post('/etablissement/offre', EtablissementController::class, 'creerOffre');
$router->post('/etablissement/candidature', EtablissementController::class, 'statutCandidature');

// --- Organisation (ex app/organisation/*) ---
$router->get('/organisation', OrganisationController::class, 'home');
$router->post('/organisation', OrganisationController::class, 'home');
$router->get('/organisation/api/mes-evenements', OrganisationController::class, 'apiMesEvenements');
$router->get('/organisation/api/mes-competitions', OrganisationController::class, 'apiMesCompetitions');
$router->get('/organisation/api/participants', OrganisationController::class, 'apiParticipants');

// --- Professeur (ex app/professeur/*) ---
$router->get('/professeur', ProfesseurController::class, 'home');
$router->post('/professeur/candidater', ProfesseurController::class, 'candidater');
$router->post('/professeur/upload-exercice', ProfesseurController::class, 'uploadExercice');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
