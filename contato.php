<?php
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style-banner-menu.css">
    <link rel="stylesheet" href="../css/style-corpo.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/contato.css">
    <title>Cafeteria - Contato </title>
</head>
<body>
<header>
        <div class="container_site">
            <?php
                require "./pages/layout.php"
            ?>
        </div>
        <div class="conteudo_interior">
              <!-- Seção de contato -->
              <div class="contato">
            <!-- Título da seção de contato -->
            <h2 class="centralizado-justificado">Entre em Contato</h2>
            
            <!-- Mapa real do Google Maps -->
            <div class="mapa-simulacao">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3656.283288034369!2d-46.64838482513559!3d-23.592100184659773!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59d7a42d6e29%3A0x454e89ab9c1a4c67!2sRua%20das%20Flores%2C%20123%20-%20Centro%2C%20S%C3%A3o%20Paulo%20-%20SP!5e0!3m2!1spt-BR!2sbr!4v1694111111111!5m2!1spt-BR!2sbr"
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Informações de contato -->
            <div class="informacoes-contato centralizado-justificado">
                <p><strong>Endereço:</strong> Rua das Flores, 123 - Centro, São Paulo, SP</p>
                <p><strong>Telefone:</strong> (11) 98765-4321</p>
                <p><strong>Email:</strong> contato@cafeteriaonline.com.br</p>
                <p><strong>Horário de Funcionamento:</strong> Segunda a Sexta, das 8h às 20h</p>
            </div>
        </div>
    </div>
    </header><br><br><br><br>
    <?php require './pages/rodapé.php'     ?>

    
</body>
</html>