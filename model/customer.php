<?php
// require_once "../config/connection.php";
require_once __DIR__ . "/../config/connection.php";

class Customer extends ConnectionDB
{
  // ============================
  // INSERTAR CLIENTE
  // ============================
  public function insertCustomer($nombre, $numeroDocumento, $telefonoCliente, $correoCliente)
  {
    $sql = "INSERT INTO clientes 
                (nombre_cliente, cedula_cliente, numero_cliente, correo_cliente)
                VALUES (:nombre, :numeroDocumento, :telefonoCliente, :correoCliente)";

    $query = parent::connection()->prepare($sql);

    $query->bindParam(':nombre', $nombre);
    $query->bindParam(':numeroDocumento', $numeroDocumento);
    $query->bindParam(':telefonoCliente', $telefonoCliente);
    $query->bindParam(':correoCliente', $correoCliente);

    return $query->execute();
  }

  // ============================
  // VALIDAR CORREO EXISTENTE
  // ============================
  public function existCorreo($correoCliente)
  {
    $sql = "SELECT COUNT(*) AS count 
                FROM clientes 
                WHERE correo_cliente = :correoCliente";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':correoCliente', $correoCliente);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
  }

  // ============================
  // VALIDAR DOCUMENTO EXISTENTE
  // ============================
  public function existCedula($numeroDocumento)
  {
    $sql = "SELECT COUNT(*) AS count 
                FROM clientes 
                WHERE cedula_cliente = :numeroDocumento";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':numeroDocumento', $numeroDocumento);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
  }

  // ============================
  // LISTAR CLIENTES (NO ELIMINADOS)
  // ============================
  public function getCustomer()
  {
    $query = parent::connection()->prepare("
            SELECT id, nombre_cliente, cedula_cliente, 
              numero_cliente, correo_cliente
            FROM clientes
            WHERE eliminado = 0
        ");

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  // ============================
  // OBTENER CLIENTE POR ID
  // ============================
  public function getCustomerById($id)
  {
    $sql = "SELECT * FROM clientes WHERE id = :id";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);
    $query->execute();

    return $query->fetch(PDO::FETCH_ASSOC);
  }

  // ============================
  // ELIMINACIÓN LÓGICA
  // ============================
  public function eliminarCustomer($id)
  {
    $sql = "UPDATE clientes 
                SET eliminado = 1 
                WHERE id = :id";

    $query = parent::connection()->prepare($sql);
    $query->bindParam(':id', $id);

    return $query->execute();
  }

  // ============================
  // ACTUALIZAR CLIENTE
  // ============================
  public function updateCustomer($id, $nombre, $numeroDocumento, $telefonoCliente, $correoCliente)
  {
    $sql = "UPDATE clientes SET 
                    nombre_cliente = :nombre,
                    cedula_cliente = :numeroDocumento,
                    numero_cliente = :telefonoCliente,
                    correo_cliente = :correoCliente
                WHERE id = :id";

    $query = parent::connection()->prepare($sql);

    $query->bindParam(':id', $id);
    $query->bindParam(':nombre', $nombre);
    $query->bindParam(':numeroDocumento', $numeroDocumento);
    $query->bindParam(':telefonoCliente', $telefonoCliente);
    $query->bindParam(':correoCliente', $correoCliente);

    return $query->execute();
  }

  // ============================
  // (OPCIONAL) LISTAR TODOS
  // ============================
  public function getAllCustomers()
  {
    $query = parent::connection()->prepare("
            SELECT * FROM clientes
        ");

    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
}
