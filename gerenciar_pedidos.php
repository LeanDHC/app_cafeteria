<?php
session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Definir o status inicial como "Em aberto", caso não tenha sido selecionado um filtro
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'Em aberto';

// Consulta para buscar os pedidos com base no status, sem a quantidade de produtos
try {
    $sql = "SELECT p.id_pedido, p.data_pedido, p.status, p.total_pedido, c.nome AS cliente_nome
            FROM pedidos p
            JOIN clientes c ON p.id_cliente = c.id_cliente
            WHERE p.status = :status
            ORDER BY p.data_pedido DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status_filter, PDO::PARAM_STR);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os registros
} catch (PDOException $e) {
    die("Erro ao buscar pedidos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Pedidos</title>
    <link rel="stylesheet" href="../css/gerenciar_pedidos.css">
    <link rel="stylesheet" href="../css/gerenciar_site.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #8b5e3c;
        }

        .btn-new-order {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #8b5e3c;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn-new-order:hover {
            background-color: #a76d4b;
        }

        select {
            padding: 5px;
            font-size: 14px;
            margin-right: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            color: #8b5e3c;
        }

        table td {
            vertical-align: middle;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            color: #fff;
            font-weight: bold;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .actions .btn-update {
            background-color: #4CAF50;
        }

        .actions .btn-update:hover {
            background-color: #45a049;
        }

        .actions .btn-remove {
            background-color: #f44336;
        }

        .actions .btn-remove:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <nav class="menu">
            <ul>
                <li><a href="gerenciar_clientes.php">Clientes</a></li>
                <li><a href="gerenciar_produtos.php">Produtos</a></li>
                <li><a href="gerenciar_pedidos.php">Pedidos</a></li>
                <li><a href="index.php">Página Inicial</a></li>
            </ul>
        </nav>
    </header>

    <h1>Gerenciar Pedidos</h1>

    <!-- Filtro de Status -->
    <form action="gerenciar_pedidos.php" method="GET" style="margin-bottom: 20px;">
        <label for="status">Exibir Pedidos com Status:</label>
        <select name="status" id="status" onchange="this.form.submit()">
            <option value="Em aberto" <?= $status_filter == 'Em aberto' ? 'selected' : ''; ?>>Em Aberto</option>
            <option value="Concluído" <?= $status_filter == 'Concluído' ? 'selected' : ''; ?>>Concluído</option>
        </select>
    </form>

    <!-- Tabela de Pedidos -->
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Data do Pedido</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($pedidos) > 0): ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['id_pedido']); ?></td>
                        <td><?= htmlspecialchars($pedido['cliente_nome']); ?></td>
                        <td><?= htmlspecialchars($pedido['data_pedido']); ?></td>
                        <td><?= htmlspecialchars($pedido['status']); ?></td>
                        <td>R$ <?= number_format($pedido['total_pedido'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum pedido encontrado com o status selecionado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
