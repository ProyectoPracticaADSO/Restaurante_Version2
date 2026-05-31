<?php

session_start();

require_once '../model/factura.php';
require_once '../model/order.php';

header('Content-Type: application/json');

try {

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  $idMesa = $_POST['idMesa'];
  $efectivo = $_POST['efectivo'];

  $factura = new Factura();
  $order = new Order();

  $pedidos = $order->getOrderByMesa($idMesa);

  if (!$pedidos || count($pedidos) == 0) {
    throw new Exception('No existe pedido activo para la mesa');
  }

  $pedido = $pedidos[0];

  if ($pedido['estado'] === 'facturado') {
    throw new Exception('El pedido ya fue facturado');
  }

  $idPedido = $pedido['id'];
  $idUsuario = $_SESSION['user_id'];

  $subtotal = $pedido['total'];
  $iva = round($subtotal * 0.19, 2);
  $total = $subtotal + $iva;

  $cambio = $efectivo - $total;

  if ($cambio < 0) {
    throw new Exception('El efectivo recibido es insuficiente');
  }

  $resultado = $factura->generarFactura(
    $idPedido,
    $idUsuario,
    $idMesa,
    $subtotal,
    $iva,
    $total,
    'Efectivo',
    $efectivo,
    $cambio
  );

  echo json_encode([
    'success' => $resultado
  ]);
} catch (Exception $e) {

  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
