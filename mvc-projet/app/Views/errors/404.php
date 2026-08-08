<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Page introuvable — Vie Scolaire</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--color-bg);
            font-family: var(--font-base);
            color: var(--color-text);
            padding: 24px;
        }
        .error-card {
            background: var(--color-surface);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 56px 48px;
            text-align: center;
            max-width: 440px;
            width: 100%;
        }
        .error-card i {
            width: 72px;
            height: 72px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: var(--color-primary-soft);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
        }
        .error-card h1 {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .error-card p {
            color: var(--color-text-muted);
            font-size: 14.5px;
            margin-bottom: 26px;
        }
        .error-card a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            background: var(--color-primary);
            color: #fff;
            padding: 12px 22px;
            border-radius: var(--radius-pill);
            font-weight: 700;
            font-size: 14px;
            transition: 0.2s;
        }
        .error-card a:hover { background: var(--color-primary-dark); }
    </style>
</head>
<body>
    <div class="error-card">
        <i class="ti ti-map-pin-off"></i>
        <h1>Page introuvable</h1>
        <p>Cette page n'existe pas ou a été déplacée.</p>
        <a href="/"><i class="ti ti-home"></i> Retour à l'accueil</a>
    </div>
</body>
</html>
