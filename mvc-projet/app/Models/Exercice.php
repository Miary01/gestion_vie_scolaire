<?php

namespace App\Models;

use App\Core\Model;
use DateTime;
use PDO;

class Exercice extends Model
{
    public function countByProfesseur(int $idProfesseur): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM exercice WHERE id_professeur = ?');
        $stmt->execute([$idProfesseur]);

        return (int) $stmt->fetchColumn();
    }

    public function derniersByProfesseur(int $idProfesseur, int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_exercice, fichier, date_envoi FROM exercice
             WHERE id_professeur = ? ORDER BY date_envoi DESC LIMIT ' . (int) $limit
        );
        $stmt->execute([$idProfesseur]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allByProfesseur(int $idProfesseur): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_exercice, fichier, date_envoi FROM exercice
             WHERE id_professeur = ? ORDER BY date_envoi DESC'
        );
        $stmt->execute([$idProfesseur]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findFichierById(int $idExercice): ?string
    {
        $stmt = $this->pdo->prepare('SELECT fichier FROM exercice WHERE id_exercice = ?');
        $stmt->execute([$idExercice]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['fichier'] ?? null;
    }

    public function create(int $idProfesseur, string $nomFichier): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercice (id_professeur, fichier) VALUES (?, ?)');
        $stmt->execute([$idProfesseur, $nomFichier]);
    }

    /** Déduit un titre lisible depuis "ex<uniqid>_<nom-original>.pdf". */
    public static function titreDepuisFichier(string $fichier): string
    {
        $sansExtension = pathinfo($fichier, PATHINFO_FILENAME);
        $sansPrefixe = preg_replace('/^ex[a-z0-9.]+_/i', '', $sansExtension);
        $titre = trim(str_replace(['-', '_'], ' ', $sansPrefixe));

        return $titre !== '' ? $titre : 'Exercice sans titre';
    }

    public static function formatDate(string $dateEnvoi): string
    {
        return (new DateTime($dateEnvoi))->format('d/m/Y à H:i');
    }
}
