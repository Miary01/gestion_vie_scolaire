// ===== Accueil =====
const homeContent = document.getElementById("contenuPrincipal").innerHTML;

function goHome() {
    document.getElementById("contenuPrincipal").innerHTML = homeContent;
}

// ===== Menu mobile =====
const menuToggle = document.getElementById("menuToggle");
const sidebar = document.querySelector(".sidebar");
const backdrop = document.getElementById("sidebarBackdrop");

if (menuToggle) {
    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        backdrop.classList.toggle("show");
    });
}

if (backdrop) {
    backdrop.addEventListener("click", () => {
        sidebar.classList.remove("open");
        backdrop.classList.remove("show");
    });
}

function logout() {
    window.location.href = "/logout";
}

// ==================================================
// PROFESSEURS
// ==================================================
function renderProfesseurs(title, professeurs) {
    const container = document.getElementById("contenuPrincipal");

    let html = `
        <div class="section-header">
            <h3>${title}</h3>
            <button class="cta secondary" onclick="goHome()">
                <i class="ti ti-arrow-left"></i> Accueil
            </button>
        </div>

        <div class="card" data-search-container>
    `;

    if (professeurs.length === 0) {
        html += `<p>Aucun professeur disponible.</p>`;
    } else {
        professeurs.forEach(prof => {
            html += `
                <div class="person" style="padding:16px;border-bottom:1px solid #eee;"
                     data-search-item
                     data-search-text="${escapeHtml((prof.nom_professeur + ' ' + prof.mail_professeur).toLowerCase())}">
                    <strong>${escapeHtml(prof.nom_professeur)}</strong>
                    <p>${escapeHtml(prof.mail_professeur)}</p>
                </div>
            `;
        });
    }

    html += `</div>`;

    container.innerHTML = html;
}

function discover(type) {
    if (type === "professeurs") {
        renderProfesseurs(
            "Professeurs de votre région",
            window.PROFESSEURS_REGION || []
        );
    }
}

function discoverAllRegions() {
    renderProfesseurs(
        "Professeurs - Toutes les régions",
        window.ALL_PROFESSEURS || []
    );
}

// ==================================================
// PROTECTION HTML
// ==================================================
function escapeHtml(str) {
    const div = document.createElement("div");
    div.textContent = str ?? "";
    return div.innerHTML;
}

function escapeAttr(str) {
    return String(str ?? "")
        .replace(/\\/g, "\\\\")
        .replace(/'/g, "\\'")
        .replace(/\n/g, " ")
        .replace(/"/g, "&quot;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

// ==================================================
// CONTACT PAR EMAIL (établissement -> professeur)
// ==================================================
function contacterProfesseur(email, nomProfesseur, titreOffre, statut) {
    let sujet = `À propos de votre candidature — ${titreOffre}`;
    let message = `Bonjour ${nomProfesseur},\n\n`;

    if (statut === "acceptee") {
        message += `Nous avons le plaisir de vous informer que votre candidature au poste « ${titreOffre} » a été retenue.\n\n` +
            `Nous reviendrons vers vous prochainement pour organiser la suite (entretien, prise de poste...).\n\n` +
            `N'hésitez pas à nous contacter si vous avez des questions.\n\nCordialement.`;
    } else if (statut === "refusee") {
        message += `Nous vous remercions pour l'intérêt porté au poste « ${titreOffre} ».\n\n` +
            `Après étude de votre candidature, nous ne donnerons malheureusement pas suite pour ce poste.\n\n` +
            `Nous vous souhaitons pleine réussite dans vos recherches.\n\nCordialement.`;
    } else {
        message += `Nous avons bien reçu votre candidature au poste « ${titreOffre} » et souhaitons échanger avec vous à ce sujet.\n\n` +
            `Pourriez-vous nous indiquer vos disponibilités pour un entretien ?\n\nCordialement.`;
    }

    const url = "https://mail.google.com/mail/?view=cm&fs=1"
        + "&to=" + encodeURIComponent(email)
        + "&su=" + encodeURIComponent(sujet)
        + "&body=" + encodeURIComponent(message);

    window.open(url, "_blank");
}

// ==================================================
// RECRUTEMENT
// ==================================================

const STATUT_LABELS = {
    en_attente: "En attente",
    acceptee: "Acceptée",
    refusee: "Refusée",
};

function initialesDepuisNom(nom) {
    return (nom || "?")
        .split(" ")
        .filter(Boolean)
        .slice(0, 2)
        .map(mot => mot[0].toUpperCase())
        .join("");
}

function formatDate(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr.replace(" ", "T"));
    if (isNaN(d)) return dateStr;
    return d.toLocaleDateString("fr-FR", { day: "2-digit", month: "2-digit", year: "numeric" })
        + " à " + d.toLocaleTimeString("fr-FR", { hour: "2-digit", minute: "2-digit" });
}

function renderCandidature(offreId, cand, titreOffre) {
    const statutClasse = "rec-cand-statut--" + cand.statut;
    const statutLabel = STATUT_LABELS[cand.statut] || cand.statut;

    const boutonContact = `
        <button type="button" class="rec-cand-btn rec-cand-btn--contact"
                onclick="contacterProfesseur('${escapeAttr(cand.mail_professeur)}', '${escapeAttr(cand.nom_professeur)}', '${escapeAttr(titreOffre)}', '${cand.statut}')">
            <i class="ti ti-mail"></i> Contacter
        </button>
    `;

    const actions = cand.statut === "en_attente" ? `
        <div class="rec-cand-actions">
            <form method="POST" action="/etablissement/candidature">
                <input type="hidden" name="id_candidature" value="${cand.id_candidature}">
                <input type="hidden" name="statut" value="acceptee">
                <button type="submit" class="rec-cand-btn rec-cand-btn--accepter">Accepter</button>
            </form>
            <form method="POST" action="/etablissement/candidature">
                <input type="hidden" name="id_candidature" value="${cand.id_candidature}">
                <input type="hidden" name="statut" value="refusee">
                <button type="submit" class="rec-cand-btn rec-cand-btn--refuser">Refuser</button>
            </form>
            ${boutonContact}
        </div>
    ` : `
        <div class="rec-cand-actions">
            <span class="rec-cand-statut ${statutClasse}">${statutLabel}</span>
            ${boutonContact}
        </div>
    `;

    return `
        <div class="rec-candidature-item">
            <div class="rec-cand-avatar">${escapeHtml(initialesDepuisNom(cand.nom_professeur))}</div>
            <div class="rec-cand-texts">
                <p class="rec-cand-nom">${escapeHtml(cand.nom_professeur)}</p>
                <p class="rec-cand-mail">${escapeHtml(cand.mail_professeur)}</p>
            </div>
            ${actions}
        </div>
    `;
}

function renderOffre(offre) {
    const badgeClasse = offre.statut === "ouverte" ? "rec-badge--ouverte" : "rec-badge--fermee";
    const badgeLabel = offre.statut === "ouverte" ? "Ouverte" : "Fermée";

    const candidatures = offre.candidatures || [];
    const candidaturesHtml = candidatures.length
        ? candidatures.map(c => renderCandidature(offre.id_offre, c, offre.titre)).join("")
        : `<p style="font-size:12.5px; color:var(--color-text-muted); margin:0;">Aucune candidature pour le moment.</p>`;

    return `
        <div class="rec-offre-card" data-search-item data-search-text="${escapeHtml((offre.titre + ' ' + offre.description).toLowerCase())}">
            <div class="rec-offre-head">
                <div>
                    <p class="rec-offre-titre">${escapeHtml(offre.titre)}</p>
                    <p class="rec-offre-date">Publiée le ${formatDate(offre.date_publication)}</p>
                </div>
                <span class="rec-badge ${badgeClasse}">${badgeLabel}</span>
            </div>
            <p class="rec-offre-desc">${escapeHtml(offre.description)}</p>
            <div class="rec-candidatures">
                <p class="rec-candidatures-titre">${candidatures.length} candidature${candidatures.length > 1 ? "s" : ""}</p>
                ${candidaturesHtml}
            </div>
        </div>
    `;
}

function afficherRecrutement() {
    const container = document.getElementById("contenuPrincipal");
    const offres = window.MES_OFFRES || [];

    const notice = window.RECRUTEMENT_MESSAGE
        ? `<div class="rec-notice"><i class="ti ti-circle-check"></i> ${escapeHtml(window.RECRUTEMENT_MESSAGE)}</div>`
        : "";

    const listeOffres = offres.length
        ? offres.map(renderOffre).join("")
        : `<div class="rec-empty">
               <img src="/assets/images/illustration-briefcase.svg" alt="" width="120" height="96" style="display:block;margin:0 auto 10px;">
               Aucune offre publiée pour le moment.
           </div>`;

    // Statistiques de recrutement (calculées côté client à partir des
    // offres et candidatures déjà chargées).
    const toutesCandidatures = offres.flatMap(o => o.candidatures || []);
    const nbOuvertes = offres.filter(o => o.statut === "ouverte").length;
    const nbEnAttente = toutesCandidatures.filter(c => c.statut === "en_attente").length;
    const nbAcceptees = toutesCandidatures.filter(c => c.statut === "acceptee").length;

    const statsHtml = `
        <div class="rec-stats">
            <div class="rec-stat-card rec-stat-card--accent">
                <p class="rec-stat-label">Offres publiées</p>
                <p class="rec-stat-value">${offres.length}</p>
            </div>
            <div class="rec-stat-card rec-stat-card--accent">
                <p class="rec-stat-label">Dont ouvertes</p>
                <p class="rec-stat-value">${nbOuvertes}</p>
            </div>
            <div class="rec-stat-card">
                <p class="rec-stat-label">Candidatures reçues</p>
                <p class="rec-stat-value">${toutesCandidatures.length}</p>
            </div>
            <div class="rec-stat-card rec-stat-card--warning">
                <p class="rec-stat-label">En attente de réponse</p>
                <p class="rec-stat-value">${nbEnAttente}</p>
            </div>
            <div class="rec-stat-card rec-stat-card--success">
                <p class="rec-stat-label">Acceptées</p>
                <p class="rec-stat-value">${nbAcceptees}</p>
            </div>
        </div>
    `;

    container.innerHTML = `
        <div class="section-header">
            <h3>Recrutement</h3>
            <button class="cta secondary" onclick="goHome()">
                <i class="ti ti-arrow-left"></i> Accueil
            </button>
        </div>
        <p class="rec-intro">Publie une offre visible par les professeurs de ta région, et gère les candidatures reçues.</p>

        ${notice}

        ${statsHtml}

        <form class="rec-form" method="POST" action="/etablissement/offre">
            <h4>Publier une nouvelle offre</h4>
            <div class="rec-field">
                <label for="rec-titre">Titre du poste</label>
                <input type="text" id="rec-titre" name="titre" placeholder="Ex. Professeur de mathématiques — collège" required>
            </div>
            <div class="rec-field">
                <label for="rec-description">Description</label>
                <textarea id="rec-description" name="description" rows="4" placeholder="Missions, niveau, volume horaire, conditions..." required></textarea>
            </div>
            <button type="submit" class="rec-submit">
                <i class="ti ti-send"></i> Publier l'offre
            </button>
        </form>

        <div class="rec-offres" data-search-container>
            ${listeOffres}
        </div>
    `;

    // On efface le message pour ne pas le réafficher si on revient sur cette vue
    window.RECRUTEMENT_MESSAGE = null;
}

// Ouvre directement la vue Recrutement si on revient d'une action (création offre / décision candidature)
if (window.RECRUTEMENT_VUE) {
    afficherRecrutement();
}