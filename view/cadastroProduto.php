<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Xhopii - Login</title>
        <link rel="stylesheet" href="../CSS/Cproduto.css">
    </head>
    <body>
        <?php include_once "header.php" ?>
        <section  class="card">
            <h2>Cadastrar Produto</h2>
            <input type="text" placeholder ="Nome">
            <input type="text" placeholder ="Fabricante">
            <input  type="text" placeholder ="Descricção">
            <input type="text" placeholder ="Valor">
            <input type="text" placeholder ="Quantiade">
            <h3>Selecione a foto do produto:</h3>
           <section class="file-container">
                <label class="file-label">
                    Escolher arquivo
                    <input type="file" class="file"">
                </label>
                <span id="file-name">Nenhum arquivo escolhido</span>
                </section>
            <button>
                Cadastrar
            </button>
        </section>
        <?php include "footer.php"?>
    </body>
</html>