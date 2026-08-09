document.querySelectorAll(".regionInput").forEach(input => {
    const container = input.parentElement;
    const box = container.querySelector(".suggestions");
    const hidden = container.querySelector(".regionId");

    input.addEventListener("input", function () {
        const query = this.value;

        // reset si vide
        if (query.length < 1) {
            box.innerHTML = "";
            hidden.value = "";
            return;
        }

        fetch("/region/search?q=" + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {

                box.innerHTML = "";

                data.forEach(item => {
                    const div = document.createElement("div");
                    div.textContent = item.nom_region;

                    div.style.cursor = "pointer";
                    div.style.padding = "5px";

                    div.onclick = () => {
                        // afficher nom
                        input.value = item.nom_region;

                        // stocker ID réel
                        hidden.value = item.id_region;

                        // vider suggestions
                        box.innerHTML = "";
                    };

                    box.appendChild(div);
                });
            });
    });

    // optionnel : si l'utilisateur modifie après sélection
    input.addEventListener("input", () => {
        hidden.value = "";
    });
});