<?php
include_once '../model/order.php';
include_once '../model/category.php';
include_once '../model/plate.php';
include_once '../model/mesa.php';

$order = new Order();
$category = new Category();
$plate = new Plate();
$modelMesa = new Mesa();

$pedidos = $order->getOrder();
$pedidosPorMesa = [];

foreach ($pedidos as $pedido) {
    $mesaId = $pedido['fk_id_mesas'];
    $pedidosPorMesa[$mesaId][] = $pedido;
}
?>

<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="../css/inventory.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/tu-kit-id.js" crossorigin="anonymous"></script>

<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-end mb-3">
        <a href="menus/MenuCaja.php" class="btn btn-warning shadow-sm font-weight-bold text-white" style="border-radius: 20px;">
            <i class="fas fa-arrow-left"></i> Atrás
        </a>
    </div>

    <header class="mb-4 d-flex align-items-center">
        <img src="https://glitch.global" alt="Logo" style="height: 60px; margin-right: 15px;">
        <h1 class="m-0 h3"><strong>Gestión de Pedidos</strong></h1>
    </header>

    <div class="orders-container">
        <?php if (!empty($pedidosPorMesa)) : ?>
            <?php foreach ($pedidosPorMesa as $idMesa => $pedidos) : 
                $mesa = $modelMesa->getMesaById($idMesa);
                $subtotalMesa = 0;
            ?>
                <div id="mesa-<?= $idMesa ?>" class="mesa-card border-0 p-4 mb-5 shadow-sm rounded bg-white">
                    <div class="mesa-header border-bottom pb-2 mb-3">
                        <h2 class="h4 text-primary">Mesa #<?= $mesa['numero_mesa'] ?? $idMesa ?></h2>
                    </div>
                    
                    <div class="order-list">
                        <?php foreach ($pedidos as $pedido) : ?>
                            <?php foreach ($pedido['pedido'] as $detalle) : 
                                $cat = $category->getCategoryById($detalle['categoriaId']);
                                $plato = $plate->getPlateById($detalle['platoId']);
                                $precio = $plato['precio_producto'] ?? 0;
                                $cant = $detalle['cantidad'];
                                $subtotalItem = $precio * $cant;
                                $subtotalMesa += $subtotalItem;
                            ?>
                                <div class="order-item border-bottom py-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-0 font-weight-bold"><?= $plato['nombre_producto'] ?? 'Producto no encontrado' ?> <span class="text-muted">x<?= $cant ?></span></p>
                                        <small class="badge badge-secondary"><?= $cat['nombre_categoria'] ?? 'General' ?></small>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-muted small">$<?= number_format($precio, 0, ',', '.') ?></div>
                                        <div class="font-weight-bold text-dark">$<?= number_format($subtotalItem, 0, ',', '.') ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php
                        $iva = $subtotalMesa * 0.19;
                        $total = $subtotalMesa + $iva;
                    ?>

                    <div class="factura-resumen mt-4 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between small"><span>Subtotal:</span><span>$<?= number_format($subtotalMesa, 0, ',', '.') ?></span></div>
                        <div class="d-flex justify-content-between small text-muted"><span>IVA (19%):</span><span>$<?= number_format($iva, 0, ',', '.') ?></span></div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 m-0 font-weight-bold">TOTAL:</span>
                            <span class="h4 m-0 text-success font-weight-bold">$<?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-4"><a href="EditarPedido.php?id=<?= $idMesa ?>" class="btn btn-outline-warning btn-block font-weight-bold">Editar</a></div>
                        <div class="col-4"><button onclick="confirmarEliminacion(<?= $idMesa ?>)" class="btn btn-outline-danger btn-block font-weight-bold">Eliminar</button></div>
                        <div class="col-4"><button class="btn btn-success btn-block font-weight-bold" onclick="abrirModalPago(<?= $idMesa ?>, <?= $total ?>)">Facturar</button></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-info text-center py-5">No hay pedidos pendientes.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Pago -->
<div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Procesar Pago - Mesa #<span id="modalMesaId"></span></h5>
        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body text-center">
        <h4 class="mb-4">Total: <strong id="modalTotalTexto"></strong></h4>
        <div class="form-group text-left">
          <label>Efectivo Recibido:</label>
          <input type="number" id="efectivoRecibido" class="form-control form-control-lg" oninput="calcularCambio()">
        </div>
        <div class="h3 p-3 bg-light rounded"><span id="cambioTexto" class="font-weight-bold">Cambio: $0</span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success btn-lg" onclick="finalizarYImprimir()">Confirmar e Imprimir</button>
      </div>
    </div>
  </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .imprimiendo, .imprimiendo * { visibility: visible; }
    .imprimiendo { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
    .imprimiendo .btn, .imprimiendo .row.mt-4 { display: none !important; }
}
</style>

<script>
let totalActual = 0;
let mesaIdActual = "";

function confirmarEliminacion(id) {
    if(confirm('¿Seguro que deseas eliminar esta mesa?')) {
        window.location.href = '../controller/deleteOrder.php?id=' + id;
    }
}

function abrirModalPago(idMesa, total) {
    totalActual = total;
    mesaIdActual = 'mesa-' + idMesa;
    document.getElementById('modalMesaId').innerText = idMesa;
    document.getElementById('modalTotalTexto').innerText = '$' + total.toLocaleString('es-CO');
    document.getElementById('efectivoRecibido').value = '';
    document.getElementById('cambioTexto').innerText = 'Cambio: $0';
    $('#modalPago').modal('show');
}

function calcularCambio() {
    let efectivo = parseFloat(document.getElementById('efectivoRecibido').value) || 0;
    let cambio = efectivo - totalActual;
    let texto = document.getElementById('cambioTexto');
    
    if (efectivo === 0) {
        texto.innerText = "Cambio: $0";
        texto.className = "font-weight-bold text-dark";
    } else if (cambio < 0) {
        texto.innerText = "Faltan: $" + Math.abs(cambio).toLocaleString('es-CO');
        texto.className = "font-weight-bold text-danger";
    } else {
        texto.innerText = "Cambio: $" + cambio.toLocaleString('es-CO');
        texto.className = "font-weight-bold text-success";
    }
}

function finalizarYImprimir() {
    let efectivo = parseFloat(document.getElementById('efectivoRecibido').value) || 0;
    
    if (efectivo < totalActual) {
        alert("El efectivo recibido es menor al total.");
        return;
    }

    // Cerramos modal para preparar la vista
    $('#modalPago').modal('hide');

    // Marcamos la mesa específica para imprimir
    let elementoMesa = document.getElementById(mesaIdActual);
    elementoMesa.classList.add('imprimiendo');

    // Ejecutamos impresión
    window.print();

    // Limpiamos la clase después de imprimir para volver a la normalidad
    elementoMesa.classList.remove('imprimiendo');
    
    // Aquí podrías agregar un redireccionamiento al controlador para cerrar el pedido en BD
    // window.location.href = '../controller/closeOrder.php?id=' + mesaIdActual.replace('mesa-', '');
}

</script>
</body>
</html>
