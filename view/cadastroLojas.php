<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Xhopii - Cadastro Loja</title>
    <link rel="stylesheet" href="../CSS/Clojas.css">
</head>
<body>
    <?php include_once "header.php" ?>

    <section class="card">
        <h2>Cadastrar Loja</h2>

        <input type="text" placeholder="Nome da Loja">
        <input type="text" placeholder="CNPJ">
        <input type="text" placeholder="Telefone">
        <input type="email" placeholder="E-mail">
        <input type="text" placeholder="Endereço">

        <h3>Selecione a logo da loja</h3>

        <section class="file-container">
            <label class="file-label">
                Escolher arquivo
                <input type="file" class="file">
            </label>
            <span id="file-name">Nenhum arquivo escolhido</span>
        </section>

        <button>
            Cadastrar Loja
        </button>
    </section>

    <?php include "footer.php"?>
</body>
</html>