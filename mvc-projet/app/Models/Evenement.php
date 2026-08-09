<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Evenement extends Model
{
    public function create(int $organisateurId, string $nom, string $date, int $idRegion): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO evenements (organisateur_id, nom_evenement, date_evenement, id_region)
            VALUES (:organisateur_id, :nom_evenement, :date_evenement, :id_region)
        ');
        $stmt->execute([
            ':organisateur_id' => $organisateurId,
            ':nom_evenement'   => $nom,
            ':date_evenement'  => $date,
            ':id_region'       => $idRegion,
        ]);
    }

    /** Événements à venir, toutes régions confondues. */
    public function aVenir(): array
    {
        $stmt = $this->pdo->query("
            SELECT e.id_evenement, e.nom_evenement, e.date_evenement,
                   r.id_region, r.nom_region,
                   u.mail_organisation AS mail_organisateur
            FROM evenements e
            INNER JOIN region r ON e.id_region = r.id_region
            INNER JOIN organisation u ON e.organisateur_id = u.id_utilisateur
            WHERE e.date_evenement >= CURDATE()
            ORDER BY e.date_evenement ASC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByOrganisateur(int $organisateurId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT id_evenement, nom_evenement, date_evenement, nom_region
            FROM vue_evenements_organisateur
            WHERE organisateur_id = ?
            ORDER BY date_evenement ASC
        ');
        $stmt->execute([$organisateurId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
