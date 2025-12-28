document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('tbl_productos');
    const aDelete = document.getElementById('a_action_delete');
    const aUpdate = document.getElementById('a_action_update');
    const buttons = document.getElementsByClassName('disable_btn');
    let previousRow = null;
    let currentProductDes = '';

    // Maneja el clic en la tabla
    table.addEventListener('click', function (event) {
        const target = event.target;
        const row = target.closest('tr'); // Selecciona la fila (tr) más cercana al elemento clicado
        if (row) {
            // Si había una fila previamente seleccionada, restablece su color de fondo
            if (previousRow) {
                previousRow.style.backgroundColor = ''; // Restablece al color original
            }

            // Restablecer los estilos de los botones
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].style.backgroundColor = '#ffffff';
                buttons[i].style.pointerEvents = 'auto';
            }

            // Cambiar el color de fondo de la fila seleccionada
            row.style.backgroundColor = '#92FD70';

            // Guardar la fila actual como la seleccionada previamente
            previousRow = row;

            // Capturar el id y descripción del producto
            const productId = row.cells[0].textContent.trim();
            currentProductDes = row.cells[1].textContent.trim();

            // Configurar el enlace para la eliminación
            aDelete.href = `../controller/inventoryController.php?action=eliminar&id=${productId}`;
            aUpdate.href = `EditarProducto.php?action=editar&id=${productId}`;
        }
    });

    // Maneja el clic en el enlace de eliminación
    aDelete.addEventListener('click', function (event) {
        if (currentProductDes) {
            const isConfirmed = confirm(`¿Estás seguro de eliminar al producto ${currentProductDes}?`);
            if (!isConfirmed) {
                event.preventDefault(); // Prevenir la redirección si el usuario cancela
            }
        }
    });
});
