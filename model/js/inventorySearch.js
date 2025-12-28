document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.card');

  searchInput.addEventListener('input', function () {
    const searchValue = this.value.toLowerCase();

    cards.forEach(card => {
      const title = card.getAttribute('data-title').toLowerCase();
      const matches = title.includes(searchValue);
      card.style.display = matches ? 'block' : 'none';
    });
  });
});