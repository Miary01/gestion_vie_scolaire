<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Client extends Model
{
    public function create(int $idUtilisateur, string $nom, string $prenom, string $mail, int $idRegion): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO client (id_utilisateur, nom_client, prenom_client, mail_client, id_region)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([$idUtilisateur, $nom, $prenom, $mail, $idRegion]);
    }

    public function findRegionByUtilisateur(int $idUtilisateur): ?int
    {
        $stmt = $this->pdo->prepare('SELECT id_region FROM client WHERE id_utilisateur = ?');
        $stmt->execute([$idUtilisateur]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int) $row['id_region'] : null;
    }

    /** Profil complet du client connecté (vue utilisateur_client). */
    public function findProfil(int $idUtilisateur): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateur_client WHERE id_utilisateur = ?');
        $stmt->execute([$idUtilisateur]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
