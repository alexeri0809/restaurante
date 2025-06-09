<?php


//Este não faz nada, apenas é um apoio para os outros



session_start();
include "config.php";

// Só clientes podem fazer pedido
if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] !== 'cliente') {
    http_response_code(403); // Proibido
    echo "Acesso negado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método não permitido
    echo "Método inválido.";
    exit();
}

$id_user = $_SESSION['user']['id'];

// Insere pedido na tabela 'pedidos'
$stmt_pedido = $conn->prepare("INSERT INTO pedidos (id_utilizador) VALUES (?)");
$stmt_pedido->bind_param("i", $id_user);
if (!$stmt_pedido->execute()) {
    echo "Erro ao inserir pedido: " . $stmt_pedido->error;
    exit();
}

$id_pedido = $stmt_pedido->insert_id;

// Prepara inserção dos itens do pedido
$stmt_item = $conn->prepare("INSERT INTO itens_pedido (id_pedido, id_prato, quantidade) VALUES (?, ?, ?)");

foreach ($_POST['qtd'] as $id_prato => $qtd) {
    $id_prato = (int)$id_prato;
    $qtd = (int)$qtd;
    if ($qtd > 0) {
        $stmt_item->bind_param("iii", $id_pedido, $id_prato, $qtd);
        if (!$stmt_item->execute()) {
            echo "Erro ao inserir item do pedido: " . $stmt_item->error;
            exit();
        }
    }
}

echo "Pedido realizado com sucesso!";
