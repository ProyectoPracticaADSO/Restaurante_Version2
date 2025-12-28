<?php
include_once (__DIR__. '/../config/connection.php') ;

class AuthController {
    private $conn;

    public function __construct() {
        $this->conn = ConnectionDB::connection();
        if ($this->conn === null) {
                throw new Exception('Error de conexión a la base de datos: ' . ConnectionDB::connection()->errorInfo()[2]);
        }
    }

    public function login($cedula, $password) {
        // Validar los datos de entrada
        if (empty($cedula) || empty($password)) {
            return "Todos los campos son obligatorios.";
        }

        // Consultar usuario por cedula
        $query = "SELECT * FROM usuarios WHERE cedula_usuario = :cedula LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        session_start();
        if ($user) {
            // Verificar la contraseña
            if ($password == $user['contraseña_usuario']) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['user_id'] = $user['id']; // Guardar ID de usuario en sesión
                $_SESSION['user_name'] = $user['nombre_usuario']; // Guardar nombre de usuario en sesión
                $_SESSION['user_profile'] = $user['fk_id_perfil']; // Guardar perfil de usuario en sesión
                return true;
            } else {
                // Contraseña incorrecta
                return "Contraseña o usuario incorrectos. Intenta de nuevo.";
            }
        } else {
            // Usuario no encontrado
            return "Usuario no encontrado.";
        }
    }
}
?>