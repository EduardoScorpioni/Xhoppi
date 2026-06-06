<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("admin", "funcionario"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$cupons = $controller->listarCupons();

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function dataBR($data)
{
    if (empty($data)) {
        return "";
    }

    return date("d/m/Y", strtotime($data));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Vcupons.css">
    <title>Ver Cupons</title>
</head>

<body>
    <?php include "header.php"?>

    <section class="pagina">
        <section class="cupom-card">
            <h2 class="cupom-titulo">Cupons Disponiveis</h2>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Codigo</th>
                        <th>Desconto</th>
                        <th>Validade</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($cupons as $cupom) { ?>
                        <?php $classeStatus = $cupom["statusCalculado"] == "Ativo" ? "ativo" : "expirado"; ?>
                        <tr>
                            <td><?php echo (int) $cupom["id_cupom"]; ?></td>
                            <td><span class="cupom"><?php echo e($cupom["codigo"]); ?></span></td>
                            <td><?php echo number_format((float) $cupom["desconto"], 2, ",", "."); ?>%</td>
                            <td><?php echo dataBR($cupom["dataValidade"]); ?></td>
                            <td><?php echo (int) $cupom["quantidadeDisponivel"]; ?></td>
                            <td class="<?php echo $classeStatus; ?>"><?php echo e($cupom["statusCalculado"]); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </section>

    <?php include "footer.php"?>
</body>
</html>
