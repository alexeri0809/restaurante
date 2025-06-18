<?php 
session_start();
include "config.php";

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

  <!-- Barra de Ações do Admin -->
  <div class="flex justify-end gap-4 mb-6">
  <a href="/restaurante/1_adicionar_prato.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition shadow">
    Adicionar Prato
  </a>
  <a href="/restaurante/admin_pratos.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow">
    Lista de Pratos
  </a>
  <a href="/restaurante/editar_prato.php" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition shadow">
    Editar Prato
  </a>
  <a href="/restaurante/admin_pedidos.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition shadow">
    Pedidos Recebidos
  </a>
  <a href="http://localhost/phpmyadmin/index.php?route=/database/structure&db=restaurante" target="_blank"
     class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition shadow">
    Ver PHPMYADMIN
  </a>
  <a href="/restaurante/logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition shadow">
    Sair
  </a>
</div>



  <div class="flex items-center justify-between max-w-3xl mx-auto mb-6">
  <h2 class="text-2xl font-bold text-center text-gray-800">Pedidos Recebidos</h2>
  <a href="/restaurante/admin_pedidos.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition shadow">
    Ver Todos
  </a>
</div>


  <div class="max-w-3xl mx-auto space-y-4">
    <?php while ($pedido = $pedidos->fetch_assoc()): ?>
      <div class="bg-white p-4 rounded shadow">
        <p class="font-semibold text-green-700 mb-2">
          Pedido #<?= htmlspecialchars($pedido['id']) ?> — <?= htmlspecialchars($pedido['nome']) ?> — 
          <time datetime="<?= $pedido['data_pedido'] ?>"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></time>
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
