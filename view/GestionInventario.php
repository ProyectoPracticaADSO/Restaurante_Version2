<?php  
include_once '../model/inventory.php';

$inventory = new Inventory();
$products = $inventory->getInventory();

$productoMasViejo = $inventory->getOldestProduct();
$productoProximoVencer = $inventory->getNearestExpiringProduct();
$totalValorBodega = $inventory->getTotalWarehouseValue();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Inventario</title>
  <link rel="stylesheet" href="../css/inventory.css">
  <link rel="stylesheet" href="../../css/modal.css"/>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>
<body>
 
  <div class="container mt-1">
    <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
  <div class="d-flex align-items-center mb-3 mb-md-0">
    <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
    <h1 class="display-5 mb-0 mr-3">Inventario</h1>
    
    <div class="btn-group ml-3 flex-wrap">
      <a href="AgregarProducto.php" class="btn btn-outline-secondary mx-1 mb-1">Agregar Producto</a>
      <button id="btn-oldest" class="btn btn-outline-primary mx-1 mb-1">Producto más viejo</button>
      <button id="btn-nearest-expiring" class="btn btn-outline-warning mx-1 mb-1">Próximo a vencer</button>
      <button id="btn-total-value" class="btn btn-outline-success mx-1 mb-1">Valor total</button>
    </div>
  </div>
  <a href="./menus/MenuAdmin.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left fa-lg"></i></a>
</header>
    

    <div class="table-wrapper table-responsive">
      <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Unidad</th>
            <th>Precio Unitario</th>
            <th>Ingreso</th>
            <th>Vencimiento</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tbl_productos">
          <?php foreach ($products as $product) : ?>
            <tr>
              <td><?= $product['id'] ?></td>
              <td><?= $product['descripcion_producto'] ?></td>
              <td><?= $product['nombre_tipos'] ?></td>
              <td><?= $product['cantidad'] ?></td>
              <td><?= $product['nombre_unidad_medida'] ?></td>
              <td><?= number_format($product['precio_unitario']) ?>$</td>
              <td><?= $product['fecha_ingreso'] ?></td>
              <td><?= $product['fecha_vencimiento'] ?></td>
              <td class="action-icons">
                <a href="EditarProducto.php?id=<?= $product['id'] ?>" title="Editar">
                  <i class="fa fa-edit"></i>
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
  
  
  
  <script>
    window.oldestProductData = <?= json_encode($productoMasViejo) ?>;
    window.nearestExpiringData = <?= json_encode($productoProximoVencer) ?>;
    window.totalWarehouseValue = <?= json_encode($totalValorBodega) ?>;
  </script>
  <script src="../js/inventorySearch.js"></script>
  <script src="../../modal.js"></script>
  <?php include '../view/bases/base2.php'; ?>
  <?php include '../view/bases/base1.php'; ?>
  
  <!-- MODAL DE CONFIRMACIÓN 
  <div id="modal-confirm" class="modal-overlay">
    <div class="modal-content">
      <h3>¿Estás seguro que deseas eliminar este Producto?</h3>
      <div class="modal-actions">
        <a id="btn-confirm" class="btn-confirm">Sí, salir</a>
        <button id="btn-cancel" class="btn-cancel">Cancelar</button>
      </div>
    </div>
  </div>-->


</body>
</html>