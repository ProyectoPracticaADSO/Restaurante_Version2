<?php

require_once "../model/factura.php";

$facturaModel = new Factura();

if (!isset($_GET['id'])) {
  die("Factura no encontrada");
}

$idFactura = $_GET['id'];

$factura = $facturaModel->getFacturaById($idFactura);
$detalleFactura = $facturaModel->getDetalleFactura($idFactura);

if (!$factura) {
  die("Factura no existe");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Factura <?= $factura['numero_factura'] ?></title>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f5f5f5;
      font-family: Arial, Helvetica, sans-serif;
    }

    .ticket {
      max-width: 600px;
      margin: auto;
    }

    .ticket-header {
      text-align: center;
    }

    .ticket-header h2 {
      font-weight: bold;
      margin-bottom: 5px;
    }

    .ticket-info {
      border-top: 1px dashed #999;
      border-bottom: 1px dashed #999;
      padding: 15px 0;
      margin: 20px 0;
    }

    .table th {
      background: #f8f9fa;
    }

    .table td,
    .table th {
      vertical-align: middle;
    }

    .total-box {
      border-top: 2px solid #000;
      padding-top: 15px;
    }

    .total-final {
      font-size: 36px;
      font-weight: bold;
    }

    .footer-ticket {
      border-top: 1px dashed #999;
      margin-top: 25px;
      padding-top: 15px;
    }

    @media print {

      body {
        background: white !important;
      }

      .card {
        border: none !important;
        box-shadow: none !important;
      }

      .no-print {
        display: none !important;
      }

      .container {
        width: 100%;
        max-width: 100%;
      }
    }
  </style>
</head>

<body>

  <div class="container mt-4 ticket">

    <div class="card shadow">

      <div class="card-body">

        <!-- ENCABEZADO -->
        <div class="ticket-header">

          <h2>RESTAURANTE XYZ</h2>

          <p class="mb-1">
            NIT: 900123456-7
          </p>

          <p class="mb-1">
            Medellín - Colombia
          </p>

          <p class="mb-0">
            Tel: 300 123 4567
          </p>

        </div>

        <hr>

        <h4 class="text-center font-weight-bold">
          FACTURA DE VENTA
        </h4>

        <!-- INFORMACIÓN GENERAL -->
        <div class="ticket-info">

          <div class="row">

            <div class="col-6">
              <strong>Factura:</strong><br>
              <?= $factura['numero_factura'] ?>
            </div>

            <div class="col-6 text-right">
              <strong>Fecha:</strong><br>
              <?= date('d/m/Y H:i', strtotime($factura['fecha'])) ?>
            </div>

          </div>

          <div class="row mt-3">

            <div class="col-6">
              <strong>Mesa:</strong>
              <?= $factura['numero_mesa'] ?>
            </div>

            <div class="col-6 text-right">
              <strong>Método:</strong>
              <?= $factura['metodo_pago'] ?>
            </div>

          </div>

          <div class="row mt-2">

            <div class="col-12">
              <strong>Atendido por:</strong>
              <?= $factura['nombre_usuario'] ?>
            </div>

          </div>

        </div>

        <!-- DETALLE -->
        <table class="table table-sm">

          <thead>

            <tr>
              <th>Producto</th>
              <th class="text-center">Cant.</th>
              <th class="text-right">V/U</th>
              <th class="text-right">Subtotal</th>
            </tr>

          </thead>

          <tbody>

            <?php foreach ($detalleFactura as $item): ?>

              <tr>

                <td>
                  <?= $item['nombre_producto'] ?>
                </td>

                <td class="text-center">
                  <?= $item['cantidad'] ?>
                </td>

                <td class="text-right">
                  $<?= number_format($item['precio_unitario'], 0, ',', '.') ?>
                </td>

                <td class="text-right">
                  $<?= number_format($item['subtotal'], 0, ',', '.') ?>
                </td>

              </tr>

            <?php endforeach; ?>

          </tbody>

        </table>

        <!-- TOTALES -->
        <div class="total-box">

          <h6 class="text-right">
            Subtotal:
            $<?= number_format($factura['subtotal'], 0, ',', '.') ?>
          </h6>

          <h6 class="text-right">
            IVA:
            $<?= number_format($factura['iva'], 0, ',', '.') ?>
          </h6>

          <hr>

          <h6 class="text-right text-muted">
            TOTAL A PAGAR
          </h6>

          <h1 class="text-success text-right total-final">
            $<?= number_format($factura['total'], 0, ',', '.') ?>
          </h1>

        </div>

        <!-- PIE -->
        <div class="footer-ticket text-center">

          <p class="mb-1">
            Gracias por su visita
          </p>

          <p class="text-muted mb-1">
            Vuelva pronto
          </p>

          <small>
            Sistema POS Restaurante
          </small>

        </div>

      </div>

    </div>

    <!-- BOTÓN SOLO PANTALLA -->
    <div class="text-center mt-4 no-print">

      <button
        class="btn btn-success"
        onclick="window.print()">

        Imprimir Factura

      </button>

    </div>

  </div>

  <script>
    window.onload = function() {

      setTimeout(() => {

        window.print();

      }, 500);

    };
  </script>

</body>

</html>