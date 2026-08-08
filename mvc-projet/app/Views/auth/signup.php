<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Créer un compte — Vie Scolaire</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
<link rel="stylesheet" href="/assets/css/theme.css">
<link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>

<div class="auth-shell">

    <!-- Volet visuel -->
    <div class="auth-visual" style="background-image:url('https://images.unsplash.com/photo-1590012314607-cda9d9b699ae?auto=format&fit=crop&w=1400&q=80');">
        <div class="auth-brand">
            <span class="auth-brand__mark"><i class="ti ti-school"></i></span>
            Vie Scolaire
        </div>

        <div class="auth-visual__body">
            <span class="auth-visual__eyebrow"><i class="ti ti-rocket"></i> Rejoignez la plateforme</span>
            <h1>Créez votre compte en quelques instants.</h1>
            <p>Que vous soyez élève, professeur, établissement ou organisation, votre espace s'adapte automatiquement à votre profil.</p>

            <ul class="auth-features">
                <li><i class="ti ti-user-check"></i> Inscription guidée selon votre rôle</li>
                <li><i class="ti ti-map-pin"></i> Recherche de région intégrée</li>
                <li><i class="ti ti-shield-check"></i> Accès sécurisé et personnalisé</li>
            </ul>
        </div>

        <div class="auth-visual__footer">
            <span>Photo — Joshua Hoehne / Unsplash</span>
        </div>
    </div>

    <!-- Volet formulaire -->
    <div class="auth-panel">
        <div class="auth-panel__inner">

            <div class="auth-panel__top">
                <a href="/" class="auth-pill-link">
                    <i class="ti ti-login-2"></i> Se connecter
                </a>
            </div>

            <div class="auth-panel__header">
                <h2>Créer un compte</h2>
                <p>Renseignez vos informations pour démarrer.</p>
            </div>

            <form action="/signup" method="POST">
                <div class="form-group">
                    <label>Identifiant</label>
                    <div class="input-icon">
                        <i class="ti ti-user"></i>
                        <input type="text" name="login" required autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <div class="input-icon">
                        <i class="ti ti-lock"></i>
                        <input type="password" name="password" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group">
                    <label>Rôle</label>
                    <div class="input-icon">
                        <i class="ti ti-id-badge-2"></i>
                        <select id="roleSelect" name="role" required>
                            <option value="">Choisir un rôle</option>

                            <?php foreach ($roles as $role): ?>
                                <!-- supprime l'option admin si le nombre d'admin depasse 20-->
                                <?php if ($role['nom_role'] == 'admin' && $adminDisabled): ?>
                                    <option value="admin" disabled>
                                        Admin (limite atteinte)
                                    </option>
                                <?php else: ?>
                                    <option value="<?= $role['id_role']; ?>">
                                        <?= $role['nom_role']; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <!--Pour l'utilisateur admin-->
                <fieldset id="adminFields" class="role-section" style="display:none;">
                    <legend>Administrateur</legend>
                    <div class="form-group">
                        <label>Clé admin</label>
                        <div class="input-icon">
                            <i class="ti ti-key"></i>
                            <input type="password" name="admin_key" id="adminKey">
                        </div>
                    </div>
                </fieldset>

                <!-- Pour le client -->
                <fieldset id="clientFields" class="role-section" style="display:none;">
                    <legend>Élève / Client</legend>

                    <div class="form-group">
                        <label>Nom</label>
                        <div class="input-icon">
                            <i class="ti ti-signature"></i>
                            <input type="text" name="nom_client">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Prénom</label>
                        <div class="input-icon">
                            <i class="ti ti-signature"></i>
                            <input type="text" name="prenom_client">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-icon">
                            <i class="ti ti-mail"></i>
                            <input type="email" name="email_client">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Région</label>
                        <div class="input-icon">
                            <i class="ti ti-map-pin"></i>
                            <input type="text" class="regionInput" autocomplete="off">
                        </div>
                        <input type="hidden" name="region_client" class="regionId">
                        <div class="suggestions"></div>
                    </div>

                </fieldset>

                <!-- Pour professeur -->
                <fieldset id="professeurFields" class="role-section" style="display:none;">
                    <legend>Professeur</legend>

                    <div class="form-group">
                        <label>Nom</label>
                        <div class="input-icon">
                            <i class="ti ti-signature"></i>
                            <input type="text" name="nom_professeur">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Prénom</label>
                        <div class="input-icon">
                            <i class="ti ti-signature"></i>
                            <input type="text" name="prenom_professeur">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-icon">
                            <i class="ti ti-mail"></i>
                            <input type="email" name="email_professeur">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Région</label>
                        <div class="input-icon">
                            <i class="ti ti-map-pin"></i>
                            <input type="text" class="regionInput" autocomplete="off">
                        </div>
                        <input type="hidden" name="region_professeur" class="regionId">
                        <div class="suggestions"></div>
                    </div>

                </fieldset>

                <!-- Pour l'etablissement scolaire-->
                <fieldset id="etablissementFields" class="role-section" style="display:none;">
                    <legend>Établissement scolaire</legend>

                    <div class="form-group">
                        <label>Nom</label>
                        <div class="input-icon">
                            <i class="ti ti-building"></i>
                            <input type="text" name="nom_etablissement">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-icon">
                            <i class="ti ti-mail"></i>
                            <input type="email" name="email_etablissement">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Région</label>
                        <div class="input-icon">
                            <i class="ti ti-map-pin"></i>
                            <input type="text" class="regionInput" autocomplete="off">
                        </div>
                        <input type="hidden" name="region_etablissement" class="regionId">
                        <div class="suggestions"></div>
                    </div>

                </fieldset>

                <!-- Pour Organisation-->
                <fieldset id="organisationFields" class="role-section" style="display:none;">
                    <legend>Organisation</legend>

                    <div class="form-group">
                        <label>Nom</label>
                        <div class="input-icon">
                            <i class="ti ti-building-community"></i>
                            <input type="text" name="nom_organisation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-icon">
                            <i class="ti ti-mail"></i>
                            <input type="email" name="email_organisation">
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="auth-submit">
                    Créer mon compte <i class="ti ti-arrow-right"></i>
                </button>
            </form>

            <p class="auth-panel__switch">
                Déjà inscrit ? <a href="/">Connectez-vous</a>
            </p>
        </div>
    </div>

</div>

<script src="/assets/js/dyn.js" defer></script>
</body>
</html>
