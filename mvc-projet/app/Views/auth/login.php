<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<!-- Top-right Sign Up button -->
<a href="/signup" class="signup-btn">Sign up</a>

<div class="container">
    <h2>Login</h2>

    <?php if (!empty($erreur)): ?>
        <p class="error-message"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form action="/login" method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="login-btn">Login</button>
    </form>
</div>

</body>
</html>
