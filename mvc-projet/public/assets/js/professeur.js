const dropzone = document.getElementById('dropzone');
const fichierInput = document.getElementById('fichierInput');
const dropzoneSub = document.getElementById('dropzoneSub');

function afficherNomFichier(nom) {
    dropzoneSub.textContent = nom;
    dropzone.classList.add('dropzone--filled');
}

fichierInput.addEventListener('change', () => {
    if (fichierInput.files.length) {
        afficherNomFichier(fichierInput.files[0].name);
    }
});

['dragenter', 'dragover'].forEach(evt =>
    dropzone.addEventListener(evt, (e) => {
        e.preventDefault();
        dropzone.classList.add('dropzone--drag');
    })
);

['dragleave', 'drop'].forEach(evt =>
    dropzone.addEventListener(evt, (e) => {
        e.preventDefault();
        dropzone.classList.remove('dropzone--drag');
    })
);

dropzone.addEventListener('drop', (e) => {
    const fichiers = e.dataTransfer.files;
    if (fichiers.length) {
        fichierInput.files = fichiers;
        afficherNomFichier(fichiers[0].name);
    }
});