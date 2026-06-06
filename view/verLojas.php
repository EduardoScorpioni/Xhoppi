<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("admin", "funcionario"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$lojas = $controller->listarLojas();

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function caminhoImagemLoja($imagem)
{
    if (empty($imagem)) {
        return "../img/Loja.png";
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/VLojas.css">
  <title>Xhopii - Lojas</title>
</head>

    <body>
        <?php include "header.php" ?>
        <section class="pagina">
            <section class="lojas-container">
                <?php foreach ($lojas as $loja) { ?>
                    <section class="loja-card">
                        <img src="<?php echo e(caminhoImagemLoja($loja["logo"])); ?>" alt="<?php echo e($loja["nome"]); ?>">

                        <section class="loja-info">
                            <h2 class="loja-nome"><?php echo e($loja["nome"]); ?></h2>
                            <p class="loja-desc"><?php echo e($loja["descricao"]); ?></p>
                            <p class="loja-desc"><?php echo e($loja["telefone"]); ?></p>
                            <button type="button" class="btn-ver-loja">Ver Loja</button>
                        </section>
                    </section>
                <?php } ?>
            </section>
        </section>
        <?php include "footer.php"?>
    </body>
</html>
