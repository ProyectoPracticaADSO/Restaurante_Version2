<?php
include_once '../model/category.php';
include_once '../model/plate.php';
include_once '../model/inventory.php';
include_once '../model/mesa.php';

$category = new Category();
$plate = new Plate();
$inventory = new Inventory();
$modelMesa = new Mesa();

$categorias = $category->getCategory();

$idMesa = $_POST['id'] ?? $_GET['id'];
$mesa = $modelMesa->getMesaById($idMesa);
$numeroMesa = $mesa['numero_mesa'];

$platos = [];
$nombreIngredientes = [];
$nombreAdiciones = [];

// Si el formulario fue enviado con una categoría seleccionada
if (isset($_POST['categoria_id'])) {
    $platos = $plate->getPlateByCategory($_POST['categoria_id']);
}

if (isset($_POST['plato']) && !empty($_POST['plato'])) {
    $plato = $plate->getPlateById($_POST['plato']);
    $idIngredientes = $plato['ingredientes_producto'];
    $idAdiciones = $plato['posibles_adiciones'];

    foreach (explode(',', $idIngredientes) as $idIngrediente) {
        if ($producto = $inventory->getProductById($idIngrediente)) {
            array_push($nombreIngredientes, $producto['descripcion_producto']);
        }
    }

    foreach (explode(',', $idAdiciones) as $idAdicion) {
        if ($producto = $inventory->getProductById($idAdicion)) {
            array_push($nombreAdiciones, $producto['descripcion_producto']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creación de Pedido</title>
    <link rel="stylesheet" href="../css/StyleAddOrder.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <div class="container">
        <a href="./MenuPedidos.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        <h1 class="title">CREACIÓN DE PEDIDO - MESA #<?= $numeroMesa ?></h1>
        <div class="forms d-flex">
            <!-- Formulario 1: para agregar elementos al pedido -->
            <form action="AgregarPedido.php" method="POST" class="needs-validation col-md-6" novalidate>
                <input type="hidden" name="id" value="<?= $idMesa ?>">
            
                <div class="left-panel col-md-12">
                    <div class="form-group">
                        <label for="categoria">CATEGORIAS</label>
                        <select id="categoria" name="categoria_id" onchange="this.form.submit()" class="form-control" required>
                            <option value="">Seleccione una categoría</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= $categoria['id'] ?>" <?= isset($_POST['categoria_id']) && $_POST['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                                    <?= $categoria['nombre_categoria'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="plato">PLATO</label>
                        <div class="input-number-group">
                            <select id="plato" name="plato" class="form-control" onchange="this.form.submit()" required>
                                <option value="">Seleccione un plato</option>
                                <?php if (!empty($platos)) { ?>
                                    <?php foreach ($platos as $plato): ?>
                                        <option value="<?= $plato['id'] ?>" <?= isset($_POST['plato']) && $_POST['plato'] == $plato['id'] ? 'selected' : '' ?>><?= $plato['nombre_producto'] ?></option>
                                    <?php endforeach; ?>
                                <?php } ?>
                            </select>
                            <input type="number" id="cantidad" min="1" name="cantidad" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ingredientes">Ingredientes:</label>
                        <input type="text" class="form-control" id="ingredientes" name="ingredientes" value="<?= isset($nombreIngredientes) ? implode(', ', $nombreIngredientes) : '' ?>" maxlength="100" required readonly>
                        <input type="hidden" id="ingredientes-ids" name="ingredientes_ids" value="<?= $idIngredientes ?>">
                        <button type="button" class="btn btn-info mt-2" data-toggle="modal" data-target="#pageModal">
                            Ver Ingredientes
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="adiciones">Adiciones:</label>
                        <input type="text" class="form-control" id="adiciones" name="adiciones" maxlength="100" value="<?= isset($nombreAdiciones) ? implode(', ', $nombreAdiciones) : '' ?>" required readonly>
                        <input type="hidden" id="adiciones-ids" name="adiciones_ids" value="<?= $idAdiciones ?>">
                        <button type="button" class="btn btn-info mt-2" data-toggle="modal" data-target="#pageModalAdditions">
                            Ver Adiciones
                        </button>
                    </div>
                    <button type="button" class="btn" name="btn_agregar" onclick="agregarPedido()">Agregar</button>
                </div>
            </form>

            <!-- Formulario 2: para confirmar o eliminar el pedido -->
            <form id="confirmar-form" action="../controller/orderController.php" method="POST" class="col-md-6">
                <input type="hidden" name="idMesa" value="<?= $idMesa ?>">
                <div class="right-panel col-md-12">
                    <div id="order-items">
                        <!-- Aquí se mostrarán los elementos agregados -->
                    </div>
                </div>
                <input type="hidden" id="pedidos-input" name="pedidos">
                <button type="button" class="btn btn-create" onclick="confirmarPedido()">Confirmar Pedido</button>
            </form>

        </div>
    </div>

    <!-- Modal Ingredientes -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pageModalLabel">Materia prima</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="./Ingredientes.php" style="width: 100%; height: 500px; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
        <input type="hidden" name="categoria" value="<?= $idCategoria ?>">
    </div>

    <!-- Modal Adiciones -->
    <div class="modal fade" id="pageModalAdditions" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pageModalLabel">Posibles adiciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="./Adiciones.php" style="width: 100%; height: 500px; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
        <input type="hidden" name="categoria" value="<?= $idCategoria ?>">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gI2vcHpK7hWyh1zF0b7X6Ai8H83F9o5F+Kp6AXF2hdS2pqFw2o" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            mostrarPedidos();
        });

        function agregarPedido() {
            var categoriaId = document.getElementById('categoria').value;
            var categoriaNombre = document.getElementById('categoria').options[document.getElementById('categoria').selectedIndex].text;
            var platoId = document.getElementById('plato').value;
            var platoNombre = document.getElementById('plato').options[document.getElementById('plato').selectedIndex].text;
            var cantidad = document.getElementById('cantidad').value;
            var ingredientes = document.getElementById('ingredientes').value;
            var idIngredientes = document.getElementById('ingredientes-ids').value;
            var adiciones = document.getElementById('adiciones').value;
            var idAdiciones = document.getElementById('adiciones-ids').value;

            if (platoId === "" || cantidad === "" || cantidad <= 0) {
                alert('Por favor, seleccione un plato y una cantidad válida.');
                return;
            }

            var pedido = {
                categoriaNombre: categoriaNombre,
                platoNombre: platoNombre,
                cantidad: cantidad,
                ingredientes: ingredientes,
                adiciones: adiciones
            };

            var idPedido = {
                categoriaId: categoriaId,
                platoId: platoId,
                cantidad: cantidad,
                ingredientesId: idIngredientes,
                adicionesId: idAdiciones,
            };

            var pedidos = JSON.parse(localStorage.getItem('pedidos')) || [];
            var idPedidos = JSON.parse(localStorage.getItem('idPedidos')) || [];

            pedidos.push(pedido);
            idPedidos.push(idPedido);


            localStorage.setItem('pedidos', JSON.stringify(pedidos));
            localStorage.setItem('idPedidos', JSON.stringify(idPedidos));


            // Limpiar el formulario
            document.getElementById('categoria').value = '';
            document.getElementById('plato').value = '';
            document.getElementById('cantidad').value = '';
            document.getElementById('ingredientes').value = '';
            document.getElementById('adiciones').value = '';

            mostrarPedidos();
        }

        function mostrarPedidos() {
            var rightPanel = document.querySelector('.right-panel');
            var pedidos = JSON.parse(localStorage.getItem('pedidos')) || [];
            rightPanel.innerHTML = '';

            pedidos.forEach(function(pedido, index) {
                var orderContent = `
                <div class="order-item">
                    <p><strong>Categoría:</strong> ${pedido.categoriaNombre}</p>
                    <p><strong>Plato:</strong> ${pedido.platoNombre}</p>
                    <p><strong>Cantidad:</strong> ${pedido.cantidad}</p>
                    <p><strong>Ingredientes:</strong> ${pedido.ingredientes}</p>
                    <p><strong>Adiciones:</strong> ${pedido.adiciones}</p>
                    <button class="btn btn-danger" onclick="eliminarPedido(${index})">Eliminar</button>
                </div>
            `;
                rightPanel.innerHTML += orderContent;
            });
        }

        // Función para eliminar pedidos
        function eliminarPedido(index) {
            var pedidos = JSON.parse(localStorage.getItem('pedidos')) || [];
            var idPedidos = JSON.parse(localStorage.getItem('idPedidos')) || [];

            // Elimina el pedido en la posición especificada por el índice
            pedidos.splice(index, 1);
            idPedidos.splice(index, 1);

            // Guarda los pedidos actualizados en localStorage
            localStorage.setItem('pedidos', JSON.stringify(pedidos));
            localStorage.setItem('idPedidos', JSON.stringify(idPedidos));

            // Vuelve a mostrar los pedidos actualizados
            mostrarPedidos();
        }

        function confirmarPedido() {
            var idPedidos = JSON.parse(localStorage.getItem('idPedidos')) || [];

            if (idPedidos.length === 0) {
                alert('No hay elementos en el pedido.');
                return;
            }

            // Envía el array como una cadena JSON al servidor
            document.getElementById('pedidos-input').value = JSON.stringify(idPedidos);

            // Envía el formulario
            document.getElementById('confirmar-form').submit();
            localStorage.removeItem("pedidos");
            localStorage.removeItem("idPedidos");
        }





        window.addEventListener('message', function(event) {
            if (event.data.type === 'ingredients') {
                const ingredientes = event.data.data;
                const ingredientNames = ingredientes.map(ingredient => ingredient.name).join(', ');
                const ingredientIds = ingredientes.map(ingredient => ingredient.id).join(',');

                // Actualiza el campo de texto con los nombres
                document.getElementById('ingredientes').value = ingredientNames;

                // Guarda los IDs en un campo oculto si es necesario
                document.getElementById('ingredientes-ids').value = ingredientIds;
            }

            if (event.data.type === 'additions') {
                const adiciones = event.data.data;
                const additionsNames = adiciones.map(ingredient => ingredient.name).join(', ');
                const additionsIds = adiciones.map(ingredient => ingredient.id).join(',');

                // Actualiza el campo de texto con los nombres
                document.getElementById('adiciones').value = additionsNames;

                // Guarda los IDs en un campo oculto si es necesario
                document.getElementById('adiciones-ids').value = additionsIds;
            }
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            var ingredientesInput = document.getElementById('ingredientes');
            if (ingredientesInput.value.trim() === '') {
                event.preventDefault();
                alert('Por favor, seleccione al menos un ingrediente.');
            }
        });
    </script>

    <script src="../js/validation.js"></script>
</body>

</html>