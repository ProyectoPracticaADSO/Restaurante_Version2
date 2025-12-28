<?php
include_once '../model/plate.php';
include_once '../model/category.php';
include_once '../model/inventory.php';

$inventory = new Inventory();
$category = new Category();

$categories = $category->getCategory();

$menu = new Plate();

$categoriaActual = '';

if (isset($_GET['id'])) {
  $idCategoria = $_GET['id'];
  $plates = $menu->getPlateByCategory($idCategoria);

  foreach ($categories as $category) {
    if ($category['id'] == $idCategoria) {
      $categoriaActual = $category['nombre_categoria'];
      break;
    }
  }
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Productos</title>
  <link rel="stylesheet" href="../css/StyleEditProducts.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>

<body>

  <div class="container">

    <?php include '../view/bases/base4.php'; ?>
    <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      <div class="d-flex align-items-center mb-3 mb-md-0">
        <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
        <h1>Editar Productos <strong><?= $categoriaActual ?></strong></h1>

      </div>


      <a href="./GestionCategorias.php" class="back-icon"></a>
    </header>

    <div class="product-grid">
      <?php if ($plates) {
        foreach ($plates as $plate) :
          $idIngredientes = $plate['ingredientes_producto'];
          $idAdiciones = $plate['posibles_adiciones'];

          $nombreProducto = [];
          $nombreAdiciones = [];

          foreach (explode(',', $idIngredientes) as $idIngrediente) {
            if ($producto = $inventory->getProductById($idIngrediente)) {
              array_push($nombreProducto, $producto['descripcion_producto']);
            }
          }
          foreach (explode(',', $idAdiciones) as $idAdicion) {
            if ($producto = $inventory->getProductById($idAdicion)) {
              array_push($nombreAdiciones, $producto['descripcion_producto']);
            }
          }
      ?>
          <div class="product-box">
            <div class="product-info">
              <p><strong>Nombre: </strong><?= $plate['nombre_producto'] ?></p>
              <p><strong>Categoría: </strong><?= $plate['nombre_categoria'] ?></p>
              <p><strong>Descripción: </strong><?= $plate['descripcion_producto'] ?></p>
              <p><strong>Ingredientes: </strong><?= implode(', ', $nombreProducto) ?></p>
              <p><strong>Posibles adiciones: </strong><?= implode(', ', $nombreAdiciones) ?></p>
              <p><strong>Precio: </strong><?= number_format($plate['precio_producto']) ?>$</p>
            </div>
            <div class="actions">
              <a href="EditarMenu.php?action=editar&id=<?= $plate['id'] ?>" class="icon btn-lg"><i class="fas fa-pen"></i></a>
              <a href="../controller/menuController.php?action=eliminar&id=<?= $plate['id'] ?>&idCat=<?= $plate['categoria_id'] ?>" onclick="return confirm('¿Estás seguro de eliminar el plato <?= $plate['nombre_producto'] ?>?')" class="icon btn-lg"><i class="fas fa-trash"></i></a>
            </div>
          </div>
      <?php endforeach;
      } else {
        echo "No se encontraron platos para esta categoría.";
      } ?>
    </div>
    <div class="add-category">
      <a href="AgregarMenu.php?id=<?= $idCategoria ?>"><i class="fa-solid fa-plus"></i></a>
    </div>
  </div>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('success');
    if (mensaje === 'eliminado') {
      alertify.success('Plato eliminado correctamente');
    }
    if (mensaje === 'actualizado') {
      alertify.success('Plato actualizado correctamente');
    }
  </script>

  <?php include '../view/bases/base2.php'; ?>
  <?php include '../view/bases/base1.php'; ?>

</body>

</html>