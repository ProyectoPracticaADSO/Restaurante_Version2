<?php
include_once '../model/inventory.php';

$inventory = new Inventory();

$products = $inventory->getInventory();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/StyleTable.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <header class="d-flex justify-content-between align-items-center">
            <h1>Inventario</h1>
        </header>

        <div class="search-bar">
            <input type="text" placeholder="Buscar">
            <i class="fa fa-search"></i>

            <div class="form-row">
                <div class="button-container col-md-3">
                    <button id="addAdditionsBtn" type="button" class="btn btn-primary">Añadir adiciones</button>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Agregar</th>
                    </tr>
                </thead>
                <tbody id="tbl_productos">
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?= $product['descripcion_producto'] ?></td>
                            <td><input type='checkbox' name='productos[]' value="<?= $product['id'] ?>" class="additions-checkbox" data-name="<?= $product['descripcion_producto'] ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script src="../js/validation.js"></script>
    <script>
        document.getElementById('addAdditionsBtn').addEventListener('click', function() {
            var selectedAdditions = [];
            document.querySelectorAll('.additions-checkbox:checked').forEach(function(checkbox) {
                selectedAdditions.push({
                    id: checkbox.value,
                    name: checkbox.getAttribute('data-name')
                });
            });
            window.parent.postMessage({
                type: 'additions',
                data: selectedAdditions
            }, '*');
            $('#pageModalAdditions').modal('hide');
        });

        // Dentro del archivo de adiciones (Adiciones.php)
        window.addEventListener('load', function() {
            // Recibir los IDs de las adiciones seleccionadas desde el parent
            const selectedAdditionsIds = parent.document.getElementById('adiciones-ids').value.split(',');

            // Marcar los checkboxes correspondientes
            document.querySelectorAll('.additions-checkbox').forEach(function(checkbox) {
                if (selectedAdditionsIds.includes(checkbox.value)) {
                    checkbox.checked = true;
                }
            });
        });
    </script>

</body>

</html>