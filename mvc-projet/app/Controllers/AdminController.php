<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Statistique;
use App\Models\Utilisateur;

class AdminController extends Controller
{
    private const MESSAGES = [
        'supprime'    => 'Compte supprimé avec succès.',
        'introuvable' => 'Ce compte est introuvable (peut-être déjà supprimé).',
        'liee'        => "Impossible de supprimer ce compte : des données lui sont encore liées (exercices, offres, candidatures...).",
        'soi_meme'    => 'Tu ne peux pas supprimer ton propre compte administrateur.',
    ];

    /** GET /admin */
    public function home(): void
    {
        Auth::requireLogin('/');

        $statistiqueModel = new Statistique();
        $utilisateurModel = new Utilisateur();

        $succesKey = $_GET['succes'] ?? null;

        $this->view('admin/home', [
            'stats'         => $statistiqueModel->globales(),
            'repartition'   => $statistiqueModel->repartitionParRole(),
            'utilisateurs'  => $utilisateurModel->allWithDetails(),
            'message'       => $succesKey && isset(self::MESSAGES[$succesKey]) ? self::MESSAGES[$succesKey] : null,
        ]);
    }

    /** POST /admin/utilisateur/supprimer */
    public function supprimerUtilisateur(): void
    {
        Auth::requireLogin('/');

        $idUtilisateur = filter_input(INPUT_POST, 'id_utilisateur', FILTER_VALIDATE_INT);

        if (!$idUtilisateur) {
            $this->redirect('/admin?succes=introuvable');
        }

        if ($idUtilisateur === Auth::id()) {
            $this->redirect('/admin?succes=soi_meme');
        }

        $resultat = (new Utilisateur())->delete($idUtilisateur);

        $this->redirect('/admin?succes=' . ($resultat === 'ok' ? 'supprime' : $resultat));
    }
}
