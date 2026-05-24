<?php
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$produtos = $controller->listarProdutos();

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function moeda($valor)
{
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}

function caminhoImagemProduto($imagem)
{
    if (empty($imagem)) {
        return "../img/produto1.png";
    }

    if (strpos($imagem, "http") === 0 || strpos($imagem, "../") === 0) {
        return $imagem;
    }

    return "../" . ltrim($imagem, "/");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Produtos.css">
    <title>Xhopii - Produtos</title>
</head>
<body>
        <?php include_once "header.php"?>
      
        <section class="produtos">
            <h2 class="titulo-secao">Produtos</h2>

            <?php foreach ($produtos as $produto) { ?>
                <section class="card">
                    <a href="verProduto.php?id=<?php echo (int) $produto["id_produto"]; ?>">
                        <img src="<?php echo e(caminhoImagemProduto($produto["imagem"])); ?>" alt="<?php echo e($produto["nome"]); ?>">
                        <p class="titulo"><?php echo e($produto["nome"]); ?></p>
                        <section class="info">
                            <section class="detalhes">
                                <p><strong>Fabricante:</strong> <?php echo e($produto["fabricante"]); ?></p>
                                <p><strong>Descricao:</strong> <?php echo e($produto["descricao"]); ?></p>
                            </section>
                            <span class="preco"><?php echo moeda($produto["valor"]); ?></span>
                            <span class="estoque"><?php echo (int) $produto["quantidade"]; ?> disponiveis</span>
                        </section>
                    </a>
                </section>
            <?php } ?>
        </section>

        <?php include_once "footer.php"?>
</body>
</html>
