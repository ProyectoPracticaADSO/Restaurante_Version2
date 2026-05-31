<?php

require_once "../config/connection.php";

class Factura extends ConnectionDB
{

  public function generarFactura(
    $idPedido,
    $idUsuario,
    $idMesa,
    $subtotal,
    $iva,
    $total,
    $metodoPago,
    $efectivo,
    $cambio
  ) {

    $conn = parent::connection();

    try {

      $conn->beginTransaction();

      // Generar consecutivo
      $numeroFactura = 'FAC-' . date('YmdHis');

      // Insertar factura
      $sqlFactura = "INSERT INTO facturas
            (
                numero_factura,
                fk_id_pedido,
                fk_id_usuario,
                fk_id_mesa,
                subtotal,
                iva,
                total,
                metodo_pago,
                efectivo_recibido,
                cambio
            )
            VALUES
            (
                :numero,
                :pedido,
                :usuario,
                :mesa,
                :subtotal,
                :iva,
                :total,
                :metodo,
                :efectivo,
                :cambio
            )";

      $queryFactura = $conn->prepare($sqlFactura);

      $queryFactura->bindParam(':numero', $numeroFactura);
      $queryFactura->bindParam(':pedido', $idPedido);
      $queryFactura->bindParam(':usuario', $idUsuario);
      $queryFactura->bindParam(':mesa', $idMesa);
      $queryFactura->bindParam(':subtotal', $subtotal);
      $queryFactura->bindParam(':iva', $iva);
      $queryFactura->bindParam(':total', $total);
      $queryFactura->bindParam(':metodo', $metodoPago);
      $queryFactura->bindParam(':efectivo', $efectivo);
      $queryFactura->bindParam(':cambio', $cambio);

      $queryFactura->execute();

      // Obtener factura creada
      $idFactura = $conn->lastInsertId();

      // Obtener detalle pedido
      $sqlDetallePedido = "SELECT * 
                          FROM detalle_pedido
                          WHERE fk_id_pedido = :pedido";

      $queryDetallePedido = $conn->prepare($sqlDetallePedido);

      $queryDetallePedido->bindParam(':pedido', $idPedido);

      $queryDetallePedido->execute();

      $detalles = $queryDetallePedido->fetchAll(PDO::FETCH_ASSOC);

      // Insertar detalle factura
      foreach ($detalles as $detalle) {

        $sqlDetalleFactura = "INSERT INTO detalle_factura
                (
                    fk_id_factura,
                    fk_id_producto,
                    cantidad,
                    precio_unitario,
                    subtotal
                )
                VALUES
                (
                    :factura,
                    :producto,
                    :cantidad,
                    :precio,
                    :subtotal
                )";

        $queryDetalleFactura = $conn->prepare($sqlDetalleFactura);

        $queryDetalleFactura->bindParam(':factura', $idFactura);
        $queryDetalleFactura->bindParam(':producto', $detalle['fk_id_producto']);
        $queryDetalleFactura->bindParam(':cantidad', $detalle['cantidad']);
        $queryDetalleFactura->bindParam(':precio', $detalle['precio_unitario']);
        $queryDetalleFactura->bindParam(':subtotal', $detalle['subtotal']);

        $queryDetalleFactura->execute();
      }

      // Cambiar pedido a facturado
      $sqlPedido = "UPDATE pedidos
                          SET estado = 'facturado'
                          WHERE id = :pedido";

      $queryPedido = $conn->prepare($sqlPedido);

      $queryPedido->bindParam(':pedido', $idPedido);

      $queryPedido->execute();

      // Liberar mesa
      $sqlMesa = "UPDATE mesas
                        SET estado = 'Disponible'
                        WHERE id = :mesa";

      $queryMesa = $conn->prepare($sqlMesa);

      $queryMesa->bindParam(':mesa', $idMesa);

      $queryMesa->execute();

      $conn->commit();

      return true;
    } catch (Exception $e) {

      $conn->rollBack();

      echo $e->getMessage();

      return false;
    }
  }
}
