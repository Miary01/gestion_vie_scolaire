<?php

namespace App\Models;

use App\Core\Model;

class Admin extends Model
{
    public function create(int $idUtilisateur): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO admin (id_utilisateur) VALUES (?)');
        $stmt->execute([$idUtilisateur]);
    }
}
