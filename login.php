<?php

//Este não faz nada, apenas é um apoio para os outros




session_start();
include "config.php";

if (isset($_SESSION['user'])) {
    header("Location: " . ($_SESSION['user']['perfil'] == 'admin' ? "admin.php" : "dashboard.php"));
    exit();
}

$msgErro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user'] = $user;
        header("Location: " . ($user['perfil'] == 'admin' ? "admin.php" : "dashboard.php"));
        exit();
    } else {
        $msgErro = "Login inválido.";
    }
}
?>


<form method="POST" class="max-w-md mx-auto p-4">
  <input type="email" name="email" placeholder="Email" required class="mb-2 w-full">
  <input type="password" name="senha" placeholder="Senha" required class="mb-2 w-full">
  <button type="submit" class="bg-blue-500 text-white px-4 py-2">Entrar</button>
  <?php if ($msgErro): ?>
    <p class="text-red-600 mt-2"><?= htmlspecialchars($msgErro) ?></p>
  <?php endif; ?>
</form>
