<?php 
session_start(); // 👈 Mantém para acessar $_SESSION
include "config.php";

// Removi a verificação de perfil admin
if (!isset($_SESSION['user'])) {  
    header("Location: login.php");
    exit();
}

$pedidos = $conn->query("
  SELECT p.id, u.nome, p.data_pedido 
  FROM pedidos p
  JOIN utilizadores u ON p.id_cliente = u.id
  ORDER BY p.data_pedido DESC
"); 
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title>Pedidos Recebidos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 min-h-screen">

  <h2 class="text-2xl font-bold mb-6 text-center">Pedidos Recebidos</h2>

  <div class="max-w-3xl mx-auto space-y-4">
  <?php while ($pedido = $pedidos->fetch_assoc()): ?>
    <div class="bg-white p-4 rounded shadow">
      <p class="font-semibold text-green-700 mb-2">
        Pedido #<?= htmlspecialchars($pedido['id']) ?> — <?= htmlspecialchars($pedido['nome']) ?> — <time datetime="<?= $pedido['data_pedido'] ?>"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></time>
      </p>
      <ul class="list-disc list-inside text-gray-700">
        <?php
        $itens = $conn->query("
          SELECT i.quantidade, pr.nome 
          FROM itens_pedido i
          JOIN pratos pr ON i.id_prato = pr.id
          WHERE i.id_pedido = {$pedido['id']}
        ");
        while ($item = $itens->fetch_assoc()):
        ?>
          <li><?= htmlspecialchars($item['quantidade']) ?>x <?= htmlspecialchars($item['nome']) ?></li>
        <?php endwhile; ?>
      </ul>
    </div>
  <?php endwhile; ?>
  </div>

</body>
</html>
