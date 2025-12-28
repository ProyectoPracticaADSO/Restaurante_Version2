<?php
// se incluye el archivo de la clase connection
include 'Connection.php'; 

$conn = ConnectionDB::connection();

if ($conn) {
    echo "conexión exitosa!";
} else {
    echo "Error en la conexión!";

}
?>