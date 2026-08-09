<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class OffreEmploi extends Model
{
    public function create(int $idEtablissement, string $titre, string $description): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO offre_emploi (id_etablissement, titre, description) VALUES (?, ?, ?)'
        );
        $stmt->execute([$idEtablissement, $titre, $description]);
    }

    public function findByEtablissement(int $idEtablissement): array
    {
        $stmt = $this->pdo->prepare('
            SELECT id_offre, titre, description, date_publication, statut
            FROM offre_emploi
            WHERE id_etablissement = ?
            ORDER BY date_publication DESC
        ');
        $stmt->execute([$idEtablissement]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Offres ouvertes, publiées par les établissements d'une région donnée. */
    public function ouvertesParRegion(int $idRegion): array
    {
        $stmt = $this->pdo->prepare("
            SELECT o.id_offre, o.titre, o.description, o.date_publication,
                   e.nom_etablissement, e.mail_etablissement
            FROM offre_emploi o
            JOIN EtabScolaire e ON e.id_utilisateur = o.id_etablissement
            WHERE o.statut = 'ouverte'
              AND e.id_region = ?
            ORDER BY o.date_publication DESC
        ");
        $stmt->execute([$idRegion]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Vérifie qu'une offre est ouverte et appartient à la région donnée. */
    public function estOuverteDansRegion(int $idOffre, int $idRegion): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT o.id_offre
            FROM offre_emploi o
            JOIN EtabScolaire e ON e.id_utilisateur = o.id_etablissement
            WHERE o.id_offre = ?
              AND o.statut = 'ouverte'
              AND e.id_region = ?
        ");
        $stmt->execute([$idOffre, $idRegion]);

        return (bool) $stmt->fetch();
    }
}
