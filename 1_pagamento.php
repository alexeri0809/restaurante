<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Confirmação de Pedido</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white p-8 rounded shadow-md text-center max-w-lg">
    <h1 class="text-2xl font-bold mb-4 text-green-600">Pedido Realizado com Sucesso!</h1>
    <p class="text-gray-700 mb-6">Obrigado, <?= htmlspecialchars($_SESSION['user']['nome']) ?>! O seu pedido foi recebido e está a ser processado.</p>
    <a href="dashboard.php" class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
      Voltar ao menu
    </a>
  </div>
</body>
</html>
    