<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuarioHeader = isset($_SESSION["usuario"]) ? $_SESSION["usuario"] : null;
$nivelHeader = $usuarioHeader && isset($usuarioHeader["nivel_acesso"]) ? $usuarioHeader["nivel_acesso"] : "";
$logadoHeader = $usuarioHeader != null;
$ehClienteHeader = $nivelHeader == "cliente";
$ehFuncionarioHeader = $nivelHeader == "funcionario" || $nivelHeader == "admin";
$ehAdminHeader = $nivelHeader == "admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/Git/Xhoppi/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/Git/Xhoppi/CSS/header.css">
    <title>Document</title>
</head>
<body>
    <header>
        <section class="cabecalho">
            <section class="logo">
                <a href="/Git/Xhoppi/index.php"><img src="/Git/Xhoppi/img/logo.png" alt=""></a>
                <a href="/Git/Xhoppi/index.php"><h2>Xhoppi</h2></a>
            </section>
            <section class="sair">
                <label>
                    <?php if ($logadoHeader) { ?>
                        <a href="/Git/Xhoppi/processamento/Processamento.php?acao=sair">Sair</a>
                    <?php } else { ?>
                        <a href="/Git/Xhoppi/view/login.php">Entrar</a>
                    <?php } ?>
                </label>
            </section>
        </section>

        <section class="navBar">
            <ul>
                <li><a href="/Git/Xhoppi/index.php">Home</a></li>
                <li><a href="/Git/Xhoppi/view/Produtos.php">Ver Produtos</a></li>

                <?php if ($logadoHeader) { ?>
                    <li><a href="/Git/Xhoppi/view/perfilUsuario.php">Meu Perfil</a></li>
                <?php } ?>

                <?php if ($ehClienteHeader) { ?>
                    <li><a href="/Git/Xhoppi/view/carrinho.php">Carrinho</a></li>
                <?php } ?>

                <?php if ($ehFuncionarioHeader) { ?>
                    <li><a href="/Git/Xhoppi/view/cadastroProduto.php">Cadastro Produto</a></li>
                    <li><a href="/Git/Xhoppi/view/verClientes.php">Ver Clientes</a></li>

                    <?php if ($ehAdminHeader) { ?>
                        <li><a href="/Git/Xhoppi/view/cadastroFuncionario.php">Cadastro Funcionario</a></li>
                        <li><a href="/Git/Xhoppi/view/verFuncionario.php">Ver Funcionarios</a></li>
                    <?php } ?>

                    <li class="dropdown">
                        <a href="#">Admin v</a>
                        <ul class="dropdown-menu">
                            <li><a href="/Git/Xhoppi/view/cadastroLojas.php">Cadastrar Loja</a></li>
                            <li><a href="/Git/Xhoppi/view/verLojas.php">Ver Lojas</a></li>
                            <li><a href="/Git/Xhoppi/view/cadastroCupons.php">Cadastrar Cupom</a></li>
                            <li><a href="/Git/Xhoppi/view/verCupons.php">Ver Cupons</a></li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </section>
    </header>
</body>
</html>
