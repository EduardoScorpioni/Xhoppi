<?php
$status = isset($_GET["status"]) ? $_GET["status"] : "";
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Xhopii - Login</title>
        <link rel="stylesheet" href="../CSS/style.css">
    </head>
    <body>
        <header class="topbar">
            <section class="top-left">
                <img src="../img/logo.png" class="logo">
                <span class="brand">Xhopii</span>
                <span class="enter">Entre</span>
            </section>
            <section class="top-right">
                <a href="#">Precisa de ajuda?</a>
            </section>
        </header>

        <main class="background">
            <form class="login-card" action="../processamento/Processamento.php" method="post">
                <input type="hidden" name="acao" value="login">

                <h2>Login</h2>

                <?php if ($status == "erro") { ?>
                    <p class="erro-login">E-mail ou senha inválidos.</p>
                <?php } ?>

                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>

                <button type="submit" class="btn-login">ENTRE</button>

                <section class="login-links">
                    <a href="redefinir.php">Esqueci minha senha</a>
                    <a href="#">Fazer login com SMS</a>
                </section>

                <section class="divider">
                    <section class="line"></section>
                    <span>OU</span>
                    <section class="line"></section>
                </section>

                <section class="social-login">
                    <button type="button" class="social-btn">
                        <img src="../img/facebook.png">
                        Facebook
                    </button>
                    <button type="button" class="social-btn">
                        <img src="../img/google.png">
                        Google
                    </button>
                    <button type="button" class="social-btn">
                        <img src="../img/apple.png">
                        Apple
                    </button>
                </section>

                <p class="register">
                    Novo na Xhopii? <a href="cadastroCliente.php">Cadastrar</a>
                </p>
            </form>
        </main>
    </body>
</html>