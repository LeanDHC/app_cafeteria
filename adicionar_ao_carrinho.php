<?php
session_start();
require_once 'includes/db.php'; // Conexão com o banco de dados

// Verifica se o cliente está logado
if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$id_produto = $_POST['id_produto']; // Recebe o ID do produto a partir do formulário

// Verifica se o produto já existe em um pedido em aberto para este cliente
try {
    // Verifica se existe um pedido em aberto para o cliente
    $queryPedido = "SELECT id_pedido FROM pedidos WHERE id_cliente = :id_cliente AND status = 'Em Aberto'";
    $stmt = $pdo->prepare($queryPedido);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se não existir um pedido em aberto, cria um novo pedido
    if (!$pedido) {
        $queryNovoPedido = "INSERT INTO pedidos (id_cliente, status, total_pedido) VALUES (:id_cliente, 'Em Aberto', 0)";
        $stmt = $pdo->prepare($queryNovoPedido);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();
        $pedidoId = $pdo->lastInsertId();
    } else {
        $pedidoId = $pedido['id_pedido'];
    }

    // Verifica se o produto já está no carrinho (na tabela itens_pedido)
    $queryItem = "SELECT quantidade FROM itens_pedido WHERE id_pedido = :id_pedido AND id_produto = :id_produto";
    $stmt = $pdo->prepare($queryItem);
    $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
    $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Produto já está no carrinho, incrementa a quantidade
        $novaQuantidade = $item['quantidade'] + 1;
        $queryAtualizaItem = "UPDATE itens_pedido SET quantidade = :quantidade WHERE id_pedido = :id_pedido AND id_produto = :id_produto";
        $stmt = $pdo->prepare($queryAtualizaItem);
        $stmt->bindParam(':quantidade', $novaQuantidade, PDO::PARAM_INT);
        $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
        $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Produto não está no carrinho, adiciona como novo item
        $queryNovoItem = "INSERT INTO itens_pedido (id_pedido, id_produto, quantidade, preco_unitario) 
                          VALUES (:id_pedido, :id_produto, 1, (SELECT preco FROM produtos WHERE id_produto = :id_produto))";
        $stmt = $pdo->prepare($queryNovoItem);
        $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
        $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Redireciona para a página do carrinho ou exibe uma mensagem de sucesso
    header("Location: carrinho.php");
    exit();

} catch (PDOException $e) {
    die("Erro ao adicionar ao carrinho: " . $e->getMessage());
}
?>
