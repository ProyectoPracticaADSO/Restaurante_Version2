<?php
include_once '../model/order.php';
include_once '../model/category.php';
include_once '../model/plate.php';
include_once '../model/inventory.php';
include_once '../model/mesa.php';

$order = new Order();
$category = new Category();
$plate = new Plate();
$inventory = new Inventory();
$modelMesa = new Mesa();

$pedidos = $order->getOrder();

// Agrupar pedidos por mesa
$pedidosPorMesa = [];
foreach ($pedidos as $pedido) {
    $mesaId = $pedido['fk_id_mesas'];
    if (!isset($pedidosPorMesa[$mesaId])) {
        $pedidosPorMesa[$mesaId] = [];
    }
    $pedidosPorMesa[$mesaId][] = $pedido;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Pedidos</title>
  <link rel="stylesheet" href="../css/inventory.css">
  <link rel="stylesheet" href="../../css/modal.css"/>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>

<body>
  <div class="container mt-5 position-relative">
    <?php include '../view/bases/base4.php'; ?>

    <!-- Encabezado -->
    <header class="d-flex align-items-center mb-4">
      <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
      <h1 class="mb-0"><strong>Gestión de Pedidos</strong></h1>
    </header>

    <!-- Contenedor de mesas -->
    <div class="orders-container">
      <?php if (!empty($pedidosPorMesa)) : ?>
        <?php foreach ($pedidosPorMesa as $idMesa => $pedidos) : ?>
          <?php $mesa = $modelMesa->getMesaById($idMesa); ?>
          <div class="mesa-container border p-3 mb-4 rounded shadow-sm">
            <div class="mesa-header d-flex justify-content-between align-items-center">
              <h4>Mesa #<?= $mesa['numero_mesa'] ?></h4>
              <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPedido<?= $idMesa ?>">Ver Detalles</button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalPedido<?= $idMesa ?>" tabindex="-1" role="dialog" aria-labelledby="modalPedidoLabel<?= $idMesa ?>" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalPedidoLabel<?= $idMesa ?>">Pedido - Mesa #<?= $mesa['numero_mesa'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <?php foreach ($pedidos as $pedido) : ?>
                      <?php $detallePedido = $pedido['pedido']; ?>
                      <?php foreach ($detallePedido as $detalle) : ?>
                        <?php 
                          $nombreCategoria = $category->getCategoryById($detalle['categoriaId']);
                          $detallesPlato = $plate->getPlateById($detalle['platoId']);
                          $nombreIngredientes = [];
                          $nombreAdiciones = [];

                          foreach (explode(',', $detalle['ingredientesId']) as $idIngrediente) {
                              if ($producto = $inventory->getProductById($idIngrediente)) {
                                  $nombreIngredientes[] = $producto['descripcion_producto'];
                              }
                          }

                          foreach (explode(',', $detalle['adicionesId']) as $idAdicion) {
                              if ($producto = $inventory->getProductById($idAdicion)) {
                                  $nombreAdiciones[] = $producto['descripcion_producto'];
                              }
                          }
                        ?>
                        <div class="order-item border-bottom py-2">
                          <p><strong>Categoría:</strong> <?= $nombreCategoria['nombre_categoria'] ?></p>
                          <p><strong>Plato:</strong> <?= $detallesPlato['nombre_producto'] ?> <strong>Cantidad:</strong> <?= $detalle['cantidad'] ?></p>
                          <p><strong>Ingredientes:</strong> <?= implode(', ', $nombreIngredientes) ?></p>
                          <p><strong>Adiciones:</strong> <?= implode(', ', $nombreAdiciones) ?></p>
                        </div>
                      <?php endforeach; ?>
                    <?php endforeach; ?>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botones -->
            <div class="form-row mt-3">
              <div class="col-md-6">
                <a href="EditarPedido.php?id=<?= $idMesa ?>" class="btn btn-warning btn-block">Editar</a>
              </div>
              <div class="col-md-6">
                <button onclick="confirmarEliminacion(<?= $idMesa ?>)" class="btn btn-danger btn-block">Eliminar</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>No se encontraron pedidos.</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      localStorage.clear();
    });

    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('success');
    if (mensaje === 'actualizado') {
      alertify.success('Pedido actualizado correctamente');
    }
    if (mensaje === 'eliminado') {
      alertify.success('Producto eliminado correctamente');
    }

    function confirmarEliminacion(idMesa) {
      alertify.confirm(
        'Eliminar',
        '¿Está seguro de que desea eliminar este pedido?',
        function () {
          window.location.href = `../controller/orderController.php?action=eliminarcocina&id=${idMesa}`;
        },
        function () {
          alertify.error('Eliminación cancelada');
        }
      );
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../modal.js"></script>
  <?php include '../view/bases/base2.php'; ?>
  <?php include '../view/bases/base1.php'; ?>
</body>
</html>
