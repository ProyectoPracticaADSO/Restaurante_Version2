<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Empleado</title>
    <link rel="stylesheet" href="../css/inventory.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

    <style>
        /* Asegura espacio debajo del header si es fijo */
        .header-space {
            padding-top: 80px; /* Ajusta esto a la altura de tu header fijo */
        }
    </style>
</head>

<body>
    <!-- Contenedor blanco principal -->
<div class="container mt-5 position-relative">
  <?php include '../view/bases/base4.php'; ?>

  <?php
  if (isset($_GET['error'])) {
      echo '<div class="alert alert-danger">Error al agregar usuario.</div>';
  } elseif (isset($_GET['success'])) {
      echo '<div class="alert alert-success">Usuario agregado exitosamente.</div>';
  }
  ?>

  <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div class="d-flex align-items-center mb-3 mb-md-0">
      <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
      <h1 class="display-5 mb-0 mr-3">Agregar Empleados</h1>
    </div>
  </header>

  <!-- Formulario centrado -->
  <div class="mx-auto" style="max-width: 800px;">
    <form action="../controller/userController.php?action=agregar" method="POST" class="needs-validation" novalidate>
      
      <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" class="form-control" pattern="^[A-Za-zÀ-ÿ\u00f1\u00d1]+(?: [A-Za-zÀ-ÿ\u00f1\u00d1]+)*$" id="nombre" name="nombre" required>
        <div class="invalid-feedback">Por favor ingrese el nombre (Verifique los espacios en blanco).</div>
      </div>

      <!-- Cédula y Número en la misma fila -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="cedula">Cédula:</label>
          <input type="text" class="form-control" id="cedula" name="cedula" pattern="\d+" required>
          <div class="invalid-feedback">Por favor ingrese la cédula (solo números).</div>
        </div>
        <div class="form-group col-md-6">
          <label for="numero">Número:</label>
          <input type="text" class="form-control" id="numero" name="numero" pattern="\d+" required>
          <div class="invalid-feedback">Por favor ingrese el celular (solo números).</div>
        </div>
      </div>

      <div class="form-group">
        <label for="correo">Correo:</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
        <div class="invalid-feedback">Por favor ingrese el correo.</div>
      </div>

      <!-- Rol y Estado en la misma fila -->
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="perfil">Rol:</label>
          <select class="form-control" id="perfil" name="perfil" required>
            <option value="">Seleccione un perfil</option>
            <option value="1">Administrador</option>
            <option value="2">Mesero</option>
            <option value="3">Caja</option>
            <option value="4">Cocina</option>
          </select>
          <div class="invalid-feedback">Por favor seleccione un perfil.</div>
        </div>
        <div class="form-group col-md-6">
          <label for="estado">Estado:</label>
          <select class="form-control" id="estado" name="estado" required>
            <option value="">Seleccione un estado</option>
            <option value="1">Activo</option>
            <option value="2">Inactivo</option>
          </select>
          <div class="invalid-feedback">Por favor seleccione un estado.</div>
        </div>
      </div>

      <div class="form-group" id="contrasenaDiv" style="display:none;">
        <label for="contrasena">Contraseña:</label>
        <input type="password" class="form-control" pattern="^[A-Za-zÀ-ÿ\u00f1\u00d1\d]+(?: [A-Za-zÀ-ÿ\u00f1\u00d1\d]+)*$" id="contrasena" name="contrasena" required>
        <div class="invalid-feedback">Por favor ingrese una contraseña.</div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-warning px-5">Actualizar</button>
      </div>
    </form>
  </div>
</div>


    <script src="../js/validation.js"></script>
    <script src="../js/adminValidation.js"></script>
    <script src="../js/inventorySearch.js"></script>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>
</body>

</html>
