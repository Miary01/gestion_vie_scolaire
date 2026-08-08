<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Espace Établissement — Gestion du personnel</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
<link rel="stylesheet" href="/assets/css/theme.css">
<link rel="stylesheet" href="/assets/css/etablissement.css">
<style>
    /* ==================================================
       RECRUTEMENT — styles dédiés
       ================================================== */
    .rec-intro { color: var(--color-text-muted); font-size: 13.5px; margin: 4px 0 18px; }

    .rec-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }
    .rec-stat-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: 16px 18px;
        box-shadow: var(--shadow-sm);
    }
    .rec-stat-card .rec-stat-label { font-size: 12.5px; color: var(--color-text-muted); margin: 0 0 4px; }
    .rec-stat-card .rec-stat-value { font-size: 24px; font-weight: 700; color: var(--color-text); margin: 0; }
    .rec-stat-card.rec-stat-card--accent .rec-stat-value { color: var(--color-primary); }
    .rec-stat-card.rec-stat-card--warning .rec-stat-value { color: var(--color-warning); }
    .rec-stat-card.rec-stat-card--success .rec-stat-value { color: var(--color-success); }

    .rec-form {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: 20px 22px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 26px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .rec-form h4 {
        margin: 0;
        font-family: var(--font-base);
        font-weight: 600;
        font-size: 17px;
        color: var(--color-text);
    }

    .rec-field { display: flex; flex-direction: column; gap: 6px; }
    .rec-field label { font-size: 13px; font-weight: 600; color: var(--color-text); }

    .rec-field input[type="text"],
    .rec-field textarea {
        font-family: inherit;
        font-size: 14px;
        padding: 10px 12px;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-sm);
        background: var(--color-bg);
        color: var(--color-text);
        outline: none;
        resize: vertical;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .rec-field input[type="text"]:focus,
    .rec-field textarea:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px var(--color-primary-light);
    }

    .rec-submit {
        align-self: flex-start;
        background: var(--color-primary);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: 10px 18px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s ease;
    }
    .rec-submit:hover { background: var(--color-primary-dark); }

    .rec-notice {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 11px 14px;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 18px;
        background: var(--color-success-light);
        color: var(--color-success);
    }

    .rec-offres { display: flex; flex-direction: column; gap: 16px; }

    .rec-offre-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
    }

    .rec-offre-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .rec-offre-titre { margin: 0; font-family: var(--font-base); font-weight: 600; font-size: 16.5px; color: var(--color-text); }
    .rec-offre-date { margin: 2px 0 0; font-size: 11.5px; color: var(--color-text-muted); }
    .rec-offre-desc { margin: 0 0 12px; font-size: 13.5px; color: var(--color-text-muted); line-height: 1.5; }

    .rec-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: var(--radius-pill);
        white-space: nowrap;
    }
    .rec-badge--ouverte { background: var(--color-success-light); color: var(--color-success); }
    .rec-badge--fermee  { background: var(--color-surface-alt); color: var(--color-text-muted); }

    .rec-candidatures { border-top: 1px dashed var(--color-border); margin-top: 8px; padding-top: 12px; }
    .rec-candidatures-titre { margin: 0 0 8px; font-size: 12.5px; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: .05em; }

    .rec-candidature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--color-border);
        flex-wrap: wrap;
    }
    .rec-candidature-item:last-child { border-bottom: none; }

    .rec-cand-avatar {
        width: 34px; height: 34px; flex-shrink: 0; border-radius: 50%;
        background: var(--color-info); color: #fff;
        display: grid; place-items: center; font-size: 13px; font-weight: 700;
    }

    .rec-cand-texts { flex: 1; min-width: 160px; }
    .rec-cand-nom { margin: 0; font-size: 13.5px; font-weight: 600; color: var(--color-text); }
    .rec-cand-mail { margin: 1px 0 0; font-size: 12px; color: var(--color-text-muted); word-break: break-all; }

    .rec-cand-statut {
        font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: var(--radius-pill); flex-shrink: 0;
    }
    .rec-cand-statut--en_attente { background: var(--color-warning-light); color: var(--color-warning); }
    .rec-cand-statut--acceptee   { background: var(--color-success-light); color: var(--color-success); }
    .rec-cand-statut--refusee    { background: var(--color-danger-light); color: var(--color-danger); }

    .rec-cand-actions { display: flex; gap: 6px; flex-shrink: 0; flex-wrap: wrap; }
    .rec-cand-actions form { margin: 0; }
    .rec-cand-btn {
        border: none; border-radius: var(--radius-sm); padding: 6px 10px;
        font-size: 12px; font-weight: 700; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
    }
    .rec-cand-btn--accepter { background: var(--color-success-light); color: var(--color-success); }
    .rec-cand-btn--refuser  { background: var(--color-danger-light); color: var(--color-danger); }
    .rec-cand-btn--contact  { background: var(--color-info-light); color: var(--color-info); }
    .rec-cand-btn:hover { filter: brightness(0.95); }

    .rec-empty { padding: 24px; text-align: center; color: var(--color-text-muted); font-size: 13.5px; }
</style>
</head>
<body>

<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="logo"><i class="ti ti-building"></i></div>
            <span><?= htmlspecialchars($nomEtablissement) ?></span>
        </div>

        <button onclick="goHome()" class="nav-item active">
            <i class="ti ti-layout-grid"></i>
            Tableau de bord
        </button>

        <button onclick="discover('professeurs')" class="nav-item">
            <i class="ti ti-chalkboard"></i>
            Rechercher un professeur
        </button>

        <button class="nav-item">
            <i class="ti ti-user-search"></i>
            Rechercher un responsable
        </button>

        <button onclick="afficherRecrutement()" class="nav-item">
            <i class="ti ti-briefcase"></i>
            Recrutement
        </button>

        <button onclick="logout()" class="nav-item">
            <i class="ti ti-logout"></i>
            Déconnexion
        </button>
    </aside>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="greeting">
                <h1>Gestion du personnel</h1>
                <p class="loc"><?= htmlspecialchars($nomEtablissement) ?></p>
            </div>

            <div class="topbar-actions">
                <button class="icon-btn ghost menu-toggle" id="menuToggle" aria-label="Menu">
                    <i class="ti ti-menu-2"></i>
                </button>

                <div class="search">
                    <i class="ti ti-search"></i>
                    <input type="text" placeholder="Rechercher un professeur...">
                </div>

                <button class="icon-btn ghost" aria-label="Profil">
                    <i class="ti ti-user"></i>
                </button>
            </div>
        </div>

        <!-- CONTENU -->
        <div id="contenuPrincipal" class="content">

            <div class="section-header">
                <h3>Accès rapide</h3>
            </div>

            <div class="modules">

                <!-- PROFESSEURS -->
                <div class="module-card" style="--accent-color: var(--fill-warning)">
                    <i class="ti ti-chalkboard"></i>
                    <p class="title">Professeurs</p>
                    <p class="subtitle">Consulter les professeurs disponibles</p>

                    <div style="display:flex; gap:10px; margin-top:12px;">
                        <button onclick="discover('professeurs')" class="cta">
                            Ma région
                        </button>

                        <button onclick="discoverAllRegions()" class="cta secondary">
                            Autres régions
                        </button>
                    </div>
                </div>

                <!-- RESPONSABLES -->
                <div class="module-card" style="--accent-color: var(--fill-accent)">
                    <i class="ti ti-user-search"></i>
                    <p class="title">Responsables</p>
                    <p class="subtitle">Rechercher un responsable administratif</p>
                    <button class="cta">Découvrir</button>
                </div>

                <!-- RECRUTEMENT -->
                <div class="module-card" style="--accent-color: var(--fill-success)">
                    <i class="ti ti-briefcase"></i>
                    <p class="title">Recrutement</p>
                    <p class="subtitle">Publier une offre, voir les candidatures</p>
                    <button onclick="afficherRecrutement()" class="cta">Découvrir</button>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<script>
window.PROFESSEURS_REGION = <?php echo json_encode($professeurs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
window.ALL_PROFESSEURS = <?php echo json_encode($allProfesseurs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
window.MES_OFFRES = <?php echo json_encode($mesOffres, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS); ?>;
window.RECRUTEMENT_VUE = <?php echo $vueRecrutement ? 'true' : 'false'; ?>;
window.RECRUTEMENT_MESSAGE = <?php echo $succesRecrutement ? json_encode($succesRecrutement, JSON_UNESCAPED_UNICODE) : 'null'; ?>;
</script>

<script src="/assets/js/etablissement.js" defer></script>
<script src="/assets/js/smart-search.js" defer></script>

</body>
</html>