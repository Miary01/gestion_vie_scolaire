// ===== Accueil =====
const homeContent = document.querySelector('.content').innerHTML;

function goHome() {
  document.querySelector('.content').innerHTML = homeContent;
}

// ===== Logout =====
function logout() {
  window.location.href = "/logout";
}

// ===== Menu mobile =====
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');
const backdrop = document.getElementById('sidebarBackdrop');

menuToggle?.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  backdrop.classList.toggle('visible');
});

backdrop?.addEventListener('click', () => {
  sidebar.classList.remove('open');
  backdrop.classList.remove('visible');
});

// ==================================================
// CONFIGURATION DES MODULES
// ==================================================
const MODULES = {

  // =========================
  // PROFESSEURS
  // =========================
  professeurs: {
    data: () => window.PROFESSEURS_REGION || [],
    dataAll: () => window.ALL_PROFESSEURS || [],
    titre: "Professeurs",
    icon: "ti-chalkboard",
    bg: "var(--bg-warning)",
    color: "var(--text-warning)",
    ligne1: p => p.nom_professeur,
    ligne2: p => p.mail_professeur,
    action: "Discuter",
    vide: "Aucun professeur trouvé."
  },

  // =========================
  // ETABLISSEMENTS
  // =========================
  etablissements: {
    data: () => window.ETABLISSEMENTS_REGION || [],
    dataAll: () => window.ALL_ETABLISSEMENTS || [],
    titre: "Établissements",
    icon: "ti-building",
    bg: "var(--bg-accent)",
    color: "var(--text-accent)",
    ligne1: e => e.nom_etablissement,
    ligne2: e => e.mail_etablissement,
    action: "S'inscrire",
    vide: "Aucun établissement trouvé."
  }
};

// ==================================================
// AFFICHAGE DES MODULES
// ==================================================
function discover(moduleKey) {
  afficherModule(moduleKey, true);
}

function discoverAll(moduleKey) {
  afficherModule(moduleKey, false);
}

function afficherModule(moduleKey, regionOnly) {
  const config = MODULES[moduleKey];
  if (!config) {
    console.error(`Module inconnu : ${moduleKey}`);
    return;
  }

  const items = regionOnly ? config.data() : config.dataAll();
  renderListe(items, config, moduleKey, regionOnly);
}

// ==================================================
// OUVRIR GMAIL AVEC UN FORMULAIRE
// ==================================================
function ouvrirGmail(email, type) {
  let sujet = "";
  let message = "";

  // =========================
  // CONTACTER UN PROFESSEUR
  // =========================
  if (type === "professeur") {
    sujet = "Prise de contact";
    message = `Bonjour,

Je souhaite prendre contact avec vous.

Cordialement.`;
  }

  // =========================
  // INSCRIPTION
  // =========================
  else if (type === "etablissement") {
    sujet = "Demande d'inscription";
    message = `Bonjour,

Je souhaite m'inscrire dans votre établissement.

Voici mes informations :

Nom :
Prénom :
Date de naissance :
Niveau d'études :
Numéro de téléphone :

Je vous remercie par avance pour votre réponse.

Cordialement.`;
  }

  // =========================
  const url =
    "https://mail.google.com/mail/?view=cm&fs=1"
    + "&to=" + encodeURIComponent(email)
    + "&su=" + encodeURIComponent(sujet)
    + "&body=" + encodeURIComponent(message);

  window.open(url, "_blank");
}

// ==================================================
// RENDU DES CARTES
// ==================================================
function renderListe(items, config, moduleKey, regionOnly) {
  const content = document.querySelector('.content');

  const cards = items.length
    ? items.map(item => `
        <div class="card" style="display:flex; align-items:center; gap:12px;"
             data-search-item
             data-search-text="${escapeAttribute((config.ligne1(item) + ' ' + (config.ligne2(item) || '')).toLowerCase())}">

          <!-- AVATAR -->
          <div class="avatar" style="background:${config.bg}; color:${config.color}; width:40px; height:40px; font-size:14px;">
            <i class="ti ${config.icon}"></i>
          </div>

          <!-- INFORMATIONS -->
          <div style="flex:1; min-width:0;">
            <p style="font-size:13px; font-weight:500;">${escapeHtml(config.ligne1(item))}</p>
            ${config.ligne2(item)
              ? `<p style="font-size:12px; color:var(--text-secondary); margin-top:2px;">${escapeHtml(config.ligne2(item))}</p>`
              : ""}
          </div>

          <!-- BOUTON D'ACTION -->
          ${
            moduleKey === "professeurs"
            ? `<button class="ghost" onclick="ouvrirGmail('${escapeAttribute(item.mail_professeur)}', 'professeur')">Discuter</button>`
            : moduleKey === "etablissements"
            ? `<button class="ghost" onclick="ouvrirGmail('${escapeAttribute(item.mail_etablissement)}', 'etablissement')">S'inscrire</button>`
            : `<button class="ghost">${config.action}</button>`
          }

        </div>
      `).join("")
    : `
        <p style="color:var(--text-secondary);">${config.vide}</p>
      `;

  const titreComplet = regionOnly
    ? `${config.titre} de votre région`
    : `${config.titre} — toutes régions`;

  content.innerHTML = `
    <div class="section-header">
      <h3>${titreComplet}</h3>

      <div style="display:flex; gap:8px;">
        <button class="ghost" onclick="goHome()">
          <i class="ti ti-arrow-left"></i>
          Accueil
        </button>

        <button onclick="${regionOnly ? "discoverAll" : "discover"}('${moduleKey}')">
          <i class="ti ${regionOnly ? "ti-world" : "ti-map-pin"}"></i>
          ${regionOnly ? "Voir autres régions" : "Ma région seulement"}
        </button>
      </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:10px;" data-search-container>
      ${cards}
    </div>
  `;
}

// ==================================================
// PROTECTION HTML
// ==================================================
function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str ?? "";
  return div.innerHTML;
}

// ==================================================
// PROTECTION DES ATTRIBUTS
// ==================================================
function escapeAttribute(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/'/g, "&#39;")
    .replace(/"/g, "&quot;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

// ==================================================
// NAVIGATION
// ==================================================
async function see(type) {
  if (type === "competitions") {
    await chargerCompetitions();
  }
  if (type === "evenements") {
    await chargerEvenements();
  }
}

// ==================================================
// CHARGER LES COMPETITIONS
// ==================================================
async function chargerCompetitions() {
  try {
    const response = await fetch("/client/api/competitions");
    const data = await response.json();

    if (!data.success) {
      alert(data.message);
      return;
    }

    afficherCompetitions(data.competitions);
  } catch (error) {
    console.error("Erreur :", error);
    alert("Une erreur est survenue.");
  }
}
// ==================================================
// CHARGER LES EVENNEMENTS
// ==================================================

async function chargerEvenements() {
    try {
        const response = await fetch("/client/api/evenements");
        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            return;
        }

        afficherEvenements(data.evenements);
    } catch (error) {
        console.error("Erreur :", error);
        alert("Une erreur est survenue.");
    }
}
// ==================================================
// AFFICHER LES COMPETITIONS
// ==================================================
function afficherCompetitions(competitions) {
  const contenuPrincipal = document.getElementById("contenuPrincipal");

  contenuPrincipal.innerHTML = `
    <div class="section-header">
      <h2>Compétitions à venir</h2>
    </div>
  `;

  if (competitions.length === 0) {
    contenuPrincipal.innerHTML += `
      <p>Aucune compétition à venir.</p>
    `;
    return;
  }

  const container = document.createElement("div");
  container.className = "competitions-container";

  competitions.forEach(competition => {
    const carte = document.createElement("div");
    carte.className = "competition-card";

    carte.innerHTML = `
      <h3>${competition.nom_competition}</h3>
      <p><strong>Date :</strong> ${competition.date_competition}</p>
      <p><strong>Région :</strong> ${competition.nom_region}</p>
      <p><strong>Organisateur :</strong> ${competition.mail_organisateur}</p>
      <button class="primary" onclick="sInscrire(${competition.id_competition})">
        S'inscrire
      </button>
    `;

    container.appendChild(carte);
  });

  contenuPrincipal.appendChild(container);
}
// ==================================================
// AFFICHER LES EVENNEMENTS
// ==================================================
function afficherEvenements(evenements) {
    const contenuPrincipal = document.getElementById("contenuPrincipal");

    contenuPrincipal.innerHTML = `
        <div class="section-header">
            <h2>Événements à venir</h2>
        </div>
    `;

    if (evenements.length === 0) {
        contenuPrincipal.innerHTML += `
            <p>Aucun événement à venir.</p>
        `;
        return;
    }

    const container = document.createElement("div");

    container.className = "competitions-container";

    evenements.forEach(evenement => {
        const carte = document.createElement("div");

        carte.className = "competition-card";

        carte.innerHTML = `
            <h3>${evenement.nom_evenement}</h3>

            <p>
                <strong>Date :</strong>
                ${evenement.date_evenement}
            </p>

            <p>
                <strong>Région :</strong>
                ${evenement.nom_region}
            </p>

            <p>
                <strong>Organisateur :</strong>
                ${evenement.mail_organisateur}
            </p>

            <button
                class="primary"
                onclick="sInscrireEvenement(${evenement.id_evenement})"
            >
                S'inscrire
            </button>
        `;

        container.appendChild(carte);
    });

    contenuPrincipal.appendChild(container);
}
// ==================================================
// S'INSCRIRE A UNE COMPETITION
// ==================================================
async function sInscrire(id_competition) {
  try {
    const response = await fetch("/client/api/inscription", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        id_competition: id_competition
      })
    });

    const data = await response.json();

    if (data.success) {
      alert("Inscription réussie !");
    } else {
      alert(data.message);
    }
  } catch (error) {
    console.error("Erreur :", error);
    alert("Une erreur est survenue lors de l'inscription.");
  }
}
// ==================================================
// S'INSCRIRE A UNE EVENEMENT
// ==================================================
async function sInscrireEvenement(id_evenement) {
    try {
        const response = await fetch(
            "/client/api/inscription-evenement",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_evenement: id_evenement
                })
            }
        );

        const data = await response.json();

        if (data.success) {
            alert("Inscription réussie !");
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error("Erreur :", error);
        alert(
            "Une erreur est survenue lors de l'inscription."
        );
    }
}

// ==================================================
// EXERCICES (professeurs -> exercices d'un professeur)
// Suit le même pattern que les autres modules : on
// remplace le innerHTML de #contenuPrincipal, ce qui
// le rend compatible avec goHome() et le reste de l'app.
// ==================================================

function afficherListeProfsExercices() {
    const contenuPrincipal = document.getElementById("contenuPrincipal");
    const profs = window.PROFESSEURS_REGION || [];

    const cartes = profs.length
        ? profs.map(p => `
            <div class="exo-prof-card">
                <p class="exo-prof-name">${escapeHtml(p.nom_professeur)}</p>
                <p class="exo-prof-mail">${escapeHtml(p.mail_professeur)}</p>
                <p class="exo-prof-count">${p.nb_exercices} exercice${p.nb_exercices > 1 ? 's' : ''}</p>
                <button class="exo-voir-btn" onclick="afficherExercicesProfesseur(${p.id_professeur}, '${escapeAttribute(p.nom_professeur)}')">
                    Voir
                </button>
            </div>
        `).join("")
        : `<p style="color:var(--text-secondary);">Aucun professeur trouvé.</p>`;

    contenuPrincipal.innerHTML = `
        <div class="section-header">
            <h3>Exercices par professeur</h3>
            <div style="display:flex; gap:8px;">
                <button class="ghost" onclick="goHome()">
                    <i class="ti ti-arrow-left"></i>
                    Accueil
                </button>
            </div>
        </div>
        <div class="exo-prof-list">${cartes}</div>
    `;
}

async function afficherExercicesProfesseur(idProfesseur, nomProfesseur) {
    const contenuPrincipal = document.getElementById("contenuPrincipal");

    contenuPrincipal.innerHTML = `
        <div class="section-header">
            <h3>Exercices de ${escapeHtml(nomProfesseur)}</h3>
            <div style="display:flex; gap:8px;">
                <button class="ghost" onclick="afficherListeProfsExercices()">
                    <i class="ti ti-arrow-left"></i>
                    Professeurs
                </button>
            </div>
        </div>
        <ul class="exo-exercice-list" id="exoExerciceList">
            <li class="exo-loading">Chargement…</li>
        </ul>
    `;

    try {
        const response = await fetch("/client/exercices-professeur?id_professeur=" + encodeURIComponent(idProfesseur));
        const exercices = await response.json();
        const liste = document.getElementById("exoExerciceList");

        if (!liste) return; // l'utilisateur a déjà changé de vue entre-temps

        if (!exercices.length) {
            liste.innerHTML = `<li class="exo-empty">
                <img src="/assets/images/illustration-document.svg" alt="" width="110" height="88" style="display:block;margin:0 auto 8px;">
                Ce professeur n'a envoyé aucun exercice pour le moment.
            </li>`;
            return;
        }

        liste.innerHTML = exercices.map(ex => `
            <li class="exo-exercice-item">
                <div class="exo-exercice-icon"><i class="ti ti-file-type-pdf"></i></div>
                <div class="exo-exercice-texts">
                    <p class="exo-exercice-titre">${escapeHtml(ex.titre)}</p>
                    <p class="exo-exercice-date">${escapeHtml(ex.date_envoi)}</p>
                </div>
                <div class="exo-exercice-actions">
                    <a class="exo-action-voir" href="/client/fichier-exercice?id=${ex.id_exercice}&action=voir" target="_blank" rel="noopener">
                        <i class="ti ti-eye"></i> Ouvrir
                    </a>
                    <a class="exo-action-telecharger" href="/client/fichier-exercice?id=${ex.id_exercice}&action=telecharger">
                        <i class="ti ti-download"></i> Télécharger
                    </a>
                </div>
            </li>
        `).join("");
    } catch (error) {
        console.error("Erreur :", error);
        const liste = document.getElementById("exoExerciceList");
        if (liste) {
            liste.innerHTML = `<li class="exo-empty">Impossible de charger les exercices pour le moment.</li>`;
        }
    }
}