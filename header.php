<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/header.css">
    <title>Document</title>
</head>
<body>
    <header>
        <section class = "cabeçalho">  
                <section class = "logo">
                    <a href="index.php"> <img src="img/logo.png" alt=""></a>
                    <a href="index.php"><h2>Xhoppi</h2></a>
                </section>
            <section class = "sair">
                <label for="">
                    <a href="login.html"> Sair</a>
                </label>
            </section>

        </section>
            
        <section class = navBar>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cadastroCliente.php">Cadastro Cliente</a></li>
                    <li><a href="cadastroFuncionario.php">Cadastro Funcionario</a></li>
                    <li><a href="cadastroProduto.php">Cadastro Produto</a></li>
                    <li><a href="">Ver Clientes</a></li>
                    <li><a href="">Ver Funcionarios</a></li>
                    <li><a href="Produtos.php">Ver Produtos</a></li>
                    <li class="dropdown">
                    <a href="#">Admin ▼</a>
                    <ul class="dropdown-menu">
                        <li><a href="cadastroLojas.php">Cadastrar Loja</a></li>
                        <li><a href="verLojas.php">Ver Lojas</a></li>
                        <li><a href="cadastroCupons.php">Cadastrar Cupom</a></li>
                        <li><a href="verCupons.php">Ver Cupons</a></li>
                    </ul>
                </li>               
                </ul>
        </section>
    </header>
</body>
</html>