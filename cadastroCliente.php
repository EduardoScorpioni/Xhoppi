<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Xhopii - Login</title>
        <link rel="stylesheet" href="CSS/Ccliente.css">
    </head>
    <body>
        <?php include_once "header.php" ?>
        <section  class="card">
            <h2>Cadastrar Clientes</h2>
            <input type="text" placeholder ="Nome">
            <input type="text" placeholder ="Sobrenome">
            <input  type="text" placeholder ="CPF">
            <input type="date" >
            <input type="text" placeholder ="Telefone">
            <input type="email" placeholder ="E-mail">
            <input type="password" placeholder ="Senha">
            <h3>Selecione a foto de Perfil</h3>
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