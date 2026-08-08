<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Competition;
use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\Region;

class OrganisationController extends Controller
{
    /** GET /organisation et POST /organisation (ex app/organisation/home.php) */
    public function home(): void
    {
        Auth::requireLogin('/');

        $organisateurId = Auth::id();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($organisateurId);
            return;
        }

        $this->view('organisation/home', [
            'regions' => (new Region())->all(),
            'stats'   => $this->statistiques($organisateurId),
        ]);
    }

    private function statistiques(int $organisateurId): array
    {
        $evenements = (new Evenement())->findByOrganisateur($organisateurId);
        $competitions = (new Competition())->findByOrganisateur($organisateurId);

        $aujourdHui = date('Y-m-d');
        $evenementsAvenir = array_filter($evenements, fn($e) => substr($e['date_evenement'], 0, 10) >= $aujourdHui);
        $competitionsAvenir = array_filter($competitions, fn($c) => substr($c['date_competition'], 0, 10) >= $aujourdHui);

        return [
            'evenements'          => count($evenements),
            'competitions'        => count($competitions),
            'evenements_avenir'   => count($evenementsAvenir),
            'competitions_avenir' => count($competitionsAvenir),
        ];
    }

    private function handlePost(int $organisateurId): void
    {
        if (isset($_POST['nom_evenement'], $_POST['date_evenement'], $_POST['id_region'])) {
            (new Evenement())->create(
                $organisateurId,
                trim($_POST['nom_evenement']),
                $_POST['date_evenement'],
                (int) $_POST['id_region']
            );
            $this->redirect('/organisation');
        }

        if (isset($_POST['nom_competition'], $_POST['date_competition'], $_POST['id_region'])) {
            (new Competition())->create(
                $organisateurId,
                trim($_POST['nom_competition']),
                $_POST['date_competition'],
                (int) $_POST['id_region']
            );
            $this->redirect('/organisation');
        }

        $this->redirect('/organisation');
    }

    /** GET /organisation/api/mes-evenements (ex app/organisation/api/mes_evenements.php) */
    public function apiMesEvenements(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        }

        $this->json([
            'success'    => true,
            'evenements' => (new Evenement())->findByOrganisateur((int) Auth::id()),
        ]);
    }

    /** GET /organisation/api/mes-competitions (ex app/organisation/api/mes_competitions.php) */
    public function apiMesCompetitions(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        }

        $this->json([
            'success'      => true,
            'competitions' => (new Competition())->findByOrganisateur((int) Auth::id()),
        ]);
    }

    /** GET /organisation/api/participants?type=&id= (ex app/organisation/api/participants.php) */
    public function apiParticipants(): void
    {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Utilisateur non connecté']);
        }

        $type = $_GET['type'] ?? null;
        $id = $_GET['id'] ?? null;

        if (!$type || !$id) {
            $this->json(['success' => false, 'message' => 'Paramètres manquants']);
        }

        $participants = (new Inscription())->participants((int) $type, (int) $id, (int) Auth::id());

        $this->json(['success' => true, 'participants' => $participants]);
    }
}
