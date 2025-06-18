<?php
session_start();
include "config.php";

// --- PERMISSÃO OPCIONAL ---
// if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] != 'admin') {
//     header("Location: login.php");
//     exit();
// }

$modo_edicao = false;
$sucesso = "";
$erro = "";

// Verifica se é uma edição
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM pratos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $erro = "Prato não encontrado.";
    } else {
        $prato = $result->fetch_assoc();
        $modo_edicao = true;
    }
}

// Atualizar prato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $imagem = trim($_POST['imagem']);

    $update = $conn->prepare("UPDATE pratos SET nome = ?, descricao = ?, preco = ?, imagem = ? WHERE id = ?");
    $update->bind_param("ssdsi", $nome, $descricao, $preco, $imagem, $id);

    if ($update->execute()) {
        header("Location: editar_prato.php?sucesso=1");
        exit();
    } else {
        $erro = "Erro ao atualizar o prato.";
    }
}

// Mensagem de sucesso após redirecionamento
if (isset($_GET['sucesso'])) {
    $sucesso = "Prato atualizado com sucesso!";
}

// Buscar todos os pratos para exibição
$pratos = $conn->query("SELECT * FROM pratos ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Pratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-lg">

    <h1 class="text-3xl font-bold text-green-700 mb-6 text-center">Gestão de Pratos</h1>

    <?php if (!empty($sucesso)): ?>
        <div class="bg-green-100 text-green-700 p-4 mb-6 rounded text-center">
            <?= htmlspecialchars($sucesso) ?>
        </div>
    <?php elseif (!empty($erro)): ?>
        <div class="bg-red-100 text-red-700 p-4 mb-6 rounded text-center">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <?php if ($modo_edicao): ?>
        <h2 class="text-xl font-semibold mb-4">Editar Prato: <?= htmlspecialchars($prato['nome']) ?></h2>
        <form method="POST" class="space-y-4 mb-8">
            <input type="hidden" name="id" value="<?= $prato['id'] ?>">
            <div>
                <label class="font-semibold">Nome:</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($prato['nome']) ?>" required class="w-full p-3 border rounded">
            </div>
            <div>
                <label class="font-semibold">Descrição:</label>
                <textarea name="descricao" required class="w-full p-3 border rounded"><?= htmlspecialchars($prato['descricao']) ?></textarea>
            </div>
            <div>
                <label class="font-semibold">Preço (€):</label>
                <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($prato['preco']) ?>" required class="w-full p-3 border rounded">
            </div>
            <div>
                <label class="font-semibold">Imagem:</label>
                <input type="text" name="imagem" value="<?= htmlspecialchars($prato['imagem']) ?>" required class="w-full p-3 border rounded">
            </div>

            <div class="flex justify-between mt-6">
                <a href="editar_prato.php" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">Cancelar</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Salvar Alterações</button>
            </div>
        </form>
    <?php endif; ?>

    <h2 class="text-2xl font-semibold mb-4">Lista de Pratos</h2>
    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-green-100 text-left">
                <th class="p-3">Nome</th>
                <th class="p-3">Preço (€)</th>
                <th class="p-3">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($prato = $pratos->fetch_assoc()): ?>
                <tr class="border-b hover:bg-green-50">
                    <td class="p-3"><?= htmlspecialchars($prato['nome']) ?></td>
                    <td class="p-3">€<?= number_format($prato['preco'], 2, ',', '.') ?></td>
                    <td class="p-3">
                        <a href="editar_prato.php?id=<?= $prato['id'] ?>" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Editar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="mt-6 text-center">
        <a href="admin.php" class="text-gray-600 hover:text-black underline">Voltar ao Painel</a>
    </div>

</div>

</body>
</html>
