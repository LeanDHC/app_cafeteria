<?php
session_start();
require_once 'includes/db.php'; // Conexão com o banco de dados
print("ANTES DE ENTRAR NO IF");  
// Verifica se o cliente está logado
if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit();
}
if (isset($_POST['id_item'])) {
    $id_item = $_POST['id_item'];
    error_log("ID do item recebido: " . $id_item);
} else {
    error_log("ID do item não recebido.");
}

if (isset($_POST['id_item'])) {
    $id_item = $_POST['id_item'];
    $id_cliente = $_SESSION['id_cliente'];

    // Verifique se existe um pedido em aberto para o cliente
    $queryPedido = "SELECT id_pedido FROM pedidos WHERE id_cliente = :id_cliente AND status = 'Em Aberto'";
    $stmt = $pdo->prepare($queryPedido);
    $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pedido) {
        $pedidoId = $pedido['id_pedido'];

        // Verifique se o item existe para esse pedido
        $queryVerificarItem = "SELECT * FROM itens_pedido WHERE id_item = :id_item AND id_pedido = :id_pedido";
        $stmtVerificar = $pdo->prepare($queryVerificarItem);
        $stmtVerificar->bindParam(':id_item', $id_item, PDO::PARAM_INT);
        $stmtVerificar->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);
        $stmtVerificar->execute();
       
        if ($stmtVerificar->rowCount() > 0) {
            error_log("Removendo item ID: " . $id_item . " do pedido ID: " . $pedidoId);
            
            // Remover o item

            $queryRemover = "DELETE FROM itens_pedido WHERE id_item = :id_item AND id_pedido = :id_pedido";
            $stmtRemover = $pdo->prepare($queryRemover);
            $stmtRemover->bindParam(':id_item', $id_item, PDO::PARAM_INT);
            $stmtRemover->bindParam(':id_pedido', $pedidoId, PDO::PARAM_INT);

            if ($stmtRemover->execute()) {
                error_log("Item ID " . $id_item . " removido com sucesso do pedido ID " . $pedidoId);
                header("Location: carrinho.php"); // Redireciona após a remoção
                exit();
            } else {
                error_log("Erro ao tentar remover o item ID " . $id_item . " do pedido ID " . $pedidoId);
            }
        } else {
            error_log("Item ID " . $id_item . " não encontrado no pedido ID " . $pedidoId);
        }
    } else {
        error_log("Nenhum pedido em aberto encontrado para o cliente ID " . $id_cliente);
    }
}


?>
