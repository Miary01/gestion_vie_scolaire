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
    .rec-intro { color: #6b7280; font-size: 13.5px; margin: 4px 0 18px; }

    .rec-form {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 26px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .rec-form h4 {
        margin: 0;
        font-family: "Fraunces", Georgia, serif;
        font-weight: 600;
        font-size: 17px;
    }

    .rec-field { display: flex; flex-direction: column; gap: 6px; }
    .rec-field label { font-size: 13px; font-weight: 600; color: #1f2937; }

    .rec-field input[type="text"],
    .rec-field textarea {
        font-family: inherit;
        font-size: 14px;
        padding: 10px 12px;
        border: 1px solid rgba(0,0,0,0.12);
        border-radius: 10px;
        background: #fafaf8;
        color: #1f2937;
        outline: none;
        resize: vertical;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .rec-field input[type="text"]:focus,
    .rec-field textarea:focus {
        border-color: var(--fill-warning, #C97F2E);
        box-shadow: 0 0 0 3px rgba(201,127,46,0.15);
    }

    .rec-submit {
        align-self: flex-start;
        background: var(--fill-warning, #C97F2E);
        color: #26170A;
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
    }
    .rec-submit:hover { filter: brightness(1.05); }

    .rec-notice {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 11px 14px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 18px;
        background: #E4F0E8;
        color: #295A3F;
    }

    .rec-offres { display: flex; flex-direction: column; gap: 16px; }

    .rec-offre-card {
        background: #fff;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 16px;
        padding: 18px 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .rec-offre-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .rec-offre-titre { margin: 0; font-family: "Fraunces", Georgia, serif; font-weight: 600; font-size: 16.5px; }
    .rec-offre-date { margin: 2px 0 0; font-size: 11.5px; color: #9ca3af; font-family: "IBM Plex Mono", monospace; }
    .rec-offre-desc { margin: 0 0 12px; font-size: 13.5px; color: #4b5563; line-height: 1.5; }

    .rec-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }
    .rec-badge--ouverte { background: rgba(63,125,92,0.14); color: var(--fill-success, #3F7D5C); }
    .rec-badge--fermee  { background: rgba(107,114,128,0.14); color: #6b7280; }

    .rec-candidatures { border-top: 1px dashed rgba(0,0,0,0.1); margin-top: 8px; padding-top: 12px; }
    .rec-candidatures-titre { margin: 0 0 8px; font-size: 12.5px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; }

    .rec-candidature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .rec-candidature-item:last-child { border-bottom: none; }

    .rec-cand-avatar {
        width: 34px; height: 34px; flex-shrink: 0; border-radius: 50%;
        background: var(--fill-accent, #35708C); color: #fff;
        display: grid; place-items: center; font-size: 13px; font-weight: 700;
    }

    .rec-cand-texts { flex: 1; min-width: 0; }
    .rec-cand-nom { margin: 0; font-size: 13.5px; font-weight: 600; }
    .rec-cand-mail { margin: 1px 0 0; font-size: 12px; color: #6b7280; word-break: break-all; }

    .rec-cand-statut {
        font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 999px; flex-shrink: 0;
    }
    .rec-cand-statut--en_attente { background: rgba(201,127,46,0.14); color: var(--fill-warning, #C97F2E); }
    .rec-cand-statut--acceptee   { background: rgba(63,125,92,0.14); color: var(--fill-success, #3F7D5C); }
    .rec-cand-statut--refusee    { background: rgba(178,70,50,0.14); color: var(--fill-danger, #B24632); }

    .rec-cand-actions { display: flex; gap: 6px; flex-shrink: 0; }
    .rec-cand-actions form { margin: 0; }
    .rec-cand-btn {
        border: none; border-radius: 8px; padding: 6px 10px;
        font-size: 12px; font-weight: 700; cursor: pointer;
    }
    .rec-cand-btn--accepter { background: rgba(63,125,92,0.14); color: var(--fill-success, #3F7D5C); }
    .rec-cand-btn--refuser  { background: rgba(178,70,50,0.14); color: var(--fill-danger, #B24632); }
    .rec-cand-btn:hover { filter: brightness(0.95); }

    .rec-empty { padding: 24px; text-align: center; color: #9ca3af; font-size: 13.5px; }
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

            <div class="welcome-banner" style="background-image:url('https://images.unsplash.com/photo-1638920329718-ad6f890bb311?auto=format&fit=crop&w=1200&q=80');">
                <div class="welcome-banner__content">
                    <span class="welcome-banner__eyebrow"><i class="ti ti-building"></i> Espace établissement</span>
                    <h1>Recrutez et gérez votre personnel</h1>
                    <p>Publiez des offres, examinez les candidatures et gardez le contact avec les professeurs de votre région.</p>
                </div>
            </div>

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