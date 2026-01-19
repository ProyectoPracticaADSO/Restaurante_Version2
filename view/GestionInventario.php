<?php
include_once '../model/inventory.php';
include_once '../config/connection.php'; // Sin espacios en la ruta

$inventory = new Inventory();
$products = $inventory->getInventory();

$productoMasViejo = $inventory->getOldestProduct();
$productoProximoVencer = $inventory->getNearestExpiringProduct();
$totalValorBodega = $inventory->getTotalWarehouseValue();

include_once '../model/inventory.php';
include_once '../config/connection.php';

$inventory = new Inventory();
$products = $inventory->getInventory();

// --- Lógica de eliminación con PDO ---
if (isset($_GET['eliminar']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 1. Llamamos al método estático correctamente
        $pdo = ConnectionDB::connection();

        // 2. Usamos una consulta preparada (más seguro contra inyecciones SQL)
        $sql = "DELETE FROM inventario WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        // 3. Ejecutamos pasando el ID
        if ($stmt->execute(['id' => $id])) {
            // 4. Redirigimos si todo salió bien
            header("Location: GestionInventario.php?success=1");
            exit();
        }
    } catch (PDOException $e) {
        echo "❌ Error al eliminar el producto: " . $e->getMessage();
    }
}

// Otras variables...
$productoMasViejo = $inventory->getOldestProduct();
// ...
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="../css/inventory.css">
    <link rel="stylesheet" href="../../css/modal.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>

<body>

    <div class="container mt-1">
        <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
                <h1 class="display-5 mb-0 mr-3">Inventario</h1>

                <div class="btn-group ml-3 flex-wrap">
                    <a href="AgregarProducto.php" class="btn btn-outline-secondary mx-1 mb-1">Agregar Producto</a>
                    <button id="btn-oldest" class="btn btn-outline-primary mx-1 mb-1">Producto más viejo</button>
                    <button id="btn-nearest-expiring" class="btn btn-outline-warning mx-1 mb-1">Próximo a vencer</button>
                    <button id="btn-total-value" class="btn btn-outline-success mx-1 mb-1">Valor total</button>
                </div>
            </div>
            <a href="./menus/MenuAdmin.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left fa-lg"></i></a>
        </header>


        <div class="table-wrapper table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Precio Unitario</th>
                        <th>Ingreso</th>
                        <th>Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tbl_productos">
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= $product['descripcion_producto'] ?></td>
                            <td><?= $product['nombre_tipos'] ?></td>
                            <td><?= $product['cantidad'] ?></td>
                            <td><?= $product['nombre_unidad_medida'] ?></td>
                            <td><?= number_format($product['precio_unitario']) ?>$</td>
                            <td><?= $product['fecha_ingreso'] ?></td>
                            <td><?= $product['fecha_vencimiento'] ?></td>
                            <td class="action-icons">
                                <a href="EditarProducto.php?id=<?= $product['id'] ?>" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="#" class="eliminar-btn"
                                    data-id="<?= $product['id'] ?>"
                                    data-nombre="<?= $product['descripcion_producto'] ?>"
                                    title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <div id="result-container" class="alert alert-info text-center" style="display: none;"></div>

    </div>

    <script>
        window.oldestProductData = <?= json_encode($productoMasViejo) ?>;
        window.nearestExpiringData = <?= json_encode($productoProximoVencer) ?>;
        window.totalWarehouseValue = <?= json_encode($totalValorBodega) ?>;
    </script>

    <!--Añado Event Listener para que estos tres botones funcionen-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            const closeModal = document.getElementById('closeModal');

            function showModal(title, body) {
                modalTitle.textContent = title;
                modalBody.textContent = body;
                modal.style.display = 'block';
            }

            closeModal.onclick = () => modal.style.display = 'none';
            window.onclick = (e) => {
                if (e.target === modal) modal.style.display = 'none';
            };

            // 1. Producto más viejo
            document.getElementById('btn-oldest').addEventListener('click', () => {
                const p = window.oldestProductData;
                if (p) {
                    showModal(
                        'Producto más viejo',
                        `${p.descripcion_producto} (Ingreso: ${p.fecha_ingreso})`
                    );
                } else {
                    showModal('Sin datos', 'No hay datos disponibles');
                }
            });

            // 2. Próximo a vencer
            document.getElementById('btn-nearest-expiring').addEventListener('click', () => {
                const p = window.nearestExpiringData;
                if (p) {
                    showModal(
                        'Próximo a vencer',
                        `${p.descripcion_producto} (Vence: ${p.fecha_vencimiento})`
                    );
                } else {
                    showModal('Aviso', 'No hay productos con fecha de vencimiento');
                }
            });

            // 3. Valor total
            document.getElementById('btn-total-value').addEventListener('click', () => {
                const valor = window.totalWarehouseValue;
                showModal(
                    'Valor total del inventario',
                    `$${valor.toLocaleString()}`
                );
            });

        });
    </script>

    <script src="../js/inventorySearch.js"></script>
    <script src="../../modal.js"></script>
    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>

    <!-- Modal de confirmación para salir -->
    <div id="modal-confirm" class="modal-overlay">
        <div class="modal-content">
            <h3 id="modal-text">¿Estás seguro de eliminar este registro?</h3>
            <div class="modal-actions">
                <a id="btn-confirm" class="btn-confirm">Sí, eliminar</a>
                <button id="btn-cancel" class="btn-cancel">Cancelar</button>
            </div>
        </div>
    </div>

    <!--Añado modificación al modal-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modal-confirm');
            const btnConfirm = document.getElementById('btn-confirm');
            const btnCancel = document.getElementById('btn-cancel');

            // 1. Escuchar clics en los botones de eliminar
            document.querySelectorAll('.eliminar-btn').forEach(boton => {
                boton.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Usamos .closest('.eliminar-btn') para asegurar que capturemos los datos
                    // Esto evita el error de "null" si haces clic sobre el icono <i>
                    const btnOriginal = this.closest('.eliminar-btn');
                    const id = btnOriginal.getAttribute('data-id');
                    const nombre = btnOriginal.getAttribute('data-nombre');

                    // Personalizar el mensaje inyectando el nombre vía JavaScript
                    // Esto reemplaza cualquier error previo de PHP en el h3
                    const tituloModal = modal.querySelector('h3');
                    if (tituloModal) {
                        tituloModal.innerText = `¿Estás seguro de eliminar el producto: ${nombre}?`;
                    }

                    // Configurar el enlace de eliminación hacia el mismo archivo GestionInventario.php
                    // Se agrega el parámetro 'eliminar=true' para que la lógica PHP lo detecte
                    btnConfirm.setAttribute('href', `GestionInventario.php?eliminar=true&id=${id}`);

                    // Mostrar el modal
                    modal.classList.add('active');
                });
            });

            // 2. Cerrar el modal al cancelar
            btnCancel.addEventListener('click', () => {
                modal.classList.remove('active');
            });

            // 3. Cerrar si se hace clic fuera del contenido blanco (overlay)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.remove('active');
            });
        });
    </script>

    <!-- Modal para producto más viejo, próximo a vencer y total-->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <h3 id="modalTitle"></h3>
            <p id="modalBody"></p>
        </div>
    </div>


</body>

</html>