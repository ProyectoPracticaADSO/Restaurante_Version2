
<!DOCTYPE html>

<html lang="es-CO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/StyleLogins.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Restablecer Contraseña</title>
</head>

<header>
    <h1>RESTAURANTE</h1>
</header>

<body>
    <form action="../controller/RecuperarController.php?action=restablecer" method="POST">
        <a href=".\RecuperarContrasena.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        <h2>Restablecer</h2>


            <div class="form-group">
                <label for="token">Token de Recuperación</label>
                <input type="text" class="form-control" id="token" name="token" placeholder="Ingrese el token recibido" required>
            </div>

            <div class="form-group">
                <label for="nueva_contrasena">Nueva Contraseña</label>
                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" placeholder="Ingrese su nueva contraseña" required>
            </div>

            <div class="form-group">
                <label for="confirmar_contrasena">Confirmar Nueva Contraseña</label>
                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirme su nueva contraseña" required>
            </div>

            <div class="button-container mt-3">
                <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
            </div>
        </form>
    </div>
</body>
</html>
