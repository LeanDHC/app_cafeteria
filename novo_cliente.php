<?php
session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Variável para mensagem de sucesso ou erro
$mensagem = "";

// Lógica de inserção
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    // Inserir dados no banco
    $sql = "INSERT INTO clientes (nome, email, senha, telefone, endereco) VALUES (:nome, :email, :senha, :telefone, :endereco)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);

        if ($stmt->execute()) {
            $mensagem = "Cadastro realizado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar. Tente novamente.";
        }
    } catch (PDOException $e) {
        // Verifica se o erro é de chave duplicada para o email
        if ($e->getCode() == 23000) {
            $mensagem = "Email já cadastrado!";
        } else {
            // Outro erro (caso queira capturar para debug)
            $mensagem = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Cliente</title>
    <link rel="stylesheet" href="../css/gerenciar_clientes.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #8b5e3c;
        }

        .form-container form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container form button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #8b5e3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container form button:hover {
            background-color: #a76d4b;
        }

        .alert {
            background-color: #ffdddd;
            color: #d32f2f;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }

        .back-button a {
            text-decoration: none;
            font-size: 16px;
            color: #fff;
            background-color: #8b5e3c;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease-in-out;
        }

        .back-button a:hover {
            background-color: #a76d4b;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Cadastrar Novo Cliente</h2>

    <!-- Mensagem de Alerta -->
    <?php if ($mensagem): ?>
        <div class="alert">
            <?= htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone">

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco">

        <button type="submit">Salvar Cliente</button>
    </form>

    <!-- Botão Voltar -->
    <div class="back-button">
        <a href="gerenciar_clientes.php">Voltar</a>
    </div>
</div>
</body>
</html>
