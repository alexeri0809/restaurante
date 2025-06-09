<?php

//Este não faz nada, apenas é um apoio para os outros





























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

<h2 class="text-xl mb-4">Pedidos Recebidos</h2>

<?php while ($pedido = $pedidos->fetch_assoc()): ?>
  <div class="border p-4 mb-2">
    <p><strong>#<?= $pedido['id'] ?></strong> de <?= $pedido['nome'] ?> em <?= $pedido['data_pedido'] ?></p>
    <ul class="ml-4">
      <?php
      $itens = $conn->query("
        SELECT i.quantidade, pr.nome 
        FROM itens_pedido i
        JOIN pratos pr ON i.id_prato = pr.id
        WHERE i.id_pedido = {$pedido['id']}
      ");
      while ($item = $itens->fetch_assoc()):
      ?>
        <li><?= $item['quantidade'] ?>x <?= $item['nome'] ?></li>
      <?php endwhile; ?>
    </ul>
  </div>
<?php endwhile; ?>
