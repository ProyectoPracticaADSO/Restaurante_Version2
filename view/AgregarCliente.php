<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agregar Cliente</title>

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
      <h2>Agregar Cliente</h2>
    </header>

    <!-- MENSAJES -->
    <?php if (isset($_GET['error'])): ?>
      <script>
        alertify.set('notifier', 'position', 'top-right');

        <?php if ($_GET['error'] == 'campos_vacios'): ?>
          alertify.error('Todos los campos son obligatorios');
        <?php elseif ($_GET['error'] == 'cedula_existe'): ?>
          alertify.error('El documento ya está registrado');
        <?php elseif ($_GET['error'] == 'correo_existe'): ?>
          alertify.error('El correo ya está registrado');
        <?php else: ?>
          alertify.error('Error al registrar cliente');
        <?php endif; ?>
      </script>
    <?php endif; ?>

    <!-- FORMULARIO -->
    <div class="mx-auto" style="max-width: 500px;">
      <form id="formAgregarCliente" action="../controller/customerController.php?action=agregar" method="POST" class="needs-validation" novalidate>

        <!-- NOMBRE -->
        <div class="form-group">
          <label>Nombre:</label>
          <input type="text" class="form-control"
            name="nombre"
            pattern="^[A-Za-zÀ-ÿ\u00f1\u00d1]+(?: [A-Za-zÀ-ÿ\u00f1\u00d1]+)*$"
            required>
          <div class="invalid-feedback">Ingrese un nombre válido.</div>
        </div>

        <!-- DOCUMENTO -->
        <div class="form-group">
          <label>Número de documento:</label>
          <input type="text" class="form-control"
            name="numeroDocumento"
            pattern="\d+"
            required>
          <div class="invalid-feedback">Ingrese solo números.</div>
        </div>

        <!-- TELÉFONO -->
        <div class="form-group">
          <label>Teléfono:</label>
          <input type="text" class="form-control"
            name="telefonoCliente"
            pattern="\d+"
            required>
          <div class="invalid-feedback">Ingrese solo números.</div>
        </div>

        <!-- CORREO -->
        <div class="form-group">
          <label>Correo:</label>
          <input type="email" class="form-control"
            name="correoCliente"
            required>
          <div class="invalid-feedback">Ingrese un correo válido.</div>
        </div>

        <!-- BOTONES -->
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-warning px-5">Guardar</button>
          <a href="GestionClientes.php" class="btn btn-secondary">Volver</a>
        </div>

      </form>
    </div>
  </div>

  <!-- VALIDACIÓN JS -->
  <script>
    document.getElementById('formAgregarCliente').addEventListener('submit', function(e) {
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