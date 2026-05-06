<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo isset($tituloPagina) ? $tituloPagina : "Dashboard Restaurante"; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .btn-circle-orange {
            width: 45px;
            height: 45px;
            background-color: #ff7f27;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-circle-orange:hover {
            background-color: #e06d1c;
            transform: translateY(-1px);
        }

        .bg-custom-light {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body class="bg-custom-light">

    <div class="container mt-4">
        <div class="mb-4 d-flex align-items-center">
            <a href="../view/menus/MenuAdmin.php" class="btn-circle-orange me-3" title="Volver al Menú Admin">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="mb-0 h3"><i class="fa-solid fa-chart-pie text-primary"></i> Dashboard de Informes</h1>
        </div>

        <div class="row g-4">
            <div class="row g-3"> <!-- Contenedor de las tarjetas -->

                <!-- Tarjeta 1: Valorización del Kardex (Historial) -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small fw-bold">Valorización (Kardex)</h6>
                            <h2 class="display-6 fw-bold">
                                $<?php echo number_format($valorTotalKardex, 0, ',', '.'); ?>
                            </h2>
                            <p class="small text-muted mb-0">Historial acumulado de movimientos registrados.</p>
                        </div>
                        <div class="card-footer bg-success text-white border-0">
                            <i class="fa-solid fa-clock-rotate-left"></i> Auditoría Histórica
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Capital Real (Inventario Actual) -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted text-uppercase small fw-bold">Capital Real en Bodega</h6>
                            <h2 class="display-6 fw-bold">
                                $<?php echo number_format($valorRealHoy, 0, ',', '.'); ?>
                            </h2>
                            <p class="small text-muted mb-0">Cálculo basado en stock físico actual.</p>
                        </div>
                        <div class="card-footer bg-primary text-white border-0">
                            <i class="fa-solid fa-boxes-stacked"></i> Existencias Reales
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small fw-bold">Pedidos de Hoy</h6>
                        <h2 class="display-6 fw-bold text-primary"><?php echo $totalPedidos; ?></h2>
                        <p class="small text-muted mb-0">Cantidad de órdenes registradas este día.</p>
                    </div>
                    <div class="card-footer bg-primary text-white border-0">
                        <i class="fa-solid fa-utensils"></i> Flujo de Cocina
                    </div>
                </div>
            </div>

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">Top 5 Productos con Mayor Existencia</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Producto</th>
                                        <th class="text-center">Cantidad / Movimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contadorTop = 0;
                                    if (isset($productosTop) && $productosTop):
                                        while ($row = $productosTop->fetch(PDO::FETCH_ASSOC)):
                                            $contadorTop++; ?>
                                            <tr>
                                                <td class="ps-4"><strong><?php echo $row['descripcion_producto']; ?></strong></td>
                                                <td class="text-center">
                                                    <span class="badge bg-info text-dark">
                                                        <?php echo $row['cantidad']; ?> unidades
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    endif;

                                    if ($contadorTop == 0): ?>
                                        <tr>
                                            <td colspan="2" class="text-center py-3 text-muted">No hay datos de existencias.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold text-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i> Próximos Vencimientos
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Producto</th>
                                        <th class="text-center">Fecha de Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contadorVenc = 0;
                                    $fuenteVencimiento = isset($datosVencimiento) ? $datosVencimiento : (isset($datos) ? $datos : null);

                                    if ($fuenteVencimiento):
                                        while ($prod = $fuenteVencimiento->fetch(PDO::FETCH_ASSOC)):
                                            $contadorVenc++; ?>
                                            <tr>
                                                <td class="ps-4"><strong><?php echo $prod['descripcion_producto']; ?></strong></td>
                                                <td class="text-center">
                                                    <?php
                                                    $hoy = date('Y-m-d');
                                                    $clase = ($prod['fecha_vencimiento'] < $hoy) ? 'bg-danger' : 'bg-warning text-dark';
                                                    ?>
                                                    <span class="badge <?php echo $clase; ?>">
                                                        <i class="fa-solid fa-calendar-days me-1"></i>
                                                        <?php echo $prod['fecha_vencimiento']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    endif;

                                    if ($contadorVenc == 0): ?>
                                        <tr>
                                            <td colspan="2" class="text-center py-3 text-muted">No hay alertas de vencimiento.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>