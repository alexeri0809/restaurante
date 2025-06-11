<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $perfil = "cliente"; // Forçar o perfil para "cliente"

    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO utilizadores (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $perfil);

        if ($stmt->execute()) {
            header("Location: login.php?registo=sucesso");
            exit();
        } else {
            $erro = "Erro ao registar. Verifique se o email já está em uso.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Registo de Cliente</title>
  <script>
    function validarFormulario(e) {
      const senha = document.querySelector('input[name="senha"]').value;
      const confirmar = document.querySelector('input[name="confirmar_senha"]').value;
      if (senha !== confirmar) {
        alert("As senhas não coincidem.");
        e.preventDefault();
      } else if (senha.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        e.preventDefault();
      }
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-green-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-green-200">
    <h2 class="text-3xl font-bold text-center text-green-600 mb-6">Criar Conta de Cliente</h2>

    <?php if (!empty($erro)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <form method="POST" onsubmit="validarFormulario(event)" class="space-y-4">
      <input type="text" name="nome" placeholder="Nome completo" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400">
      <input type="email" name="email" placeholder="Email" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400">
      <input type="password" name="senha" placeholder="Senha" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400">
      <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-400">
      <input type="hidden" name="perfil" value="cliente">

      <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-lg font-semibold py-3 rounded-full transition">
        Registar Conta
      </button>
    </form>

    <p class="text-sm text-center text-gray-500 mt-4">
      Já tem conta? <a href="login.php" class="text-green-600 font-semibold hover:underline">Entrar</a>
    </p>
  </div>

</body>
</html>
