<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controller/mesaController.php';  
require_once '../model/mesa.php';  

$mesasModel = new Mesa();  
$cantidadMesas = $mesasModel->getMesas();  
?>  

<!DOCTYPE html>  
<html lang="es">  
<head>  
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
   <br> 
  <title>Gestión de mesas</title>  

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">  
  <link rel="stylesheet" href="../css/StyleTable.css">  
  <link rel="stylesheet" href="../../css/modal.css"/>  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />  
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>  
  
  <style>
      body {
        padding-top: 40px;
        background-color: #f8f9fa;
      }
      header {
        position: relative;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
      }
      .table-wrapper {
        background-color: #f8f9fa;
        padding: 0.5px;
        border-radius: 5px;
        margin-top: 10px;
      }
      .table {
        border-radius: 5px !important;
        overflow: hidden;
      }
      .back-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 1.8rem;
        color: #333;
        text-decoration: none;
      }
      .back-icon:hover {
        color: #007bff;
      }
    </style>
  
</head>  

<body>  
<div class="container mt-4 position-relative">
  <?php include '../view/bases/base4.php'; ?>

  <!-- HEADER -->
  <header class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div class="d-flex align-items-center">
      <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png" alt="Logo" style="height:50px; margin-right:10px;">
      <h1 class="display-5 mb-0">Gestión de Mesas</h1>
    </div>
  </header>

  <!-- Formulario de añadir -->
  <div class="top-bar mb-3">  
    <form id="formGestionMesas" class="needs-validation d-flex align-items-center" action="GestionMesas.php" method="POST" novalidate>  
      <label for="numero_mesas" class="me-2 mb-0">Cantidad de mesas a añadir:</label>  
      <input type="number" id="numero_mesas" name="numero_mesas" min="1" class="form-control me-2" style="width:100px;" required>  
      <button type="submit" class="btn btn-primary">Añadir</button>  
    </form>  
  </div>  

  <!-- Formulario de eliminar + tabla -->
  <form id="deleteForm">
    <div class="delete-button-wrapper mb-3 d-none" id="deleteButtonWrapper">  
      <button type="button" class="btn btn-danger" id="deleteSelectedBtn">Eliminar Mesas Seleccionadas</button>  
    </div>

    <div class="table-wrapper table-responsive">  
      <table class="table table-bordered table-hover text-center">  
        <thead class="table-light">  
          <tr>  
            <th>Número de mesa</th>  
            <th>Estado</th>  
            <th>Editar</th>  
            <th><input type="checkbox" id="selectAll" /> Seleccionar Todas</th>  
          </tr>  
        </thead>  
        <tbody>  
          <?php foreach ($cantidadMesas as $mesa) : ?>  
            <tr>  
              <td><?= htmlspecialchars($mesa['numero_mesa']) ?></td>  
              <td>  
                <?php  
                  $estado = $mesa['estado'] ?? 'Desconocido';
                  $badgeClass = 'secondary';
                  if ($estado === 'Disponible') $badgeClass = 'success';
                  elseif ($estado === 'Ocupada') $badgeClass = 'danger';
                  elseif ($estado === 'Reservada') $badgeClass = 'warning';    
                ?>  
                <span class="badge bg-<?= $badgeClass ?>"><?= htmlspecialchars($estado) ?></span>  
              </td>  
              <td>  
                <a href="EditarMesa.php?id=<?= urlencode($mesa['id']) ?>" class="btn btn-sm btn-primary">Editar</a>  
              </td>  
              <td>  
                <input type="checkbox" name="mesas[]" value="<?= htmlspecialchars($mesa['id']) ?>" class="mesa-checkbox">  
              </td>  
            </tr>  
          <?php endforeach; ?>  
        </tbody>  
      </table>  
    </div>  
  </form>  
</div>  

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirmar Eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar las mesas seleccionadas?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Operación Exitosa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p id="successMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- JS personalizado -->
<script src="../model/js/validation.js"></script>  
<script src="../model/js/checkValidation.js"></script>  
<script src="../model/js/modal.js"></script>  

<script>
  const urlParams = new URLSearchParams(window.location.search);
  const mensaje = urlParams.get('success');

  if (mensaje === 'eliminado' || mensaje === 'agregado') {
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const successMessage = document.getElementById('successMessage');

    if (mensaje === 'eliminado') {
      successMessage.textContent = 'Mesa(s) eliminada correctamente.';
    } else if (mensaje === 'agregado') {
      successMessage.textContent = 'Mesa(s) agregadas correctamente.';
    }

    successModal.show();

    // Eliminar el parámetro 'success' de la URL al cerrar el modal
    document.querySelector('.btn-success').addEventListener('click', () => {
      const newUrl = window.location.href.split('?')[0];
      window.history.replaceState({}, document.title, newUrl);
    });
  }

  document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.mesa-checkbox').forEach(cb => cb.checked = this.checked);
    toggleDeleteButton();
  });

  document.querySelectorAll('.mesa-checkbox').forEach(cb => cb.addEventListener('change', toggleDeleteButton));

  function toggleDeleteButton() {
    const any = Array.from(document.querySelectorAll('.mesa-checkbox')).some(cb => cb.checked);
    document.getElementById('deleteButtonWrapper').classList.toggle('d-none', !any);
  }

  // Modal confirmación
  document.addEventListener("DOMContentLoaded", function () {
    const deleteBtn = document.getElementById("deleteSelectedBtn");
    const confirmBtn = document.getElementById("confirmDeleteBtn");
    let selectedIds = [];

    deleteBtn.addEventListener("click", () => {
      selectedIds = Array.from(document.querySelectorAll('input[name="mesas[]"]:checked'))
        .map(checkbox => checkbox.value);

      if (selectedIds.length === 0) {
        alert("Selecciona al menos una mesa para eliminar.");
        return;
      }

      const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
      deleteModal.show();
    });

    confirmBtn.addEventListener("click", () => {
      fetch("../controller/mesaController.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mesas: selectedIds })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert("Error al eliminar mesas.");
        }
      })
      .catch(error => {
        console.error("Error en la petición:", error);
      });
    });
  });
</script>

<?php include '../view/bases/base2.php'; ?>
<?php include '../view/bases/base1.php'; ?>
</body>  
</html>
