<?php

require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$acao = isset($_POST["acao"]) ? $_POST["acao"] : "";

switch ($acao) {
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
