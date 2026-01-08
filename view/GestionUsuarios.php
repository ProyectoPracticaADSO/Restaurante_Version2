<?php
include_once '../model/user.php';
$user = new User();
$usuarios = $user->getUser();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Personal</title>
  <link rel="stylesheet" href="../css/inventory.css">
  <link rel="stylesheet" href="../css/modal.css" />
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

</head>

<body>
  <div class="container mt-5 position-relative">
    <?php include '../view/bases/base4.php'; ?>

    <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <div class="d-flex align-items-center mb-3 mb-md-0">
        <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 40px; margin-right: 5px;">
        <h1 class="display-6 mb-0 mr-1">Empleados</h1>
        <div class="btn-group ml-3 flex-wrap">
          <br> <br>
          <a href="AgregarUsuario.php" class="btn btn-outline-secondary mx-0.5 mb-0.5">Agregar Empleado</a>
        </div>
      </div>

    </header>

    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Buscar empleado...">
      <i class="fa fa-search"></i>
    </div>

    <div class="table-wrapper table-responsive">
      <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Cédula</th>
            <th>Número</th>
            <th>Correo</th>
            <th>Perfil</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $usuario) : ?>
            <tr>
              <td><?= $usuario['id'] ?></td>
              <td><?= $usuario['nombre_usuario'] ?></td>
              <td><?= $usuario['cedula_usuario'] ?></td>
              <td><?= $usuario['numero_usuario'] ?></td>
              <td><?= $usuario['correo_usuario'] ?></td>
              <td><?= $usuario['nombre_perfil'] ?></td>
              <td><?= $usuario['nombre_estado'] ?></td>
              <td class="action-icons">
                <a href="EditarUsuario.php?action=editar&id=<?= $usuario['id'] ?>" title="Editar">
                  <i class="fa-solid fa-user-pen"></i>
                </a>
                <a href="#" class="eliminar-btn"
                  data-id="<?= $usuario['id'] ?>"
                  data-nombre="<?= $usuario['nombre_usuario'] ?>" title="Eliminar">
                  <i class="fa fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div id="result-container" class="alert alert-info text-center" style="display: none;"></div>
  </div>


</body>

</html>

<!-- MODAL CONFIRMACIÓN ELIMINAR -->
<div id="modal-confirm" class="modal-overlay">
  <div class="modal-content">
    <h5>Confirmar eliminación</h5>
    <p>¿Está seguro de eliminar este usuario?</p>
    <div class="text-right">
      <a id="btn-confirm" class="btn btn-danger">Eliminar</a>
      <button id="btn-cancel" class="btn btn-secondary">Cancelar</button>
    </div>
  </div>
</div>




<script src="../model/js/modal.js"></script>
<script src="../model/js/searchGestionUsuarios.js"></script>
<?php include '../view/bases/base2.php'; ?>
<?php include '../view/bases/base1.php'; ?>




</body>

</html>