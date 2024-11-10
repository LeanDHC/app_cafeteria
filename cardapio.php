<?php
include './includes/db.php';
// Consultar todos os produtos
$stmt = $pdo->query("SELECT * FROM produtos");
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

    <div class="conteudo_interior">
         <div id="container">
            <div class="barra cima"></div>
            <h1 class="titulo">Destaques da casa</h1>
            <div class="barra baixo"></div>
    </div>
    <!-- Tabela para exibir os produtos -->
    <table class="cardapio-tabela">
            
            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <!-- Exibir imagem do produto -->
                        <td><img src="<?= $produto['imagem_url']; ?>" alt="<?= $produto['nome']; ?>" class="produto-imagem"></td>
                        
                        <!-- Exibir descrição do produto -->
                        <td><?= $produto['descricao']; ?></td>
                        
                        <!-- Exibir preço do produto -->
                        <td>
            <div class="preco-container">
                <span class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></span>
                <button class="adicionar-btn">Adicionar</button>
            </div>
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