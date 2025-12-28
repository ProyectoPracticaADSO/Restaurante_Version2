<?php
include_once '../model/category.php';
include_once '../model/plate.php';
include_once '../model/inventory.php';
include_once '../model/mesa.php';
include_once '../model/order.php';

$category = new Category();
$plate = new Plate();
$inventory = new Inventory();
$modelMesa = new Mesa();
$modelOrder = new Order();


$idMesa = $_GET['id'];
$pedidos = $modelOrder->getOrderByMesa($idMesa);
if ($pedidos) {
    foreach ($pedidos as $pedido) {
        $detallePedido = $pedido['pedido'];
    }
}

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
        <a href="./VerPedido.php?id=<?= $idMesa ?>" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        <h1 class="title">ACTUALIZAR PEDIDO - MESA #<?= $numeroMesa ?></h1>
        <div class="forms d-flex">
            <!-- Formulario 1: para agregar elementos al pedido -->
            <form action="EditarPedido.php?id=<?= $idMesa ?>" method="POST" class="needs-validation col-md-6" novalidate>
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
            <form id="confirmar-form" action="../controller/orderController.php?action=editar&id=<?= $idMesa ?>" method="POST" class="col-md-6">
                <input type="hidden" name="idMesa" value="<?= $idMesa ?>">
                <div class="right-panel col-md-12">

                    <div id="order-items">
                        <!-- Aquí se mostrarán los elementos de la db (Los ids) -->
                        <?php foreach ($detallePedido as $index => $detalle):
                            $nombreCategoria = $category->getCategoryById($detalle['categoriaId']);
                            $detallesPlato = $plate->getPlateById($detalle['platoId']);
                            $nombreIngredientes = [];
                            $nombreAdiciones = [];
                            $idIngredientes = $detalle['ingredientesId'];
                            $idAdiciones = $detalle['adicionesId'];

                            foreach (explode(',', $idIngredientes) as $idIngrediente) {
                                if ($producto = $inventory->getProductById($idIngrediente)) {
                                    array_push($nombreIngredientes, $producto['descripcion_producto']);
                                }
                            }

                            foreach (explode(',', $idAdiciones) as $idAdicion) {
                                if ($producto = $inventory->getProductById($idAdicion)) {
                                    array_push($nombreAdiciones, $producto['descripcion_producto']);
                                }
                            } ?>
                            <div class="order-item" id="item-<?= $index ?>">

                                <input type="hidden" class="nombreCategoriaBd" value="<?= $nombreCategoria['nombre_categoria'] ?>">
                                <input type="hidden" class="nombrePlatoBd" value="<?= $detallesPlato['nombre_producto'] ?>">
                                <input type="hidden" class="cantidadBd" value="<?= $detalle['cantidad'] ?>">
                                <input type="hidden" class="nombreIngredientesBd" value="<?= implode(', ', $nombreIngredientes) ?>">
                                <input type="hidden" class="nombreAdicionesBd" value="<?= implode(', ', $nombreAdiciones) ?>">

                                <input type="hidden" class="idCategoriaBd" value="<?= $detalle['categoriaId'] ?>">
                                <input type="hidden" class="idPlatoBd" value="<?= $detalle['platoId'] ?>">
                                <input type="hidden" class="cantidadBd" value="<?= $detalle['cantidad'] ?>">
                                <input type="hidden" class="idsIngredientesBd" value="<?= $detalle['ingredientesId'] ?>">
                                <input type="hidden" class="idsAdicionesBd" value="<?= $detalle['adicionesId'] ?>">

                            </div>
                        <?php endforeach ?>

                    </div>

                    <div id="order-local-items">
                        <!-- Aquí se mostrarán los elementos agregados -->
                    </div>

                </div>
                <input type="hidden" id="pedidos-input" name="pedidos">
                <button type="button" class="btn btn-create" onclick="actualizarPedido()">Actualizar Pedido</button>
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
        // Llamada a la función al cargar la página
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
            var storageBd = JSON.parse(localStorage.getItem('idPedidosBD')) || [];
            var storage = JSON.parse(localStorage.getItem('idPedidos')) || [];
            if (storageBd.length === 0 && storage.length === 0) {
                var pedidos = [];
                var elementos = document.querySelectorAll('.order-item'); // Obtenemos todos los elementos del pedido

                // Recorremos los elementos visuales de la página (los pedidos que vienen de la BD)
                elementos.forEach(function(elemento, index) {

                    // Obtener cada campo, pero con validación de existencia
                    var idCategoriaElement = elemento.querySelector('.idCategoriaBd');
                    var idPlatoElement = elemento.querySelector('.idPlatoBd');
                    var cantidadElement = elemento.querySelector('.cantidadBd');
                    var idIngredientesElement = elemento.querySelector('.idsIngredientesBd');
                    var idAdicionesElement = elemento.querySelector('.idsAdicionesBd');

                    var nameCategoriaElement = elemento.querySelector('.nombreCategoriaBd');
                    var namePlatoElement = elemento.querySelector('.nombrePlatoBd');
                    var nameIngredientesElement = elemento.querySelector('.nombreIngredientesBd');
                    var nameAdicionesElement = elemento.querySelector('.nombreAdicionesBd');

                    // Validar si existen todos los elementos
                    if (!idCategoriaElement || !idPlatoElement || !cantidadElement || !idIngredientesElement || !idAdicionesElement) {
                        return
                    }

                    // Si existen, obtener los valores (ids)
                    var categoriaId = idCategoriaElement.value;
                    var platoId = idPlatoElement.value;
                    var cantidad = cantidadElement.value;
                    var ingredientesId = idIngredientesElement.value;
                    var adicionesId = idAdicionesElement.value;

                    //Nombres
                    var categoriaName = nameCategoriaElement.value;
                    var platoName = namePlatoElement.value;
                    var ingredientesName = nameIngredientesElement.value;
                    var adicionesName = nameAdicionesElement.value;

                    // Creamos un objeto de pedido con los datos
                    var idPedidoBD = {
                        categoriaId: categoriaId,
                        platoId: platoId,
                        cantidad: cantidad,
                        ingredientesId: ingredientesId,
                        adicionesId: adicionesId
                    };

                    var namesPedidoBD = {
                        categoriaName: categoriaName,
                        platoName: platoName,
                        cantidad: cantidad,
                        ingredientesName: ingredientesName,
                        adicionesName: adicionesName
                    };

                    var idPedidosBD = JSON.parse(localStorage.getItem('idPedidosBD')) || [];
                    var pedidosBD = JSON.parse(localStorage.getItem('pedidosBD')) || [];


                    idPedidosBD.push(idPedidoBD);
                    pedidosBD.push(namesPedidoBD);

                    localStorage.setItem('idPedidosBD', JSON.stringify(idPedidosBD));
                    localStorage.setItem('pedidosBD', JSON.stringify(pedidosBD));

                    elementos.innerHTML = '';
                });
            }

            var orderLocalItems = document.querySelector('#order-local-items');
            var pedidos = JSON.parse(localStorage.getItem('pedidos')) || [];
            orderLocalItems.innerHTML = '';

            pedidos.forEach(function(pedido, index) {
                var orderContent = `
                <div class="order-item">
                    <p><strong>Categoría:</strong> ${pedido.categoriaNombre}</p>
                    <p><strong>Plato:</strong> ${pedido.platoNombre}</p>
                    <p><strong>Cantidad:</strong> ${pedido.cantidad}</p>
                    <p><strong>Ingredientes:</strong> ${pedido.ingredientes}</p>
                    <p><strong>Adiciones:</strong> ${pedido.adiciones}</p>
                    <button type="button" class="btn btn-danger" onclick="eliminarPedidoLocal(${index})">Eliminar</button>
                </div>
            `;
                orderLocalItems.innerHTML += orderContent;
            });

            var orderDbItems = document.querySelector('#order-items');
            var pedidosBd = JSON.parse(localStorage.getItem('pedidosBD')) || [];
            orderDbItems.innerHTML = '';

            pedidosBd.forEach(function(pedidoBd, index) {
                var orderContentBd = `
                <div class="order-item">
                    <p><strong>Categoría:</strong> ${pedidoBd.categoriaName}</p>
                    <p><strong>Plato:</strong> ${pedidoBd.platoName}</p>
                    <p><strong>Cantidad:</strong> ${pedidoBd.cantidad}</p>
                    <p><strong>Ingredientes:</strong> ${pedidoBd.ingredientesName}</p>
                    <p><strong>Adiciones:</strong> ${pedidoBd.adicionesName}</p>
                    <button type="button" class="btn btn-danger" onclick="eliminarPedidoBd(${index})">Eliminar</button>
                </div>
            `;
                orderDbItems.innerHTML += orderContentBd;
            });
        }

        function eliminarPedidoLocal(index) {
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

        function eliminarPedidoBd(index) {
            var pedidos = JSON.parse(localStorage.getItem('pedidosBD')) || [];
            var idPedidos = JSON.parse(localStorage.getItem('idPedidosBD')) || [];

            // Elimina el pedido en la posición especificada por el índice
            pedidos.splice(index, 1);
            idPedidos.splice(index, 1);

            // Guarda los pedidos actualizados en localStorage
            localStorage.setItem('pedidosBD', JSON.stringify(pedidos));
            localStorage.setItem('idPedidosBD', JSON.stringify(idPedidos));

            // Vuelve a mostrar los pedidos actualizados
            mostrarPedidos();
        }

        function actualizarPedido() {

            var elementos = document.querySelectorAll('.order-item');
            // Obtener los pedidos del localStorage (elementos añadidos localmente)
            var idPedidosLocalStorage = JSON.parse(localStorage.getItem('idPedidos')) || [];
            var idPedidosLocalStorageBd = JSON.parse(localStorage.getItem('idPedidosBD')) || [];

            // Combinamos ambos arrays (los de la BD y los del localStorage)
            idPedidosLocalStorageBd = idPedidosLocalStorageBd.concat(idPedidosLocalStorage);

            if (elementos.length === 0 && idPedidosLocalStorageBd.length === 0) {
                alert('No se puede enviar un pedido vacío');
                return;
            }

            // Envía el array como una cadena JSON al servidor
            document.getElementById('pedidos-input').value = JSON.stringify(idPedidosLocalStorageBd);

            // Envía el formulario
            document.getElementById('confirmar-form').submit();

            // Limpiar el localStorage
            localStorage.clear();
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