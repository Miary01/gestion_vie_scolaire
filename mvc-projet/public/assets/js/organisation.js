// ==================================================
// VARIABLES
// ==================================================

function escapeAttr(str) {
    return String(str ?? "")
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

const formulaireEvenement =
    document.getElementById(
        "formulaireEvenement"
    );


const formulaireCompetition =
    document.getElementById(
        "formulaireCompetition"
    );


const modalValidation =
    document.getElementById(
        "modalValidation"
    );


// ==================================================
// OUVRIR LE FORMULAIRE EVENEMENT
// ==================================================

function ouvrirFormulaireEvenement(event) {


    // Empêcher la propagation
    event.stopPropagation();


    // Fermer le formulaire compétition
    formulaireCompetition.style.display =
        "none";


    // Afficher le formulaire événement
    formulaireEvenement.style.display =
        "block";


    // Faire défiler automatiquement
    formulaireEvenement.scrollIntoView({

        behavior: "smooth",

        block: "center"

    });

}


// ==================================================
// OUVRIR LE FORMULAIRE COMPETITION
// ==================================================

function ouvrirFormulaireCompetition(event) {


    // Empêcher la propagation
    event.stopPropagation();


    // Fermer le formulaire événement
    formulaireEvenement.style.display =
        "none";


    // Afficher le formulaire compétition
    formulaireCompetition.style.display =
        "block";


    // Faire défiler automatiquement
    formulaireCompetition.scrollIntoView({

        behavior: "smooth",

        block: "center"

    });

}


// ==================================================
// FERMER LES FORMULAIRES
// ==================================================

document.addEventListener(

    "click",

    function(event) {


        // Formulaire événement visible
        // et clic à l'extérieur

        if (

            formulaireEvenement.style.display
            === "block"

            &&

            !formulaireEvenement.contains(
                event.target
            )

        ) {

            formulaireEvenement.style.display =
                "none";

        }


        // Formulaire compétition visible
        // et clic à l'extérieur

        if (

            formulaireCompetition.style.display
            === "block"

            &&

            !formulaireCompetition.contains(
                event.target
            )

        ) {

            formulaireCompetition.style.display =
                "none";

        }

    }

);


// ==================================================
// EMPECHER LA FERMETURE DANS LE FORMULAIRE
// ==================================================

formulaireEvenement.addEventListener(

    "click",

    function(event) {

        event.stopPropagation();

    }

);


formulaireCompetition.addEventListener(

    "click",

    function(event) {

        event.stopPropagation();

    }

);


// ==================================================
// VOIR LES PARTICIPANTS
// ==================================================

async function voirParticipants(type, id) {
    try {
        const response = await fetch(
            `/organisation/api/participants?type=${type}&id=${id}`
        );

        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            return;
        }

        afficherParticipants(
            data.participants
        );

    } catch (error) {
        console.error(error);
        alert("Erreur lors du chargement des participants.");
    }
}


// ==================================================
// VOIR LES EVENEMENTS CREE
// ==================================================

async function voirMesEvenements() {
    try {
        const response = await fetch(
            "/organisation/api/mes-evenements"
        );

        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            return;
        }

        const contenu =
            document.querySelector(".content");

        contenu.innerHTML = `
            <div class="section-header">
                <h2>Mes événements</h2>
            </div>

            <div class="organisation-grid" data-search-container>
                ${
                    data.evenements.length === 0
                    ?
                    "<p class='organisation-empty'><img src='/assets/images/illustration-calendar.svg' alt='' width='130' height='104' style='display:block;margin:0 auto 10px;'>Aucun événement créé.</p>"
                    :
                    data.evenements.map(evenement => `
                        <div class="organisation-card"
                             data-search-item
                             data-search-text="${escapeAttr((evenement.nom_evenement + ' ' + evenement.nom_region).toLowerCase())}">

                            <h3>
                                ${evenement.nom_evenement}
                            </h3>

                            <p>
                                Date :
                                ${evenement.date_evenement}
                            </p>

                            <p>
                                Région :
                                ${evenement.nom_region}
                            </p>

                            <button
                                class="primary"
                                onclick="
                                    voirParticipants(
                                        2,
                                        ${evenement.id_evenement}
                                    )
                                "
                            >
                                <i class="ti ti-users"></i>
                                Participants
                            </button>

                        </div>
                    `).join("")
                }
            </div>
        `;

    } catch (error) {
        console.error(error);
    }
}


// ==================================================
// VOIR LES COMPETITIONS CREE
// ==================================================

async function voirMesCompetitions() {
    try {
        const response = await fetch(
            "/organisation/api/mes-competitions"
        );

        const data = await response.json();

        if (!data.success) {
            alert(data.message);
            return;
        }

        const contenu =
            document.querySelector(".content");

        contenu.innerHTML = `
            <div class="section-header">
                <h2>Mes compétitions</h2>
            </div>

            <div class="organisation-grid" data-search-container>
                ${
                    data.competitions.length === 0
                    ?
                    "<p class='organisation-empty'><img src='/assets/images/illustration-calendar.svg' alt='' width='130' height='104' style='display:block;margin:0 auto 10px;'>Aucune compétition créée.</p>"
                    :
                    data.competitions.map(competition => `
                        <div class="organisation-card"
                             data-search-item
                             data-search-text="${escapeAttr((competition.nom_competition + ' ' + competition.nom_region).toLowerCase())}">

                            <h3>
                                ${competition.nom_competition}
                            </h3>

                            <p>
                                Date :
                                ${competition.date_competition}
                            </p>

                            <p>
                                Région :
                                ${competition.nom_region}
                            </p>

                            <button
                                class="primary"
                                onclick="
                                    voirParticipants(
                                        1,
                                        ${competition.id_competition}
                                    )
                                "
                            >
                                <i class="ti ti-users"></i>
                                Participants
                            </button>

                        </div>
                    `).join("")
                }
            </div>
        `;

    } catch (error) {
        console.error(error);
    }
}


// ==================================================
// AFFICHER LES PARTICIPANTS
// ==================================================

function afficherParticipants(
    participants
) {
    const contenu =
        document.querySelector(".content");

    contenu.innerHTML = `
        <div class="section-header">
            <h2>Participants</h2>
        </div>

        <div class="participants-list" data-search-container>

            ${
                participants.length === 0
                ?
                "<p class='organisation-empty'><img src='/assets/images/illustration-empty-box.svg' alt='' width='120' height='96' style='display:block;margin:0 auto 10px;'>Aucun participant.</p>"
                :
                participants.map(participant => `
                    <div class="participant-card"
                         data-search-item
                         data-search-text="${escapeAttr((participant.nom_client + ' ' + participant.mail).toLowerCase())}">

                        <div class="participant-info">

                            <h3>
                                ${participant.nom_client}
                            </h3>

                            <p>
                                ${participant.mail}
                            </p>

                            <small>
                                ${participant.nom_activite}
                            </small>

                        </div>

                        <button
                            class="primary"
                            onclick="
                                ouvrirValidation(
                                    '${participant.mail}'
                                )
                            "
                        >
                            <i class="ti ti-check"></i>
                            Valider
                        </button>

                    </div>
                `).join("")
            }

        </div>
    `;
}


// ==================================================
// OUVRIR LA MODAL DE VALIDATION
// ==================================================

function ouvrirValidation(mail) {

    document.getElementById("mailParticipant").value = mail;

    modalValidation.style.display = "flex";

}


// ==================================================
// FERMER LA MODAL DE VALIDATION
// ==================================================

function fermerValidation() {

    modalValidation.style.display = "none";

}


// Fermer la modal si on clique en dehors du formulaire
// (clic sur le fond sombre, pas sur .modal-content)

modalValidation.addEventListener(

    "click",

    function(event) {

        if (event.target === modalValidation) {

            fermerValidation();

        }

    }

);


// Fermer la modal avec la touche Échap

document.addEventListener(

    "keydown",

    function(event) {

        if (

            event.key === "Escape"

            &&

            modalValidation.style.display === "flex"

        ) {

            fermerValidation();

        }

    }

);


// ==================================================
// VALIDER LA PARTICIPATION ET ENVOYER LE MAIL
// ==================================================

function envoyerValidation(event) {
    event.preventDefault();

    const mail = document.getElementById("mailParticipant").value;
    const message = document.getElementById("messageValidation").value;

    const sujet = encodeURIComponent(
        "Validation de votre participation"
    );

    const corps = encodeURIComponent(message);

    const url =
        `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(mail)}&su=${sujet}&body=${corps}`;

    window.open(url, "_blank");

    fermerValidation();
}


// ==================================================
// NAVIGATION SIDEBAR
// ==================================================

function afficherDashboard() {
    window.location.reload();
}

function voirStatistiques() {
    // TODO: afficher les statistiques
}

function voirParametres() {
    // TODO: afficher la page de paramètres
}

function logout() {
    window.location.href = "/logout";
}


// ==================================================
// SIDEBAR MOBILE
// ==================================================

const sidebar = document.querySelector(".sidebar");
const sidebarBackdrop = document.getElementById("sidebarBackdrop");
const menuToggle = document.getElementById("menuToggle");

if (menuToggle) {

    menuToggle.addEventListener("click", function() {
        sidebar.classList.add("open");
        sidebarBackdrop.classList.add("open");
    });

}

if (sidebarBackdrop) {

    sidebarBackdrop.addEventListener("click", function() {
        sidebar.classList.remove("open");
        sidebarBackdrop.classList.remove("open");
    });

}