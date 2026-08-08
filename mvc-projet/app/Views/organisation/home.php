<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Organisation — Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/organisation.css">
</head>
<body>
<div class="app">

    <!-- ================= SIDEBAR ================= -->
    <aside class="sidebar">
        <div class="brand">
            <div class="logo"><i class="ti ti-flag-3"></i></div>
            <span>Organisation</span>
        </div>

        <button onclick="afficherDashboard()" class="nav-item active">
            <i class="ti ti-layout-grid"></i>
            Tableau de bord
        </button>
        <button onclick="voirMesEvenements()" class="nav-item">
            <i class="ti ti-calendar-event"></i>
            Événements
        </button>
        <button onclick="voirMesCompetitions()" class="nav-item">
            <i class="ti ti-trophy"></i>
            Compétitions
        </button>
        <div class="nav-spacer"></div>

        <button onclick="logout()" class="nav-item logout">
            <i class="ti ti-logout"></i>
            Déconnexion
        </button>
    </aside>

    <!-- ================= MAIN ================= -->
    <div class="main">

        <!-- ================= TOPBAR ================= -->
        <div class="topbar">
            <div class="greeting">
                <h1>Bonjour, <?= htmlspecialchars($_SESSION['login']) ?></h1>
            </div>
            <div class="topbar-actions">
                <button class="icon-btn ghost menu-toggle" id="menuToggle" aria-label="Menu">
                    <i class="ti ti-menu-2"></i>
                </button>
                <div class="search">
                    <i class="ti ti-search"></i>
                    <input type="text" placeholder="Chercher un événement, une compétition...">
                </div>
                <button class="icon-btn ghost" aria-label="Notifications">
                    <i class="ti ti-bell"></i>
                </button>
                <button class="icon-btn ghost" aria-label="Profil">
                    <i class="ti ti-user"></i>
                </button>
            </div>
        </div>

        <!-- ================= CONTENT ================= -->
        <div class="content">

            <!-- ================= STATS ================= -->
            <div class="stats">
                <div class="stat-card">
                    <p class="label">Événements créés</p>
                    <p class="value"><?= (int)$stats['evenements'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Dont à venir</p>
                    <p class="value"><?= (int)$stats['evenements_avenir'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Compétitions créées</p>
                    <p class="value"><?= (int)$stats['competitions'] ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Dont à venir</p>
                    <p class="value"><?= (int)$stats['competitions_avenir'] ?></p>
                </div>
            </div>

            <!-- ================= CREATION RAPIDE ================= -->
            <div>
                <div class="section-header">
                    <h3>Mettre en place</h3>
                </div>

                <div class="create-row">

                    <!-- EVENEMENT -->
                    <div class="create-card">
                        <div class="icon-wrap" style="background:var(--bg-success);">
                            <i class="ti ti-calendar-plus" style="color:var(--text-success);"></i>
                        </div>
                        <div class="texts">
                            <p class="title">Créer un événement</p>
                            <p class="subtitle">Ouvert à toute la région ou à toutes les régions</p>
                        </div>
                        <button class="primary" onclick="ouvrirFormulaireEvenement(event)">Créer</button>
                    </div>

                    <!-- COMPETITION -->
                    <div class="create-card">
                        <div class="icon-wrap" style="background:var(--bg-danger);">
                            <i class="ti ti-trophy" style="color:var(--text-danger);"></i>
                        </div>
                        <div class="texts">
                            <p class="title">Créer une compétition</p>
                            <p class="subtitle">Compétition interscolaire, une ou plusieurs régions</p>
                        </div>
                        <button class="primary" onclick="ouvrirFormulaireCompetition(event)">Créer</button>
                    </div>

                </div>
            </div>

            <!-- ================= FORMULAIRES ================= -->
            <div class="forms-container">

                <!-- ================= EVENEMENT ================= -->
                <div id="formulaireEvenement" class="event-form" style="display: none;">
                    <div class="section-header">
                        <h3>Créer un événement</h3>
                    </div>

                    <form method="POST">
                        <div class="form-group">
                            <label for="nom_evenement">Nom de l'événement</label>
                            <input type="text" id="nom_evenement" name="nom_evenement" placeholder="Ex : Tournoi interscolaire" required>
                        </div>

                        <div class="form-group">
                            <label for="date_evenement">Date de l'événement</label>
                            <input type="date" id="date_evenement" name="date_evenement" required>
                        </div>

                        <!-- REGION -->
                        <div class="form-group">
                            <label for="id_region_evenement">Région</label>
                            <select id="id_region_evenement" name="id_region" required>
                                <option value="">Sélectionner une région</option>
                                <?php foreach ($regions as $region): ?>
                                    <option value="<?= htmlspecialchars($region['id_region']) ?>"><?= htmlspecialchars($region['nom_region']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="primary">Enregistrer l'événement</button>
                    </form>
                </div>

                <!-- ================= COMPETITION ================= -->
                <div id="formulaireCompetition" class="event-form" style="display: none;">
                    <div class="section-header">
                        <h3>Créer une compétition</h3>
                    </div>

                    <form method="POST">
                        <div class="form-group">
                            <label for="nom_competition">Nom de la compétition</label>
                            <input type="text" id="nom_competition" name="nom_competition" placeholder="Ex : Championnat interscolaire" required>
                        </div>

                        <div class="form-group">
                            <label for="date_competition">Date de la compétition</label>
                            <input type="date" id="date_competition" name="date_competition" required>
                        </div>

                        <!-- REGION -->
                        <div class="form-group">
                            <label for="id_region_competition">Région</label>
                            <select id="id_region_competition" name="id_region" required>
                                <option value="">Sélectionner une région</option>
                                <?php foreach ($regions as $region): ?>
                                    <option value="<?= htmlspecialchars($region['id_region']) ?>"><?= htmlspecialchars($region['nom_region']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="primary">Enregistrer la compétition</button>
                    </form>
                </div>

            </div>

            <!-- ================= COLUMNS ================= -->
            <div class="columns">

                <div>
                    <div class="section-header">
                        <h3>Mes événements</h3>
                        <button onclick="voirMesEvenements()" class="ghost">Voir tout</button>
                    </div>
                </div>

                <div>
                    <div class="section-header">
                        <h3>Mes compétitions</h3>
                        <button onclick="voirMesCompetitions()" class="ghost">Voir tout</button>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- ================= MODAL VALIDATION ================= -->
<div id="modalValidation" class="modal">
    <div class="modal-content">

        <button class="modal-close" onclick="fermerValidation()">
            <i class="ti ti-x"></i>
        </button>

        <h2>Validation du participant</h2>

        <form onsubmit="envoyerValidation(event)">

            <label>Adresse e-mail</label>
            <input type="email" id="mailParticipant" readonly>

            <label>Message</label>
            <textarea id="messageValidation" required>Bonjour, votre participation a été validée. Nous vous remercions pour votre inscription.</textarea>

            <div class="modal-actions">
                <button type="button" class="ghost" onclick="fermerValidation()">Annuler</button>
                <button type="submit" class="primary">Valider et envoyer</button>
            </div>

        </form>

    </div>
</div>

<script src="/assets/js/organisation.js" defer></script>
<script src="/assets/js/smart-search.js" defer></script>

</body>
</html>