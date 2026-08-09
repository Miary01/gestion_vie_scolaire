<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up</title>
<link rel="stylesheet" href="/assets/css/theme.css">
<link rel="stylesheet" href="/assets/css/sign.css">
</head>
<body>

<!-- Top-left Login button -->
<a href="/" class="login-link">Login</a>

<div class="container">
    <h2>Sign Up</h2>

    <form action="/signup" method="POST">
        <div class="form-group">
            <label>Login</label>
            <input type="text" name="login" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Role</label>
             <select id="roleSelect" name="role" required>
                <option value=""> Role </option>

                 <?php foreach ($roles as $role): ?>
                    <!-- supprime l'option admin si le nombre d'admin depasse 20-->
                    <?php if ($role['nom_role'] == 'admin' && $adminDisabled): ?>
                        <option value="admin" disabled>
                            Admin (limit reached)
                        </option>
                    <?php else: ?>
                        <option value="<?= $role['id_role']; ?>">
                            <?= $role['nom_role']; ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>

            </select>
               <!-- texte dynamique -->
        </div>
    <!--Pour l'utilisateur admin-->
    <div id="adminFields" style="display:none;">
        <label>Admin Key</label>
        <input type="password" name="admin_key" id="adminKey">
    </div>
    <!-- Pour le client -->
    <div id="clientFields" style="display:none; margin-top:15px;">

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom_client">
        </div>

        <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom_client">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_client">
        </div>

        <div class="form-group" style="position:relative;">
            <label>Région</label>
            <input type="text" class="regionInput" autocomplete="off">
            <input type="hidden" name="region_client" class="regionId">
        <div class="suggestions"></div>
        </div>

    </div>
    <!-- Pour professeur -->
    <div id="professeurFields" style="display:none; margin-top:15px;">

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom_professeur">
        </div>

        <div class="form-group">
            <label>Prénom</label>
            <input type="text" name="prenom_professeur">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_professeur">
        </div>

        <div class="form-group" style="position:relative;">
            <label>Région</label>
            <input type="text"  class="regionInput" autocomplete="off">
            <input type="hidden" name="region_professeur" class="regionId">
        <div class="suggestions"></div>
        </div>

    </div>
    <!-- Pour l'etablissement scolaire-->
    <div id="etablissementFields" style="display:none; margin-top:15px;">

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom_etablissement">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_etablissement">
        </div>

        <div class="form-group" style="position:relative;">
            <label>Région</label>
            <input type="text" class="regionInput" autocomplete="off">
            <input type="hidden" name="region_etablissement" class="regionId">
            <div class="suggestions"></div>
        </div>

    </div>
 <!-- Pour Organisation-->
    <div id="organisationFields" style="display:none; margin-top:15px;">

        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom_organisation">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_organisation">
        </div>
    </div>
        <button type="submit" class="submit-btn">Create Account</button>
    </form>
</div>
<script src="/assets/js/dyn.js" defer></script>
</body>
</html>
