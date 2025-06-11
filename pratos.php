<?php
session_start();
include "config.php"; // conexão com o banco

// Buscar todos os pratos
$result = $conn->query("SELECT id, nome, descricao FROM pratos ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Pratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center text-green-700">🍽️ Menu do Restaurante</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while($prato = $result->fetch_assoc()): ?>
                    <div class="bg-white rounded shadow p-4">
                        <h2 class="text-xl font-semibold mb-2 text-gray-800"><?= htmlspecialchars($prato['nome']) ?></h2>
                        <p class="text-gray-600"><?= nl2br(htmlspecialchars($prato['descricao'])) ?></p>
                        <a href="prato_detalhes.php?id=<?= $prato['id'] ?>" class="mt-4 inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Ver detalhes / Avaliar
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600">Nenhum prato encontrado.</p>
        <?php endif; ?>
    </div>

</body>
</html>
