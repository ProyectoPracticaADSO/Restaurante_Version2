<?php
include_once '../model/category.php';

$category = new Category();

if (isset($_GET['id'])) {
    $idCategory = $_GET['id'];
    $categoria = $category->getCategoryById($idCategory);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../css/inventory.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css"/>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>
<body>

    <div class="container mt-5 position-relative">
        <?php include '../view/bases/base4.php'; ?>

        <!-- HEADER -->
        <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="d-flex align-items-center">
                <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 60px; margin-right: 15px;">
                <h1 class="m-0" style="font-size: 30px;"><strong>Editar Categoría</strong></h1>
            </div>
        </header>

        <!-- FORMULARIO -->
        <form action="../controller/categoryController.php?action=editar&id=<?= $categoria['id'] ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $categoria['id'] ?>">

            <div class="form-group">
                <label for="nombre_categoria">Nombre de la Categoría</label>
                <input type="text" class="form-control" name="nombre_categoria" id="nombre_categoria" placeholder="Nombre de la categoría" value="<?= $categoria['nombre_categoria'] ?>" required pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios.">
            </div>

           <button type="submit" class="btn btn-warning px-5">Actualizar</button>
        </form>
    </div>

    <!-- JS SCRIPTS -->
    <script src="../js/validation.js"></script>
    <script src="../js/adminValidation.js"></script>
    <script src="../js/inventorySearch.js"></script>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>

</body>
</html>
