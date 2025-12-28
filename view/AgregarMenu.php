<?php
include_once '../model/category.php';

$category = new Category();
$categories = $category->getCategory();
if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/StyleForm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <a href="./GestionMenu.php?id=<?= $idCategoria ?>" class="back-icon"><i class="fa-solid fa-circle-arrow-left"></i></a>
        <h2 class="text-center mb-4">Nuevo producto</h2>

        <?php
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">Producto agregado exitosamente</div>';
        }
        ?>

        <form action="../controller/menuController.php?action=agregar" method="POST" class="needs-validation" novalidate>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" pattern="^\S+(?: \S+)*$" maxlength="100" required>
                    <div class="invalid-feedback">Por favor ingrese el nombre del plato.</div>
                </div>

                <div class="form-group col-md-6">
                    <label for="precio">Precio:</label>
                    <input type="text" class="form-control" id="precio" name="precio" maxlength="10" pattern="^[0-9]+$" required>
                    <div class="invalid-feedback">Por favor ingrese el precio (sin punto ni coma).</div>
                </div>

                <div class="form-group col-md-12">
                    <label for="descripcion">Descripción del producto:</label>
                    <input type="text" class="form-control" pattern="^\S+(?: \S+)*$" id="descripcion" name="descripcion" maxlength="100" required>
                    <div class="invalid-feedback">Por favor ingrese la descripción del producto (Verifique los espacios en blanco).</div>
                </div>

                <div class="form-group col-md-12">
                    <label for="ingredientes">Ingredientes:</label>
                    <input type="text" class="form-control" id="ingredientes" name="ingredientes" maxlength="100" required readonly>
                    <input type="hidden" id="ingredientes-ids" name="ingredientes_ids">
                    <button type="button" class="btn btn-info mt-2" data-toggle="modal" data-target="#pageModal">
                        Ver Ingredientes
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pageModalLabel">Materia prima</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <iframe src="./Ingredientes.php" style="width: 100%; height: 500px; border: none;"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="categoria" value="<?= $idCategoria ?>">

                </div>

                <div class="form-group col-md-12">
                    <label for="adiciones">Posibles adiciones:</label>
                    <input type="text" class="form-control" id="adiciones" name="adiciones" maxlength="100" required readonly>
                    <input type="hidden" id="adiciones-ids" name="adiciones_ids">
                    <button type="button" class="btn btn-info mt-2" data-toggle="modal" data-target="#pageModalAdditions">
                        Ver Adiciones
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="pageModalAdditions" tabindex="-1" role="dialog" aria-labelledby="pageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pageModalLabel">Posibles adiciones</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <iframe src="./Adiciones.php" style="width: 100%; height: 500px; border: none;"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="categoria" value="<?= $idCategoria ?>">

                </div>

                <div class="button-container col-md-12">
                    <button type="submit">Crear</button>
                </div>
        </form>
    </div>

    <script src="../js/validation.js"></script>
    <script>
        window.addEventListener('message', function(event) {
            if (event.data.type === 'ingredients') {
                const ingredientes = event.data.data;
                const ingredientNames = ingredientes.map(ingredient => ingredient.name).join(', ');
                const ingredientIds = ingredientes.map(ingredient => ingredient.id).join(',');

                // Actualiza el campo de texto con los nombres
                document.getElementById('ingredientes').value = ingredientNames;

                // Guarda los IDs en un campo oculto si es necesario
                document.getElementById('ingredientes-ids').value = ingredientIds;
            }

            if (event.data.type === 'additions') {
                const adiciones = event.data.data;
                const additionsNames = adiciones.map(addition => addition.name).join(', ');
                const additionsIds = adiciones.map(addition => addition.id).join(',');

                // Actualiza el campo de texto con los nombres
                document.getElementById('adiciones').value = additionsNames;

                // Guarda los IDs en un campo oculto si es necesario
                document.getElementById('adiciones-ids').value = additionsIds;
            }  
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            var ingredientesInput = document.getElementById('ingredientes');
            if (ingredientesInput.value.trim() === '') {
                event.preventDefault();
                alert('Por favor, seleccione al menos un ingrediente.');
            }
        });
    </script>

</body>

</html>