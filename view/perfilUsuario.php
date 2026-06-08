<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$usuario = $controller->buscarUsuarioSessao($_SESSION["usuario"]);

if (!$usuario) {
    header("Location: login.php");
    exit;
}

$status = isset($_GET["status"]) ? $_GET["status"] : "";
$ehCliente = $_SESSION["usuario"]["tipo"] == "cliente";
$nomeCompleto = trim($usuario["nome"] . " " . $usuario["sobrenome"]);

function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

function caminhoImagemPerfil($imagem)
{
    if (empty($imagem)) {
        return "../img/logo.png";
    }

    if (strpos($imagem, "http") === 0 || strpos($imagem, "../") === 0) {
        return $imagem;
    }

    return "../" . ltrim($imagem, "/");
}

function nomeTipoUsuario($tipo)
{
    if ($tipo == "cliente") {
        return "Cliente";
    }

    if ($tipo == "funcionario") {
        return "Funcionario";
    }

    return ucfirst($tipo);
}

function nomeNivelAcesso($nivelAcesso)
{
    if ($nivelAcesso == "admin") {
        $nivelAcesso = "gerente";
    }

    if ($nivelAcesso == "gerente") {
        return "Gerente";
    }

    if ($nivelAcesso == "funcionario") {
        return "Funcionario";
    }

    if ($nivelAcesso == "cliente") {
        return "Cliente";
    }

    return ucfirst($nivelAcesso);
}

function dataBrasil($data)
{
    if (empty($data)) {
        return "Nao informado";
    }

    $tempo = strtotime($data);

    if (!$tempo) {
        return "Nao informado";
    }

    return date("d/m/Y", $tempo);
}

function dataInput($data)
{
    if (empty($data)) {
        return "";
    }

    $tempo = strtotime($data);

    if (!$tempo) {
        return "";
    }

    return date("Y-m-d", $tempo);
}

function moeda($valor)
{
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}

function textoOuPadrao($valor)
{
    return trim((string) $valor) == "" ? "Nao informado" : $valor;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/perfil.css?v=2">
    <title>Xhoppi - Perfil</title>
</head>
<body>
    <?php include "header.php" ?>

    <main class="perfil-pagina">
        <section class="perfil-hero">
            <section class="perfil-identidade">
                <img class="perfil-avatar" src="<?php echo e(caminhoImagemPerfil($usuario["fotoPerfil"])); ?>" alt="<?php echo e($nomeCompleto); ?>">
                <section>
                    <span class="perfil-etiqueta"><?php echo e(nomeNivelAcesso($_SESSION["usuario"]["nivel_acesso"])); ?></span>
                    <h1><?php echo e($nomeCompleto); ?></h1>
                    <p><?php echo e($usuario["email"]); ?></p>
                </section>
            </section>
        </section>

        <?php if ($status == "sucesso") { ?>
            <p class="mensagem sucesso">Perfil atualizado com sucesso.</p>
        <?php } elseif ($status == "erro") { ?>
            <p class="mensagem erro">Nao foi possivel atualizar o perfil. Confira os dados.</p>
        <?php } ?>

        <section class="perfil-layout">
            <aside class="perfil-painel">
                <section class="perfil-bloco">
                    <h2>Dados da conta</h2>
                    <dl class="perfil-dados">
                        <div>
                            <dt>Tipo</dt>
                            <dd><?php echo e(nomeTipoUsuario($_SESSION["usuario"]["tipo"])); ?></dd>
                        </div>

                        <div>
                            <dt>Nivel</dt>
                            <dd><?php echo e(nomeNivelAcesso($_SESSION["usuario"]["nivel_acesso"])); ?></dd>
                        </div>

                        <div>
                            <dt>CPF</dt>
                            <dd><?php echo e($usuario["cpf"]); ?></dd>
                        </div>

                        <div>
                            <dt>Nascimento</dt>
                            <dd><?php echo e(dataBrasil($usuario["dataNascimento"])); ?></dd>
                        </div>

                        <?php if (!$ehCliente) { ?>
                            <div>
                                <dt>Cargo</dt>
                                <dd><?php echo e(textoOuPadrao($usuario["cargo"])); ?></dd>
                            </div>

                            <div>
                                <dt>Salario</dt>
                                <dd><?php echo e(moeda($usuario["salario"])); ?></dd>
                            </div>
                        <?php } else { ?>
                            <div>
                                <dt>Conta</dt>
                                <dd>Comprador Xhoppi</dd>
                            </div>
                        <?php } ?>

                        <div>
                            <dt>Cadastrado em</dt>
                            <dd><?php echo e(dataBrasil($usuario["criado_em"])); ?></dd>
                        </div>
                    </dl>
                </section>
            </aside>

            <section class="perfil-card">
                <section class="secao-titulo">
                    <span>Editar perfil</span>
                    <h2>Informacoes pessoais</h2>
                </section>

                <form action="../processamento/Processamento.php" method="post" enctype="multipart/form-data" class="perfil-form">
                    <input type="hidden" name="acao" value="atualizar_perfil">

                    <section class="form-grid">
                        <label>
                            Nome
                            <input type="text" name="nome" value="<?php echo e($usuario["nome"]); ?>" required>
                        </label>

                        <label>
                            Sobrenome
                            <input type="text" name="sobrenome" value="<?php echo e($usuario["sobrenome"]); ?>" required>
                        </label>

                        <label>
                            Data de nascimento
                            <input type="date" name="dataNascimento" value="<?php echo e(dataInput($usuario["dataNascimento"])); ?>" required>
                        </label>

                        <label>
                            Telefone
                            <input type="text" name="telefone" value="<?php echo e($usuario["telefone"]); ?>" required>
                        </label>

                        <label class="largura-total">
                            E-mail
                            <input type="email" name="email" value="<?php echo e($usuario["email"]); ?>" required>
                        </label>
                    </section>

                    <section class="perfil-upload">
                        <img src="<?php echo e(caminhoImagemPerfil($usuario["fotoPerfil"])); ?>" alt="<?php echo e($nomeCompleto); ?>">
                        <label>
                            Foto de perfil
                            <input type="file" name="fotoPerfil" accept="image/*">
                        </label>
                    </section>

                    <section class="seguranca-bloco">
                        <h3>Seguranca</h3>
                        <label>
                            Nova senha
                            <input type="password" name="nova_senha" placeholder="Preencha apenas se quiser alterar">
                        </label>
                    </section>

                    <button type="submit">Salvar perfil</button>
                </form>
            </section>
        </section>
    </main>

    <?php include "footer.php" ?>
</body>
</html>
