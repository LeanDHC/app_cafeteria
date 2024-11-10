<?php
session_start();
?>
<div class="container">
        <div class="parte-1"></div>

        <div class="parte-2">
            <p>COFFEE</p>
        </div>

        <div class="parte-3">
            <ul class="lista-nomes">
                <li><a href="index.php">Home</a></li>
                <li><a href="cardapio.php">Cardápio</a></li>
                <li><a href="contato.php">Contato</a></li>
            </ul>
        </div>

        <div class="parte-4">
        <div class="imagem-container">
                <div class="item">
                    <img src="../images/index_images/usuario_login.png" alt="Imagem 1">
                    <!-- Exibe o nome do cliente logado -->
              <!-- Exibe o nome do cliente logado -->
              <?php if (isset($_SESSION['nome_cliente'])): ?>
        <p>Bem-vindo, <?= htmlspecialchars($_SESSION['nome_cliente']) ?>!</p>
        <!-- Link de logout -->
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <p><a href="login.php">Login</a> | <a href="cadastro_cliente.php">Cadastre-se</a></p>
    <?php endif; ?>
                    
                </div>
                <div class="item">
                    <img src="../images/index_images/carrinho_compra.png" alt="Imagem 2">
                    <h3><a href="#">Carrinho</a></h3>
                </div>
            </div>
        </div>
    </div>

