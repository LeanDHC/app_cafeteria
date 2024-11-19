<?php
session_start();
require 'includes/db.php'; // Conexão com o banco de dados

// Verificar se o ID do produto foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID do produto não especificado.');
}

$id_produto = $_GET['id'];

// Consultar os dados do produto
try {
    $sql = "SELECT * FROM produtos WHERE id_produto = :id_produto";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        die('Produto não encontrado.');
    }
} catch (PDOException $e) {
    die("Erro ao buscar produto: " . $e->getMessage());
}

// Consultar as categorias
try {
    $sql = "SELECT id_categoria, nome FROM categorias";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}

// Atualizar os dados do produto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque_quantidade = $_POST['estoque_quantidade'];
    $id_categoria = $_POST['id_categoria'];
    $imagem_url = $produto['imagem_url'];
    $erro = '';

    // Validar os dados
    if (empty($nome) || empty($descricao) || empty($preco) || empty($estoque_quantidade) || empty($id_categoria)) {
        $erro = 'Todos os campos são obrigatórios.';
    } else {
        // Processar a nova imagem (se enviada)
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $diretorio = './images/cardapio_images/';
            $nome_imagem = $_FILES['imagem']['name'];
            $caminho_imagem = $diretorio . basename($nome_imagem);
            $extensao = strtolower(pathinfo($caminho_imagem, PATHINFO_EXTENSION));
            $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extensao, $tipos_permitidos)) {
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
                    $imagem_url = $caminho_imagem;
                } else {
                    $erro = "Erro ao fazer upload da imagem.";
                }
            } else {
                $erro = "A imagem deve ser nos formatos JPG, JPEG, PNG ou GIF.";
            }
        }

        if (!$erro) {
            try {
                $sql = "UPDATE produtos 
                        SET nome = :nome, descricao = :descricao, preco = :preco, estoque_quantidade = :estoque_quantidade, 
                            id_categoria = :id_categoria, imagem_url = :imagem_url 
                        WHERE id_produto = :id_produto";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':descricao', $descricao);
                $stmt->bindParam(':preco', $preco);
                $stmt->bindParam(':estoque_quantidade', $estoque_quantidade);
                $stmt->bindParam(':id_categoria', $id_categoria);
                $stmt->bindParam(':imagem_url', $imagem_url);
                $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    header('Location: gerenciar_produtos.php');
                    exit;
                } else {
                    $erro = "Erro ao atualizar produto.";
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
    <title>Atualizar Produto</title>
    <link rel="stylesheet" href="../css/gerenciar_clientes.css">
    <link rel="stylesheet" href="../css/gerenciar_site.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        .container {
            max-width: 600px;
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
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }

        input[type="text"], input[type="number"], textarea, select {
            padding: 10px;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #8b5e3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #a76d4b;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Atualizar Produto</h1>

    <?php if (isset($erro) && $erro): ?>
        <div class="error"><?= htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form action="atualizar_produto.php?id=<?= $produto['id_produto']; ?>" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']); ?>" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" rows="4" required><?= htmlspecialchars($produto['descricao']); ?></textarea>

        <label for="preco">Preço (R$)</label>
        <input type="number" id="preco" name="preco" value="<?= htmlspecialchars($produto['preco']); ?>" step="0.01" required>

        <label for="estoque_quantidade">Estoque</label>
        <input type="number" id="estoque_quantidade" name="estoque_quantidade" value="<?= htmlspecialchars($produto['estoque_quantidade']); ?>" required>

        <label for="id_categoria">Categoria</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="">Selecione uma categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id_categoria']; ?>" <?= $produto['id_categoria'] == $categoria['id_categoria'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="imagem">Imagem (atual: <?= basename($produto['imagem_url']); ?>)</label>
        <br>
        <input type="file" id="imagem" name="imagem" accept="image/*">
        <br><br>

        <button type="submit">Atualizar Produto</button>
    </form>
</div>
</body>
</html>
