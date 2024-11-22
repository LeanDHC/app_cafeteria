<?php
session_start();
require_once 'includes/db.php'; // Conexão com o banco de dados

// Verifica se o cliente está logado
// if (!isset($_SESSION['id_cliente'])) {
//     header("Location: login.php");
//     exit();
// }

$id_cliente = $_SESSION['id_cliente'];

// Buscar os produtos do cardápio
$query = "SELECT * FROM produtos";
$stmt = $pdo->prepare($query);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/cardapio.css">
    <title>Cafeteria </title>
</head>
<body>
    
    <header>
        <div class="container_site">
            <?php
                require "./pages/layout.php"
            ?>
        </div>

    </header>
    <br><br><br>
    <table class="cardapio-tabela">
   
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($produto['imagem_url']); ?>" alt="<?= htmlspecialchars($produto['nome']); ?>" class="produto-imagem"></td>
                    <td><?= '<strong>' . nl2br(htmlspecialchars($produto['nome'])) . '</strong>'; ?><br><?= nl2br(htmlspecialchars($produto['descricao'])); ?> </td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <form action="adicionar_ao_carrinho.php" method="post">
                            <input type="hidden" name="id_produto" value="<?= htmlspecialchars($produto['id_produto']); ?>">
                            <button type="submit" class="adicionar-btn">Adicionar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<br>
<br>
<?php require './pages/rodapé.php'     ?>
    
</body>
</html>