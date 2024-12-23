<?php
session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Processa a remoção de cliente se o formulário de remoção for enviado
if (isset($_POST['remover_cliente'])) {
    $id_cliente = $_POST['id_cliente'];

    try {
        // Inicia a transação
        $pdo->beginTransaction();

        // Exclui o cliente da tabela "clientes"
        $sql = "DELETE FROM clientes WHERE id_cliente = :id_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        // Confirma a transação
        $pdo->commit();

        // Redireciona para a mesma página para atualizar a lista
        header("Location: gerenciar_clientes.php");
        exit();
    } catch (PDOException $e) {
        // Em caso de erro, faz o rollback da transação
        $pdo->rollBack();
        die("Erro ao remover cliente: " . $e->getMessage());
    }
}

// Consulta para buscar os clientes
try {
    $sql = "SELECT id_cliente, nome, email, telefone, endereco FROM clientes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os registros
} catch (PDOException $e) {
    die("Erro ao buscar clientes: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Clientes</title>
    <link rel="stylesheet" href="../css/gerenciar_clientes.css">
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

        .btn-new-client {
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

        .btn-new-client:hover {
            background-color: #a76d4b;
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
        .actions a, .actions button {
    display: inline-block;
    padding: 5px 15px; /* Tamanho de preenchimento para os botões */
    font-size: 16px;
    color: #fff;
    font-weight: bold;
    border-radius: 4px;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.actions .btn-update {
    background-color: #4CAF50; /* Cor de fundo do botão 'Atualizar' */
}

.actions .btn-update:hover {
    background-color: #45a049;
}

.actions .btn-remove {
    background-color: #f44336; /* Cor de fundo do botão 'Remover' */
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

    <h1>Gerenciar Clientes</h1>

    <!-- Botão de Novo Cliente -->
    <a href="novo_cliente.php" class="btn-new-client">Novo Cliente</a>

    <!-- Tabela de Clientes -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['id_cliente']); ?></td>
                        <td><?= htmlspecialchars($cliente['nome']); ?></td>
                        <td><?= htmlspecialchars($cliente['email']); ?></td>
                        <td><?= htmlspecialchars($cliente['telefone']); ?></td>
                        <td><?= htmlspecialchars($cliente['endereco']); ?></td>
                        <td class="actions">
                            <a href="atualizar_cliente.php?id=<?= $cliente['id_cliente']; ?>" class="btn-update">Atualizar</a>
                            
                            <!-- Formulário de Remoção do Cliente -->
                            <form action="gerenciar_clientes.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_cliente" value="<?= $cliente['id_cliente']; ?>">
                                <button type="submit" name="remover_cliente" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este cliente?')">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum cliente cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
