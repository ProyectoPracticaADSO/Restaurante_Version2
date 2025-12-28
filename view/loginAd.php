<?php
// Incluir el archivo de conexión a la base de datos y el controlador de autenticación
include_once __DIR__. '/../config/connection.php';
include_once __DIR__. '/../controller/authController.php';

// Inicializar la variable de error
$error_message = "";

// Verificar si se recibió una solicitud POST desde el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $cedula = $_POST['cedula'];
    $password = $_POST['contraseña'];

    // Instanciar el controlador de autenticación
    $authController = new AuthController();

    // Llamar al método para realizar el inicio de sesión
    $loginResult = $authController->login($cedula, $password);

    $profile = 3;

    // Verificar el resultado del inicio de sesión
    if ($loginResult === true) {
        // Inicio de sesión exitoso, redirigir al dashboard o a otra página
        $userProfile = $_SESSION['user_profile'];
        
        if ($userProfile == $profile || $userProfile == 1) {
            header('Location: ./menus/MenuAdmin.php');
            exit();
        } else {
            $error_message = "No tienes el permiso para acceder a este perfil";
        }
    } else {
        // Guardar el mensaje de error en una variable
        $error_message = $loginResult;
    }
}
?>

<!DOCTYPE html>
<html lang="es-CO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login_Userd/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Login Administrador</title>
</head>
<body class="admin-bg">
    <div class="form-container">
        <form action="loginAd.php" method="POST" class="needs-validation" novalidate>
            <a href="../index.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
            <h2>Inicio Sesión Administrador</h2>

            <!-- Mostrar mensaje de error si existe -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert"><?= $error_message; ?></div>
            <?php endif; ?>

            <!-- Campo Cédula con icono dentro -->
            <div class="form-group mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" id="cedula" name="cedula" pattern="\d+" required placeholder="Cédula">
                </div>
            </div>

            <!-- Campo Contraseña con icono dentro -->
            <div class="form-group mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required placeholder="Contraseña">
                </div>
            </div>

            <!-- Botón de enviar -->
            <div class="button-container">
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                <a href="RecuperarContrasena.php" class="d-block text-center mt-2">¿Olvidó su contraseña?</a>
            </div>
        </form>
    </div>

    <?php include '../view/bases/base2.php'; ?>
    <?php include '../view/bases/base1.php'; ?>
</body>
</html>