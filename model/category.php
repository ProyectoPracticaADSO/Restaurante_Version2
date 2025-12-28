<?php
require_once "../config/connection.php";
class Category extends ConnectionDB
{
  public function getCategory()
  {
    $query = parent::connection()->prepare("SELECT * FROM categorias");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insertCategory($id, $nombre, $imagen)
  {
    $sql = "INSERT INTO categorias (id, nombre_categoria, imagen) 
                VALUES (:id, :nombre, :imagen)";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->bindParam(':nombre', $nombre);
    $query->bindParam(':imagen', $imagen, PDO::PARAM_LOB);

    return $query->execute();
  }


  public function deleteCategory($id)
  {
    $sql = "DELETE FROM categorias WHERE id = :id";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);

    return $query->execute();
  }

  public function getCategoryById($id)
  {
    $sql = "SELECT * FROM categorias WHERE id = :id";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
  }

  public function updateCategory($id, $nombre_categoria)
  {
    $sql = "UPDATE categorias SET 
                nombre_categoria = :nombre_categoria
            WHERE id = :id";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->bindParam(':nombre_categoria', $nombre_categoria);

    return $query->execute();
  }
  public function checkCategoryExists($nombre)
  {
    $sql = "SELECT COUNT(*) FROM categorias WHERE nombre_categoria = :nombre";
    $query = parent::connection()->prepare($sql);
    $query->bindParam(':nombre', $nombre);
    $query->execute();
    $count = $query->fetchColumn();
    return $count > 0; // Devuelve true si ya existe, de lo contrario false
  }
}
