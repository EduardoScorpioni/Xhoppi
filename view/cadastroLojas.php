<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("gerente", "funcionario"));
$status = isset($_GET["status"]) ? $_GET["status"] : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Xhopii - Cadastro Loja</title>
    <link rel="stylesheet" href="../CSS/Clojas.css">
</head>
<body>
    <?php include_once "header.php" ?>

    <form class="card" action="../processamento/Processamento.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="cadastrar_loja">
        <h2>Cadastrar Loja</h2>

        <?php if ($status == "sucesso") { ?>
            <p>Loja cadastrada com sucesso.</p>
        <?php } elseif ($status == "erro") { ?>
            <p>Nao foi possivel cadastrar a loja.</p>
        <?php } ?>

        <input type="text" name="nome" placeholder="Nome da Loja" required>
        <input type="text" name="cnpj" placeholder="CNPJ" required>
        <input type="text" name="telefone" placeholder="Telefone" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="text" name="endereco" placeholder="Endereco" required>
        <input type="text" name="descricao" placeholder="Descricao da loja">

        <h3>Selecione a logo da loja</h3>

        <section class="file-container">
            <label class="file-label">
                Escolher arquivo
                <input type="file" name="logo" class="file">
            </label>
            <span id="file-name">Nenhum arquivo escolhido</span>
        </section>

        <button type="submit">Cadastrar Loja</button>
    </form>

    <?php include "footer.php"?>
</body>
</html>
