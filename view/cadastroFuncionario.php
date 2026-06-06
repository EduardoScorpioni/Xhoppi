<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("admin"));
$status = isset($_GET["status"]) ? $_GET["status"] : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Xhopii - Cadastro Funcionario</title>
        <link rel="stylesheet" href="../CSS/Cfuncionario.css">
    </head>
    <body>
        <?php include_once "header.php" ?>

        <form class="card" action="../processamento/Processamento.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="cadastrar_funcionario">
            <h2>Cadastrar Funcionario</h2>

            <?php if ($status == "sucesso") { ?>
                <p>Funcionario cadastrado com sucesso.</p>
            <?php } elseif ($status == "erro") { ?>
                <p>Nao foi possivel cadastrar o funcionario.</p>
            <?php } ?>

            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="sobrenome" placeholder="Sobrenome" required>
            <input type="text" name="cpf" placeholder="CPF" required>
            <input type="date" name="dataNascimento" required>
            <input type="text" name="telefone" placeholder="Telefone" required>
            <input type="text" name="cargo" placeholder="Cargo / Funcao" required>
            <input type="text" name="salario" placeholder="Salario" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <select name="nivel_acesso" required>
                <option value="funcionario">Funcionario</option>
                <option value="admin">Administrador</option>
            </select>

            <h3>Selecione a foto de perfil</h3>
            <section class="file-container">
                <label class="file-label">
                    Escolher arquivo
                    <input type="file" name="fotoPerfil" class="file">
                </label>
                <span id="file-name">Nenhum arquivo escolhido</span>
            </section>

            <button type="submit">Cadastrar</button>
        </form>

        <?php include "footer.php"?>
    </body>
</html>
