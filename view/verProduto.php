<?php
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$idProduto = isset($_GET["id"]) ? (int) $_GET["id"] : null;
$produto = $controller->buscarProduto($idProduto);
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/verProduto.css">
  <title>Xhopii - Produto</title>
</head>

    <body>
        <?php include "header.php"?>
        <section class="pagina">
            <?php if (!$produto) { ?>
                <section class="produto-card">
                    <section class="produto-info">
                        <h1 class="produto-titulo">Produto nao encontrado</h1>
                    </section>
                </section>
            <?php } else { ?>
                <section class="produto-card">
                    <section class="galeria">
                        <section class="miniaturas">
                            <?php foreach ($produtos as $indice => $item) { ?>
                                <?php if ($indice < 5) { ?>
                                    <section class="thumb <?php echo ((int) $item["id_produto"] == (int) $produto["id_produto"]) ? "active" : ""; ?>">
                                        <a href="verProduto.php?id=<?php echo (int) $item["id_produto"]; ?>">
                                            <img src="<?php echo e(caminhoImagemProduto($item["imagem"])); ?>" alt="<?php echo e($item["nome"]); ?>"/>
                                        </a>
                                    </section>
                                <?php } ?>
                            <?php } ?>
                        </section>
                        <section class="imagem-principal">
                            <img src="<?php echo e(caminhoImagemProduto($produto["imagem"])); ?>" alt="<?php echo e($produto["nome"]); ?>" />
                        </section>
                    </section>
                    <section class="produto-info">
                        <h1 class="produto-titulo"><?php echo e($produto["nome"]); ?></h1>
                        <p class="preco"><?php echo moeda($produto["valor"]); ?></p>
                        <p class="estoque"><?php echo (int) $produto["quantidade"]; ?> pecas disponiveis</p>
                        <p class="estoque">Com cupom XHOP10: <?php echo moeda($produto["valorComCupom10"]); ?></p>
                        <hr/>
                        <section>
                            <p class="label-opcao">Fabricante:</p>
                            <p><?php echo e($produto["fabricante"]); ?></p>
                        </section>
                        <hr/>
                        <section>
                            <p class="label-opcao">Descricao:</p>
                            <p><?php echo e($produto["descricao"]); ?></p>
                        </section>
                        <button type="button" class="btn-comprar">Comprar Agora</button>
                    </section>
                </section>
            <?php } ?>
        </section>
        <?php include "footer.php"?>
    </body>
</html>
