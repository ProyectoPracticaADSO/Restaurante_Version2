// Esto define la función global para que el HTML pueda usarla
window.toggleSidebar = function () {
  const sidebar = document.querySelector('.sidebar');
  const main = document.querySelector('.main-content');

  sidebar.classList.toggle('open');
  main.classList.toggle('shifted');
};

// Cerrar sidebar al hacer clic fuera
document.addEventListener('click', function (event) {
  const sidebar = document.querySelector('.sidebar');
  const toggleBtn = document.querySelector('.toggle-btn');

  const isClickInside = sidebar.contains(event.target) 
    || toggleBtn.contains(event.target) 
    || event.target.closest('.toggle-btn');

  if (sidebar.classList.contains('open') && !isClickInside) {
    sidebar.classList.remove('open');
    document.querySelector('.main-content').classList.remove('shifted');
  }
});

// Confirmación de salida
function confirmExit(event) {
  if (!confirm("¿Estás seguro de que deseas salir?")) {
    event.preventDefault();
  }
}

// Esperar que cargue el DOM para aplicar filtro de búsqueda
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.card');

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase();

      cards.forEach(card => {
        const title = card.dataset.title?.toLowerCase() || '';
        card.style.display = title.includes(searchTerm) ? 'block' : 'none';
      });
    });
  }
});