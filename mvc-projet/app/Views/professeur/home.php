<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Professeur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.44.0/iconfont/tabler-icons.min.css">
    <link rel="stylesheet" href="/assets/css/theme.css">
    <link rel="stylesheet" href="/assets/css/professeur.css">
</head>
<body>
    <div class="app">

        <aside class="sidebar">
            <div class="brand">
                <div class="logo">
                    <i class="ti ti-chalkboard"></i>
                </div>
                <span>Professeur</span>
            </div>

            <button class="nav-item active">
                <i class="ti ti-layout-grid"></i>
                Tableau de bord
            </button>
            <a href="#exercices" class="nav-item">
                <i class="ti ti-file-text"></i>
                Exercices
            </a>
            <a href="#offres" class="nav-item">
                <i class="ti ti-briefcase"></i>
                Offres d'emploi
            </a>
            <a href="/logout" class="nav-item nav-item--logout">
                <i class="ti ti-logout"></i>
                Déconnexion
            </a>
        </aside>

        <div class="main">

            <div class="topbar">
                <div class="greeting">
                    <p class="eyebrow">Bonjour</p>
                    <h1><?= htmlspecialchars($nom_professeur) ?></h1>
                    <p class="loc"><i class="ti ti-map-pin"></i> Antananarivo, Analamanga</p>
                </div>
                <div class="smart-search">
                    <div class="smart-search__input-wrap">
                        <i class="ti ti-search"></i>
                        <input type="text" placeholder="Chercher une offre, un exercice...">
                    </div>
                </div>
            </div>

            <div class="content">

                <div class="stats">
                    <div class="stat-card stat-card--accent">
                        <p class="label">Exercices envoyés</p>
                        <p class="value"><?= (int)$nb_exercices ?></p>
                    </div>
                    <div class="stat-card">
                        <p class="label">Offres disponibles</p>
                        <p class="value"><?= count($offresRegion) ?></p>
                    </div>
                    <div class="stat-card">
                        <p class="label">Candidatures envoyées</p>
                        <p class="value"><?= (int)$nb_candidatures ?></p>
                    </div>
                    <div class="stat-card">
                        <p class="label">En attente de réponse</p>
                        <p class="value"><?= count(array_filter($mesCandidatures, fn($s) => $s === 'en_attente')) ?></p>
                    </div>
                    <div class="stat-card">
                        <p class="label">Candidatures acceptées</p>
                        <p class="value"><?= count(array_filter($mesCandidatures, fn($s) => $s === 'acceptee')) ?></p>
                    </div>
                </div>

                <div class="section-row" id="exercices">

                    <!-- COLONNE ENVOI -->
                    <div class="panel">
                        <h3 class="panel-title">Envoyer un exercice</h3>

                        <?php if ($succes): ?>
                            <div class="notice notice--success">
                                <i class="ti ti-circle-check"></i>
                                Exercice envoyé avec succès.
                            </div>
                        <?php elseif ($erreur): ?>
                            <div class="notice notice--error">
                                <i class="ti ti-alert-circle"></i>
                                <?= htmlspecialchars($erreur) ?>
                            </div>
                        <?php endif; ?>

                        <form class="upload-card" action="/professeur/upload-exercice" method="POST" enctype="multipart/form-data">
                            <label class="dropzone" id="dropzone">
                                <input type="file" name="fichier" id="fichierInput" accept="application/pdf" required hidden>
                                <i class="ti ti-file-upload"></i>
                                <span class="dropzone-title">Glisse ton PDF ici</span>
                                <span class="dropzone-sub" id="dropzoneSub">ou clique pour parcourir — 10 Mo max</span>
                            </label>

                            <button class="primary" type="submit">
                                <i class="ti ti-send"></i>
                                Envoyer l'exercice
                            </button>
                        </form>
                    </div>

                    <!-- COLONNE HISTORIQUE -->
                    <div class="panel">
                        <h3 class="panel-title">Derniers envois</h3>

                        <?php if (empty($derniersExercices)): ?>
                            <div class="empty-state">
                                <img src="/assets/images/illustration-document.svg" alt="" width="140" height="112" loading="lazy">
                                <p>Aucun exercice envoyé pour le moment.</p>
                            </div>
                        <?php else: ?>
                            <ul class="exercice-list" data-search-container>
                                <?php foreach ($derniersExercices as $ex): ?>
                                    <?php $texteExercice = strtolower(\App\Models\Exercice::titreDepuisFichier($ex['fichier'])); ?>
                                    <li class="exercice-item" data-search-item data-search-text="<?= htmlspecialchars($texteExercice) ?>">
                                        <div class="exercice-icon"><i class="ti ti-file-type-pdf"></i></div>
                                        <div class="exercice-texts">
                                            <p class="exercice-titre"><?= htmlspecialchars(\App\Models\Exercice::titreDepuisFichier($ex['fichier'])) ?></p>
                                            <p class="exercice-date">
                                                <?= (new DateTime($ex['date_envoi']))->format('d/m/Y à H:i') ?>
                                            </p>
                                        </div>
                                        <a class="exercice-link"
                                           href="/client/fichier-exercice?id=<?= (int)$ex['id_exercice'] ?>&action=voir"
                                           target="_blank" rel="noopener">
                                            Ouvrir <i class="ti ti-external-link"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- OFFRES D'EMPLOI -->
                <div class="panel" id="offres" style="margin-top:24px;">
                    <h3 class="panel-title">Offres d'emploi de ma région</h3>

                    <?php if ($succesCandidature): ?>
                        <div class="notice notice--success">
                            <i class="ti ti-circle-check"></i>
                            Candidature envoyée avec succès.
                        </div>
                    <?php endif; ?>

                    <?php if (empty($offresRegion)): ?>
                        <div class="empty-state">
                            <img src="/assets/images/illustration-briefcase.svg" alt="" width="140" height="112" loading="lazy">
                            <p>Aucune offre disponible dans ta région pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <ul class="offre-list" data-search-container>
                            <?php foreach ($offresRegion as $offre): ?>
                                <?php
                                    $statutCandidature = $mesCandidatures[$offre['id_offre']] ?? null;
                                    $texteOffre = strtolower($offre['titre'] . ' ' . $offre['nom_etablissement']);
                                ?>
                                <li class="offre-item" data-search-item data-search-text="<?= htmlspecialchars($texteOffre) ?>">
                                    <div class="offre-texts">
                                        <p class="offre-titre"><?= htmlspecialchars($offre['titre']) ?></p>
                                        <p class="offre-etablissement">
                                            <i class="ti ti-building"></i>
                                            <?= htmlspecialchars($offre['nom_etablissement']) ?>
                                        </p>
                                        <p class="offre-description"><?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                                        <p class="offre-date">
                                            Publiée le <?= (new DateTime($offre['date_publication']))->format('d/m/Y') ?>
                                        </p>
                                    </div>

                                    <?php if ($statutCandidature === null): ?>
                                        <form method="POST" action="/professeur/candidater">
                                            <input type="hidden" name="id_offre" value="<?= (int)$offre['id_offre'] ?>">
                                            <button type="submit" class="primary offre-btn">
                                                <i class="ti ti-send"></i>
                                                Postuler
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                                            <?php if ($statutCandidature === 'en_attente'): ?>
                                                <span class="offre-statut offre-statut--attente">En attente</span>
                                            <?php elseif ($statutCandidature === 'acceptee'): ?>
                                                <span class="offre-statut offre-statut--acceptee">Acceptée</span>
                                            <?php else: ?>
                                                <span class="offre-statut offre-statut--refusee">Refusée</span>
                                            <?php endif; ?>

                                            <?php
                                                $sujetContact = "À propos de ma candidature — " . $offre['titre'];
                                                $messageContact = match ($statutCandidature) {
                                                    'acceptee' => "Bonjour,\n\nJe vous remercie d'avoir accepté ma candidature au poste « {$offre['titre']} ». Je reste à votre disposition pour organiser la suite.\n\nCordialement.",
                                                    'refusee'  => "Bonjour,\n\nJe vous remercie pour l'étude de ma candidature au poste « {$offre['titre']} ». Auriez-vous un retour à me communiquer pour mes prochaines candidatures ?\n\nCordialement.",
                                                    default    => "Bonjour,\n\nJe me permets de revenir vers vous au sujet de ma candidature au poste « {$offre['titre']} », envoyée récemment. Reste-t-elle à l'étude ?\n\nCordialement.",
                                                };
                                                $lienContact = 'https://mail.google.com/mail/?view=cm&fs=1'
                                                    . '&to=' . urlencode($offre['mail_etablissement'])
                                                    . '&su=' . urlencode($sujetContact)
                                                    . '&body=' . urlencode($messageContact);
                                            ?>
                                            <a href="<?= htmlspecialchars($lienContact) ?>" target="_blank" rel="noopener" class="ghost offre-btn" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; font-size:12.5px;">
                                                <i class="ti ti-mail"></i> Contacter
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <script src="/assets/js/professeur.js" defer></script>
    <script src="/assets/js/smart-search.js" defer></script>
</body>
</html>