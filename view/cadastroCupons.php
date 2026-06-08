<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("gerente", "funcionario"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$lojas = $controller->listarLojas();
$status = isset($_GET["status"]) ? $_GET["status"] : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Xhopii - Cadastro Cupom</title>
    <link rel="stylesheet" href="../CSS/Ccupons.css">
</head>
<body>
    <?php include_once "header.php" ?>

    <form class="card" action="../processamento/Processamento.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="cadastrar_cupom">
        <h2>Cadastrar Cupom</h2>

        <?php if ($status == "sucesso") { ?>
            <p>Cupom cadastrado com sucesso.</p>
        <?php } elseif ($status == "erro") { ?>
            <p>Nao foi possivel cadastrar o cupom.</p>
        <?php } ?>

        <input type="text" name="codigo" placeholder="Codigo do Cupom" required>
        <input type="number" name="desconto" placeholder="Desconto (%)" min="0" max="100" step="0.01" required>
        <input type="date" name="dataValidade" required>
        <input type="number" name="quantidadeDisponivel" placeholder="Quantidade Disponivel" min="0" required>

        <select name="id_loja">
            <option value="">Cupom geral (opcional)</option>
            <?php foreach ($lojas as $loja) { ?>
                <option value="<?php echo (int) $loja["id_loja"]; ?>">
                    <?php echo htmlspecialchars($loja["nome"], ENT_QUOTES, "UTF-8"); ?>
                </option>
            <?php } ?>
        </select>

        <h3>Selecione uma imagem do cupom</h3>

        <section class="file-container">
            <label class="file-label">
                Escolher arquivo
                <input type="file" name="imagem" class="file">
            </label>
            <span id="file-name">Nenhum arquivo escolhido</span>
        </section>

        <button type="submit">Cadastrar Cupom</button>
    </form>

    <?php include "footer.php"?>
</body>
</html>
