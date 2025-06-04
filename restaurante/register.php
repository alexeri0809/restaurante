<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $perfil = $_POST['perfil'];

    $stmt = $conn->prepare("INSERT INTO utilizadores (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $perfil);
    $stmt->execute();

    header("Location: login.php");
    exit();
}
?>

<!-- Formulário com Tailwind -->
<form method="POST" class="max-w-md mx-auto p-4">
  <input type="text" name="nome" placeholder="Nome" required class="mb-2 w-full">
  <input type="email" name="email" placeholder="Email" required class="mb-2 w-full">
  <input type="password" name="senha" placeholder="Senha" required class="mb-2 w-full">
  <select name="perfil" class="mb-2 w-full">
    <option value="cliente">Cliente</option>
    <option value="admin">Admin</option>
  </select>
  <button type="submit" class="bg-green-500 text-white px-4 py-2">Registar</button>
</form>
