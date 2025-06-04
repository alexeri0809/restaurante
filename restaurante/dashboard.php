<?php
include "config.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] != 'cliente') {
    header("Location: login.php");
    exit();
}

$pratos = $conn->query("SELECT * FROM pratos");
?>

<h2 class="text-xl mb-4">Olá, <?= $_SESSION['user']['nome'] ?>!</h2>

<form id="formPedido" class="grid grid-cols-1 gap-4">
  <?php while ($p = $pratos->fetch_assoc()): ?>
    <div class="p-4 border rounded">
      <img src="img/pratos/<?= $p['imagem'] ?>" class="w-32 mb-2">
      <p><strong><?= $p['nome'] ?></strong></p>
      <p><?= $p['descricao'] ?></p>
      <p class="text-green-600">€<?= $p['preco'] ?></p>
      <input type="number" name="qtd[<?= $p['id'] ?>]" min="0" value="0" class="w-16">
    </div>
  <?php endwhile; ?>
  <button type="submit" class="bg-green-600 text-white px-4 py-2 mt-4">Fazer Pedido</button>
</form>

<script src="js/script.js"></script>
