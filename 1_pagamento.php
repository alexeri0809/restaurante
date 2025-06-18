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
<body class="bg-gradient-to-r from-green-100 via-white to-green-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-10 rounded-3xl shadow-2xl text-center max-w-lg border border-green-200">
    <div class="mb-6">
      <svg class="mx-auto w-20 h-20 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
      </svg>
    </div>
    
    <h1 class="text-3xl font-bold mb-4 text-green-600">Pedido Confirmado!</h1>
    <p class="text-gray-700 text-lg mb-6">
      Obrigado, <span class="font-semibold"><?= htmlspecialchars($_SESSION['user']['nome']) ?></span>! 
      O seu pedido foi recebido e está a ser cuidadosamente preparado.
    </p>
    
    <a href="dashboard.php" class="inline-block bg-green-600 hover:bg-green-700 text-white text-lg font-bold py-3 px-6 rounded-full shadow-md transition mb-3">
      Voltar ao Menu
    </a>

    <br>

    <a href="pratos.php" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-lg font-bold py-3 px-6 rounded-full shadow-md transition">
      Fazer Avaliação
    </a>
  </div>

</body>
</html>
