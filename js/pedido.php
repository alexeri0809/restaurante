<?php
include "config.php";

if ($_SESSION['user']['perfil'] != 'cliente') exit;

$id_user = $_SESSION['user']['id'];
$conn->query("INSERT INTO pedidos (id_utilizador) VALUES ($id_user)");
$id_pedido = $conn->insert_id;

foreach ($_POST['qtd'] as $id_prato => $qtd) {
    if ($qtd > 0) {
        $conn->query("INSERT INTO itens_pedido (id_pedido, id_prato, quantidade) VALUES ($id_pedido, $id_prato, $qtd)");
    }
}

echo "Pedido realizado com sucesso!";
