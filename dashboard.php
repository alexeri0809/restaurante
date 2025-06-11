<?php
session_start();
include "config.php";

// Acesso permitido apenas para usuários logados
if (!isset($_SESSION['user'])) {
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
<body class="bg-gray-50 min-h-screen p-6">

  <header class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-semibold text-gray-800">Olá, <?= htmlspecialchars($_SESSION['user']['nome']) ?>!</h1>
    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow transition">
      Terminar Sessão
    </a>
  </header>

  <form id="formPedido" method="POST" action="fazer_pedido.php" class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">

    <?php while ($p = $pratos->fetch_assoc()): ?>
      <div class="bg-white rounded-lg shadow-md p-5 flex flex-col items-center text-center">
        <img 
          src="img/pratos/<?= htmlspecialchars($p['imagem']) ?>" 
          alt="<?= htmlspecialchars($p['nome']) ?>" 
          class="w-40 h-32 object-cover rounded mb-4 shadow"
          loading="lazy"
        >
        <h2 class="text-xl font-semibold mb-1"><?= htmlspecialchars($p['nome']) ?></h2>
        <p class="text-gray-600 mb-2"><?= htmlspecialchars($p['descricao']) ?></p>
        <p class="text-green-700 font-bold mb-4 text-lg">€<?= number_format($p['preco'], 2) ?></p>
        <label for="qtd_<?= (int)$p['id'] ?>" class="mb-1 font-medium text-gray-700">Quantidade:</label>
        <input 
          type="number" 
          id="qtd_<?= (int)$p['id'] ?>" 
          name="qtd[<?= (int)$p['id'] ?>]" 
          min="0" 
          value="0" 
          class="w-20 text-center border rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
      </div>
    <?php endwhile; ?>

    <div class="md:col-span-3 flex justify-center mt-6">
      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded shadow transition">
        Fazer Pedido
      </button>
    </div>

  </form>

</body>
</html>
