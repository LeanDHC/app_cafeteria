<?php

session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Variável para mensagem de sucesso ou erro
$mensagem = "";

// Lógica de inserção
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha =$_POST['senha'];
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
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/cadastro_clientes.css">
    <title>Cafeteria - Cadastro Cliente</title>
</head>
<body>
<header>
        <div class="container_site">
            <?php
                require "./pages/layout.php"
            ?>
        </div>

    </header>
    <br>
    <br>
    <div class="form-container">
        
    <h2>Cadastro de Cliente</h2>

    <!-- Alerta de mensagem -->
    <?php if ($mensagem): ?>
        <div class="alert">
            <?= $mensagem; ?>
        </div>
    <?php endif; ?>

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

        <button type="submit">Cadastrar</button>
       
    </form>
</div>
<br>
<br>
<?php require './pages/rodapé.php'     ?>
</body>
</html>