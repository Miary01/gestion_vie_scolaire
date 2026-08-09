<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Region extends Model
{
    public function search(string $query, int $limit = 10): array
    {
        $stmt = $this->pdo->prepare('
            SELECT id_region, nom_region
            FROM region
            WHERE nom_region LIKE ?
            LIMIT ' . (int) $limit
        );
        $stmt->execute(['%' . $query . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id_region, nom_region FROM region ORDER BY nom_region ASC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
