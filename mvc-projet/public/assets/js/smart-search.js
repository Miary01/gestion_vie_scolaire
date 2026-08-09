// ==================================================
// smart-search.js — Barre de recherche intelligente
// partagée par tous les tableaux de bord (admin, client,
// professeur, établissement, organisation).
//
// Fonctionnement : filtre en direct, côté client, tous les
// éléments marqués data-search-item selon ce qui est tapé
// dans le champ .search input du topbar, en comparant au
// texte de leur attribut data-search-text.
// ==================================================

document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('.search input, .smart-search input');
    if (!input) return;

    function normalize(str) {
        return (str || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    let filtering = false;

    function filtrer() {
        if (filtering) return;
        filtering = true;

        const query = normalize(input.value.trim());
        const containers = document.querySelectorAll('[data-search-container]');

        containers.forEach(function (container) {
            const items = container.querySelectorAll('[data-search-item]');
            let visibleCount = 0;

            items.forEach(function (item) {
                const haystack = normalize(item.getAttribute('data-search-text') || item.textContent);
                const match = query === '' || haystack.includes(query);
                item.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            let empty = container.querySelector(':scope > .smart-search-empty');

            if (items.length > 0 && query !== '' && visibleCount === 0) {
                if (!empty) {
                    empty = document.createElement('p');
                    empty.className = 'smart-search-empty';
                    empty.style.cssText = 'color:var(--text-secondary,#6B7280); padding:16px 4px; font-size:14px;';
                    container.appendChild(empty);
                }
                empty.textContent = 'Aucun résultat pour « ' + input.value.trim() + ' ».';
            } else if (empty) {
                empty.remove();
            }
        });

        filtering = false;
    }

    input.addEventListener('input', filtrer);

    // Ré-applique le filtre courant si du contenu est ajouté dynamiquement
    // après coup (ex : cartes chargées en AJAX), sans que l'utilisateur
    // ait besoin de retaper quelque chose. La garde `filtering` ci-dessus
    // empêche toute boucle : les mutations produites par filtrer() elle-même
    // sont ignorées.
    const observer = new MutationObserver(function () {
        if (!filtering && input.value.trim() !== '') {
            filtrer();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
