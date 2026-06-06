<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("cliente"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$idCliente = $_SESSION["usuario"]["id_cliente"];
$cliente = $controller->buscarCliente($idCliente);
$status = isset($_GET["status"]) ? $_GET["status"] : "";
$idProduto = isset($_GET["id"]) ? (int) $_GET["id"] : null;
$idPedido = isset($_GET["pedido"]) ? (int) $_GET["pedido"] : null;
$produto = $idProduto ? $controller->buscarProduto($idProduto) : null;
$pedido = $idPedido ? $controller->buscarPedido($idPedido, $idCliente) : null;
$itensPedido = $idPedido ? $controller->buscarItensPedido($idPedido, $idCliente) : array();

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function moeda($valor)
{
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}

function caminhoImagemCheckout($imagem)
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
    <link rel="stylesheet" href="../CSS/finalizarCompra.css">
    <title>Xhoppi - Finalizar Compra</title>
</head>
<body>
    <?php include "header.php" ?>

    <main class="checkout-pagina">
        <?php if ($status == "sucesso" && $pedido) { ?>
            <section class="checkout-card sucesso-card">
                <h1>Compra finalizada com sucesso</h1>
                <p>Pedido #<?php echo (int) $pedido["id_pedido"]; ?></p>

                <?php foreach ($itensPedido as $item) { ?>
                    <section class="resumo-produto item-pedido">
                        <img src="<?php echo e(caminhoImagemCheckout($item["imagem"])); ?>" alt="<?php echo e($item["nome_produto"]); ?>">
                        <section>
                            <h2><?php echo e($item["nome_produto"]); ?></h2>
                            <p>Quantidade: <?php echo (int) $item["quantidade"]; ?></p>
                            <p>Valor unitario: <?php echo moeda($item["valor_unitario"]); ?></p>
                            <strong>Subtotal: <?php echo moeda($item["subtotal"]); ?></strong>
                        </section>
                    </section>
                <?php } ?>

                <section class="dados-pedido">
                    <p>Pagamento: <?php echo e($pedido["forma_pagamento"]); ?></p>
                    <p>Status: <?php echo e($pedido["status"]); ?></p>
                    <strong>Total: <?php echo moeda($pedido["valor_total"]); ?></strong>
                </section>

                <a href="../index.php" class="btn-principal">Voltar para a home</a>
            </section>
        <?php } elseif (!$produto) { ?>
            <section class="checkout-card">
                <h1>Produto nao encontrado</h1>
                <a href="../index.php" class="btn-principal">Voltar para a home</a>
            </section>
        <?php } else { ?>
            <section class="checkout-grid">
                <section class="checkout-card">
                    <h1>Finalizar compra</h1>

                    <?php if ($status == "erro") { ?>
                        <p class="mensagem-erro">Nao foi possivel finalizar a compra. Confira os dados e o estoque.</p>
                    <?php } ?>

                    <form action="../processamento/Processamento.php" method="post" class="checkout-form">
                        <input type="hidden" name="acao" value="finalizar_compra">
                        <input type="hidden" name="id_produto" value="<?php echo (int) $produto["id_produto"]; ?>">

                        <label>
                            Nome
                            <input type="text" value="<?php echo e($cliente["nome"] . " " . $cliente["sobrenome"]); ?>" readonly>
                        </label>

                        <label>
                            E-mail
                            <input type="email" value="<?php echo e($cliente["email"]); ?>" readonly>
                        </label>

                        <label>
                            Endereco de entrega
                            <input type="text" name="endereco_entrega" placeholder="Rua, numero, bairro e cidade" required>
                        </label>

                        <section class="linha-form">
                            <label>
                                Quantidade
                                <input type="number" name="quantidade" value="1" min="1" max="<?php echo (int) $produto["quantidade"]; ?>" required>
                            </label>

                            <label>
                                Pagamento
                                <select name="forma_pagamento" required>
                                    <option value="Pix">Pix</option>
                                    <option value="Cartao de credito">Cartao de credito</option>
                                    <option value="Boleto">Boleto</option>
                                </select>
                            </label>
                        </section>

                        <button type="submit" class="btn-principal">Finalizar compra</button>
                    </form>
                </section>

                <aside class="checkout-card resumo-card">
                    <h2>Resumo</h2>
                    <section class="resumo-produto">
                        <img src="<?php echo e(caminhoImagemCheckout($produto["imagem"])); ?>" alt="<?php echo e($produto["nome"]); ?>">
                        <section>
                            <h3><?php echo e($produto["nome"]); ?></h3>
                            <p><?php echo e($produto["fabricante"]); ?></p>
                            <p><?php echo (int) $produto["quantidade"]; ?> pecas disponiveis</p>
                        </section>
                    </section>
                    <section class="total">
                        <span>Valor unitario</span>
                        <strong><?php echo moeda($produto["valor"]); ?></strong>
                    </section>
                </aside>
            </section>
        <?php } ?>
    </main>

    <?php include "footer.php" ?>
</body>
</html>
