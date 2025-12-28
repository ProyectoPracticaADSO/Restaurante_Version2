<?php
require_once '../model/mesa.php';
$mesasModel = new Mesa();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $rawInput = file_get_contents("php://input");
    $jsonData = json_decode($rawInput, true);

   if (isset($jsonData['mesas'])) {
        $resultado = true;
        foreach ($jsonData['mesas'] as $id) {
           if (!$mesasModel->deleteMesa(intval($id))) {
                $resultado = false;
            }
       }
      echo json_encode(['success' => $resultado]);
      exit;
   }
  
  
    // Agregar nuevas mesas
    if (isset($_POST['numero_mesas'])) {
        $cantidad = intval($_POST['numero_mesas']);

        if ($cantidad > 0) {
            $mesasActuales = $mesasModel->getMesas();
            $numeroActual = count($mesasActuales);
            $resultado = true;

            for ($i = 1; $i <= $cantidad; $i++) {
                $numeroMesa = $numeroActual + $i;
                if (!$mesasModel->insertMesa($numeroMesa)) {
                    $resultado = false;
                    break;
                }
            }

            if ($resultado) {
                header('Location: ../view/GestionMesas.php?success=agregado');
                exit;
            } else {
                header('Location: ../view/GestionMesas.php?error=insertar');
                exit;
            }
        }
    }
  
  

    // Actualizar una mesa existente (editar)
    if (isset($_GET['action']) && $_GET['action'] === 'update') {
        if (isset($_POST['id'], $_POST['numero_mesa'], $_POST['estado'])) {
            $id = intval($_POST['id']);
            $numero_mesa = intval($_POST['numero_mesa']);
            $estado = $_POST['estado'];

            if ($mesasModel->updateMesa($id, $numero_mesa, $estado)) {
                header('Location: ../view/GestionMesas.php?success=actualizado');
                exit;
            } else {
                header('Location: ../view/GestionMesas.php?error=actualizar');
                exit;
            }
        }
    }
  }