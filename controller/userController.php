<?php
include_once '../model/user.php';



if (isset($_GET['action'])) {
	$user = new User();


	if ($_GET['action'] == 'agregar') {
		$id = '0'; //Al ser autoincrementable se pone 0.
		$nombre = $_POST['nombre'];
		$cedula = $_POST['cedula'];
		$numero = $_POST['numero'];
		$correo = $_POST['correo'];
		$contrasena = $_POST['contrasena'];
		$perfil = $_POST['perfil'];
		$estado = $_POST['estado'];

		//Validación de campos vacíos o nulos 
		if (empty($nombre) || empty($cedula) || empty($numero) || empty($correo) || empty($perfil) || empty($estado)) {
			header('Location: ../view/AgregarUsuario.php?error=campos_vacios');
			exit();
		}

		if ($perfil != 1) {
			$contrasena = $cedula;
		}

		$existeCedula = $user->existCedula($cedula);
		if ($existeCedula) {
			header('Location: ../view/AgregarUsuario.php?error');
			exit();
		}

		$resultado = $user->insertUser($id, $nombre, $cedula, $numero, $correo, $contrasena, $perfil, $estado);

		if ($resultado) {
			header('Location: ../view/AgregarUsuario.php?success');
		} else {
			header('Location: ../view/AgregarUsuario.php?error');
		}
	}


	if ($_GET['action'] == 'eliminar' && isset($_GET['id'])) {
		$idUsuario = $_GET['id'];

		$resultado = $user->deleteUser($idUsuario);

		if ($resultado) {
			header('Location: ../view/GestionUsuarios.php?success=eliminado');
		} else {
			header('Location: ../view/GestionUsuarios.php');
		}
	}

	if ($_GET['action'] == 'editar' && isset($_POST['id'])) {
		$id = $_POST['id'];
		$nombre = $_POST['nombre'];
		$cedula = $_POST['cedula'];
		$numero = $_POST['numero'];
		$correo = $_POST['correo'];
		$perfil = $_POST['perfil'];
		$estado = $_POST['estado'];
		$contrasena = $_POST['contrasena'];

		if ($perfil != 1) {
			$contrasena = $cedula;
		}

		$resultado = $user->updateUser($id, $nombre, $cedula, $numero, $correo, $contrasena, $perfil, $estado);

		if ($resultado) {
			header('Location: ../view/GestionUsuarios.php?success=actualizado');
		} else {
			header('Location: ../view/EditarUsuario.php?id=' . $id . '&error');
		}
	}
}
