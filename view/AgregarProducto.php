<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto</title>
    <link rel="stylesheet" href="../css/inventory.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>

<body>
   <!-- Contenedor blanco principal -->
<div class="container mt-5 position-relative">
  <?php include '../view/bases/base4.php'; ?>

  <?php
  if (isset($_GET['error'])) {
      echo '<div class="alert alert-danger">Error al agregar producto.</div>';
  } elseif (isset($_GET['success'])) {
      echo '<div class="alert alert-success">producto agregado exitosamente.</div>';
  }
  ?>

  <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div class="d-flex align-items-center mb-3 mb-md-0">
      <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
      <h1 class="display-5 mb-0 mr-3">Agregar Productos</h1>
    </div>
  </header>

        <form action="../controller/inventoryController.php?action=agregar" method="POST" class="needs-validation" novalidate>

            <div class="form-group">
                <label for="descripcion">Descripción del producto:</label>
                <input type="text" class="form-control"  pattern="^\S+(?: \S+)*$" id="descripcion" name="descripcion" maxlength="100" required>
                <div class="invalid-feedback">Por favor ingrese la descripción del producto (Verifique los espacios en blanco).</div>
            </div>

            <div class="form-row">

                <div class="form-group col-md-4">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" step="0.1" min="0.1" max="99999999" class="form-control" id="cantidad" name="cantidad" pattern="\d+" required>
                    <div class="invalid-feedback">Por favor ingrese la cantidad (solo números).</div>
                </div>

                <div class="form-group col-md-4">
                    <label for="Unidad-medida">Unidad de medida:</label>
                    <select class="form-control" id="unidadMedida" name="unidadMedida" required>
                        <option value="">Seleccione una unidad de medida</option>
                        <option value="1">G</option>
                        <option value="2">Kg</option>
                        <option value="3">Oz</option>
                        <option value="4">Ml</option>
                        <option value="5">L</option>
                        <option value="6">Mg</option>
                        <option value="7">Lb</option>
                        <option value="8">Cm</option>
                        <option value="9">Otro</option>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione una unidad de medida.</div>
                </div>

                <div class="form-group col-md-4">
                    <label for="precioUnitario">Precio Unitario:</label>
                    <input type="text" class="form-control" id="precio-unitario" name="precio_unitario" maxlength="10" pattern="^[0-9]+$" required>
                    <div class="invalid-feedback">Por favor ingrese el precio unitario (sin punto ni coma).</div>
                </div>
                
            </div>

                <div class="form-group">
                    <label for="tipo">Tipo del producto:</label>
                    <select class="form-control" id="tipoProducto" name="tipoProducto" required>
                        <option value="">Seleccione un tipo</option>
                        <option value="1">Físico</option>
                        <option value="2">Digital</option>
                        <option value="3">Otro</option>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione el tipo del producto.</div>
                </div>


            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="fecha-ingreso">Fecha de ingreso:</label>
                    <input class="form-control" type="date" id="fechaIngreso" name="fechaIngreso" value="" min="2024-01-01" max="2030-12-31" required />
                    <div class="invalid-feedback">Por favor seleccione una fecha.</div>
                </div>

                <div class="form-group col-md-6">
                    <label for="fecha-vencimiento">Fecha de vencimiento:</label>
                    <input class="form-control" type="date" id="fechaVencimiento" name="fechaVencimiento" value="" min="2024-01-01" max="2030-12-31"required />
                    <div class="invalid-feedback">Por favor seleccione una fecha.</div>
                </div>

                <div class="button-container col-md-12 d-flex justify-content-center">
                <button type="submit" class="btn btn-warning px-5">Crear</button>
                </div>

        </form>
    </div>
        <script src="../js/validation.js"></script>
    </div>
    
  <script src="../js/validation.js"></script>
    <script src="../js/adminValidation.js"></script>
    <script src="../js/inventorySearch.js"></script>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>
</body>
      
</html>