<?php
session_start();
include "config.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] != 'cliente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_POST['qtd']) || empty(array_filter($_POST['qtd']))) {
    echo "Nenhum prato selecionado. <a href='dashboard.php'>Voltar</a>";
    exit();
}

$id_cliente = $_SESSION['user']['id'];

// Inserir pedido
$stmt = $conn->prepare("INSERT INTO pedidos (id_cliente, data_pedido) VALUES (?, NOW())");
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$id_pedido = $conn->insert_id;

// Inserir itens do pedido
foreach ($_POST['qtd'] as $id_prato => $quantidade) {
    $quantidade = (int)$quantidade;
    $id_prato = (int)$id_prato;

    if ($quantidade > 0) {
        $stmt_item = $conn->prepare("INSERT INTO pedido_itens (id_pedido, id_prato, quantidade) VALUES (?, ?, ?)");
        $stmt_item->bind_param("iii", $id_pedido, $id_prato, $quantidade);
        $stmt_item->execute();
    }
}

header("Location: sucesso.php");
exit();
