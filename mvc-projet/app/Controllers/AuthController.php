<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Admin;
use App\Models\Client;
use App\Models\EtabScolaire;
use App\Models\Organisation;
use App\Models\Professeur;
use App\Models\Role;
use App\Models\Utilisateur;

class AuthController extends Controller
{
    private const HOME_BY_ROLE = [
        Auth::ROLE_ADMIN         => '/admin',
        Auth::ROLE_CLIENT        => '/client',
        Auth::ROLE_PROFESSEUR    => '/professeur',
        Auth::ROLE_ETABLISSEMENT => '/etablissement',
        Auth::ROLE_ORGANISATION  => '/organisation',
    ];

    /** GET / : formulaire de connexion (ex index.php) */
    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    /** POST /login (ex login.php) */
    public function login(): void
    {
        $utilisateurModel = new Utilisateur();
        $log = $utilisateurModel->findByLogin($_POST['username'] ?? '');

        if (!$log || !$utilisateurModel->verifyPassword($_POST['password'] ?? '', $log['password'])) {
            $this->view('auth/login', ['erreur' => 'Problème de connexion']);
            return;
        }

        Auth::login($log['login'], (int) $log['id_utilisateur']);
        $role = (int) $log['id_role'];

        // Cas particulier client : on précharge sa région en session, comme
        // dans l'ancien code, pour l'utiliser ensuite dans ClientController.
        if ($role === Auth::ROLE_CLIENT) {
            $idRegion = (new Client())->findRegionByUtilisateur((int) $log['id_utilisateur']);
            $_SESSION['id_region'] = $idRegion;
        }

        $this->redirect(self::HOME_BY_ROLE[$role] ?? '/');
    }

    /** GET /signup : formulaire d'inscription (ex signup.php en GET) */
    public function showSignup(): void
    {
        $roleModel = new Role();
        $adminCount = (new Utilisateur())->countByRole(Auth::ROLE_ADMIN);

        $this->view('auth/signup', [
            'roles'         => $roleModel->all(),
            'adminDisabled' => $adminCount >= 20,
        ]);
    }

    /** POST /signup (ex signup.php en POST) */
    public function signup(): void
    {
        $conf = $GLOBALS['config'];
        $utilisateurModel = new Utilisateur();
        $adminCount = $utilisateurModel->countByRole(Auth::ROLE_ADMIN);

        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = (int) ($_POST['role'] ?? 0);

        if ($role === Auth::ROLE_ADMIN) {
            if ($adminCount >= 20) {
                die('Admin limit reached');
            }
            if (($_POST['admin_key'] ?? '') !== $conf['admin_key']) {
                die('Invalid admin key');
            }
        }

        $userId = $utilisateurModel->create($login, $password, $role);

        switch ($role) {
            case Auth::ROLE_CLIENT:
                (new Client())->create(
                    $userId,
                    $_POST['nom_client'] ?? '',
                    $_POST['prenom_client'] ?? '',
                    $_POST['email_client'] ?? '',
                    (int) ($_POST['region_client'] ?? 0)
                );
                break;
            case Auth::ROLE_PROFESSEUR:
                (new Professeur())->create(
                    $userId,
                    $_POST['nom_professeur'] ?? '',
                    $_POST['prenom_professeur'] ?? '',
                    $_POST['email_professeur'] ?? '',
                    (int) ($_POST['region_professeur'] ?? 0)
                );
                break;
            case Auth::ROLE_ADMIN:
                (new Admin())->create($userId);
                break;
            case Auth::ROLE_ETABLISSEMENT:
                (new EtabScolaire())->create(
                    $userId,
                    $_POST['nom_etablissement'] ?? '',
                    $_POST['email_etablissement'] ?? '',
                    (int) ($_POST['region_etablissement'] ?? 0)
                );
                break;
            case Auth::ROLE_ORGANISATION:
                (new Organisation())->create(
                    $userId,
                    $_POST['nom_organisation'] ?? '',
                    $_POST['email_organisation'] ?? ''
                );
                break;
        }

        $this->redirect('/');
    }

    /** GET /logout : unifie les 4 anciens fichiers app/<role>/logout.php identiques */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}
