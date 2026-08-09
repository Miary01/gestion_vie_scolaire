<?php

namespace App\Core;

use PDO;
use SessionHandlerInterface;

/**
 * Sessions PHP stockées en base de données (table `sessions`).
 * Reprend à l'identique la logique de l'ancien sessionHandler.php,
 * simplement déplacée dans l'espace de noms App\Core.
 */
class SessionHandlerDB implements SessionHandlerInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string
    {
        $stmt = $this->pdo->prepare('SELECT data FROM sessions WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['data'] : '';
    }

    public function write($id, $data): bool
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO sessions (id, data, timestamp, id_utilisateur)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                data = VALUES(data),
                timestamp = VALUES(timestamp)
        ');

        $userId = $_SESSION['id_utilisateur'] ?? 0;

        return $stmt->execute([$id, $data, time(), $userId]);
    }

    public function destroy($id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM sessions WHERE id = ?');

        return $stmt->execute([$id]);
    }

    public function gc($max_lifetime): int|false
    {
        $old = time() - $max_lifetime;
        $stmt = $this->pdo->prepare('DELETE FROM sessions WHERE timestamp < ?');
        $stmt->execute([$old]);

        return $stmt->rowCount();
    }
}
