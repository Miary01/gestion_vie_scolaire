<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Connexion — Plateforme scolaire</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<!-- Top-right Sign Up button -->
<a href="/signup" class="signup-btn">Créer un compte</a>

<div class="auth-layout">
    <div class="auth-illustration">
        <img src="/assets/images/illustration-education.svg" alt="Illustration d'une salle de classe avec tableau et livre" width="320" height="240">
        <p class="auth-illustration__title">Bienvenue sur la plateforme scolaire</p>
        <p class="auth-illustration__text">Connecte élèves, professeurs, établissements et organisations en un seul endroit.</p>
    </div>

    <div class="container">
        <h2>Connexion</h2>

        <?php if (!empty($erreur)): ?>
            <p class="error-message"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label>Identifiant</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Se connecter</button>
        </form>
    </div>
</div>

</body>
</html>
