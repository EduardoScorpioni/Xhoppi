<?php

session_start();

require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$acao = isset($_POST["acao"]) ? $_POST["acao"] : (isset($_GET["acao"]) ? $_GET["acao"] : "");

switch ($acao) {
    case "login":
    $usuario = $controller->autenticarUsuario($_POST);

    if ($usuario) {
        $_SESSION["usuario"] = array(
            "id" => $usuario["id_usuario"],
            "id_cliente" => $usuario["id_cliente"],
            "id_funcionario" => $usuario["id_funcionario"],
            "nome" => $usuario["nome"],
            "email" => $usuario["email"],
            "tipo" => $usuario["tipo_usuario"],
            "nivel_acesso" => $usuario["nivel_acesso"]
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
    case "atualizar_perfil":
        if (!isset($_SESSION["usuario"])) {
            header("Location: ../view/login.php");
            exit;
        }

        $sucesso = $controller->atualizarPerfil($_POST, $_FILES, $_SESSION["usuario"]);

        if ($sucesso) {
            $_SESSION["usuario"]["nome"] = isset($_POST["nome"]) ? trim($_POST["nome"]) : $_SESSION["usuario"]["nome"];
            $_SESSION["usuario"]["email"] = isset($_POST["email"]) ? trim($_POST["email"]) : $_SESSION["usuario"]["email"];
        }

        redirecionar("perfilUsuario.php", $sucesso);
        break;
    case "cadastrar_cliente":
        redirecionar("cadastroCliente.php", $controller->cadastrarCliente($_POST, $_FILES));
        break;

    case "cadastrar_funcionario":
        exigirNivelProcessamento(array("admin"));
        redirecionar("cadastroFuncionario.php", $controller->cadastrarFuncionario($_POST, $_FILES));
        break;

    case "cadastrar_produto":
        exigirNivelProcessamento(array("admin", "funcionario"));
        redirecionar("cadastroProduto.php", $controller->cadastrarProduto($_POST, $_FILES));
        break;

    case "cadastrar_loja":
        exigirNivelProcessamento(array("admin", "funcionario"));
        redirecionar("cadastroLojas.php", $controller->cadastrarLoja($_POST, $_FILES));
        break;

    case "cadastrar_cupom":
        exigirNivelProcessamento(array("admin", "funcionario"));
        redirecionar("cadastroCupons.php", $controller->cadastrarCupom($_POST, $_FILES));
        break;

    case "finalizar_compra":
        exigirNivelProcessamento(array("cliente"));

        $idProduto = isset($_POST["id_produto"]) ? (int) $_POST["id_produto"] : 0;
        $idPedido = $controller->finalizarCompra($_POST, $_SESSION["usuario"]["id_cliente"]);

        if ($idPedido) {
            header("Location: ../view/finalizarCompra.php?status=sucesso&pedido=" . $idPedido);
            exit;
        }

        header("Location: ../view/finalizarCompra.php?id=" . $idProduto . "&status=erro");
        exit;

    case "adicionar_carrinho":
        exigirNivelProcessamento(array("cliente"));

        $idProduto = isset($_POST["id_produto"]) ? (int) $_POST["id_produto"] : 0;
        $sucesso = $controller->adicionarAoCarrinho($_POST, $_SESSION["usuario"]["id_cliente"]);
        $status = $sucesso ? "adicionado" : "erro";

        header("Location: ../view/carrinho.php?status=" . $status . "&produto=" . $idProduto);
        exit;

    case "atualizar_carrinho":
        exigirNivelProcessamento(array("cliente"));

        redirecionar("carrinho.php", $controller->atualizarCarrinho($_POST, $_SESSION["usuario"]["id_cliente"]));
        break;

    case "remover_carrinho":
        exigirNivelProcessamento(array("cliente"));

        redirecionar("carrinho.php", $controller->removerDoCarrinho($_POST, $_SESSION["usuario"]["id_cliente"]));
        break;

    case "finalizar_carrinho":
        exigirNivelProcessamento(array("cliente"));

        $idPedido = $controller->finalizarCarrinho($_POST, $_SESSION["usuario"]["id_cliente"]);

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

function exigirNivelProcessamento($niveisPermitidos)
{
    if (!isset($_SESSION["usuario"])) {
        header("Location: ../view/login.php");
        exit;
    }

    $nivelUsuario = isset($_SESSION["usuario"]["nivel_acesso"]) ? $_SESSION["usuario"]["nivel_acesso"] : "";

    if (!in_array($nivelUsuario, $niveisPermitidos)) {
        header("Location: ../index.php?acesso=negado");
        exit;
    }
}

?>
