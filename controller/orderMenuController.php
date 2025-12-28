
<?php
include_once '../model/order.php';
$order = new Order();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        $idMesa = $_POST['mesa'];
        $pedidos = $order->getOrderByMesa($idMesa);

        if (is_array($pedidos) && !$pedidos) {
            header('Location: ../view/AgregarPedido.php?id=' . $_POST['mesa']);
        } elseif (is_array($pedidos) && $pedidos) {
            header('Location: ../view/MenuPedidos.php?success=existe');
        }

    } elseif ($_POST['action'] === 'view') {
        header('Location: ../view/VerPedido.php?id=' . $_POST['mesa']);
    }
}
