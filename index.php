<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfiles de Usuario</title>
    <link rel="stylesheet" href="./css/index/styles.css">
    <!-- Importar Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    
   <style>
    body {
      /* Imagen de fondo */
      background-image: url("css/index/img/Hamburguesa.jpg"); 
      /* Ajustes para que quede bien */
      background-size: cover;       /* La imagen cubre toda la pantalla */
      background-position: center;  /* La centra */
      background-repeat: no-repeat; /* Evita que se repita */
      background-attachment: fixed; /* Se queda fija al hacer scroll */
    }
  </style>




    <!-- Encabezado Base1 -->
    <?php include 'view/bases/base1.php'; ?>

    <header>
    </header>

    <!-- Contenedor de Perfiles - Ahora clickeables -->
    <div class="profiles-grid">
        <a href="./view/loginAd.php" class="profile btn">
            <i class="fa-solid fa-user-tie"></i>
            <span>Admin</span>
        </a>

        <a href="./view/loginMesero.php" class="profile btn">
            <i class="fa-solid fa-concierge-bell"></i>
            <span>Mesero</span>
        </a>

        <a href="./view/loginCocina.php" class="profile btn">
            <i class="fa-solid fa-kitchen-set"></i>
            <span>Cocina</span>
        </a>

        <a href="./view/loginCaja.php" class="profile btn">
            <i class="fa-solid fa-cash-register"></i>
            <span>Caja</span>
        </a>
    </div>

    <!-- Pie de Página Base2 -->
   
   <?php include './view/bases/base2.php'; ?>
  

</body>
</html>
