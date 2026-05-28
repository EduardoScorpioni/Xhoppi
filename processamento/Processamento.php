<?php

session_start();

require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$acao = isset($_POST["acao"]) ? $_POST["acao"] : (isset($_GET["acao"]) ? $_GET["acao"] : "");

switch ($acao) {
    case "login":
    $cliente = $controller->autenticarCliente($_POST);

    if ($cliente) {
        $_SESSION["usuario"] = array(
            "id" => $cliente["id_cliente"],
            "nome" => $cliente["nome"],
            "email" => $cliente["email"]
        );

        header("Location: ../index.php");
        exit;
    }

    header("Location: ../view/login.php?status=erro");
    exit;
    case "sair":
    session_destroy();
    header("Location: ../view/login.php");
    exit;
    case "cadastrar_cliente":
        redirecionar("cadastroCliente.php", $controller->cadastrarCliente($_POST, $_FILES));
        break;

    case "cadastrar_funcionario":
        redirecionar("cadastroFuncionario.php", $controller->cadastrarFuncionario($_POST, $_FILES));
        break;

    case "cadastrar_produto":
        redirecionar("cadastroProduto.php", $controller->cadastrarProduto($_POST, $_FILES));
        break;

    case "cadastrar_loja":
        redirecionar("cadastroLojas.php", $controller->cadastrarLoja($_POST, $_FILES));
        break;

    case "cadastrar_cupom":
        redirecionar("cadastroCupons.php", $controller->cadastrarCupom($_POST, $_FILES));
        break;

    default:
        header("Location: ../index.php");
        exit;
}

function redirecionar($pagina, $sucesso)
{
    $status = $sucesso ? "sucesso" : "erro";
    header("Location: ../view/" . $pagina . "?status=" . $status);
    exit;
}

?>
