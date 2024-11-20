<?php
session_start();
require_once 'includes/db.php'; // Conexão com o banco de dados

// Consulta para buscar os produtos, incluindo a categoria
try {
    $sql = "SELECT p.id_produto, p.nome, p.preco, p.estoque_quantidade, c.nome AS categoria, p.imagem_url 
            FROM produtos p
            JOIN categorias c ON p.id_categoria = c.id_categoria";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém todos os registros
} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}

// Verifica se foi enviado o ID do produto para remoção
if (isset($_POST['id_produto'])) {
    $id_produto = $_POST['id_produto'];

    // Realizar a remoção do produto
    try {
        $sqlRemover = "DELETE FROM produtos WHERE id_produto = :id_produto";
        $stmtRemover = $pdo->prepare($sqlRemover);
        $stmtRemover->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
        
        if ($stmtRemover->execute()) {
            // Caso a remoção seja bem-sucedida, recarregue a página
            header("Location: gerenciar_produtos.php"); // Redireciona para recarregar a página
            exit();
        } else {
            echo "Erro ao remover produto.";
        }
    } catch (PDOException $e) {
        die("Erro ao remover produto: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link rel="stylesheet" href="../css/gerenciar_produtos.css">
    <link rel="stylesheet" href="../css/gerenciar_site.css">
    <style>
        /* Seu CSS */
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

        .btn-new-product {
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

        .btn-new-product:hover {
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

    <h1>Gerenciar Produtos</h1>

    <!-- Botão de Novo Produto -->
    <a href="novo_produto.php" class="btn-new-product">Novo Produto</a>

    <!-- Tabela de Produtos -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Categoria</th>
                <th>Imagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($produtos) > 0): ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['id_produto']); ?></td>
                        <td><?= htmlspecialchars($produto['nome']); ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($produto['estoque_quantidade']); ?></td>
                        <td><?= htmlspecialchars($produto['categoria']); ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($produto['imagem_url']); ?>" alt="<?= htmlspecialchars($produto['nome']); ?>" width="50">
                        </td>
                        <td class="actions">
                            <a href="atualizar_produto.php?id=<?= $produto['id_produto']; ?>" class="btn-update">Atualizar</a>
                            <!-- Formulário para remover produto -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="id_produto" value="<?= $produto['id_produto']; ?>">
                                <button type="submit" class="btn-remove" onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Nenhum produto cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
