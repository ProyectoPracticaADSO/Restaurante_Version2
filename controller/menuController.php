<?php
require_once '../model/plate.php';

if (isset($_GET['action'])) {
    $plate = new Plate();


    if ($_GET['action'] == 'agregar') {
        $id = '0'; //Al ser autoincrementable se pone 0.
        $categoria = $_POST['categoria'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $idIngredientes = $_POST['ingredientes_ids'];
        $idAdiciones = $_POST['adiciones_ids'];



        $resultado = $plate->insertPlate($id, $categoria, $nombre, $descripcion, $precio, $idIngredientes, $idAdiciones);

        if ($resultado) {
            header('Location: ../view/AgregarMenu.php?success&id='. $categoria);
        } else {
            header('Location: ../view/AgregarMenu.php?error');
        }
    }

    if ($_GET['action'] == 'eliminar' && isset($_GET['id'])) {
        $idPlate = $_GET['id'];
        $categoria = $_GET['idCat'];

        $resultado = $plate->deletePlate($idPlate);

        if ($resultado) {
            header('Location: ../view/GestionMenu.php?success=eliminado&id='. $categoria);
        } else {
            header('Location: ../view/GestionMenu.php');
        }
    }

    if ($_GET['action'] == 'editar' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $categoria = $_POST['categoria'];
        $idIngredientes = $_POST['ingredientes_ids'];
        $idAdiciones = $_POST['adiciones_ids'];

        $resultado = $plate->updatePlate($id, $categoria, $nombre, $descripcion, $precio, $idIngredientes, $idAdiciones);

        if ($resultado) {
            header('Location: ../view/GestionMenu.php?success=actualizado&id='. $categoria);
        } else {
            header('Location: ../view/EditarMenu.php?id=' . $id . '&error');
        }
    }


    

}
