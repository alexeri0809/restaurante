<?php

session_start();
include "config.php";

// Permitir acesso a qualquer usuário logado:
if (!isset($_SESSION['user'])) {
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

$id_utilizador = $_SESSION['user']['id'];

// Inserir pedido (coluna correta: id_utilizador)
$stmt = $conn->prepare("INSERT INTO pedidos (id_cliente, data_pedido) VALUES (?, NOW())");
$stmt->bind_param("i", $id_utilizador);
$stmt->execute();
$id_pedido = $conn->insert_id;

// Inserir itens do pedido (tabela correta: itens_pedido)
foreach ($_POST['qtd'] as $id_prato => $quantidade) {
    $quantidade = (int)$quantidade;
    $id_prato = (int)$id_prato;

    if ($quantidade > 0) {
        $stmt_item = $conn->prepare("INSERT INTO itens_pedido (id_pedido, id_prato, quantidade) VALUES (?, ?, ?)");
        if (!$stmt_item) {
            die("Erro no prepare: " . $conn->error);
        }
        $stmt_item->bind_param("iii", $id_pedido, $id_prato, $quantidade);
        $stmt_item->execute();
    }
}

header("Location: 1_pagamento.php");
exit();

?>
