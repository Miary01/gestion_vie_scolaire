<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Connexion — Vie Scolaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>

<div class="auth-shell">

    <!-- Volet visuel -->
    <div class="auth-visual" style="background-image:url('https://images.unsplash.com/photo-1741699428220-65f37f3fbbcb?auto=format&fit=crop&w=1400&q=80');">
        <div class="auth-brand">
            <span class="auth-brand__mark"><i class="ti ti-school"></i></span>
            Vie Scolaire
        </div>

        <div class="auth-visual__body">
            <span class="auth-visual__eyebrow"><i class="ti ti-sparkles"></i> Plateforme unifiée</span>
            <h1>Toute la vie scolaire, réunie au même endroit.</h1>
            <p>Élèves, professeurs, établissements et organisations gèrent inscriptions, offres, exercices et événements depuis un seul tableau de bord.</p>

            <ul class="auth-features">
                <li><i class="ti ti-users-group"></i> Un espace dédié pour chaque profil</li>
                <li><i class="ti ti-calendar-event"></i> Compétitions et événements en un clic</li>
                <li><i class="ti ti-file-upload"></i> Partage d'exercices et de candidatures</li>
            </ul>
        </div>

        <div class="auth-visual__footer">
            <span>Photo — Zoshua Colah / Unsplash</span>
        </div>
    </div>

    <!-- Volet formulaire -->
    <div class="auth-panel">
        <div class="auth-panel__inner">

            <div class="auth-panel__top">
                <a href="/signup" class="auth-pill-link">
                    <i class="ti ti-user-plus"></i> Créer un compte
                </a>
            </div>

            <div class="auth-panel__header">
                <h2>Bon retour</h2>
                <p>Connectez-vous pour accéder à votre espace.</p>
            </div>

            <?php if (!empty($erreur)): ?>
                <p class="error-message"><i class="ti ti-alert-circle"></i> <?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>

            <form action="/login" method="POST">
                <div class="form-group">
                    <label>Nom d'utilisateur</label>
                    <div class="input-icon">
                        <i class="ti ti-user"></i>
                        <input type="text" name="username" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-icon">
                        <i class="ti ti-lock"></i>
                        <input type="password" name="password" required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="auth-submit">
                    Se connecter <i class="ti ti-arrow-right"></i>
                </button>
            </form>

            <p class="auth-panel__switch">
                Pas encore de compte ? <a href="/signup">Inscrivez-vous</a>
            </p>
        </div>
    </div>

</div>

</body>
</html>
