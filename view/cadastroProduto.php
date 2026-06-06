<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("admin", "funcionario"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$lojas = $controller->listarLojas();
$status = isset($_GET["status"]) ? $_GET["status"] : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Xhopii - Cadastro Produto</title>
        <link rel="stylesheet" href="../CSS/Cproduto.css">
    </head>
    <body>
        <?php include_once "header.php" ?>

        <form class="card" action="../processamento/Processamento.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="cadastrar_produto">
            <h2>Cadastrar Produto</h2>

            <?php if ($status == "sucesso") { ?>
                <p>Produto cadastrado com sucesso.</p>
            <?php } elseif ($status == "erro") { ?>
                <p>Nao foi possivel cadastrar o produto.</p>
            <?php } ?>

            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="fabricante" placeholder="Fabricante" required>
            <input type="text" name="descricao" placeholder="Descricao" required>
            <input type="text" name="valor" placeholder="Valor" required>
            <input type="number" name="quantidade" placeholder="Quantidade" min="0" required>

            <select name="id_loja">
                <option value="">Selecione uma loja (opcional)</option>
                <?php foreach ($lojas as $loja) { ?>
                    <option value="<?php echo (int) $loja["id_loja"]; ?>">
                        <?php echo htmlspecialchars($loja["nome"], ENT_QUOTES, "UTF-8"); ?>
                    </option>
                <?php } ?>
            </select>

            <h3>Selecione a foto do produto</h3>
            <section class="file-container">
                <label class="file-label">
                    Escolher arquivo
                    <input type="file" name="imagem" class="file">
                </label>
                <span id="file-name">Nenhum arquivo escolhido</span>
            </section>

            <button type="submit">Cadastrar</button>
        </form>

        <?php include "footer.php"?>
    </body>
</html>
