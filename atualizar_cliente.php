<?php
session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Verificar se o ID do cliente foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do cliente não especificado.');
}

// Obter o ID do cliente
$id_cliente = $_GET['id'];

// Consultar os dados do cliente
try {
    $sql = "SELECT id_cliente, nome, email, telefone, endereco FROM clientes WHERE id_cliente = :id_cliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        die('Cliente não encontrado.');
    }
} catch (PDOException $e) {
    die("Erro ao buscar cliente: " . $e->getMessage());
}

// Atualizar os dados do cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Validar os dados
    if (empty($nome) || empty($email) || empty($telefone) || empty($endereco)) {
        $erro = 'Todos os campos são obrigatórios.';
    } else {
        try {
            // Atualizar os dados do cliente no banco de dados
            $sql = "UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco WHERE id_cliente = :id_cliente";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->execute();

            // Redirecionar para a página de gerenciamento de clientes após a atualização
            header('Location: gerenciar_clientes.php');
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar cliente: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cliente</title>
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
            max-width: 600px;
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

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }

        input[type="text"], input[type="email"], textarea {
            padding: 10px;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #8b5e3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #a76d4b;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Atualizar Cliente</h1>

    <!-- Exibir mensagem de erro, se houver -->
    <?php if (isset($erro)): ?>
        <div class="error"><?= htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <!-- Formulário de Atualização -->
    <form action="atualizar_cliente.php?id=<?= $cliente['id_cliente']; ?>" method="POST">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($cliente['nome']); ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']); ?>" required>

        <label for="telefone">Telefone</label>
        <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']); ?>" required>

        <label for="endereco">Endereço</label>
        <textarea id="endereco" name="endereco" rows="4" required><?= htmlspecialchars($cliente['endereco']); ?></textarea>

        <button type="submit">Atualizar Cliente</button>
    </form>
</div>
</body>
</html>
