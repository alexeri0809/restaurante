<?php
session_start();
include "config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Prato inválido.";
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM pratos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$prato = $stmt->get_result()->fetch_assoc();

if (!$prato) {
    echo "Prato não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $nota = (int)$_POST['nota'];
    $comentario = trim($_POST['comentario']);
    $id_utilizador = $_SESSION['user']['id'];

    if ($nota >= 1 && $nota <= 5) {
        $stmt = $conn->prepare("INSERT INTO avaliacoes (id_prato, id_utilizador, nota, comentario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $id, $id_utilizador, $nota, $comentario);
        $stmt->execute();
        echo "<p class='text-green-600'>Avaliação enviada com sucesso!</p>";
    } else {
        echo "<p class='text-red-600'>Nota inválida.</p>";
    }
}

$avaliacoes = $conn->prepare("
    SELECT a.nota, a.comentario, a.data_avaliacao, u.nome 
    FROM avaliacoes a 
    JOIN utilizadores u ON a.id_utilizador = u.id 
    WHERE a.id_prato = ? 
    ORDER BY a.data_avaliacao DESC
");
$avaliacoes->bind_param("i", $id);
$avaliacoes->execute();
$result_avaliacoes = $avaliacoes->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($prato['nome']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold mb-4 text-green-700"><?= htmlspecialchars($prato['nome']) ?></h1>
        <p class="mb-6 text-gray-700"><?= htmlspecialchars($prato['descricao']) ?></p>

        <h2 class="text-xl font-semibold mb-4">Avaliar este prato</h2>

        <?php if (isset($_SESSION['user'])): ?>
        <form method="POST" class="bg-white p-4 rounded shadow mb-6">
            <label class="block mb-2 font-semibold">Nota:</label>
            <select name="nota" required class="border p-2 rounded w-full mb-4">
                <option value="">Escolha</option>
                <?php for ($i=1; $i<=5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> estrela<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>

            <label class="block mb-2 font-semibold">Comentário:</label>
            <textarea name="comentario" rows="4" class="border p-2 rounded w-full mb-4"></textarea>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Enviar Avaliação</button>
        </form>
        <?php else: ?>
            <p class="text-red-600 mb-6">Precisa estar logado para avaliar.</p>
        <?php endif; ?>

        <h2 class="text-xl font-semibold mb-4">Avaliações</h2>

        <?php if ($result_avaliacoes->num_rows > 0): ?>
            <?php while ($av = $result_avaliacoes->fetch_assoc()): ?>
                <div class="bg-white p-4 rounded shadow mb-3">
                    <p><strong><?= htmlspecialchars($av['nome']) ?></strong></p>
                    <p><?= str_repeat("⭐", $av['nota']) ?></p>
                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($av['comentario'])) ?></p>
                    <p class="text-sm text-gray-500"><?= $av['data_avaliacao'] ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Sem avaliações ainda.</p>
        <?php endif; ?>

        <a href="pratos.php" class="mt-6 inline-block bg-blue-500 text-white px-4 py-2 rounded">Voltar ao menu</a>
    </div>

</body>
</html>
