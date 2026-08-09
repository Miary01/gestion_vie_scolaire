<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Candidature;
use App\Models\EtabScolaire;
use App\Models\OffreEmploi;
use App\Models\Professeur;

class EtablissementController extends Controller
{
    private const MESSAGES = [
        'creation'    => 'Offre publiée avec succès.',
        'maj'         => 'Candidature mise à jour.',
        'champs'      => 'Merci de remplir le titre et la description.',
        'introuvable' => 'Offre ou candidature introuvable.',
    ];

    /** GET /etablissement (ex app/etablissement/home.php) */
    public function home(): void
    {
        Auth::requireLogin('/');

        $idUtilisateur = Auth::id();
        $etabModel = new EtabScolaire();
        $data = $etabModel->findByUtilisateur($idUtilisateur);

        if (!$data) {
            $this->redirect('/');
        }

        $idRegion = (int) $data['id_region'];
        $professeurModel = new Professeur();
        $offreModel = new OffreEmploi();
        $candidatureModel = new Candidature();

        $mesOffres = $offreModel->findByEtablissement($idUtilisateur);
        foreach ($mesOffres as &$offre) {
            $offre['candidatures'] = $candidatureModel->findByOffre((int) $offre['id_offre']);
        }
        unset($offre);

        $succesKey = $_GET['succes'] ?? null;

        $this->view('etablissement/home', [
            'nomEtablissement'   => $data['nom_etablissement'],
            'idEtablissement'    => $idUtilisateur,
            'idRegion'           => $idRegion,
            'professeurs'        => $professeurModel->findByRegion($idRegion),
            'allProfesseurs'     => $professeurModel->all(),
            'mesOffres'          => $mesOffres,
            'vueRecrutement'     => isset($_GET['vue']) && $_GET['vue'] === 'recrutement',
            'succesRecrutement'  => $succesKey && isset(self::MESSAGES[$succesKey]) ? self::MESSAGES[$succesKey] : null,
        ]);
    }

    /** POST /etablissement/offre (ex app/etablissement/offre_creer.php) */
    public function creerOffre(): void
    {
        Auth::requireLogin('/');

        $idEtablissement = Auth::id();
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($titre === '' || $description === '') {
            $this->redirect('/etablissement?vue=recrutement&succes=champs');
        }

        (new OffreEmploi())->create($idEtablissement, $titre, $description);

        $this->redirect('/etablissement?vue=recrutement&succes=creation');
    }

    /** POST /etablissement/candidature (ex app/etablissement/candidature_statut.php) */
    public function statutCandidature(): void
    {
        Auth::requireLogin('/');

        $idEtablissement = Auth::id();
        $idCandidature = filter_input(INPUT_POST, 'id_candidature', FILTER_VALIDATE_INT);
        $statut = $_POST['statut'] ?? '';

        if (!$idCandidature || !in_array($statut, ['acceptee', 'refusee'], true)) {
            $this->redirect('/etablissement?vue=recrutement&succes=introuvable');
        }

        $candidatureModel = new Candidature();

        // Empêche un établissement de modifier les candidatures d'un autre.
        if (!$candidatureModel->appartientAEtablissement($idCandidature, $idEtablissement)) {
            $this->redirect('/etablissement?vue=recrutement&succes=introuvable');
        }

        $candidatureModel->updateStatut($idCandidature, $statut);

        $this->redirect('/etablissement?vue=recrutement&succes=maj');
    }
}
