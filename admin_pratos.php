<?php
session_start();
include "config.php";

// --- PERMISSÃO DESATIVADA TEMPORARIAMENTE ---
// if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] != 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Adicionar prato
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar'])) {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $imagem = trim($_POST['imagem']);

    $stmt = $conn->prepare("INSERT INTO pratos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $nome, $descricao, $preco, $imagem);
    $stmt->execute();
}

// Eliminar prato
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM pratos WHERE id = $id");
}

// Buscar pratos
$res = $conn->query("SELECT * FROM pratos");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Pratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-green-100 p-8">

<div class="max-w-5xl mx-auto">

    <h2 class="text-4xl font-bold text-center text-green-700 mb-10">Gestão de Pratos</h2>

    <!-- Formulário para adicionar prato -->
    <div class="bg-white p-8 rounded-lg shadow-lg mb-10">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Adicionar Novo Prato</h3>
        <form method="POST" class="space-y-4">
            <div>
                <input type="text" name="nome" placeholder="Nome do prato" required class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <textarea name="descricao" placeholder="Descrição" required class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            <div>
                <input type="number" step="0.01" name="preco" placeholder="Preço (€)" required class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <input type="text" name="imagem" placeholder="Ficheiro da imagem (ex: prato.jpg)" required class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <button type="submit" name="adicionar" class="w-full bg-green-600 text-white font-semibold py-3 rounded hover:bg-green-700 transition">
                    Adicionar Prato
                </button>
            </div>
        </form>
    </div>

    <!-- Tabela de pratos -->
    <div class="bg-white p-8 rounded-lg shadow-lg">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Pratos Existentes</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Nome</th>
                        <th class="p-3 border">Preço</th>
                        <th class="p-3 border">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php while ($prato = $res->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-green-50 transition">
                            <td class="p-3 border"><?= $prato['id'] ?></td>
                            <td class="p-3 border"><?= htmlspecialchars($prato['nome']) ?></td>
                            <td class="p-3 border">€<?= number_format($prato['preco'], 2) ?></td>
                            <td class="p-3 border flex justify-center gap-2">
                                <a href="editar_prato.php?id=<?= $prato['id'] ?>" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                    Editar
                                </a>
                                <a href="?eliminar=<?= $prato['id'] ?>" 
                                   onclick="return confirm('Deseja eliminar este prato?')" 
                                   class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
