<?php
include_once '../model/mesa.php';

$mesa = new Mesa();
$mesas = $mesa->getMesas();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <link rel="stylesheet" href="../css/StyleOrderMenu.css">
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

</head>

<body>
    <div class="container-css">
        <a href="./menus/MenuMesero.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        <h1>PEDIDOS</h1>

        <form action="../controller/orderMenuController.php" method="POST" class="needs-validation" novalidate>

            <div class="order-form">
                <div class="form-group">
                    <label for="mesa">Número de la mesa</label>
                    <select class="form-control" id="mesa" name="mesa" required>
                        <option value="">Seleccione una Mesa</option>
                        <?php foreach ($mesas as $mesa) : ?>
                            <option value="<?= $mesa['id'] ?>"><?= $mesa['numero_mesa'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione una mesa.</div>
                </div>
                <div class="button-container">
                    <button type="submit" name="action" value="create">Crear Pedido</button>
                    <button type="submit" name="action" value="view">Ver Pedido</button>
                </div>
            </div>

        </form>
    </div>

    <script src="../js/validation.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            localStorage.clear();
        });
        const urlParams = new URLSearchParams(window.location.search);
        const mensaje = urlParams.get('success');
        if (mensaje === 'existe') {
            alertify.error('La mesa tiene un pedido en curso');
        }
        if (mensaje === 'agregado') {
            alertify.success('Pedido agregado exitosamente');
        }
    </script>

</body>

</html>