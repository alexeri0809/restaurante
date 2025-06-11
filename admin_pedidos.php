<?php
session_start();
include "config.php";

// (Podes descomentar quando quiseres ativar a verificação de admin)
// if (!isset($_SESSION['user']) || $_SESSION['user']['perfil'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

// Atualizar estado do pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['novo_estado'])) {
    $pedido_id = (int)$_POST['pedido_id'];
    $novo_estado = $_POST['novo_estado'];

    $estados_validos = ['Pendente', 'Em preparação', 'Pronto', 'Entregue', 'Cancelado'];
    if (in_array($novo_estado, $estados_validos)) {
        $stmt = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_estado, $pedido_id);
        $stmt->execute();
    }
}

// Buscar pedidos
$sql = "SELECT p.id, p.data_pedido, p.estado, u.nome AS cliente_nome 
        FROM pedidos p
        JOIN utilizadores u ON p.id_cliente = u.id
        ORDER BY p.data_pedido DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Gestão de Pedidos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 via-white to-green-100 min-h-screen p-8">

    <div class="max-w-6xl mx-auto">
        <h1 class="text-4xl font-bold text-center text-green-700 mb-10">Gestão de Pedidos</h1>

        <div class="overflow-x-auto bg-white shadow-lg rounded-lg p-6">
            <table class="min-w-full text-center">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-3 px-4">ID Pedido</th>
                        <th class="py-3 px-4">Cliente</th>
                        <th class="py-3 px-4">Data</th>
                        <th class="py-3 px-4">Estado Atual</th>
                        <th class="py-3 px-4">Alterar Estado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php while ($pedido = $result->fetch_assoc()): ?>
                        <tr class="border-b">
                            <td class="py-3 px-4"><?= htmlspecialchars($pedido['id']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
                            <td class="py-3 px-4"><?= htmlspecialchars($pedido['data_pedido']) ?></td>
                            <td class="py-3 px-4 font-semibold">
                                <span class="<?php
                                    switch ($pedido['estado']) {
                                        case 'Pendente': echo 'text-yellow-500'; break;
                                        case 'Em preparação': echo 'text-blue-500'; break;
                                        case 'Pronto': echo 'text-purple-500'; break;
                                        case 'Entregue': echo 'text-green-600'; break;
                                        case 'Cancelado': echo 'text-red-500'; break;
                                    }
                                ?>">
                                    <?= htmlspecialchars($pedido['estado']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <form method="POST" class="flex items-center justify-center gap-2">
                                     <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                                    <select name="novo_estado" class="border rounded p-1">
                                        <?php
                                        $estados = ['Pendente', 'Em preparação', 'Pronto', 'Entregue', 'Cancelado'];
                                        foreach ($estados as $estado) {
                                            $selected = ($estado == $pedido['estado']) ? 'selected' : '';
                                            echo "<option value=\"$estado\" $selected>$estado</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                        Atualizar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
