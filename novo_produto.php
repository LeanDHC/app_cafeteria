<?php
session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Consultar as categorias para preencher o dropdown
try {
    $sql = "SELECT id_categoria, nome FROM categorias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque_quantidade = $_POST['estoque_quantidade'];
    $id_categoria = $_POST['id_categoria'];

    // Validação simples (você pode expandir isso conforme necessário)
    if (empty($nome) || empty($descricao) || empty($preco) || empty($estoque_quantidade) || empty($id_categoria)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        // Processar a imagem enviada
        $imagem_url = '';
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            // Definir o diretório onde a imagem será salva
            $diretorio = './images/cardapio_images/';
            $nome_imagem = $_FILES['imagem']['name'];
            $caminho_imagem = $diretorio . basename($nome_imagem);

            // Verificar se a imagem é válida (extensão e tipo)
            $extensao = strtolower(pathinfo($caminho_imagem, PATHINFO_EXTENSION));
            $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extensao, $tipos_permitidos)) {
                // Mover a imagem para o diretório desejado
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
                    // Definir a URL da imagem para salvar no banco
                    $imagem_url = $caminho_imagem;
                } else {
                    $erro = "Erro ao fazer upload da imagem!";
                }
            } else {
                $erro = "A imagem deve ser nos formatos JPG, JPEG, PNG ou GIF.";
            }
        } else {
            $erro = "Selecione uma imagem válida para o produto.";
        }

        if (!isset($erro)) {
            // Prepara a consulta para inserir o novo produto
            try {
                $sql = "INSERT INTO produtos (nome, descricao, preco, estoque_quantidade, id_categoria, imagem_url) 
                        VALUES (:nome, :descricao, :preco, :estoque_quantidade, :id_categoria, :imagem_url)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':descricao', $descricao);
                $stmt->bindParam(':preco', $preco);
                $stmt->bindParam(':estoque_quantidade', $estoque_quantidade);
                $stmt->bindParam(':id_categoria', $id_categoria);
                $stmt->bindParam(':imagem_url', $imagem_url); // URL da imagem

                // Executa a consulta
                if ($stmt->execute()) {
                    $sucesso = "Produto cadastrado com sucesso!";
                } else {
                    $erro = "Erro ao cadastrar produto.";
                }
            } catch (PDOException $e) {
                $erro = "Erro: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="../css/gerenciar_produtos.css">
    <link rel="stylesheet" href="../css/gerenciar_site.css">
    <style>
        /* CSS semelhante ao anterior, você pode ajustar conforme necessário */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #8b5e3c;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, textarea, select {
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-submit {
            padding: 10px 20px;
            background-color: #8b5e3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #a76d4b;
        }

        .alert {
            padding: 10px;
            background: #f8d7da;
            color: #721c24;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="container">

    <header>
        <nav class="menu">
            <ul>
                <li><a href="gerenciar_clientes.php">Clientes</a></li>
                <li><a href="gerenciar_produtos.php">Produtos</a></li>
                <li><a href="gerenciar_pedidos.php">Pedidos</a></li>
            </ul>
        </nav>
    </header>

    <h1>Cadastrar Novo Produto</h1>

    <!-- Exibe a mensagem de sucesso ou erro -->
    <?php if (isset($erro)): ?>
        <div class="alert"><?= $erro; ?></div>
    <?php elseif (isset($sucesso)): ?>
        <div class="alert success"><?= $sucesso; ?></div>
    <?php endif; ?>

    <!-- Formulário para cadastro do produto -->
    <form action="novo_produto.php" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome do Produto:</label>
        <input type="text" id="nome" name="nome" value="<?= isset($nome) ? htmlspecialchars($nome) : ''; ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?= isset($descricao) ? htmlspecialchars($descricao) : ''; ?></textarea>

        <label for="preco">Preço (R$):</label>
        <input type="number" id="preco" name="preco" value="<?= isset($preco) ? htmlspecialchars($preco) : ''; ?>" step="0.01" required>

        <label for="estoque_quantidade">Quantidade em Estoque:</label>
        <input type="number" id="estoque_quantidade" name="estoque_quantidade" value="<?= isset($estoque_quantidade) ? htmlspecialchars($estoque_quantidade) : ''; ?>" required>

        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="">Selecione uma categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria']; ?>" <?= isset($id_categoria) && $id_categoria == $categoria['id_categoria'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="imagem">Imagem:</label>
        <input type="file" id="imagem" name="imagem" accept="image/*" required>

        <button type="submit" class="btn-submit">Cadastrar Produto</button>
    </form>
</div>
</body>
</html>
