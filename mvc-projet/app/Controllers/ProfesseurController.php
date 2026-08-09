<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Candidature;
use App\Models\Exercice;
use App\Models\OffreEmploi;
use App\Models\Professeur;

class ProfesseurController extends Controller
{
    private const MESSAGES = [
        'upload'       => "L'envoi du fichier a échoué. Réessaie.",
        'taille'       => 'Le fichier dépasse la taille maximale de 10 Mo.',
        'type'         => 'Seuls les fichiers PDF sont acceptés.',
        'deplacement'  => "Impossible d'enregistrer le fichier sur le serveur.",
        'bdd'          => "Une erreur est survenue lors de l'enregistrement.",
        'requete'      => 'Requête invalide.',
        'candidature'  => 'Candidature envoyée avec succès.',
        'deja_postule' => 'Tu as déjà postulé à cette offre.',
        'offre_fermee' => "Cette offre n'est plus disponible.",
    ];

    /** GET /professeur (ex app/professeur/home.php) */
    public function home(): void
    {
        Auth::requireLogin('/');

        $idProfesseur = Auth::id();
        $exerciceModel = new Exercice();
        $professeurModel = new Professeur();
        $candidatureModel = new Candidature();

        $idRegion = $professeurModel->findRegion($idProfesseur) ?? 0;

        $erreurKey = $_GET['erreur'] ?? null;

        $this->view('professeur/home', [
            'nom_professeur'     => $_SESSION['login'] ?? 'Professeur',
            'nb_exercices'       => $exerciceModel->countByProfesseur($idProfesseur),
            'derniersExercices'  => $exerciceModel->derniersByProfesseur($idProfesseur),
            'offresRegion'       => (new OffreEmploi())->ouvertesParRegion($idRegion),
            'mesCandidatures'    => $candidatureModel->statutsParProfesseur($idProfesseur),
            'nb_candidatures'    => $candidatureModel->count($idProfesseur),
            'erreur'             => $erreurKey && isset(self::MESSAGES[$erreurKey]) ? self::MESSAGES[$erreurKey] : null,
            'succes'             => ($_GET['succes'] ?? null) === '1',
            'succesCandidature'  => ($_GET['succes'] ?? null) === 'candidature',
        ]);
    }

    /** POST /professeur/candidater (ex app/professeur/candidater.php) */
    public function candidater(): void
    {
        Auth::requireLogin('/');

        $idProfesseur = Auth::id();
        $idOffre = filter_input(INPUT_POST, 'id_offre', FILTER_VALIDATE_INT);

        if (!$idOffre) {
            $this->redirect('/professeur?erreur=requete#offres');
        }

        $idRegion = (new Professeur())->findRegion($idProfesseur);
        $offreModel = new OffreEmploi();

        if (!$idRegion || !$offreModel->estOuverteDansRegion($idOffre, $idRegion)) {
            $this->redirect('/professeur?erreur=offre_fermee#offres');
        }

        $resultat = (new Candidature())->create($idOffre, $idProfesseur);

        $this->redirect(match ($resultat) {
            'ok'        => '/professeur?succes=candidature#offres',
            'duplicate' => '/professeur?erreur=deja_postule#offres',
            default     => '/professeur?erreur=bdd#offres',
        });
    }

    /** POST /professeur/upload-exercice (ex app/professeur/upload_exercice.php) */
    public function uploadExercice(): void
    {
        Auth::requireLogin('/');

        $idProfesseur = Auth::id();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['fichier'])) {
            $this->redirect('/professeur?erreur=requete');
        }

        $fichier = $_FILES['fichier'];

        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            $this->redirect('/professeur?erreur=upload');
        }

        $tailleMax = 10 * 1024 * 1024;
        if ($fichier['size'] > $tailleMax) {
            $this->redirect('/professeur?erreur=taille');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fichier['tmp_name']);
        finfo_close($finfo);

        if ($mimeType !== 'application/pdf') {
            $this->redirect('/professeur?erreur=type');
        }

        $dossierUpload = $GLOBALS['config']['paths']['uploads'] . '/';
        if (!is_dir($dossierUpload)) {
            mkdir($dossierUpload, 0755, true);
        }

        $nomOriginal = pathinfo($fichier['name'], PATHINFO_FILENAME);
        $nomOriginal = preg_replace('/[^A-Za-z0-9._-]+/', '-', $nomOriginal);
        $nomOriginal = trim($nomOriginal, '-');
        if ($nomOriginal === '') {
            $nomOriginal = 'exercice';
        }
        $nomOriginal = substr($nomOriginal, 0, 80);

        $nomFichier = uniqid('ex', true) . '_' . $nomOriginal . '.pdf';
        $cheminDestination = $dossierUpload . $nomFichier;

        if (!move_uploaded_file($fichier['tmp_name'], $cheminDestination)) {
            $this->redirect('/professeur?erreur=deplacement');
        }

        try {
            (new Exercice())->create($idProfesseur, $nomFichier);
        } catch (\PDOException $e) {
            unlink($cheminDestination);
            $this->redirect('/professeur?erreur=bdd');
        }

        $this->redirect('/professeur?succes=1');
    }
}
