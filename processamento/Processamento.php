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

    case "finalizar_compra":
        if (!isset($_SESSION["usuario"])) {
            header("Location: ../view/login.php");
            exit;
        }

        $idProduto = isset($_POST["id_produto"]) ? (int) $_POST["id_produto"] : 0;
        $idPedido = $controller->finalizarCompra($_POST, $_SESSION["usuario"]["id"]);

        if ($idPedido) {
            header("Location: ../view/finalizarCompra.php?status=sucesso&pedido=" . $idPedido);
            exit;
        }

        header("Location: ../view/finalizarCompra.php?id=" . $idProduto . "&status=erro");
        exit;

    case "adicionar_carrinho":
        if (!isset($_SESSION["usuario"])) {
            header("Location: ../view/login.php");
            exit;
        }

        $idProduto = isset($_POST["id_produto"]) ? (int) $_POST["id_produto"] : 0;
        $sucesso = $controller->adicionarAoCarrinho($_POST, $_SESSION["usuario"]["id"]);
        $status = $sucesso ? "adicionado" : "erro";

        header("Location: ../view/carrinho.php?status=" . $status . "&produto=" . $idProduto);
        exit;

    case "atualizar_carrinho":
        if (!isset($_SESSION["usuario"])) {
            header("Location: ../view/login.php");
            exit;
        }

        redirecionar("carrinho.php", $controller->atualizarCarrinho($_POST, $_SESSION["usuario"]["id"]));
        break;

    case "remover_carrinho":
        if (!isset($_SESSION["usuario"])) {
            header("Location: ../view/login.php");
            exit;
        }

        redirecionar("carrinho.php", $controller->removerDoCarrinho($_POST, $_SESSION["usuario"]["id"]));
        break;

    case "finalizar_carrinho":
        if (!isset($_SESSION["usuario"])) {
            header("Location: ../view/login.php");
            exit;
        }

        $idPedido = $controller->finalizarCarrinho($_POST, $_SESSION["usuario"]["id"]);

        if ($idPedido) {
            header("Location: ../view/finalizarCompra.php?status=sucesso&pedido=" . $idPedido);
            exit;
        }

        header("Location: ../view/carrinho.php?status=erro_finalizar");
        exit;

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
