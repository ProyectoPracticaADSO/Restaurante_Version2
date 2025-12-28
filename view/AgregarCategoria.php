
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Agregar Categoria</title>
  <link rel="stylesheet" href="../css/inventory.css">
  <link rel="stylesheet" href="../../css/modal.css"/>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

<body>
    
      <div class="container mt-5 position-relative">
  <?php include '../view/bases/base4.php'; ?>
  
        
        
 <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div class="d-flex align-items-center mb-3 mb-md-0">
      <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
      <h1 class="display-5 mb-0 mr-3">Agregar Categoria</h1>
    </div>
  </header>
        <?php
        
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">Categoría agregada exitosamente</div>';
        }
          // Mensaje de error si la categoría ya existe
        if (isset($_GET['error']) && $_GET['error'] == 'exists') {
            echo '<div class="alert alert-danger">El nombre de la categoría ya existe. Por favor, elige otro nombre.</div>';
        }
        ?>

    <form id="categoria-form" action="../controller/categoryController.php?action=agregar" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="text" name="nombre_categoria" placeholder="Nombre de la categoría" required pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios.">
        <div class="invalid-feedback">Por favor ingrese un nombre válido, solo letras y espacios.</div>

        <label for="imagen_categoria">Subir imagen:</label>
        <input type="file" name="imagen_categoria" id="imagen_categoria" accept="image/*" required>
        <div class="invalid-feedback">Por favor seleccione una imagen.</div>
      
            <button type="submit" class="btn btn-warning px-5">Crear Categoria</button>
    </form>

    </div>

    <script src="../js/validation.js"></script>
  
    <script src="../js/validation.js"></script>
    <script src="../js/adminValidation.js"></script>
    <script src="../js/inventorySearch.js"></script>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>
</body>

</html>