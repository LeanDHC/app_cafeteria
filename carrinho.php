<?php
session_start();
require_once 'includes/db.php'; // Conexão com o banco de dados

// Verifica se o cliente está logado
if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];

// Verifica se existe um pedido em aberto para o cliente
$queryPedido = "SELECT id_pedido, status FROM pedidos WHERE id_cliente = :id_cliente AND status = 'Em Aberto'";
$stmt = $pdo->prepare($queryPedido);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pedido) {
    $pedidoId = $pedido['id_pedido']; // Atribuindo o ID do pedido

    // Buscar itens do carrinho (itens do pedido)
    $queryItens = "SELECT ip.id_item, p.nome, ip.quantidade, ip.preco_unitario, (ip.quantidade * ip.preco_unitario) AS total, p.estoque_quantidade
                   FROM itens_pedido ip
                   INNER JOIN produtos p ON ip.id_produto = p.id_produto
                   WHERE ip.id_pedido = :id_pedido";
    $stmt = $pdo->prepare($queryItens);
    $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
    $stmt->execute();
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
 
    $itens = [];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/carrinho.css">
    <title>Carrinho de Compras</title>
   
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
    <h1>Carrinho de Compras</h1>

    <?php if (empty($itens)): ?>
        <div class="carrinho-vazio">
        <p>Seu carrinho está vazio!</p>
        <img src="images/carrinho_vazio.png" alt="Carrinho vazio">
    </div>
        <?php else: ?>
        
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <!-- Fomulario atualizar-->
                <form action="atualizar_quantidade.php" method="post">
            <tr>
            <td><?= htmlspecialchars($item['nome']); ?></td>
            <td>
                <input type="number" name="quantidade[<?= $item['id_item']; ?>]" value="<?= $item['quantidade']; ?>" min="1" max="<?= $item['estoque_quantidade']; ?>" />
            </td>
            <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
            <td>R$ <?= number_format($item['total'], 2, ',', '.'); ?></td>
            <td>
            
                <button type="submit" name="atualizar" value="<?= htmlspecialchars($item['id_item']); ?>">Atualizar</button>
                </form>
                <!-- Formulário separado para remoção -->
                <form action="remover_item.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_item" value="<?= htmlspecialchars($item['id_item']); ?>">
                    <button type="submit" name="remover">Remover</button>
                </form>
            </td>
             </tr>
            <?php endforeach; ?>
            </tbody>
            </table>

            <div>
                <h3>Total do Carrinho: R$ 
                    <?php
                    $queryTotal = "SELECT SUM(quantidade * preco_unitario) AS total FROM itens_pedido WHERE id_pedido = :id_pedido";
                    $stmt = $pdo->prepare($queryTotal);
                    $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
                    $stmt->execute();
                    $totalPedido = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo number_format($totalPedido['total'], 2, ',', '.');
                    ?>
                </h3>
                <a href="finalizar_compra.php">Finalizar Compra</a>
                <a href="cardapio.php">Voltar ao cardápio</a>
            </div>
        </form>
    <?php endif; ?>

</body>
</html>
