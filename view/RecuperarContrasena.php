<?php
include_once '../model/user.php';
include_once '../controller/RecuperarController.php';
?>

<!DOCTYPE html>
<html lang="es-CO">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/StyleLogins.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
  
  <header>
  </header>

  <div class="form-container">
    <form action="../controller/RecuperarController.php?action=solicitar" method="POST">
      <a href="/index.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
      <h2>Recuperar Contraseña</h2>
      
      <div class="form-group input-icon">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required>
      </div>

      <div class="button-container">
        <button type="submit">Enviar correo de recuperación</button>
        <a href="RecuperarPorToken.php">Recuperar por token</a>
      </div>
    </form>
  </div>

     
</body>
</html>
