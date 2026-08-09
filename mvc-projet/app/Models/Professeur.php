<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Professeur extends Model
{
    public function create(int $idUtilisateur, string $nom, string $prenom, string $mail, int $idRegion): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO professeur (id_utilisateur, nom_professeur, prenom_professeur, mail_professeur, id_region)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([$idUtilisateur, $nom, $prenom, $mail, $idRegion]);
    }

    public function findRegion(int $idUtilisateur): ?int
    {
        $stmt = $this->pdo->prepare('SELECT id_region FROM professeur WHERE id_utilisateur = ?');
        $stmt->execute([$idUtilisateur]);
        $region = $stmt->fetchColumn();

        return $region !== false ? (int) $region : null;
    }

    /** Tous les professeurs, avec le nombre d'exercices envoyés par chacun. */
    public function allWithExerciceCount(): array
    {
        $stmt = $this->pdo->query('
            SELECT p.id_utilisateur AS id_professeur, p.nom_professeur, p.mail_professeur,
                   (SELECT COUNT(*) FROM exercice e WHERE e.id_professeur = p.id_utilisateur) AS nb_exercices
            FROM professeur p
            ORDER BY p.nom_professeur
        ');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT nom_professeur, mail_professeur FROM professeur');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByRegion(int $idRegion): array
    {
        $stmt = $this->pdo->prepare('SELECT nom_professeur, mail_professeur FROM professeur WHERE id_region = ?');
        $stmt->execute([$idRegion]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
