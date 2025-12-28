<?php
include_once '../model/user.php';
include_once '../controller/RecuperarController.php';


?>

<!DOCTYPE html>

<html lang="es-CO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/StyleLogins.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Recuperar Contraseña</title>
</head>

<header>
</header>

<body>
    <form action="../controller/RecuperarController.php?action=solicitarToken" method="POST">
        <a href=".\RecuperarContrasena.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        <h2>Recuperar Por Token </h2>
        
          <div class="form-group input-icon">
        <i class="fa-solid fa-user"></i>
        <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required>
      </div>
            <button type="submit">Enviar correo de recuperación</button>
            
        </div>
        </div>
    </form>
      
          

  
</body>
</html>
