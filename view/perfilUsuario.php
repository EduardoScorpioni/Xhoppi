<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$usuario = $controller->buscarUsuarioSessao($_SESSION["usuario"]);
$status = isset($_GET["status"]) ? $_GET["status"] : "";

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/perfil.css">
    <title>Xhoppi - Perfil</title>
</head>
<body>
    <?php include "header.php" ?>

    <main class="perfil-pagina">
        <section class="perfil-card">
            <section class="perfil-topo">
                <img src="<?php echo e(caminhoImagemPerfil($usuario["fotoPerfil"])); ?>" alt="<?php echo e($usuario["nome"]); ?>">
                <section>
                    <h1>Meu perfil</h1>
                    <p><?php echo e($_SESSION["usuario"]["tipo"]); ?> - <?php echo e($_SESSION["usuario"]["nivel_acesso"]); ?></p>
                </section>
            </section>

            <?php if ($status == "sucesso") { ?>
                <p class="mensagem sucesso">Perfil atualizado com sucesso.</p>
            <?php } elseif ($status == "erro") { ?>
                <p class="mensagem erro">Nao foi possivel atualizar o perfil. Confira os dados.</p>
            <?php } ?>

            <form action="../processamento/Processamento.php" method="post" enctype="multipart/form-data" class="perfil-form">
                <input type="hidden" name="acao" value="atualizar_perfil">

                <section class="linha-form">
                    <label>
                        Nome
                        <input type="text" name="nome" value="<?php echo e($usuario["nome"]); ?>" required>
                    </label>

                    <label>
                        Sobrenome
                        <input type="text" name="sobrenome" value="<?php echo e($usuario["sobrenome"]); ?>" required>
                    </label>
                </section>

                <section class="linha-form">
                    <label>
                        Telefone
                        <input type="text" name="telefone" value="<?php echo e($usuario["telefone"]); ?>" required>
                    </label>

                    <label>
                        E-mail
                        <input type="email" name="email" value="<?php echo e($usuario["email"]); ?>" required>
                    </label>
                </section>

                <?php if ($_SESSION["usuario"]["tipo"] == "funcionario") { ?>
                    <section class="dados-bloqueados">
                        <p><strong>Cargo:</strong> <?php echo e($usuario["cargo"]); ?></p>
                        <p><strong>Nivel:</strong> <?php echo e($usuario["nivel_acesso"]); ?></p>
                    </section>
                <?php } else { ?>
                    <section class="dados-bloqueados">
                        <p><strong>CPF:</strong> <?php echo e($usuario["cpf"]); ?></p>
                        <p><strong>Nivel:</strong> <?php echo e($usuario["nivel_acesso"]); ?></p>
                    </section>
                <?php } ?>

                <label>
                    Nova senha
                    <input type="password" name="nova_senha" placeholder="Preencha apenas se quiser alterar">
                </label>

                <label>
                    Foto de perfil
                    <input type="file" name="fotoPerfil">
                </label>

                <button type="submit">Salvar perfil</button>
            </form>
        </section>
    </main>

    <?php include "footer.php" ?>
</body>
</html>
