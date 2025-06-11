<?php
session_start();
include "config.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = (float) $_POST['preco'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagem']['tmp_name'];
        $fileName = $_FILES['imagem']['name'];
        $fileSize = $_FILES['imagem']['size'];
        $fileType = $_FILES['imagem']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if ($fileSize <= 2 * 1024 * 1024) { 
                $newFileName = time() . '.' . $fileExtension;
                $uploadFileDir = 'img/pratos/';
                $dest_path = $uploadFileDir . $newFileName;

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $stmt = $conn->prepare("INSERT INTO pratos (nome, descricao, preco, imagem) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssds", $nome, $descricao, $preco, $newFileName);

                    if ($stmt->execute()) {
                        $sucesso = "Prato adicionado com sucesso!";
                    } else {
                        $erro = "Erro ao adicionar prato no banco de dados.";
                    }
                } else {
                    $erro = "Erro ao mover o arquivo.";
                }
            } else {
                $erro = "O arquivo é muito grande. Máximo: 2MB.";
            }
        } else {
            $erro = "Tipo de arquivo inválido. Use jpg, jpeg, png ou gif.";
        }
    } else {
        $erro = "Erro no upload da imagem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title>Adicionar Prato</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

  <div class="bg-white p-8 rounded shadow-lg w-full max-w-lg">
    <h2 class="text-3xl mb-6 text-center font-bold text-green-700">Adicionar Novo Prato</h2>

    <?php if ($erro): ?>
      <div class="bg-red-100 text-red-700 border border-red-400 p-4 rounded mb-4">
        <?= $erro; ?>
      </div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
      <div class="bg-green-100 text-green-700 border border-green-400 p-4 rounded mb-4">
        <?= $sucesso; ?> 
        <a href="admin.php" class="text-green-800 underline ml-2">Voltar</a>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <div>
        <label class="block font-semibold mb-1">Nome do Prato</label>
        <input type="text" name="nome" required class="w-full border p-2 rounded focus:ring-2 focus:ring-green-500" />
      </div>

      <div>
        <label class="block font-semibold mb-1">Descrição</label>
        <textarea name="descricao" rows="3" required class="w-full border p-2 rounded focus:ring-2 focus:ring-green-500"></textarea>
      </div>

      <div>
        <label class="block font-semibold mb-1">Preço (€)</label>
        <input type="number" name="preco" step="0.01" required class="w-full border p-2 rounded focus:ring-2 focus:ring-green-500" />
      </div>

      <div>
        <label class="block font-semibold mb-1">Imagem</label>
        <input type="file" name="imagem" accept="image/*" required class="w-full border p-2 rounded" />
      </div>

      <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded font-semibold">Adicionar Prato</button>
    </form>
  </div>

</body>
</html>
