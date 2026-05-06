<?php
include_once '../model/customer.php';

if (isset($_GET['action'])) {

  $customer = new Customer();

  // ============================
  // AGREGAR CLIENTE
  // ============================
  if ($_GET['action'] == 'agregar') {

    $nombre = $_POST['nombre'] ?? '';
    $numeroDocumento = $_POST['numeroDocumento'] ?? '';
    $telefonoCliente = $_POST['telefonoCliente'] ?? '';
    $correoCliente = $_POST['correoCliente'] ?? '';

    // VALIDAR CAMPOS VACÍOS
    if (empty($nombre) || empty($numeroDocumento) || empty($telefonoCliente) || empty($correoCliente)) {
      header('Location: ../view/AgregarCliente.php?error=campos_vacios');
      exit();
    }

    // VALIDAR DUPLICADOS
    if ($customer->existCedula($numeroDocumento)) {
      header('Location: ../view/AgregarCliente.php?error=cedula_existe');
      exit();
    }

    if ($customer->existCorreo($correoCliente)) {
      header('Location: ../view/AgregarCliente.php?error=correo_existe');
      exit();
    }

    // INSERTAR
    $resultado = $customer->insertCustomer($nombre, $numeroDocumento, $telefonoCliente, $correoCliente);

    if ($resultado) {
      header('Location: ../view/GestionClientes.php?success=creado');
    } else {
      header('Location: ../view/AgregarCliente.php?error=error_insert');
    }
  }

  // ============================
  // ELIMINAR CLIENTE (LÓGICO)
  // ============================
  if ($_GET['action'] == 'eliminar' && isset($_GET['id'])) {

    $idCliente = $_GET['id'];

    $resultado = $customer->eliminarCustomer($idCliente);

    if ($resultado) {
      header('Location: ../view/GestionClientes.php?success=eliminado');
    } else {
      header('Location: ../view/GestionClientes.php?error=error_eliminar');
    }
  }

  // ============================
  // EDITAR CLIENTE
  // ============================
  if ($_GET['action'] == 'editar' && isset($_POST['id'])) {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'] ?? '';
    $numeroDocumento = $_POST['numeroDocumento'] ?? '';
    $telefonoCliente = $_POST['telefonoCliente'] ?? '';
    $correoCliente = $_POST['correoCliente'] ?? '';

    // VALIDAR CAMPOS VACÍOS
    if (empty($nombre) || empty($numeroDocumento) || empty($telefonoCliente) || empty($correoCliente)) {
      header('Location: ../view/EditarCliente.php?id=' . $id . '&error=campos_vacios');
      exit();
    }

    $resultado = $customer->updateCustomer($id, $nombre, $numeroDocumento, $telefonoCliente, $correoCliente);

    if ($resultado) {
      header('Location: ../view/GestionClientes.php?success=actualizado');
    } else {
      header('Location: ../view/EditarCliente.php?id=' . $id . '&error=error_update');
    }
  }
}
