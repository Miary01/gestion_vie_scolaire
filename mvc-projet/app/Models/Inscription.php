<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Inscription extends Model
{
    public const TYPE_COMPETITION = 1;
    public const TYPE_EVENEMENT = 2;

    public function existe(int $type, int $id, int $clientId): bool
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM inscription WHERE type = :type AND id = :id AND client_id = :client_id
        ');
        $stmt->execute([':type' => $type, ':id' => $id, ':client_id' => $clientId]);

        return (bool) $stmt->fetch();
    }

    public function create(int $type, int $id, int $clientId): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO inscription (type, id, client_id) VALUES (:type, :id, :client_id)
        ');
        $stmt->execute([':type' => $type, ':id' => $id, ':client_id' => $clientId]);
    }

    /** Participants inscrits à une compétition (type=1) ou un événement (type=2). */
    public function participants(int $type, int $id, int $organisateurId): array
    {
        if ($type === self::TYPE_COMPETITION) {
            $sql = '
                SELECT DISTINCT
                    i.client_id, cl.nom_client, cl.mail_client AS mail,
                    vc.nom_competition AS nom_activite, vc.date_competition AS date_activite
                FROM inscription i
                INNER JOIN utilisateur_client cl ON cl.id_utilisateur = i.client_id
                INNER JOIN vue_competitions_organisateur vc ON vc.id_competition = i.id
                WHERE i.type = 1 AND i.id = ? AND vc.organisateur_id = ?
            ';
        } else {
            $sql = '
                SELECT DISTINCT
                    i.client_id, cl.nom_client, cl.mail_client AS mail,
                    ve.nom_evenement AS nom_activite, ve.date_evenement AS date_activite
                FROM inscription i
                INNER JOIN utilisateur_client cl ON cl.id_utilisateur = i.client_id
                INNER JOIN vue_evenements_organisateur ve ON ve.id_evenement = i.id
                WHERE i.type = 2 AND i.id = ? AND ve.organisateur_id = ?
            ';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id, $organisateurId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
