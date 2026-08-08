<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Client;
use App\Models\Competition;
use App\Models\EtabScolaire;
use App\Models\Evenement;
use App\Models\Exercice;
use App\Models\Inscription;
use App\Models\Professeur;

class ClientController extends Controller
{
    /** GET /client (ex app/client/home.php) */
    public function home(): void
    {
        Auth::requireLogin('/');

        $idUtilisateur = Auth::id();
        $client = new Client();
        $data = $client->findProfil($idUtilisateur);

        $professeurModel = new Professeur();
        $etabModel = new EtabScolaire();
        $professeursRegion = $professeurModel->allWithExerciceCount();
        $etablissementsRegion = $etabModel->findByRegion((int) $data['id_region']);

        $this->view('client/home', [
            'data'               => $data,
            'professeur'         => $professeursRegion,
            'etablissement'      => $etablissementsRegion,
            'Allprofesseurs'     => $professeurModel->all(),
            'Alletablissements'  => $etabModel->all(),
            'stats'              => [
                'professeurs'    => count($professeursRegion),
                'etablissements' => count($etablissementsRegion),
                'evenements'     => count((new Evenement())->aVenir()),
                'competitions'   => count((new Competition())->aVenir()),
            ],
        ]);
    }

    /** GET /client/api/competitions (ex app/client/api/competitions.php) */
    public function apiCompetitions(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté.']);
        }

        try {
            $this->json(['success' => true, 'competitions' => (new Competition())->aVenir()]);
        } catch (\PDOException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /** GET /client/api/evenements (ex app/client/api/evenements.php) */
    public function apiEvenements(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté.']);
        }

        try {
            $this->json(['success' => true, 'evenements' => (new Evenement())->aVenir()]);
        } catch (\PDOException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /** POST /client/api/inscription (ex app/client/api/inscription.php) */
    public function apiInscription(): void
    {
        $this->inscrire(Inscription::TYPE_COMPETITION, 'id_competition', 'cette compétition');
    }

    /** POST /client/api/inscription-evenement (ex app/client/api/inscription_evenement.php) */
    public function apiInscriptionEvenement(): void
    {
        $this->inscrire(Inscription::TYPE_EVENEMENT, 'id_evenement', 'cet événement');
    }

    private function inscrire(int $type, string $champId, string $libelle): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté.']);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data[$champId])) {
            $this->json(['success' => false, 'message' => 'Identifiant manquant.']);
        }

        $id = (int) $data[$champId];
        $clientId = (int) Auth::id();
        $inscription = new Inscription();

        try {
            if ($inscription->existe($type, $id, $clientId)) {
                $this->json(['success' => false, 'message' => "Vous êtes déjà inscrit à {$libelle}."]);
            }

            $inscription->create($type, $id, $clientId);
            $this->json(['success' => true, 'message' => 'Inscription réussie.']);
        } catch (\PDOException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /** GET /client/exercices-professeur (ex app/client/exercices_professeur.php) */
    public function exercicesProfesseur(): void
    {
        if (!Auth::check()) {
            $this->json(['erreur' => 'Non authentifié'], 401);
        }

        $idProfesseur = filter_input(INPUT_GET, 'id_professeur', FILTER_VALIDATE_INT);
        if (!$idProfesseur) {
            $this->json(['erreur' => 'id_professeur invalide'], 400);
        }

        $exercices = (new Exercice())->allByProfesseur($idProfesseur);

        $resultat = array_map(static function (array $ex) {
            return [
                'id_exercice' => (int) $ex['id_exercice'],
                'titre'       => Exercice::titreDepuisFichier($ex['fichier']),
                'date_envoi'  => Exercice::formatDate($ex['date_envoi']),
            ];
        }, $exercices);

        $this->json($resultat);
    }

    /** GET /client/fichier-exercice?id=..&action=voir|telecharger (ex app/client/fichier_exercice.php) */
    public function fichierExercice(): void
    {
        Auth::requireLogin('/');

        $idExercice = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $action = $_GET['action'] ?? 'voir';

        if (!$idExercice) {
            http_response_code(400);
            die('Exercice invalide.');
        }

        $fichier = (new Exercice())->findFichierById($idExercice);
        if (!$fichier) {
            http_response_code(404);
            die('Exercice introuvable.');
        }

        // On ne fait jamais confiance à un chemin venant de la BDD : on isole
        // le nom de fichier (basename) et on vérifie qu'il respecte le format
        // attendu, avant de le chercher sur disque.
        $nomFichier = basename($fichier);
        if (!preg_match('/^ex[a-z0-9.]+_[A-Za-z0-9._-]+\.pdf$/i', $nomFichier)) {
            http_response_code(400);
            die('Nom de fichier invalide.');
        }

        $dossierUploads = realpath($GLOBALS['config']['paths']['uploads']);
        $cheminFichier = $dossierUploads ? $dossierUploads . DIRECTORY_SEPARATOR . $nomFichier : false;

        if (!$cheminFichier || !is_file($cheminFichier)) {
            http_response_code(404);
            die('Fichier introuvable sur le serveur.');
        }

        $disposition = ($action === 'telecharger') ? 'attachment' : 'inline';

        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename="' . $nomFichier . '"');
        header('Content-Length: ' . filesize($cheminFichier));
        header('X-Content-Type-Options: nosniff');

        readfile($cheminFichier);
        exit();
    }

    /** GET /logout est géré par AuthController::logout (unifié pour tous les rôles) */
}
