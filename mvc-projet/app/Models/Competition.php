<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Competition extends Model
{
    public function create(int $organisateurId, string $nom, string $date, int $idRegion): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO competition (organisateur_id, nom_competition, date_competition, id_region)
            VALUES (:organisateur_id, :nom_competition, :date_competition, :id_region)
        ');
        $stmt->execute([
            ':organisateur_id'  => $organisateurId,
            ':nom_competition'  => $nom,
            ':date_competition' => $date,
            ':id_region'        => $idRegion,
        ]);
    }

    public function aVenir(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.id_competition, c.nom_competition, c.date_competition,
                   r.id_region, r.nom_region,
                   u.mail_organisation AS mail_organisateur
            FROM competition c
            INNER JOIN region r ON c.id_region = r.id_region
            INNER JOIN organisation u ON c.organisateur_id = u.id_utilisateur
            WHERE c.date_competition >= CURDATE()
            ORDER BY c.date_competition ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByOrganisateur(int $organisateurId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT c.id_competition, c.nom_competition, c.date_competition, r.nom_region
            FROM competition c
            JOIN region r ON r.id_region = c.id_region
            WHERE c.organisateur_id = ?
            ORDER BY c.date_competition ASC
        ');
        $stmt->execute([$organisateurId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
