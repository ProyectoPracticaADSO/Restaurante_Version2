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

// Traer todos los pedidos
$pedidos = $order->getOrder();

// Agrupar los pedidos por mesa
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Todos los Pedidos</title>
    <link rel="stylesheet" href="../css/StyleOrder.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
    <style>
        .mesa-container {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #F0F8FF;
        }

        .mesa-header {
            background-color: #F0F8FF;
            color: #333;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
        }

        .order-list {
            margin-bottom: 20px;
        }

        .button-container button {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1><strong>Ver Todos los Pedidos</strong></h1>
            <a href="../view/menus/MenuCocina.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        </header>
        <div class="orders-container">
            <?php if (!empty($pedidosPorMesa)) : ?>
                <?php foreach ($pedidosPorMesa as $idMesa => $pedidos) : ?>
                    <?php $mesa = $modelMesa->getMesaById($idMesa); ?>
                    <div class="mesa-container">
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
                                    $idIngredientes = $detalle['ingredientesId'];
                                    $idAdiciones = $detalle['adicionesId'];

                                    foreach (explode(',', $idIngredientes) as $idIngrediente) {
                                        if ($producto = $inventory->getProductById($idIngrediente)) {
                                            $nombreIngredientes[] = $producto['descripcion_producto'];
                                        }
                                    }

                                    foreach (explode(',', $idAdiciones) as $idAdicion) {
                                        if ($producto = $inventory->getProductById($idAdicion)) {
                                            $nombreAdiciones[] = $producto['descripcion_producto'];
                                        }
                                    }
                                    ?>
                                    <p><b>Categoria:</b> <?= $nombreCategoria['nombre_categoria'] ?></p>
                                    <p><b>Plato:</b> <?= $detallesPlato['nombre_producto'] ?> <b>Cantidad:</b> <?= $detalle['cantidad'] ?></p>
                                    <p><b>Ingredientes:</b> <?= implode(', ', $nombreIngredientes) ?></p>
                                    <p><b>Adiciones:</b> <?= implode(', ', $nombreAdiciones) ?></p>
                                    <p>-------------------------------------------</p>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-row">
                            <div class="button-container col-md-6">
                                <a href="EditarPedido.php?id=<?= $idMesa ?>"><button>Editar</button></a>
                            </div>
                            <div class="button-container col-md-6">
                                <button onclick="confirmarEliminacion(<?= $idMesa ?>)">Eliminar</button>
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
        document.addEventListener('DOMContentLoaded', function() {
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

        // Confirmación de eliminación
        function confirmarEliminacion(idMesa) {
            alertify.confirm(
                'Eliminar',
                '¿Está seguro de que desea eliminar este pedido?',
                function() {
                    window.location.href = `../controller/orderController.php?action=eliminarcocina&id=${idMesa}`;
                },
                function() {
                    alertify.error('Eliminación cancelada');
                }
            );
        }
    </script>
</body>

</html>
