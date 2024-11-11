<?php
 session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <title>Cafeteria</title>
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
            <h1 class="titulo">Sobre Nós</h1>
            <div class="barra baixo"></div>
        </div>
        <div class="sobre-nos">
            <p>Bem-vindo à nossa cafeteria online! Somos apaixonados por café e buscamos proporcionar a melhor experiência para os amantes dessa bebida tão especial. Fundada por um grupo de entusiastas do café, nossa missão é levar até você uma seleção de grãos de alta qualidade, torrados com cuidado e perfeição, para que cada xícara seja uma verdadeira experiência sensorial.</p>

            <p>Na nossa cafeteria, acreditamos que o café vai além de uma simples bebida. Ele tem o poder de conectar pessoas, proporcionar momentos de prazer e até mesmo inspirar novas ideias. Por isso, escolhemos apenas os melhores grãos, vindos de produtores comprometidos com a sustentabilidade e o respeito à natureza.</p>

            <p>Além dos clássicos, nossa loja oferece uma variedade de opções especiais, como cafés filtrados, expressos e drinks exclusivos, preparados com muito carinho e dedicação. E para complementar a experiência, você encontra uma seleção de acompanhamentos, como bolos, tortas e pães fresquinhos, feitos com ingredientes selecionados.</p>

            <p>A experiência de comprar em nossa loja online foi pensada para ser prática e segura. Oferecemos entrega rápida e eficiente, para que você possa saborear seu café fresquinho no conforto de sua casa. Com o nosso compromisso de qualidade e excelência no atendimento, garantimos que cada pedido seja uma verdadeira experiência de café de alta classe.</p>

            <p>Esperamos que você aproveite o melhor do café conosco, e que cada gole seja um momento único de prazer!</p>
        </div>
        <?php require './pages/rodapé.php'     ?>
    </div>
    







</body>
</html>