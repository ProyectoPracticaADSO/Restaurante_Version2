document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteForm');

    deleteForm.addEventListener('submit', function(event) {
        const checkboxes = deleteForm.querySelectorAll('input[name="mesas[]"]:checked');
        if (checkboxes.length === 0) {
            alert('Por favor, selecciona al menos una mesa para eliminar.');
            event.preventDefault();
        }
    });
});

const checkboxes = document.querySelectorAll('.mesa-checkbox');
const deleteButtonWrapper = document.querySelector('.delete-button-wrapper');

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (Array.from(checkboxes).some(cb => cb.checked)) {
            deleteButtonWrapper.style.display = 'block';
        } else {
            deleteButtonWrapper.style.display = 'none';
        }
    });
});