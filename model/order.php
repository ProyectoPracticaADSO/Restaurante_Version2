<?php
require_once "../config/connection.php";
class Order extends ConnectionDB
{
    public function insertOrder($id, $idUsuario, $idMesa, $pedidoCompleto)
    {
        $sql = "INSERT INTO pedidos (id, fk_id_usuario, fk_id_mesas, pedido) 
        VALUES (:id, :usuario, :mesa, :pedido)";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':usuario', $idUsuario);
        $query->bindParam(':mesa', $idMesa);
        $query->bindParam(':pedido', $pedidoCompleto);


        return $query->execute();
    }

    public function updateOrder($idUsuario, $idMesa, $pedidoCompleto)
    {
        $sql = "UPDATE pedidos SET
                fk_id_usuario = :usuario,
                pedido = :pedido
            WHERE fk_id_mesas = :mesa";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':usuario', $idUsuario);
        $query->bindParam(':mesa', $idMesa);
        $query->bindParam(':pedido', $pedidoCompleto);


        return $query->execute();
    }

    public function getOrder()
    {
        $query = parent::connection()->prepare("SELECT * FROM pedidos");
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Recorremos cada pedido para decodificar la columna 'pedido'
        foreach ($resultados as &$pedido) {
            if (isset($pedido['pedido'])) {
                // Decodificar la columna 'pedido' (JSON) en un array asociativo
                $pedido['pedido'] = json_decode($pedido['pedido'], true);
            }
        }

        return $resultados;
    }


    public function getOrderByMesa($id)
    {
        $query = parent::connection()->prepare("SELECT * FROM pedidos WHERE fk_id_mesas = :id");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Recorremos cada pedido para decodificar la columna 'pedido'
        foreach ($resultados as &$pedido) {
            if (isset($pedido['pedido'])) {
                // Decodificar la columna 'pedido' (JSON) en un array asociativo
                $pedido['pedido'] = json_decode($pedido['pedido'], true);
            }
        }

        return $resultados;
    }

    public function deleteOrder($idMesa)
    {
        $sql = "DELETE FROM pedidos WHERE fk_id_mesas = :id";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $idMesa);

        return $query->execute();
    }
}
