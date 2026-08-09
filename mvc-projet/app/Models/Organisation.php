<?php

namespace App\Models;

use App\Core\Model;

class Organisation extends Model
{
    public function create(int $idUtilisateur, string $nom, string $mail): void
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO organisation (id_utilisateur, nom_organisation, mail_organisation)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$idUtilisateur, $nom, $mail]);
    }
}
