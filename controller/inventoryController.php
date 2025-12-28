<?php
require_once '../model/inventory.php';

if (isset($_GET['action'])) {
    $inventory = new Inventory();

    // Agregar producto
    if ($_GET['action'] == 'agregar') {
        $id = '0'; // Al ser autoincrementable se pone 0.
        $descripcion = $_POST['descripcion'];
        $tipoProducto = $_POST['tipoProducto'];
        $cantidad = $_POST['cantidad'];
        $unidadMedida = $_POST['unidadMedida'];
        $precioUnitario = $_POST['precio_unitario'];
        $fechaIngreso = $_POST['fechaIngreso'];
        $fechaVencimiento = $_POST['fechaVencimiento'];

        $resultado = $inventory->insertProduct($id, $descripcion, $tipoProducto, $cantidad, $unidadMedida, $precioUnitario, $fechaIngreso, $fechaVencimiento);

        if ($resultado) {
            header('Location: ../view/AgregarProducto.php?success');
        } else {
            header('Location: ../view/AgregarProducto.php?error');
        }
    }

    // Eliminar producto
    if ($_GET['action'] == 'eliminar' && isset($_GET['id'])) {
        $idProducto = $_GET['id'];

        $resultado = $inventory->deleteProduct($idProducto);

        if ($resultado) {
            header('Location: ../view/GestionInventario.php?success=eliminado');
        } else {
            header('Location: ../view/GestionInventario.php');
        }
    }

    // Editar producto
    if ($_GET['action'] == 'editar' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $descripcion = $_POST['descripcion'];
        $tipoProducto = $_POST['tipoProducto'];
        $cantidad = $_POST['cantidad'];
        $unidadMedida = $_POST['unidadMedida'];
        $precioUnitario = $_POST['precio_unitario'];
        $fechaIngreso = $_POST['fechaIngreso'];
        $fechaVencimiento = $_POST['fechaVencimiento'];

        $resultado = $inventory->updateProduct($id, $descripcion, $tipoProducto, $cantidad, $unidadMedida, $precioUnitario, $fechaIngreso, $fechaVencimiento);

        if ($resultado) {
            header('Location: ../view/GestionInventario.php?success=actualizado');
        } else {
            header('Location: ../view/EditarUsuario.php?id=' . $id . '&error');
        }
    }

    // Obtener producto más viejo
    if ($_GET['action'] == 'oldest') {
        $oldestProduct = $inventory->getOldestProduct();
        echo json_encode($oldestProduct);
    }

    // Obtener producto más próximo a vencer
    if ($_GET['action'] == 'nearest_expiring') {
        $nearestExpiring = $inventory->getNearestExpiringProduct();
        echo json_encode($nearestExpiring);
    }

    // Obtener valor total de la bodega
    if ($_GET['action'] == 'total_value') {
        $totalValue = $inventory->getTotalWarehouseValue();
        echo json_encode(['total' => $totalValue]);
    }
}
?>
