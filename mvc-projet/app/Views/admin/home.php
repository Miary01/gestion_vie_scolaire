<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Administrateur — Tableau de bord</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
<link rel="stylesheet" href="/assets/css/theme.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
<div class="app">

  <aside class="sidebar">
    <div class="brand">
      <div class="logo" style="background:var(--fill-danger);"><i class="ti ti-shield-check"></i></div>
      <span>Administration</span>
    </div>

    <a href="#apercu" class="nav-item active"><i class="ti ti-layout-grid"></i>Vue d'ensemble</a>
    <a href="#utilisateurs" class="nav-item"><i class="ti ti-users"></i>Utilisateurs</a>

    <button class="nav-item messages"><i class="ti ti-message-circle"></i>Messages<span class="dot"></span></button>
    <a href="/logout" class="nav-item"><i class="ti ti-logout"></i>Déconnexion</a>
  </aside>

  <div class="main">
    <div class="topbar">
      <div class="greeting">
        <h1>Bonjour, Admin</h1>
        <p class="loc">Panneau d'administration</p>
      </div>
      <div class="topbar-actions">
        <button class="icon-btn ghost menu-toggle" id="menuToggle" aria-label="Menu"><i class="ti ti-menu-2"></i></button>
        <div class="search">
          <i class="ti ti-search"></i>
          <input type="text" placeholder="Chercher un utilisateur par nom, login ou rôle...">
        </div>
        <button class="icon-btn ghost" aria-label="Notifications"><i class="ti ti-bell"></i></button>
        <button class="icon-btn ghost" aria-label="Profil"><i class="ti ti-user"></i></button>
      </div>
    </div>

    <div class="content">

      <?php if (!empty($message)): ?>
        <div class="card" style="background:var(--bg-accent); color:var(--text-accent); margin-bottom:16px; font-size:14px;">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <div id="apercu">
        <div class="section-header"><h3>Vue d'ensemble de la plateforme</h3></div>
        <div class="stats">
          <div class="stat-card">
            <p class="label">Clients</p>
            <p class="value"><?= (int)$stats['clients'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Professeurs</p>
            <p class="value"><?= (int)$stats['professeurs'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Établissements</p>
            <p class="value"><?= (int)$stats['etablissements'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Organisations</p>
            <p class="value"><?= (int)$stats['organisations'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Offres d'emploi publiées</p>
            <p class="value"><?= (int)$stats['offres'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Candidatures</p>
            <p class="value"><?= (int)$stats['candidatures'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Exercices envoyés</p>
            <p class="value"><?= (int)$stats['exercices'] ?></p>
          </div>
          <div class="stat-card">
            <p class="label">Événements & compétitions</p>
            <p class="value"><?= (int)($stats['evenements'] + $stats['competitions']) ?></p>
          </div>
        </div>
      </div>

      <div class="columns" id="utilisateurs">

        <div style="grid-column: 1 / -1;">
          <div class="section-header">
            <h3>Tous les utilisateurs (<?= count($utilisateurs) ?>)</h3>
          </div>
          <div class="card" style="padding:0;" data-search-container>

            <?php if (empty($utilisateurs)): ?>
              <p style="padding:16px; color:var(--text-secondary);">Aucun utilisateur pour le moment.</p>
            <?php else: ?>
              <?php foreach ($utilisateurs as $u): ?>
                <?php
                    $initiales = strtoupper(substr(trim((string)$u['nom']), 0, 2)) ?: '??';
                    $texteRecherche = strtolower($u['nom'] . ' ' . $u['login'] . ' ' . $u['nom_role'] . ' ' . $u['email']);
                ?>
                <div class="list-item" data-search-item data-search-text="<?= htmlspecialchars($texteRecherche) ?>">
                  <div class="thumb" style="background:var(--bg-accent);color:var(--text-accent);"><?= htmlspecialchars($initiales) ?></div>
                  <div class="info">
                    <p class="name"><?= htmlspecialchars($u['nom']) ?></p>
                    <p class="meta">
                      Login : <?= htmlspecialchars($u['login']) ?>
                      <?php if (!empty($u['email'])): ?> · <?= htmlspecialchars($u['email']) ?><?php endif; ?>
                    </p>
                  </div>
                  <span class="badge badge-role"><?= htmlspecialchars($u['nom_role']) ?></span>
                  <div class="actions">
                    <form method="POST" action="/admin/utilisateur/supprimer"
                          onsubmit="return confirm('Supprimer définitivement le compte « <?= htmlspecialchars(addslashes($u['nom'])) ?> » ?');">
                      <input type="hidden" name="id_utilisateur" value="<?= (int)$u['id_utilisateur'] ?>">
                      <button type="submit" class="reject">Supprimer</button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>

          </div>
        </div>

        <?php if (!empty($repartition)): ?>
        <div style="grid-column: 1 / -1;">
          <div class="section-header"><h3>Répartition par rôle</h3></div>
          <div class="card" style="padding:0;">
            <?php foreach ($repartition as $r): ?>
              <div class="list-item">
                <div class="info">
                  <p class="name"><?= htmlspecialchars($r['nom_role']) ?></p>
                </div>
                <span class="badge badge-role"><?= (int)$r['total'] ?> compte(s)</span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<script src="/assets/js/admin.js" defer></script>
<script src="/assets/js/smart-search.js" defer></script>
</body>
</html>
