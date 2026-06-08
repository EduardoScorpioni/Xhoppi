<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("gerente", "funcionario"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$clientes = $controller->listarClientes();

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../CSS/Vclientes.css">
        <title>Ver Clientes</title>
    </head>
    <body>
        <?php include "header.php"?>
        <section class="pagina">
            <section class="clientes-card">
                <h2 class="clientes-titulo">Ver Clientes</h2>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Maior de idade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente) { ?>
                            <tr>
                                <td><?php echo (int) $cliente["id_cliente"]; ?></td>
                                <td><?php echo e($cliente["nomeCompleto"]); ?></td>
                                <td><?php echo e($cliente["email"]); ?></td>
                                <td><?php echo e($cliente["telefone"]); ?></td>
                                <td><?php echo e($cliente["maiorIdade"]); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </section>
        <?php include "footer.php"?>
    </body>
</html>
