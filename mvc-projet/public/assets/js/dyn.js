const roleSelect = document.getElementById("roleSelect");

const clientFields = document.getElementById("clientFields");
const professeurFields = document.getElementById("professeurFields");
const etablissementFields = document.getElementById("etablissementFields");
const organisationFields = document.getElementById("organisationFields");
const adminFields = document.getElementById("adminFields");
const adminKey = document.getElementById("adminKey");
roleSelect.addEventListener("change", function () {

    const selectedRole = this.options[this.selectedIndex].text.toLowerCase();
    console.log(selectedRole);

    // reset all
    clientFields.style.display = "none";
    professeurFields.style.display = "none";
    etablissementFields.style.display = "none";
    organisationFields.style.display = "none";
    adminFields.style.display = "none";
    const allInputs = document.querySelectorAll("#clientFields input, #professeurFields input, #etablissementFields input");

    allInputs.forEach(input => {
        input.removeAttribute("required");
    });

    // CLIENT
    if (selectedRole === "client") {
        clientFields.style.display = "block";

        clientFields.querySelectorAll("input").forEach(input => {
            input.setAttribute("required", "required");
        });
    }

    // PROFESSEUR
    else if (selectedRole === "professeur") {
        professeurFields.style.display = "block";

        professeurFields.querySelectorAll("input").forEach(input => {
            input.setAttribute("required", "required");
        });
    }

    // ETABLISSEMENT
    else if (selectedRole === "etab_scolaire") {
        etablissementFields.style.display = "block";

        etablissementFields.querySelectorAll("input").forEach(input => {
            input.setAttribute("required", "required");
        });
    }
    // ORGANISATION
    else if (selectedRole === "organisation") {
        organisationFields.style.display = "block";

        organisationFields.querySelectorAll("input").forEach(input => {
            input.setAttribute("required", "required");
        });
    } 
    // ADMIN    
    else if (selectedRole === "admin") {
        adminFields.style.display = "block";
        adminKey.setAttribute("required", "required");
    }
});
//---------------------------la recherche de region --------------------------------//
document.querySelectorAll(".regionInput").forEach(input => {
    const box = input.parentElement.querySelector(".suggestions");
    const hidden = input.parentElement.querySelector(".regionId");
    input.addEventListener("input", function () {
        hidden.value = "";
        const query = this.value;

        if (query.length < 1) {
            box.innerHTML = "";
            return;
        }


    fetch("/region/search?q=" + query)
        .then(res => res.json())
        .then(data => {
                console.log("DATA =", data);

                box.innerHTML = "";

                data.forEach(item => {
                    const div = document.createElement("div");

                    div.textContent = item.nom_region;

                    div.onclick = () => {
                        console.log("CLICK OK");
                        console.log("ID =", item.id_region);
                        input.value = item.nom_region;
                        hidden.value = item.id_region;
                        console.log("HIDDEN =", hidden.value);
                        box.innerHTML = "";
                };

                box.appendChild(div);
            });
        });
    });
});