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

        <!-- LOGO + TÍTULO -->
  <header class="mb-4 d-flex align-items-center">
    <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 60px; margin-right: 15px;">
    <h1 class="m-0" style="font-size: 1.80rem;"><strong>Gestión de Pedidos</strong></h1>
</header>


        <div class="orders-container">
            <?php if (!empty($pedidosPorMesa)) : ?>
                <?php foreach ($pedidosPorMesa as $idMesa => $pedidos) : ?>
                    <?php $mesa = $modelMesa->getMesaById($idMesa); ?>
                    <div class="mesa-container border p-3 mb-4 shadow rounded">
                        <div class="mesa-header">
                            <h2>Mesa #<?= $mesa['numero_mesa'] ?></h2>
                        </div>
                        <div class="order-list">
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
                                        <p><b>Categoria:</b> <?= $nombreCategoria['nombre_categoria'] ?></p>
                                        <p><b>Plato:</b> <?= $detallesPlato['nombre_producto'] ?> <b>Cantidad:</b> <?= $detalle['cantidad'] ?></p>
                                        <p><b>Ingredientes:</b> <?= implode(', ', $nombreIngredientes) ?></p>
                                        <p><b>Adiciones:</b> <?= implode(', ', $nombreAdiciones) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Botones -->
                        <div class="form-row mt-3">
                            <div class="col-md-4">
                                <a href="EditarPedido.php?id=<?= $idMesa ?>"><button class="btn btn-warning w-100">Editar</button></a>
                            </div>
                            <div class="col-md-4">
                                <button onclick="confirmarEliminacion(<?= $idMesa ?>)" class="btn btn-danger w-100">Eliminar</button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-info w-100" data-toggle="modal" data-target="#modalPedido<?= $idMesa ?>">Ver Detalle</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal por mesa -->
                    <div class="modal fade" id="modalPedido<?= $idMesa ?>" tabindex="-1" role="dialog" aria-labelledby="modalPedidoLabel<?= $idMesa ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modalPedidoLabel<?= $idMesa ?>">Detalle del Pedido - Mesa #<?= $mesa['numero_mesa'] ?></h5>
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
                                        <p><b>Categoria:</b> <?= $nombreCategoria['nombre_categoria'] ?></p>
                                        <p><b>Plato:</b> <?= $detallesPlato['nombre_producto'] ?> <b>Cantidad:</b> <?= $detalle['cantidad'] ?></p>
                                        <p><b>Ingredientes:</b> <?= implode(', ', $nombreIngredientes) ?></p>
                                        <p><b>Adiciones:</b> <?= implode(', ', $nombreAdiciones) ?></p>
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

    <!-- Scripts necesarios para el modal -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>
</body>
</html>

