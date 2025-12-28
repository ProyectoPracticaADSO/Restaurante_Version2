<?php
include_once '../model/category.php';

if (isset($_GET['action'])) {
    $category = new Category();

    if ($_GET['action'] == 'agregar') {
        $id = '0'; // Al ser autoincrementable se pone 0.
        $nombre = $_POST['nombre_categoria'];
        $imagen = file_get_contents($_FILES['imagen_categoria']['tmp_name']); // Leer la imagen como binario
    
        // Verificar si la categoría ya existe
        if ($category->checkCategoryExists($nombre)) {
            header('Location: ../view/AgregarCategoria.php?error=exists');
            exit;
        }
    
        // Insertar categoría con imagen
        $resultado = $category->insertCategory($id, $nombre, $imagen);
        if ($resultado) {
            header('Location: ../view/AgregarCategoria.php?success');
        } else {
            header('Location: ../view/AgregarCategoria.php?error');
        }
    }
    

    if ($_GET['action'] == 'eliminar' && isset($_GET['id'])) {
        $idCategory = $_GET['id'];
        try {

            $resultado = $category->deleteCategory($idCategory);
            if ($resultado) {
                header('Location: ../view/GestionCategorias.php?success=eliminado)');
            }
        } catch (Exception $e) {
            header('Location: ../view/GestionCategorias.php?error');
        }

    }

    if ($_GET['action'] == 'editar' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $nombre_categoria = $_POST['nombre_categoria'];
        if ($category->checkCategoryExists($nombre_categoria)) {
            // Redirigir si la categoría ya existe
            header('Location: ../view/AgregarCategoria.php?error=exists');
            exit;
        }
        $resultado = $category->updateCategory($id, $nombre_categoria);

        if ($resultado) {
            header('Location: ../view/GestionCategorias.php?success=actualizado');
        } else {
            header('Location: ../view/EditarMenu.php?id=' . $id . '&error');
        }
    }
}