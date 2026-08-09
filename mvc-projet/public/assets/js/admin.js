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

// Ferme le menu mobile après un clic sur un lien de navigation
// (utile pour les ancres #apercu / #utilisateurs de la page admin).
document.querySelectorAll('.sidebar .nav-item').forEach(function (link) {
  link.addEventListener('click', function () {
    sidebar.classList.remove('open');
    backdrop.classList.remove('visible');
  });
});
