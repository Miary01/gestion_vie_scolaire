<?php

namespace App\Models;

use App\Core\Model;

class Statistique extends Model
{
    /** Compte le nombre de lignes d'une table, 0 si la table n'existe pas encore. */
    private function count(string $table): int
    {
        try {
            return (int) $this->pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    public function globales(): array
    {
        return [
            'clients'         => $this->count('client'),
            'professeurs'     => $this->count('professeur'),
            'etablissements'  => $this->count('EtabScolaire'),
            'organisations'   => $this->count('organisation'),
            'offres'          => $this->count('offre_emploi'),
            'candidatures'    => $this->count('candidature'),
            'exercices'       => $this->count('exercice'),
            'evenements'      => $this->count('evenements'),
            'competitions'    => $this->count('competition'),
        ];
    }

    /** Répartition des utilisateurs par rôle, pour un petit graphique. */
    public function repartitionParRole(): array
    {
        try {
            $stmt = $this->pdo->query('
                SELECT r.nom_role, COUNT(u.id_utilisateur) AS total
                FROM role r
                LEFT JOIN utilisateur u ON u.id_role = r.id_role
                GROUP BY r.id_role, r.nom_role
                ORDER BY total DESC
            ');

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }
}
