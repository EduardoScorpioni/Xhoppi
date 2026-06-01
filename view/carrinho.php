<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$idCliente = $_SESSION["usuario"]["id"];
$cliente = $controller->buscarCliente($idCliente);
$itens = $controller->listarItensCarrinho($idCliente);
$total = $controller->calcularTotalCarrinho($itens);
$status = isset($_GET["status"]) ? $_GET["status"] : "";

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function moeda($valor)
{
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}

function caminhoImagemCarrinho($imagem)
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
    <link rel="stylesheet" href="../CSS/carrinho.css">
    <title>Xhoppi - Carrinho</title>
</head>
<body>
    <?php include "header.php" ?>

    <main class="carrinho-pagina">
        <section class="carrinho-topo">
            <h1>Meu carrinho</h1>
            <p><?php echo e($cliente["nome"]); ?>, confira seus itens antes de finalizar.</p>
        </section>

        <?php if ($status == "adicionado") { ?>
            <p class="mensagem sucesso">Produto adicionado ao carrinho.</p>
        <?php } elseif ($status == "sucesso") { ?>
            <p class="mensagem sucesso">Carrinho atualizado.</p>
        <?php } elseif ($status == "erro") { ?>
            <p class="mensagem erro">Nao foi possivel atualizar o carrinho. Confira o estoque.</p>
        <?php } elseif ($status == "erro_finalizar") { ?>
            <p class="mensagem erro">Nao foi possivel finalizar o carrinho. Confira os itens e o estoque.</p>
        <?php } ?>

        <?php if (count($itens) == 0) { ?>
            <section class="carrinho-vazio">
                <h2>Seu carrinho esta vazio</h2>
                <a href="Produtos.php" class="btn-principal">Ver produtos</a>
            </section>
        <?php } else { ?>
            <section class="carrinho-grid">
                <section class="lista-itens">
                    <?php foreach ($itens as $item) { ?>
                        <article class="item-carrinho">
                            <img src="<?php echo e(caminhoImagemCarrinho($item["imagem"])); ?>" alt="<?php echo e($item["nome"]); ?>">

                            <section class="item-info">
                                <h2><?php echo e($item["nome"]); ?></h2>
                                <p><?php echo e($item["fabricante"]); ?></p>
                                <span><?php echo (int) $item["estoque"]; ?> pecas disponiveis</span>
                            </section>

                            <section class="item-acoes">
                                <strong><?php echo moeda($item["valor"]); ?></strong>

                                <form action="../processamento/Processamento.php" method="post" class="form-quantidade">
                                    <input type="hidden" name="acao" value="atualizar_carrinho">
                                    <input type="hidden" name="id_item" value="<?php echo (int) $item["id_item"]; ?>">
                                    <input type="number" name="quantidade" value="<?php echo (int) $item["quantidade"]; ?>" min="1" max="<?php echo max(1, (int) $item["estoque"]); ?>">
                                    <button type="submit">Atualizar</button>
                                </form>

                                <form action="../processamento/Processamento.php" method="post">
                                    <input type="hidden" name="acao" value="remover_carrinho">
                                    <input type="hidden" name="id_item" value="<?php echo (int) $item["id_item"]; ?>">
                                    <button type="submit" class="btn-remover">Remover</button>
                                </form>

                                <p>Subtotal: <?php echo moeda($item["subtotal"]); ?></p>
                            </section>
                        </article>
                    <?php } ?>
                </section>

                <aside class="resumo-carrinho">
                    <h2>Resumo do pedido</h2>

                    <section class="linha-total">
                        <span>Itens</span>
                        <strong><?php echo count($itens); ?></strong>
                    </section>

                    <section class="linha-total">
                        <span>Total</span>
                        <strong><?php echo moeda($total); ?></strong>
                    </section>

                    <form action="../processamento/Processamento.php" method="post" class="form-finalizar">
                        <input type="hidden" name="acao" value="finalizar_carrinho">

                        <label>
                            Endereco de entrega
                            <input type="text" name="endereco_entrega" placeholder="Rua, numero, bairro e cidade" required>
                        </label>

                        <label>
                            Pagamento
                            <select name="forma_pagamento" required>
                                <option value="Pix">Pix</option>
                                <option value="Cartao de credito">Cartao de credito</option>
                                <option value="Boleto">Boleto</option>
                            </select>
                        </label>

                        <button type="submit" class="btn-principal">Finalizar compra</button>
                    </form>
                </aside>
            </section>
        <?php } ?>
    </main>

    <?php include "footer.php" ?>
</body>
</html>
