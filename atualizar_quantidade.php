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
    $pedidoId = $pedido['id_pedido'];

    // Atualiza a quantidade dos itens do pedido
    if (isset($_POST['quantidade']) && is_array($_POST['quantidade'])) {
        foreach ($_POST['quantidade'] as $id_item => $quantidade) {
            if ($quantidade > 0) {
                $queryAtualizar = "UPDATE itens_pedido SET quantidade = :quantidade WHERE id_item = :id_item AND id_pedido = :id_pedido";
                $stmt = $pdo->prepare($queryAtualizar);
                $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
                $stmt->bindParam(':id_item', $id_item, PDO::PARAM_INT);
                $stmt->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
}

header("Location: carrinho.php"); // Redireciona para a página do carrinho
exit();
