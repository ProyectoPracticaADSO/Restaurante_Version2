<?php
include_once '../model/category.php';

$category = new Category();

// Obtener todas las categorias
$categories = $category->getCategory();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Menú</title>
    <link rel="stylesheet" href="../css/StyleEditCategory.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
</head>

<body>
    <div class="container">
        <header>
            <h1><strong>Menú</strong></h1>
            <a href="./menus/MenuMesero.php" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        </header>
        <?php

        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger">No puedes eliminar una categoría con platos en su contenido</div>';
        }
        ?>

        <div class="menu-category">

            <?php if ($categories) {
                foreach ($categories as $category) : ?>
                    <div class="category-box">
                        <div class="category-content">
                            <div>
                                <a href="./CartaMesero.php?id=<?= $category['id'] ?>"><button><?= $category['nombre_categoria'] ?></button></a>
                            </div>

                        </div>
                    </div>

            <?php endforeach;
            } else {
                echo "No se encontraron categorías.";
            } ?>

        </div>

    </div>

</body>

</html>