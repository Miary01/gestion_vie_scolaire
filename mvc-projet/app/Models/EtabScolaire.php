<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class EtabScolaire extends Model
{
    public function create(int $idUtilisateur, string $nom, string $mail, int $idRegion): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO EtabScolaire (id_utilisateur, nom_etablissement, mail_etablissement, id_region)
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([$idUtilisateur, $nom, $mail, $idRegion]);
    }

    public function findByUtilisateur(int $idUtilisateur): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM EtabScolaire WHERE id_utilisateur = ?');
        $stmt->execute([$idUtilisateur]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByRegion(int $idRegion): array
    {
        $stmt = $this->pdo->prepare('SELECT nom_etablissement, mail_etablissement FROM EtabScolaire WHERE id_region = ?');
        $stmt->execute([$idRegion]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT nom_etablissement, mail_etablissement FROM EtabScolaire');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
