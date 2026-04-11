<?php
include_once '../model/customer.php';

$customer = new Customer();

if (isset($_GET['id'])) {
  $idCliente = $_GET['id'];
  $cliente = $customer->getCustomerById($idCliente);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Cliente</title>

  <link rel="stylesheet" href="../css/inventory.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

  <style>
    .ajs-message {
      top: -60px !important;
    }
  </style>
</head>

<body>

  <div class="container mt-3.5 position-relative">
    <?php include '../view/bases/base4.php'; ?>

    <header class="d-flex align-items-center mb-4">
      <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png" style="height: 50px; margin-right: 10px;">
      <h2>Editar Cliente</h2>
    </header>

    <!-- MENSAJES -->
    <?php if (isset($_GET['error'])): ?>
      <script>
        alertify.set('notifier', 'position', 'top-right');

        <?php if ($_GET['error'] == 'campos_vacios'): ?>
          alertify.error('Todos los campos son obligatorios');
        <?php else: ?>
          alertify.error('Error al actualizar cliente');
        <?php endif; ?>
      </script>
    <?php endif; ?>

    <div class="mx-auto" style="max-width: 700px;">

      <form id="formEditarCliente" action="../controller/customerController.php?action=editar" method="POST" class="needs-validation" novalidate>

        <!-- ID OCULTO -->
        <input type="hidden" name="id" value="<?= $cliente['id'] ?>">

        <!-- NOMBRE -->
        <div class="form-group">
          <label>Nombre:</label>
          <input type="text" class="form-control"
            name="nombre"
            value="<?= $cliente['nombre_cliente'] ?>"
            pattern="^[A-Za-zÀ-ÿ\u00f1\u00d1]+(?: [A-Za-zÀ-ÿ\u00f1\u00d1]+)*$"
            required>
          <div class="invalid-feedback">Ingrese un nombre válido.</div>
        </div>

        <!-- DOCUMENTO -->
        <div class="form-group">
          <label>Número de documento:</label>
          <input type="text" class="form-control"
            name="numeroDocumento"
            value="<?= $cliente['cedula_cliente'] ?>"
            pattern="\d+"
            required>
          <div class="invalid-feedback">Ingrese solo números.</div>
        </div>

        <!-- TELÉFONO -->
        <div class="form-group">
          <label>Teléfono:</label>
          <input type="text" class="form-control"
            name="telefonoCliente"
            value="<?= $cliente['numero_cliente'] ?>"
            pattern="\d+"
            required>
          <div class="invalid-feedback">Ingrese solo números.</div>
        </div>

        <!-- CORREO -->
        <div class="form-group">
          <label>Correo:</label>
          <input type="email" class="form-control"
            name="correoCliente"
            value="<?= $cliente['correo_cliente'] ?>"
            required>
          <div class="invalid-feedback">Ingrese un correo válido.</div>
        </div>

        <!-- BOTONES -->
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-warning px-5">Actualizar</button>
          <a href="GestionClientes.php" class="btn btn-secondary">Volver</a>
        </div>

      </form>
    </div>
  </div>

  <!-- VALIDACIÓN JS -->
  <script>
    document.getElementById('formEditarCliente').addEventListener('submit', function(e) {
      let campos = this.querySelectorAll('[required]');
      let valido = true;

      campos.forEach(campo => {
        if (!campo.value.trim()) {
          valido = false;
        }
      });

      if (!valido) {
        e.preventDefault();
        alertify.error('Todos los campos son obligatorios');
      }
    });
  </script>

  <script src="../model/js/validation.js"></script>
  <script src="../model/js/adminValidation.js"></script>
  <script src="../model/js/inventorySearch.js"></script>

  <?php include '../view/bases/base2.php'; ?>
  <?php include '../view/bases/base1.php'; ?>

</body>

</html>