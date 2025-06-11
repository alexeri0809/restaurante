<?php
session_start();
include "config.php";
echo "Arquivo acessível!";

// --- PERMISSÃO DESATIVADA TEMPORARIAMENTE ---
// if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] != 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Verifica se veio o ID do prato
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);

// Busca os dados atuais do prato
$stmt = $conn->prepare("SELECT * FROM pratos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Prato não encontrado.";
    exit();
}

$prato = $result->fetch_assoc();

// Atualizar prato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $imagem = trim($_POST['imagem']);

    $update = $conn->prepare("UPDATE pratos SET nome = ?, descricao = ?, preco = ?, imagem = ? WHERE id = ?");
    $update->bind_param("ssdsi", $nome, $descricao, $preco, $imagem, $id);

    if ($update->execute()) {
        $sucesso = "Prato atualizado com sucesso!";
        // Atualiza os dados do prato na variável para atualizar o formulário também
        $prato['nome'] = $nome;
        $prato['descricao'] = $descricao;
        $prato['preco'] = $preco;
        $prato['imagem'] = $imagem;
    } else {
        $erro = "Erro ao atualizar o prato.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Prato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 p-8">

<div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-lg">

    <h2 class="text-3xl font-bold text-green-700 mb-6 text-center">Editar Prato</h2>

    <?php if (!empty($sucesso)): ?>
        <div class="bg-green-100 text-green-700 p-4 mb-6 rounded text-center">
            <?= htmlspecialchars($sucesso) ?>
        </div>
    <?php elseif (!empty($erro)): ?>
        <div class="bg-red-100 text-red-700 p-4 mb-6 rounded text-center">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="font-semibold">Nome do prato:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($prato['nome']) ?>" required class="w-full p-3 border rounded focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="font-semibold">Descrição:</label>
            <textarea name="descricao" required class="w-full p-3 border rounded focus:ring-2 focus:ring-green-500"><?= htmlspecialchars($prato['descricao']) ?></textarea>
        </div>
        <div>
            <label class="font-semibold">Preço (€):</label>
            <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($prato['preco']) ?>" required class="w-full p-3 border rounded focus:ring-2 focus:ring-green-500">
        </div>
        <div>
            <label class="font-semibold">Imagem (nome do ficheiro):</label>
            <input type="text" name="imagem" value="<?= htmlspecialchars($prato['imagem']) ?>" required class="w-full p-3 border rounded focus:ring-2 focus:ring-green-500">
        </div>

        <div class="flex justify-between mt-6">
            <a href="admin.php" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Cancelar</a>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Salvar Alterações</button>
        </div>
    </form>

</div>

</body>
</html>
