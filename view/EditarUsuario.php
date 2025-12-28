<?php
include_once '../model/user.php';

$user = new User();

if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];
    $usuario = $user->getUserById($idUsuario);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
   <link rel="stylesheet" href="../css/inventory.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
  <!-- Contenedor blanco principal -->
  <div class="container mt-5 position-relative w-25" >
   <?php include '../view/bases/base5.php'; ?> <!-- Exclusivo para el backspace -->
   
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">Error al actualizar el usuario.</div>';
        } elseif (isset($_GET['success'])) {
            echo '<div class="alert alert-success">Usuario actualizado exitosamente.</div>';
        }
        ?>
    <header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
      
    <div class="d-flex align-items-center mb-3 mb-md-0">
        <img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 50px; margin-right: 10px;">
        <h1 class="display-10 mb-0 mr-3">Editar Empleados</h1>
        <div class="btn-group ml-3 flex-wrap">
          
        </div>
      </div>
      
    </header>

        <form action="../controller/userController.php?action=editar" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <div class="form-group ">
                <label for="nombre">Nombre:</label>
                <!-- Verificación para los espacios en blanco del formulario (NO se pueden al inicio ni al final y tampoco más de 
                 un espacio entre palabras ) -->
                <input type="text" class="form-control" pattern="^[A-Za-zÀ-ÿ\u00f1\u00d1]+(?: [A-Za-zÀ-ÿ\u00f1\u00d1]+)*$" id="nombre" name="nombre" value="<?= $usuario['nombre_usuario'] ?>" required>
                <div class="invalid-feedback">Por favor ingrese el nombre (Verifique los espacios en blanco).</div>
            </div>
            <div class="form-row " >
                <div class="form-group col-md-6">
                    <label for="cedula">Cédula:</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" pattern="\d+" value="<?= $usuario['cedula_usuario'] ?>" readonly>
                    <div class="invalid-feedback">Por favor ingrese la cédula (solo números).</div>
                </div>
                <div class="form-group col-md-6">
                    <label for="numero">Número:</label>
                    <input type="text" class="form-control" id="numero" name="numero" pattern="\d+" value="<?= $usuario['numero_usuario'] ?>" required>
                    <div class="invalid-feedback">Por favor ingrese el número (solo números).</div>
                </div>
            </div>
            <div class="form-group ">
                <label for="correo">Correo:</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?= $usuario['correo_usuario'] ?>" required>
                <div class="invalid-feedback">Por favor ingrese el correo.</div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="perfil">Rol:</label>
                    <select class="form-control" id="perfil" name="perfil" required>
                        <option value="">Seleccione un perfil</option>
                        <option value="1" <?= $usuario['fk_id_perfil'] == 1 ? 'selected' : '' ?>>Administrador</option>
                        <option value="2" <?= $usuario['fk_id_perfil'] == 2 ? 'selected' : '' ?>>Mesero</option>
                        <option value="3" <?= $usuario['fk_id_perfil'] == 3 ? 'selected' : '' ?>>Caja</option>
                        <option value="4" <?= $usuario['fk_id_perfil'] == 4 ? 'selected' : '' ?>>Cocina</option>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione un perfil.</div>
                </div>
                <div class="form-group col-md-6">
                    <label for="estado">Estado:</label>
                    <select class="form-control" id="estado" name="estado" required>
                        <option value="">Seleccione un estado</option>
                        <option value="1" <?= $usuario['fk_id_estado'] == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="2" <?= $usuario['fk_id_estado'] == 2 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                    <div class="invalid-feedback">Por favor seleccione un estado.</div>
                </div>
            </div>
            <div class="form-group" id="contrasenaDiv" style="display: none;">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" value="<?= $usuario['contraseña_usuario'] ?>" pattern="^[A-Za-zÀ-ÿ\u00f1\u00d1\d]+(?: [A-Za-zÀ-ÿ\u00f1\u00d1\d]+)*$" id="contrasena" name="contrasena">
                <div class="invalid-feedback">Por favor ingrese una contraseña válida.</div>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-warning px-5" data-bs-toggle="modal" data-bs-target="#confirmModal" >Actualizar</button>
           

            </div>
          
        </form>
    </div>



  <!-- Modal de Confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="confirmModalLabel">Confirmar Edición</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas guardar los cambios en este registro?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="confirmEditBtn">Sí, guardar</button>
      </div>
    </div>
  </div>
</div>

  <script>
  document.getElementById('confirmEditBtn').addEventListener('click', function() {
    document.getElementById('editForm').submit(); // Enviar formulario al confirmar
  });
</script>




  <!-- Modal de Confirmación -->
  <script src="../js/validation.js"></script>
  <script src="../js/inventorySearch.js"></script>
  <?php include '../view/bases/base2.php'; ?>
  <?php include '../view/bases/base1.php'; ?>



</body>

</html>
