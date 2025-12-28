<?php
include_once '../model/category.php';

$category = new Category();

// Obtener todas las categorías
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
	<div class="container mt-5 position-relative">
		<?php include '../view/bases/base4.php'; ?>
		<header class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
			<div class="d-flex align-items-center">
				<img src="https://cdn.glitch.global/05dd2f16-2c70-4bf2-a8e5-35c1a876912e/logo.png?v=1740605968751" alt="Logo" style="height: 40px; margin-right: 5px;">
				<h1 class="display-5 mb-0 mr-3">Editar Menú</h1>

				<div class="btn-group ml-3 flex-wrap"> <br> <br>
					<a href="./AgregarCategoria.php" class="btn btn-outline-secondary mx-1 mb-1">Agregar nueva categoría</a>
				</div>

			</div>

			<a href="./menus/MenuAdmin.php" class="back-icon"></a>
		</header>
		<?php if (isset($_GET['error'])) : ?>
			<div class="alert alert-danger">No puedes eliminar una categoría con platos en su contenido</div>
		<?php endif; ?>

		<div class="menu-category">
			<?php foreach ($categories as $category) : ?>
				<div class="category-box">
					<div class="category-content">
						<!-- Mostrar la imagen o un cuadro vacío -->
						<?php if ($category['imagen']) : ?>
							<img src="data:image/jpeg;base64,<?= base64_encode($category['imagen']) ?>" alt="<?= $category['nombre_categoria'] ?>" style="width: 100px; height: 100px; border-radius: 5px;">
						<?php else : ?>
							<div class="no-image">Sin imagen</div>
						<?php endif; ?>

						<!-- Botón con el nombre de la categoría -->
						<a href="./GestionMenu.php?id=<?= $category['id'] ?>">
							<button><?= $category['nombre_categoria'] ?></button>
						</a>
					</div>
					<div class="actions">
						<a href="EditarCategoria.php?action=editar&id=<?= $category['id'] ?>" class="icon btn-lg">
							<i class="fa-solid fa-pen"></i>
						</a>
						<a href="../controller/categoryController.php?action=eliminar&id=<?= $category['id'] ?>" class="icon btn-lg" onclick="return confirm('¿Estás seguro de eliminar la categoría <?= $category['nombre_categoria'] ?>?')">
							<i class="fa fa-trash"></i>
						</a>
					</div>
				</div>
			<?php endforeach; ?>

			<?php if (empty($categories)) : ?>
				<p>No se encontraron categorías.</p>
			<?php endif; ?>
		</div>
	</div>

	<script>
		const urlParams = new URLSearchParams(window.location.search);
		const mensaje = urlParams.get('success');
		if (mensaje === 'eliminado') {
			alertify.success('Categoría eliminada correctamente');
		}
		if (mensaje === 'actualizado') {
			alertify.success('Categoría actualizada correctamente');
		}
	</script>

	<?php include '../view/bases/base2.php'; ?>
	<?php include '../view/bases/base1.php'; ?>

</body>

</html>