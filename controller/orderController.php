<?php
session_start();
include_once '../model/order.php'; // Modelo para gestionar pedidos

$order = new Order();


if (isset($_POST['pedidos'])) {

    if ($_GET['action'] == 'editar' && isset($_GET['id'])) {

        $idUsuario = $_SESSION['user_id'];
        $idMesa = $_POST['idMesa'];
        $pedidoCompleto = $_POST['pedidos'];


        $resultado = $order->updateOrder($idUsuario, $idMesa, $pedidoCompleto);

        if ($resultado) {
            header('Location: ../view/VerPedido.php?success=actualizado&id=' . $idMesa);
        } else {
            header('Location: ../view/VerPedido.php?id=' . $idMesa . '&error');
        }
    } else {



        // Recibe el pedido completo como JSON
        $id = 0;
        $idUsuario = $_SESSION['user_id'];
        $idMesa = $_POST['idMesa'];
        $pedidoCompleto = $_POST['pedidos'];



        $order->insertOrder($id, $idUsuario, $idMesa, $pedidoCompleto);

        // Redirige a una página de confirmación o muestra un mensaje de éxito
        header('Location: ../view/MenuPedidos.php?success=agregado');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['id'])) {

    $idMesa = $_GET['id'];

    $resultado = $order->deleteOrder($idMesa);

    if ($resultado) {
        header('Location: ../view/VerPedido.php?success=eliminado&id=' . $idMesa);
    } else {
        header('Location: ../view/VerPedido.php?id=' . $idMesa . '&error');

    }

}
if (isset($_GET['action']) && $_GET['action'] == 'eliminarcocina' && isset($_GET['id'])) {

    $idMesa = $_GET['id'];

    $resultado = $order->deleteOrder($idMesa);

    if ($resultado) {
        header('Location: http://localhost/restaurante/view/VerPedidosCocina.php? &id=' . $idMesa);
    } else {
        header('Location: ../view/VerPedido.php?id=' . $idMesa . '&error');
    }
}