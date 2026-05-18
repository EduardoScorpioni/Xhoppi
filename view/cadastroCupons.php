<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Xhopii - Cadastro Cupom</title>
    <link rel="stylesheet" href="../CSS/Ccupons.css">
</head>
<body>
    <?php include_once "header.php" ?>

    <section class="card">
        <h2>Cadastrar Cupom</h2>

        <input type="text" placeholder="Código do Cupom">
        <input type="number" placeholder="Desconto (%)">
        <input type="date" placeholder="Data de Validade">
        <input type="number" placeholder="Quantidade Disponível">

        <h3>Selecione uma imagem do cupom</h3>

        <section class="file-container">
            <label class="file-label">
                Escolher arquivo
                <input type="file" class="file">
            </label>
            <span id="file-name">Nenhum arquivo escolhido</span>
        </section>

        <button>
            Cadastrar Cupom
        </button>
    </section>

    <?php include "footer.php"?>
</body>
</html>