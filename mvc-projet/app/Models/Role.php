<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Role extends Model
{
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id_role, nom_role FROM role');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
