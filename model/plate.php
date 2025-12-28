<?php
require_once "../config/connection.php";
class Plate extends ConnectionDB
{
    public function getPlate()
    {
        $query = parent::connection()->prepare("SELECT m.id, c.nombre_categoria, m.nombre_producto, m.descripcion_producto, m.precio_producto, m.ingredientes_producto, m.posibles_adiciones
         FROM menu m
         JOIN categorias c ON m.fk_id_categorias = c.id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlateByCategory($id)
    {
        $query = parent::connection()->prepare("SELECT m.id, c.id AS categoria_id, c.nombre_categoria, m.nombre_producto, m.descripcion_producto, m.precio_producto, m.ingredientes_producto, m.posibles_adiciones
         FROM menu m
         JOIN categorias c ON m.fk_id_categorias = c.id
         WHERE m.fk_id_categorias = :id_categoria");
        $query->bindParam(':id_categoria', $id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertPlate($id, $categoria, $nombre, $descripcion, $precio, $idIngredientes, $idAdiciones)
    {
        $sql = "INSERT INTO menu (id, fk_id_categorias, nombre_producto, descripcion_producto, precio_producto, ingredientes_producto, posibles_adiciones) 
         VALUES (:id, :categoria, :nombre, :descripcion, :precio, :ingredientes, :adiciones)";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':categoria', $categoria);
        $query->bindParam(':nombre', $nombre);
        $query->bindParam(':descripcion', $descripcion);
        $query->bindParam(':precio', $precio);
        $query->bindParam(':ingredientes', $idIngredientes);
        $query->bindParam(':adiciones', $idAdiciones);

        return $query->execute();
    }

    public function getPlateById($id)
    {
        $sql = "SELECT * FROM menu WHERE id = :id";
        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    

    

    public function updatePlate($id, $categoria, $nombre, $descripcion, $precio, $idIngredientes, $idAdiciones)
    {
        $sql = "UPDATE menu SET
                fk_id_categorias = :categoria,
                nombre_producto = :nombre,
                descripcion_producto = :descripcion,
                precio_producto = :precio,
                ingredientes_producto = :ingredientes,
                posibles_adiciones = :adiciones
            WHERE id = :id";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':categoria', $categoria);
        $query->bindParam(':nombre', $nombre);
        $query->bindParam(':descripcion', $descripcion);
        $query->bindParam(':precio', $precio);
        $query->bindParam(':ingredientes', $idIngredientes);
        $query->bindParam(':adiciones', $idAdiciones);


        return $query->execute();
    }

    public function deletePlate($id)
    {
        $sql = "DELETE FROM menu WHERE id = :id";

        $query = parent::connection()->prepare($sql);
        $query->bindParam(':id', $id);

        return $query->execute();
    }

}
