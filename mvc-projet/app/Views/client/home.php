<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme scolaire — Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/client.css">
    <style>
        /* ---- Vue "Exercices" : liste des professeurs + exercices d'un professeur ---- */
        .exo-prof-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 14px;
        }

        .exo-prof-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: 14px;
            padding: 16px 18px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .exo-prof-name { margin: 0; font-weight: 700; font-size: 15px; }
        .exo-prof-mail { margin: 0; font-size: 12.5px; color: var(--color-text-muted); word-break: break-all; }

        .exo-prof-count {
            margin: 6px 0 10px;
            font-size: 12px;
            font-weight: 600;
            color: var(--fill-warning);
        }

        .exo-voir-btn {
            align-self: flex-start;
            background: var(--border-strong);
            color: var(--color-surface);
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .exo-voir-btn:hover { filter: brightness(1.15); }

        .exo-exercice-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 4px; }

        .exo-exercice-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 8px;
            border-bottom: 1px solid var(--color-border);
        }
        .exo-exercice-item:last-child { border-bottom: none; }

        .exo-exercice-icon {
            width: 36px; height: 36px; flex-shrink: 0;
            border-radius: 9px;
            background: var(--bg-warning);
            color: var(--fill-warning);
            display: grid; place-items: center; font-size: 16px;
        }

        .exo-exercice-texts { min-width: 0; flex: 1; }
        .exo-exercice-titre { margin: 0; font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .exo-exercice-date { margin: 2px 0 0; font-size: 11.5px; color: var(--color-text-muted); }

        .exo-exercice-actions { display: flex; gap: 8px; flex-shrink: 0; }
        .exo-exercice-actions a {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 12.5px; font-weight: 600; text-decoration: none;
            padding: 6px 10px; border-radius: 7px;
        }
        .exo-action-voir { background: var(--bg-accent); color: var(--fill-accent); }
        .exo-action-telecharger { background: var(--bg-success); color: var(--fill-success); }

        .exo-empty, .exo-loading {
            padding: 30px 10px; text-align: center; color: var(--color-text-muted); font-size: 13.5px;
        }
    </style>
</head>
<body>
<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="logo"><i class="ti ti-school"></i></div>
            <span>Scolarité</span>
        </div>

        <button class="nav-item active">
            <i class="ti ti-layout-grid"></i>
            Tableau de bord
        </button>
        <button onclick="discover('professeurs')" class="nav-item">
            <i class="ti ti-chalkboard"></i>
            Professeurs
        </button>
        <button onclick="discover('etablissements')" class="nav-item">
            <i class="ti ti-building"></i>
            Établissements
        </button>
        <button onclick="see('competitions')" class="nav-item">
            <i class="ti ti-trophy"></i>
            Compétitions
        </button>
        <button onclick="see('evenements')" class="nav-item">
            <i class="ti ti-calendar-event"></i>
            Événements
        </button>
        <button onclick="afficherListeProfsExercices()" class="nav-item">
            <i class="ti ti-file-text"></i>
            Exercices
        </button>
        <button onclick="logout()" class="nav-item messages">
            <i class="ti ti-message-circle"></i>
            Logout
            <span class="dot"></span>
        </button>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="greeting">
                <h1>Bonjour <?= htmlspecialchars($data['nom_client']) ?></h1>
                <p class="loc">Antananarivo, Analamanga</p>
            </div>
            <div class="topbar-actions">
                <button class="icon-btn ghost menu-toggle" id="menuToggle" aria-label="Menu">
                    <i class="ti ti-menu-2"></i>
                </button>
                <div class="search">
                    <i class="ti ti-search"></i>
                    <input type="text" placeholder="Chercher un professeur, une école...">
                </div>
                <button class="icon-btn ghost" aria-label="Notifications">
                    <i class="ti ti-bell"></i>
                </button>
                <button class="icon-btn ghost" aria-label="Profil">
                    <i class="ti ti-user"></i>
                </button>
            </div>
        </div>

        <!-- CONTENU -->
        <div id="contenuPrincipal" class="content">

            <!-- STATS -->
            <div class="stats">
                <div class="stat-card">
                    <p class="label">Professeurs de ta région</p>
                    <p class="value"><?= (int)$stats['professeurs'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Établissements de ta région</p>
                    <p class="value"><?= (int)$stats['etablissements'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Événements à venir</p>
                    <p class="value"><?= (int)$stats['evenements'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Compétitions à venir</p>
                    <p class="value"><?= (int)$stats['competitions'] ?></p>
                </div>
            </div>

            <!-- COLONNES -->
            <div class="columns">

                <!-- LEFT COLUMN -->
                <div>
                    <div class="section-header">
                        <h3>Accès rapide</h3>
                    </div>

                    <div class="modules">

                        <!-- PROFESSEURS -->
                        <div class="module-card" style="--accent-color: var(--fill-warning)">
                            <i class="ti ti-chalkboard"></i>
                            <p class="title">Professeurs</p>
                            <p class="subtitle">Voir les profils et discuter</p>
                            <button onclick="discover('professeurs')" class="cta">Découvrir</button>
                        </div>

                        <!-- ETABLISSEMENTS -->
                        <div class="module-card" style="--accent-color: var(--fill-accent)">
                            <i class="ti ti-building"></i>
                            <p class="title">Établissements</p>
                            <p class="subtitle">S'inscrire dans une école</p>
                            <button onclick="discover('etablissements')" class="cta">Découvrir</button>
                        </div>

                        <!-- EVENEMENTS -->
                        <div class="module-card" style="--accent-color: var(--fill-success)">
                            <i class="ti ti-calendar-event"></i>
                            <p class="title">Événements</p>
                            <p class="subtitle">Participer aux prochains</p>
                            <button onclick="see('evenements')" class="cta">Découvrir</button>
                        </div>

                        <!-- COMPETITIONS -->
                        <div class="module-card" style="--accent-color: var(--fill-danger)">
                            <i class="ti ti-trophy"></i>
                            <p class="title">Compétitions</p>
                            <p class="subtitle">S'inscrire à un concours</p>
                            <button onclick="see('competitions')" class="cta">Découvrir</button>
                        </div>

                        <!-- EXERCICES -->
                        <div class="module-card" style="--accent-color: var(--border-strong)">
                            <i class="ti ti-file-text"></i>
                            <p class="title">Exercices</p>
                            <p class="subtitle">Traiter les sujets reçus</p>
                            <button onclick="afficherListeProfsExercices()" class="cta">Découvrir</button>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<script>
    window.PROFESSEURS_REGION = <?php echo json_encode($professeur, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
    window.ETABLISSEMENTS_REGION = <?php echo json_encode($etablissement, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
    window.ALL_PROFESSEURS = <?php echo json_encode($Allprofesseurs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
    window.ALL_ETABLISSEMENTS = <?php echo json_encode($Alletablissements, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
</script>

<script src="/assets/js/client.js" defer></script>
<script src="/assets/js/smart-search.js" defer></script>

</body>
</html>