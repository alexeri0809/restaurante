<?php
session_start();
include "config.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] != 'cliente') {
    header("Location: login.php");
    exit();
}

$pratos = $conn->query("SELECT * FROM pratos");
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title>Painel do Cliente</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <h2 class="text-xl mb-4">Olá, <?= htmlspecialchars($_SESSION['user']['nome']) ?>!</h2>

  <form id="formPedido" method="POST" action="fazer_pedido.php" class="grid grid-cols-1 gap-4 max-w-xl">
    <?php while ($p = $pratos->fetch_assoc()): ?>
      <div class="p-4 border rounded bg-white shadow">
        <img src="img/pratos/<?= htmlspecialchars($p['imagem']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>" class="w-32 mb-2">
        <p><strong><?= htmlspecialchars($p['nome']) ?></strong></p>
        <p><?= htmlspecialchars($p['descricao']) ?></p>
        <p class="text-green-600">€<?= number_format($p['preco'], 2) ?></p>
        <input type="number" name="qtd[<?= (int)$p['id'] ?>]" min="0" value="0" class="w-16 border rounded px-2 py-1">
      </div>
    <?php endwhile; ?>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 mt-4 rounded hover:bg-green-700">Fazer Pedido</button>
  </form>
</body>
</html>
