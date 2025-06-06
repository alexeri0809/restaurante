<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $perfil = "cliente"; // Forçar o perfil para "cliente"

    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
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

<!-- Formulário com Tailwind -->
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
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <form method="POST" onsubmit="validarFormulario(event)" class="bg-white p-6 rounded-xl shadow-md w-full max-w-md space-y-4">
    <h2 class="text-2xl font-bold text-center text-gray-800">Criar Conta de Cliente</h2>

    <?php if (!empty($erro)): ?>
      <p class="text-red-500 text-sm text-center"><?php echo $erro; ?></p>
    <?php endif; ?>

    <input type="text" name="nome" placeholder="Nome completo" required class="w-full p-2 border rounded">
    <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded">
    <input type="password" name="senha" placeholder="Senha" required class="w-full p-2 border rounded">
    <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required class="w-full p-2 border rounded">
    <input type="hidden" name="perfil" value="cliente">

    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded">
      Registar
    </button>
  </form>
</body>
</html>
