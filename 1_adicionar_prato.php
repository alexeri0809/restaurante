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

        // Extensões permitidas
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            if ($fileSize <= 2 * 1024 * 1024) { // 2MB
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
                    $erro = "Erro ao mover o arquivo para o diretório de destino.";
                }
            } else {
                $erro = "O arquivo é muito grande. Limite: 2MB.";
            }
        } else {
            $erro = "Tipo de arquivo não permitido. Use jpg, jpeg, png ou gif.";
        }
    } else {
        $erro = "Erro no upload da imagem. Tente novamente.";
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
<body class="bg-gray-100 p-6">
  <h2 class="text-2xl mb-4">Adicionar Novo Prato</h2>

  <?php if ($erro): ?>
    <div class="bg-red-200 text-red-800 p-3 rounded mb-4 max-w-md">
      <?php echo $erro; ?>
    </div>
  <?php endif; ?>

  <?php if ($sucesso): ?>
    <div class="bg-green-200 text-green-800 p-3 rounded mb-4 max-w-md">
      <?php echo $sucesso; ?> <a href="admin.php" class="underline">Voltar</a>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow max-w-md">
    <input type="text" name="nome" placeholder="Nome do prato" required class="w-full border p-2 rounded" />
    <textarea name="descricao" placeholder="Descrição" required class="w-full border p-2 rounded"></textarea>
    <input type="number" name="preco" placeholder="Preço (€)" step="0.01" required class="w-full border p-2 rounded" />
    <input type="file" name="imagem" accept="image/*" required class="w-full" />
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Adicionar Prato</button>
  </form>
</body>
</html>
