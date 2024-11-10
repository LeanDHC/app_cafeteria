<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/login.css">
    <title>Recuperar Senha</title>
</head>
<body>
<header>
        <div class="container_site">
            <?php
                require "./pages/layout.php"
            ?>
        </div>

    </header>
  <br><br>
    <div class="form-container">
        <h2>Recuperar Senha</h2>
        <?php if (isset($msg)): ?>
            <div class="alert"><?= $msg; ?></div>
        <?php endif; ?>
        <form action="recuperar_senha.php" method="POST">
            <label for="email">Digite seu e-mail cadastrado:</label>
            <input type="email" name="email" required><br><br>
            <button type="submit">Enviar</button>
        </form>
    </div>
    <?php
require 'includes/db.php'; // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    // Verificar se o e-mail existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Enviar email com link ou senha temporária
        $token = bin2hex(random_bytes(16));  // Gerar um token aleatório
        $link = "https://seusite.com/resetar_senha.php?token=$token";

        // Atualizar o token no banco
        $updateStmt = $pdo->prepare("UPDATE clientes SET token_recuperacao = :token WHERE email = :email");
        $updateStmt->bindParam(':token', $token);
        $updateStmt->bindParam(':email', $email);
        $updateStmt->execute();

        // Enviar o e-mail
        $assunto = "Recuperação de Senha";
        $mensagem = "Clique no link para redefinir sua senha: $link";
        $headers = "From: no-reply@seusite.com";

        if (mail($email, $assunto, $mensagem, $headers)) {
            $msg = "Um e-mail foi enviado para recuperação de senha.";
        } else {
            $msg = "Erro ao enviar e-mail.";
        }
    } else {
        $msg = "E-mail não encontrado.";
    }
}
?>


</body>
</html>