<?php
require_once '../model/mesa.php';

$mesaModel = new Mesa();

if (!isset($_GET['id'])) {
    header('Location: GestionMesas.php');
    exit;
}

$id = $_GET['id'];
$mesa = $mesaModel->getMesaById($id);

if (!$mesa) {
    echo "Mesa no encontrada.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNumero = $_POST['numero_mesa'];
    $nuevoEstado = $_POST['estado'];

    $mesaModel->actualizarMesa($id, $nuevoNumero, $nuevoEstado);
    header('Location: GestionMesas.php?success=editado');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mesa</title>
    <link rel="stylesheet" href="../css/inventory.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

    <style>
        .btn-orange {
            background-color: #f4a261; /* naranja suave */
            color: white;
            border: none;
        }

        .btn-orange:hover {
            background-color: #e0763b;
            color: white;
        }

        body {
            padding-top: 70px;
        }
    </style>
</head>

<body>
    <!-- Contenedor blanco principal -->
<div class="container mt-5 position-relative">
  <?php include '../view/bases/base4.php'; ?>

        <h1 class="display-5 mb-0 mr-3">Editar Mesa</h1>
        <form method="POST">
            <div class="form-group">
                <label for="numero_mesa">Número de Mesa:</label>
                <input type="number" id="numero_mesa" name="numero_mesa" class="form-control" required value="<?= htmlspecialchars($mesa['numero_mesa']) ?>">
            </div>

            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado" class="form-control" required>
                    <option value="Disponible" <?= $mesa['estado'] === 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="Ocupada" <?= $mesa['estado'] === 'Ocupada' ? 'selected' : '' ?>>Ocupada</option>
                    <option value="Reservada" <?= $mesa['estado'] === 'Reservada' ? 'selected' : '' ?>>Reservada</option>
                </select>
            </div>

            <div class="mt-4">
                   
                     <button type="submit" class="btn btn-warning px-5">Guardar Cambios</button>
                </button>
                <a href="GestionMesas.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <script src="../js/validation.js"></script>
    <script src="../js/adminValidation.js"></script>
    <script src="../js/inventorySearch.js"></script>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>
</body>
</html>
