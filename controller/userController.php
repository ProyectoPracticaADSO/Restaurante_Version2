<?php
include_once '../model/user.php';

if (isset($_GET['action'])) {

	$user = new User();

	/* =======================
      AGREGAR USUARIO
    ======================= */
	if ($_GET['action'] == 'agregar') {

		// Recibir datos del formulario
		$nombre = $_POST['nombre'] ?? '';
		$tipoDocumento = $_POST['tipo_documento'] ?? '';
		$numeroDocumento = $_POST['numero'] ?? '';
		$numeroTelefono = $_POST['numero_telefono'] ?? '';
		$correo = $_POST['correo'] ?? '';
		$perfil = $_POST['perfil'] ?? '';
		$estado = $_POST['estado'] ?? '';
		$contrasena = $_POST['contrasena'] ?? '';


		//  Validación de campos obligatorios
		if (
			empty($nombre) ||
			empty($tipoDocumento) ||
			empty($numeroDocumento) ||
			empty($numeroTelefono) ||
			empty($correo) ||
			empty($perfil) ||
			empty($estado)
		) {
			header('Location: ../view/AgregarUsuario.php?error=campos');
			exit();
		}

		// Lógica de contraseña
		if ($perfil != 1) {
			$contrasena = $numeroDocumento;
		}

		// Validar duplicado de cédula (usar cédula completa)
		if ($user->existCedula($numeroDocumento)) {
			header('Location: ../view/AgregarUsuario.php?error=cedula');
			exit();
		}

		// Insertar usuario (ORDEN CORRECTO)
		$resultado = $user->insertUser(
			$nombre,
			$tipoDocumento,
			$numeroDocumento,
			$numeroTelefono,
			$correo,
			$contrasena,
			$perfil,
			$estado
		);

		// Redirección
		if ($resultado) {
			header('Location: ../view/GestionUsuarios.php?success=creado');
		} else {
			header('Location: ../view/AgregarUsuario.php?error=insertar');
		}
		exit();
	}

	/* =======================
      ELIMINAR USUARIO
    ======================= */

	if ($_GET['action'] === 'eliminar' && isset($_GET['id'])) {

		$idUsuario = $_GET['id'];
		$resultado = $user->eliminarUsuario($idUsuario);

		if ($resultado) {
			header('Location: ../view/GestionUsuarios.php?success=eliminado');
		} else {
			header('Location: ../view/GestionUsuarios.php?error=eliminar');
		}
		exit();
	}

	/* =======================
      EDITAR USUARIO
    ======================= */
	if ($_GET['action'] == 'editar' && isset($_POST['id'])) {

		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$tipoDocumento = $_POST['tipo_documento'] ?? '';
		$numeroDocumento = $_POST['numero'] ?? '';
		$numeroTelefono = $_POST['numero_telefono'];
		$correo = $_POST['correo'];
		$perfil = $_POST['perfil'];
		$estado = $_POST['estado'];
		$contrasena = $_POST['contrasena'];

		if ($perfil != 1) {
			$contrasena = $numeroDocumento;
		}

		$resultado = $user->updateUser(
			$id,
			$nombre,
			$tipoDocumento,
			$numeroDocumento,
			$numeroTelefono,
			$correo,
			$contrasena,
			$perfil,
			$estado
		);

		if ($resultado) {
			header('Location: ../view/GestionUsuarios.php?success=actualizado');
		} else {
			header('Location: ../view/EditarUsuario.php?id=' . $id . '&error');
		}
		exit();
	}
}
