<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class Candidature extends Model
{
    public function findByOffre(int $idOffre): array
    {
        $stmt = $this->pdo->prepare('
            SELECT c.id_candidature, c.statut, c.date_candidature,
                   p.nom_professeur, p.mail_professeur
            FROM candidature c
            JOIN professeur p ON p.id_utilisateur = c.id_professeur
            WHERE c.id_offre = ?
            ORDER BY c.date_candidature DESC
        ');
        $stmt->execute([$idOffre]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** [id_offre => statut] pour toutes les candidatures d'un professeur. */
    public function statutsParProfesseur(int $idProfesseur): array
    {
        $stmt = $this->pdo->prepare('SELECT id_offre, statut FROM candidature WHERE id_professeur = ?');
        $stmt->execute([$idProfesseur]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $statuts = [];
        foreach ($rows as $row) {
            $statuts[$row['id_offre']] = $row['statut'];
        }

        return $statuts;
    }

    public function count(int $idProfesseur): int
    {
        return count($this->statutsParProfesseur($idProfesseur));
    }

    /**
     * @return string 'ok'|'duplicate'|'error'
     */
    public function create(int $idOffre, int $idProfesseur): string
    {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO candidature (id_offre, id_professeur) VALUES (?, ?)'
            );
            $stmt->execute([$idOffre, $idProfesseur]);

            return 'ok';
        } catch (PDOException $e) {
            // 23000 = violation de contrainte UNIQUE (déjà postulé à cette offre)
            return $e->getCode() === '23000' ? 'duplicate' : 'error';
        }
    }

    /** Vérifie que la candidature appartient bien à une offre de cet établissement. */
    public function appartientAEtablissement(int $idCandidature, int $idEtablissement): bool
    {
        $stmt = $this->pdo->prepare('
            SELECT c.id_candidature
            FROM candidature c
            JOIN offre_emploi o ON o.id_offre = c.id_offre
            WHERE c.id_candidature = ? AND o.id_etablissement = ?
        ');
        $stmt->execute([$idCandidature, $idEtablissement]);

        return (bool) $stmt->fetch();
    }

    public function updateStatut(int $idCandidature, string $statut): void
    {
        $stmt = $this->pdo->prepare('UPDATE candidature SET statut = ? WHERE id_candidature = ?');
        $stmt->execute([$statut, $idCandidature]);
    }
}
