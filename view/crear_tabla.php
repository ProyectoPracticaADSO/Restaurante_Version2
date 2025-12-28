<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../config/connection.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS mesas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        numero_mesa INT NOT NULL UNIQUE,
        estado ENUM('Disponible', 'Ocupada', 'Reservada') DEFAULT 'Disponible'
    )";

    $conexion = ConnectionDB::connection();
    $conexion->exec($sql);

    echo "Tabla 'mesas' creada correctamente o ya existe.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
?>