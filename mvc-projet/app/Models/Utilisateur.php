<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Utilisateur extends Model
{
    public function findByLogin(string $login): array|false
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateur WHERE login = ?');
        $stmt->execute([$login]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countByRole(int $idRole): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM utilisateur WHERE id_role = ?');
        $stmt->execute([$idRole]);

        return (int) $stmt->fetchColumn();
    }

    public function create(string $login, string $plainPassword, int $idRole): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO utilisateur (login, password, id_role) VALUES (?, ?, ?)'
        );
        $stmt->execute([
            $login,
            password_hash($plainPassword, PASSWORD_DEFAULT),
            $idRole,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function verifyPassword(string $plainPassword, string $hash): bool
    {
        return password_verify($plainPassword, $hash);
    }

    /**
     * Tous les utilisateurs avec leur rôle et un nom/email lisible, quel que
     * soit leur type de compte (client, professeur, établissement,
     * organisation, admin).
     */
    public function allWithDetails(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                u.id_utilisateur, u.login, u.id_role, r.nom_role,
                COALESCE(c.nom_client, p.nom_professeur, e.nom_etablissement, o.nom_organisation, 'Administrateur') AS nom,
                COALESCE(c.mail_client, p.mail_professeur, e.mail_etablissement, o.mail_organisation, '') AS email
            FROM utilisateur u
            JOIN role r ON r.id_role = u.id_role
            LEFT JOIN client c ON c.id_utilisateur = u.id_utilisateur
            LEFT JOIN professeur p ON p.id_utilisateur = u.id_utilisateur
            LEFT JOIN EtabScolaire e ON e.id_utilisateur = u.id_utilisateur
            LEFT JOIN organisation o ON o.id_utilisateur = u.id_utilisateur
            ORDER BY u.id_utilisateur DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un compte utilisateur.
     *
     * @return string 'ok'|'introuvable'|'liee' (données liées empêchant la suppression)
     */
    public function delete(int $idUtilisateur): string
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM utilisateur WHERE id_utilisateur = ?');
            $stmt->execute([$idUtilisateur]);

            return $stmt->rowCount() > 0 ? 'ok' : 'introuvable';
        } catch (\PDOException $e) {
            // Contrainte de clé étrangère : le compte a des données liées
            // (exercices, offres, candidatures, ...) qui empêchent la suppression.
            return 'liee';
        }
    }
}
