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
$queryPedido = "SELECT id_pedido FROM pedidos WHERE id_cliente = :id_cliente AND status = 'Em Aberto'";
$stmt = $pdo->prepare($queryPedido);
$stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
$stmt->execute();
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pedido) {
    $id_pedido = $pedido['id_pedido'];

    // Calcular o total do pedido
    $queryTotal = "
        SELECT SUM(ip.quantidade * ip.preco_unitario) AS total
        FROM itens_pedido ip
        WHERE ip.id_pedido = :id_pedido
    ";
    $stmtTotal = $pdo->prepare($queryTotal);
    $stmtTotal->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmtTotal->execute();
    $totalPedido = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Atualizar o total e o status do pedido para 'Concluído'
    $queryAtualizarTotal = "
        UPDATE pedidos
        SET total_pedido = :total_pedido, status = 'Concluído'
        WHERE id_pedido = :id_pedido
    ";
    $stmtAtualizarTotal = $pdo->prepare($queryAtualizarTotal);
    $stmtAtualizarTotal->bindParam(':total_pedido', $totalPedido, PDO::PARAM_STR);
    $stmtAtualizarTotal->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmtAtualizarTotal->execute();

    // Limpar o carrinho
    $queryLimparCarrinho = "
        DELETE FROM itens_pedido WHERE id_pedido = :id_pedido
    ";
    $stmtLimparCarrinho = $pdo->prepare($queryLimparCarrinho);
    $stmtLimparCarrinho->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
    $stmtLimparCarrinho->execute();

    // Redireciona para a página de sucesso
    header("Location: sucesso.php");
    exit();
} else {
    echo "<p>Não foi possível localizar o pedido em aberto.</p>";
}
?>
