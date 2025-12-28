

console.log("modaledit.js cargado correctamente");

// Mostrar el modal de confirmación y asignar la URL al botón
function showModalConfirm(url) {
  const modal = document.getElementById('modal-confirm');
  const btnConfirm = document.getElementById('btn-confirm');

  if (modal && btnConfirm) {
    modal.style.display = 'flex';
    btnConfirm.setAttribute('href', url);
  }
}


function showMensajeModal(mensaje) {
  const modal = document.getElementById('modal-mensaje');
  const texto = document.getElementById('modal-mensaje-texto');
  const btnCerrar = document.getElementById('btn-cerrar-mensaje');

  if (modal && texto && btnCerrar) {
    texto.textContent = mensaje;
    modal.style.display = 'flex';

    btnCerrar.onclick = () => {
      modal.style.display = 'none';
    };
  }
}

// Ocultar el modal de confirmación
function closeModalConfirm() {
  const modal = document.getElementById('modal-confirm');
  if (modal) {
    modal.style.display = 'none';
  }
}

// Ocultar el modal de mensaje
function closeModalMensaje() {
  const modal = document.getElementById('modal-mensaje');
  if (modal) {
    modal.style.display = 'none';
  }
}

// Esperar a que el DOM esté cargado para agregar eventos
document.addEventListener('DOMContentLoaded', function () {
  // Modal de confirmación
  const btnCancel = document.getElementById('btn-cancel');
  const modalConfirm = document.getElementById('modal-confirm');
  if (btnCancel) {
    btnCancel.addEventListener('click', closeModalConfirm);
  }
  if (modalConfirm) {
    modalConfirm.addEventListener('click', function (e) {
      if (e.target === modalConfirm) {
        closeModalConfirm();
      }
    });
  }

  // Modal de mensaje
  const btnCerrarMensaje = document.getElementById('btn-cerrar-mensaje');
  const modalMensaje = document.getElementById('modal-mensaje');
  if (btnCerrarMensaje) {
    btnCerrarMensaje.addEventListener('click', closeModalMensaje);
  }
  if (modalMensaje) {
    modalMensaje.addEventListener('click', function (e) {
      if (e.target === modalMensaje) {
        closeModalMensaje();
      }
    });
  }

  // Botones de editar
  const eliminarBtns = document.querySelectorAll('.eliminar-btn');
  eliminarBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const id = btn.getAttribute('data-id');
      const url = `EditarUsuario.php?id=${id}`;
      showModalConfirm(url);
    });
  });
});