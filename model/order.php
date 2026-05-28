<?php
require_once "../config/connection.php";
class Order extends ConnectionDB
// {
//     public function insertOrder($id, $idUsuario, $idMesa, $pedidoCompleto)
//     {
//         $sql = "INSERT INTO pedidos (id, fk_id_usuario, fk_id_mesas, pedido) 
//         VALUES (:id, :usuario, :mesa, :pedido)";

//         $query = parent::connection()->prepare($sql);
//         $query->bindParam(':id', $id);
//         $query->bindParam(':usuario', $idUsuario);
//         $query->bindParam(':mesa', $idMesa);
//         $query->bindParam(':pedido', $pedidoCompleto);


//         return $query->execute();
//     }

//     public function updateOrder($idUsuario, $idMesa, $pedidoCompleto)
//     {
//         $sql = "UPDATE pedidos SET
//                 fk_id_usuario = :usuario,
//                 pedido = :pedido
//             WHERE fk_id_mesas = :mesa";

//         $query = parent::connection()->prepare($sql);
//         $query->bindParam(':usuario', $idUsuario);
//         $query->bindParam(':mesa', $idMesa);
//         $query->bindParam(':pedido', $pedidoCompleto);


//         return $query->execute();
//     }

//     public function getOrder()
//     {
//         $query = parent::connection()->prepare("SELECT * FROM pedidos");
//         $query->execute();
//         $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

//         // Recorremos cada pedido para decodificar la columna 'pedido'
//         foreach ($resultados as &$pedido) {
//             if (isset($pedido['pedido'])) {
//                 // Decodificar la columna 'pedido' (JSON) en un array asociativo
//                 $pedido['pedido'] = json_decode($pedido['pedido'], true);
//             }
//         }

//         return $resultados;
//     }


//     public function getOrderByMesa($id)
//     {
//         $query = parent::connection()->prepare("SELECT * FROM pedidos WHERE fk_id_mesas = :id");
//         $query->bindParam(':id', $id);
//         $query->execute();
//         $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

//         // Recorremos cada pedido para decodificar la columna 'pedido'
//         foreach ($resultados as &$pedido) {
//             if (isset($pedido['pedido'])) {
//                 // Decodificar la columna 'pedido' (JSON) en un array asociativo
//                 $pedido['pedido'] = json_decode($pedido['pedido'], true);
//             }
//         }

//         return $resultados;
//     }

//     public function deleteOrder($idMesa)
//     {
//         $sql = "DELETE FROM pedidos WHERE fk_id_mesas = :id";

//         $query = parent::connection()->prepare($sql);
//         $query->bindParam(':id', $idMesa);

//         return $query->execute();
//     }
// }

//SE ACTUALIZA EL MODELO (METODO) DE PEDIDOS PARA QUE SE PUEDA ASOCIAR A UNA MESA, UN USUARIO Y UN PEDIDO COMPLETO (JSON)
{
	public function insertOrder($id, $idUsuario, $idMesa, $pedidoCompleto)
	{
		$conn = parent::connection();
		try {

			$conn->beginTransaction();

			// Calcular total general
			$pedidoArray = json_decode($pedidoCompleto, true);

			$totalPedido = 0;

			foreach ($pedidoArray as $detalle) {

				$sqlProducto = "SELECT precio_producto 
                            FROM menu 
                            WHERE id = :id";

				$queryProducto = $conn->prepare($sqlProducto);
				$queryProducto->bindParam(':id', $detalle['platoId']);
				$queryProducto->execute();

				$producto = $queryProducto->fetch(PDO::FETCH_ASSOC);

				$precio = $producto['precio_producto'];
				$subtotal = $precio * $detalle['cantidad'];

				$totalPedido += $subtotal;
			}

			// Guardar encabezado pedido
			$sql = "INSERT INTO pedidos 
                (fk_id_usuario, fk_id_mesas, pedido, total, estado) 
                VALUES (:usuario, :mesa, :pedido, :total, 'abierto')";

			$query = $conn->prepare($sql);

			$query->bindParam(':usuario', $idUsuario);
			$query->bindParam(':mesa', $idMesa);
			$query->bindParam(':pedido', $pedidoCompleto);
			$query->bindParam(':total', $totalPedido);

			$query->execute();

			// Obtener ID del pedido creado
			$idPedido = $conn->lastInsertId();

			// Insertar detalle pedido
			foreach ($pedidoArray as $detalle) {

				$sqlProducto = "SELECT precio_producto 
                            FROM menu 
                            WHERE id = :id";

				$queryProducto = $conn->prepare($sqlProducto);
				$queryProducto->bindParam(':id', $detalle['platoId']);
				$queryProducto->execute();

				$producto = $queryProducto->fetch(PDO::FETCH_ASSOC);

				$precio = $producto['precio_producto'];
				$subtotal = $precio * $detalle['cantidad'];

				$sqlDetalle = "INSERT INTO detalle_pedido
            (
                fk_id_pedido,
                fk_id_producto,
                cantidad,
                precio_unitario,
                subtotal
            )
            VALUES
            (
                :pedido,
                :producto,
                :cantidad,
                :precio,
                :subtotal
            )";

				$queryDetalle = $conn->prepare($sqlDetalle);

				$queryDetalle->bindParam(':pedido', $idPedido);
				$queryDetalle->bindParam(':producto', $detalle['platoId']);
				$queryDetalle->bindParam(':cantidad', $detalle['cantidad']);
				$queryDetalle->bindParam(':precio', $precio);
				$queryDetalle->bindParam(':subtotal', $subtotal);

				$queryDetalle->execute();
			}

			// Actualizar estado mesa
			$sqlMesa = "UPDATE mesas 
                    SET estado = 'Ocupada'
                    WHERE id = :mesa";

			$queryMesa = $conn->prepare($sqlMesa);
			$queryMesa->bindParam(':mesa', $idMesa);
			$queryMesa->execute();

			$conn->commit();

			return true;
		} catch (Exception $e) {
			$conn->rollBack();
			echo "Error: " . $e->getMessage();
			return false;
		}
	}
}
