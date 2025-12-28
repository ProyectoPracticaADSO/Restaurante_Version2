<?php
require_once __DIR__ . '/../config/connection.php';

class Mesa {
    private $conn;

    public function __construct() {
        $this->conn = ConnectionDB::connection();
    }

    public function getMesas() {
        $sql = "SELECT * FROM mesas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertMesa($numero_mesa) {
        $sql = "INSERT INTO mesas (numero_mesa, estado) VALUES (?, 'Disponible')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$numero_mesa]);
    }

    public function deleteMesa($id) {
        $sql = "DELETE FROM mesas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getMesaById($id) {
        $sql = "SELECT * FROM mesas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizarMesa($id, $numero_mesa, $estado ) {
        $sql = "UPDATE mesas SET numero_mesa = ?, estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$numero_mesa, $estado, $id]);
    }
}
?>