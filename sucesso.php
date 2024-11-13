<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido Efetuado com Sucesso</title>
    <link rel="stylesheet" href="../css/style-sucesso.css">
    <script>

        
        function alterarMensagem() {
            const mensagemElement = document.getElementById("mensagem");
            const contadorElement = document.getElementById("contador");
            const botao = document.getElementById("botao");
            let contador = 10; // Exemplo de valor inicial, em segundos

            // Função para formatar o tempo em MM:SS
            function formatarTempo(segundos) {
                const minutos = Math.floor(segundos / 60);
                const segundosRestantes = segundos % 60;
                return `${minutos}:${segundosRestantes < 10 ? '0' : ''}${segundosRestantes}`;
            }

            // Atualiza o contador a cada segundo
            const interval = setInterval(() => {
                if (contador > 0) {
                    contadorElement.innerText = `Aguarde: ${formatarTempo(contador)}`;
                    contador--;
                } else {
                    // Quando o tempo acabar, exibe a mensagem final e oculta o contador
                    clearInterval(interval);
                    mensagemElement.innerText = "Sua encomenda chegou, vá até o endereço cadastrado no pedido!";
                    contadorElement.style.display = "none"; // Oculta o contador
                    botao.innerText = "OK";
                    botao.href = "carrinho.php";
                    botao.style.cursor = "pointer"; // Habilita o clique
                }
            }, 1000);
        }
    </script>
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 20px;
            cursor: default;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<header>
    <div class="container_site">
        <?php require "./pages/layout.php"; ?>
    </div>
</header>
<body onload="alterarMensagem()">
    <h1>Confirmação de Pedido</h1>
    <p id="mensagem">Pedido efetuado com sucesso!</p>
    <p id="contador">Aguarde: 0:10</p> <!-- Valor inicial de exemplo, será atualizado pelo JavaScript -->
    
    <!-- Botão que será alterado após o tempo de espera -->
    <a id="botao" class="button">Aguarde</a>
    <br><br>
   
</body>

</html>
