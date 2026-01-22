<?php
require_once '../model/inventory.php';

if (isset($_GET['action'])) {
    $inventory = new Inventory();

    // ✅ Agregar producto
    if ($_GET['action'] == 'agregar') {

        // 1. Asegurar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../view/error.php?msg=acceso_no_valido");
            exit();
        }

        // 2. Capturar datos
        $id = 0; // autoincrement
        $descripcion = trim($_POST['descripcion']);
        $tipoProducto = trim($_POST['tipoProducto']);
        $cantidad = trim($_POST['cantidad']);
        $unidadMedida = trim($_POST['unidadMedida']);
        $precioUnitario = trim($_POST['precio_unitario']);
        $fechaIngreso = trim($_POST['fechaIngreso']);
        $fechaVencimiento = trim($_POST['fechaVencimiento']);

        // 3. Validar campos obligatorios (fechas NO opcionales)
        if (
            empty($descripcion) ||
            empty($tipoProducto) ||
            empty($cantidad) ||
            empty($unidadMedida) ||
            empty($precioUnitario) ||
            empty($fechaIngreso) ||
            empty($fechaVencimiento)
        ) {
            header("Location: ../view/AgregarProducto.php?error=campos_vacios");
            exit();
        }

        // 4. Validaciones lógicas
        if (!is_numeric($cantidad) || $cantidad <= 0) {
            header("Location: ../view/AgregarProducto.php?error=cantidad_invalida");
            exit();
        }

        if (!is_numeric($precioUnitario) || $precioUnitario <= 0) {
            header("Location: ../view/AgregarProducto.php?error=precio_invalido");
            exit();
        }

        if ($fechaVencimiento < $fechaIngreso) {
            header("Location: ../view/AgregarProducto.php?error=fechas_invalidas");
            exit();
        }

        $fechaIngreso = $_POST['fechaIngreso'] ?? '';
        $fechaVencimiento = $_POST['fechaVencimiento'] ?? '';

        if (empty($fechaIngreso) || empty($fechaVencimiento)) {
            header('Location: ../view/AgregarProducto.php?error=fechas_vacias');
            exit();
        }


        // 5. Guardar en BD
        $resultado = $inventory->insertProduct(
            $id,
            $descripcion,
            $tipoProducto,
            $cantidad,
            $unidadMedida,
            $precioUnitario,
            $fechaIngreso,
            $fechaVencimiento
        );

        if ($resultado) {
            header('Location: ../view/AgregarProducto.php?success');
        } else {
            header('Location: ../view/AgregarProducto.php?error=bd');
        }
        exit();
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
