<?php
// Inclui a conexão com o banco de dados
require_once 'includes/db.php'; 

// Inicia a sessão
session_start();
$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepara a consulta para verificar o email e buscar o cliente
    $stmt = $pdo->prepare("SELECT id_cliente, nome, senha, adm_flag FROM clientes WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o cliente foi encontrado e se a senha corresponde
    if ($cliente && $cliente['senha'] === $senha) {
        // Armazena informações do cliente na sessão
        $_SESSION['id_cliente'] = $cliente['id_cliente'];
        $_SESSION['nome_cliente'] = $cliente['nome'];

        // Verifica se o usuário é administrador
        if ($cliente['adm_flag'] == 1) {
            header("Location: gerenciar_clientes.php"); // Redireciona para a página de gerenciamento
            exit();
        } else {
            header("Location: index.php"); // Redireciona para a página inicial
            exit();
        }
    } else {
        $mensagem = 'Email ou senha incorretos.';
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
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>
</head>
<body>
<header>
    <div class="container_site">
        <?php
        require "./pages/layout.php";
        ?>
    </div>
</header>
<br><br>
<div class="form-container">
    <h2>Login</h2>

    <!-- Alerta de erro caso o login não seja bem-sucedido -->
    <?php if ($mensagem): ?>
        <div class="alert"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
</div>
<br><br>
<?php require './pages/rodapé.php'; ?>
</body>
</html>
