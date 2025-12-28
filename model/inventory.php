<?php
require_once "../config/connection.php";

class Inventory extends ConnectionDB
{
    public function getInventory()
    {
        $query = parent::connection()->prepare("SELECT i.id, i.descripcion_producto, t.nombre_tipos, i.cantidad, um.nombre_unidad_medida, i.precio_unitario, i.fecha_ingreso, i.fecha_vencimiento
         FROM inventario i
         JOIN tipos t ON i.fk_id_tipos = t.id
         JOIN unidad_medida um ON i.fk_id_unidad_medida = um.id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertProduct($id, $descripcion, $tipo, $cantidad, $unidadMedida, $precioUnitario, $fechaIngreso, $fechaVencimiento)
    {
        $sql = "INSERT INTO inventario (id, descripcion_producto, fk_id_tipos, cantidad, fk_id_unidad_medida, precio_unitario, fecha_ingreso, fecha_vencimiento) 
         VALUES (:id, :descripcion, :tipo, :cantidad, :unidadMedida, :precioUnitario, :fechaIngreso, :fechaVencimiento)";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':descripcion', $descripcion);
        $query->bindParam(':tipo', $tipo);
        $query->bindParam(':cantidad', $cantidad);
        $query->bindParam(':unidadMedida', $unidadMedida);
        $query->bindParam(':fechaIngreso', $fechaIngreso);
        $query->bindParam(':fechaVencimiento', $fechaVencimiento);
        $query->bindParam(':precioUnitario', $precioUnitario);

        return $query->execute();
    }

    public function deleteProduct($id)
    {
        $sql = "DELETE FROM inventario WHERE id = :id";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);

        return $query->execute();
    }

    public function getProductById($id)
    {
        $sql = "SELECT * FROM inventario WHERE id = :id";
        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($id, $descripcion, $tipo, $cantidad, $unidadMedida, $precioUnitario, $fechaIngreso, $fechaVencimiento)
    {
        $sql = "UPDATE inventario SET 
                descripcion_producto = :descripcion, 
                fk_id_tipos = :tipo, 
                cantidad = :cantidad, 
                fk_id_unidad_medida = :unidadMedida, 
                precio_unitario = :precioUnitario, 
                fecha_ingreso = :fechaIngreso, 
                fecha_vencimiento = :fechaVencimiento
            WHERE id = :id";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':descripcion', $descripcion);
        $query->bindParam(':tipo', $tipo);
        $query->bindParam(':cantidad', $cantidad);
        $query->bindParam(':unidadMedida', $unidadMedida);
        $query->bindParam(':fechaIngreso', $fechaIngreso);
        $query->bindParam(':fechaVencimiento', $fechaVencimiento);
        $query->bindParam(':precioUnitario', $precioUnitario);

        return $query->execute();
    }

    // Obtener el producto más viejo en bodega
    public function getOldestProduct()
    {
        $sql = "SELECT * FROM inventario ORDER BY fecha_ingreso ASC LIMIT 1";
        $query = parent::connection()->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener el producto más próximo a vencer
    public function getNearestExpiringProduct()
    {
        $sql = "SELECT * FROM inventario ORDER BY fecha_vencimiento ASC LIMIT 1";
        $query = parent::connection()->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
   

    // Obtener el valor total de la bodega
    public function getTotalWarehouseValue()
    {
        $sql = "SELECT SUM(cantidad * precio_unitario) AS total_valor FROM inventario";
        $query = parent::connection()->prepare($sql);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC)['total_valor'];
    }
}
