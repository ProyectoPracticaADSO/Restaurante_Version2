<!DOCTYPE html>
<html lang="es-CO">

<head>
  <script src="../../mb30901i.js" conjunto de caracteres="UTF-8"></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <br>
  <title>Panel Admin — Restaurante</title>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="../../css/StyleMenu.css" />
  <link rel="stylesheet" href="../../css/modal.css" />
</head>

<body>

  <div class="admin-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="brand">
        <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo restaurante" class="logo-empresa">
        <span class="titulo">Restaurant</span>
      </div>
      <nav class="nav-menu">
        <a href="../GestionCategorias.php"><i class="fa-solid fa-utensils"></i> Gestión Menú</a>
        <a href="../MenuPedidosAdmin.php"><i class="fa-solid fa-receipt"></i> Pedidos</a>
        <a href="../GestionMesas.php"><i class="fa-solid fa-chair"></i> Mesas</a>
        <a href="../GestionUsuarios.php"><i class="fa-solid fa-user-tie"></i> Personal</a>
        <a href="../GestionInventario.php"><i class="fa-solid fa-boxes"></i> Inventario</a>
        <!-- Opción de Salir con modal -->
        <a href="#" onclick="showModalConfirm('/view/loginAd.php')"><i class="fa-solid fa-sign-out-alt"></i> Salir</a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
      <!-- TOPBAR -->
      <header class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
        <h1>Panel Administrador</h1>
        <div class="perfil-header">
          <!--<i class="fa-regular fa-bell"></i>-->
          <a href="../loginAd.php">
            <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo Empresa" class="logo-perfil" />
          </a>
        </div>
      </header>

      <!-- TOOLBAR -->
      <div class="toolbar">
        <input type="search" id="searchInput" placeholder="Buscar sección…" />
      </div>

      <!-- GRID DE TARJETAS -->
      <section class="cards-grid">
        <a href="../GestionCategorias.php" class="card" data-title="Gestión Menú">
          <i class="fa-solid fa-utensils"></i>
          <h2>Gestión Menú</h2>
        </a>
        <a href="../MenuPedidosAdmin.php" class="card" data-title="Pedidos">
          <i class="fa-solid fa-receipt"></i>
          <h2>Pedidos</h2>
        </a>
        <a href="../GestionMesas.php" class="card" data-title="Mesas">
          <i class="fa-solid fa-chair"></i>
          <h2>Mesas</h2>
        </a>
        <a href="../GestionUsuarios.php" class="card" data-title="Personal">
          <i class="fa-solid fa-user-tie"></i>
          <h2>Personal</h2>
        </a>
        <a href="../GestionInventario.php" class="card" data-title="Inventario">
          <i class="fa-solid fa-boxes"></i>
          <h2>Inventario</h2>
        </a>
      </section>
    </main>
  </div>

  <!-- MODAL DE CONFIRMACIÓN -->
  <div id="modal-confirm" class="modal-overlay">
    <div class="modal-content">
      <h3>¿Estás seguro que deseas salir?</h3>
      <div class="modal-actions">
        <a id="btn-confirm" class="btn-confirm">Sí, salir</a>
        <button id="btn-cancel" class="btn-cancel">Cancelar</button>
      </div>
    </div>
  </div>




  <!-- Pie de página PHP -->
  <?php include '../bases/base2.php'; ?>



  <script src="../../modal.js"></script>

  <script src="../../main.js"></script>
</body>

</html>