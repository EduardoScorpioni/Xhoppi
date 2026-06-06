<?php
require_once dirname(__DIR__) . "/processamento/verificarLogin.php";
exigirNivel(array("admin"));
require_once dirname(__DIR__) . "/controller/Controller.php";

$controller = new Controller();
$funcionarios = $controller->listarFuncionarios();

function e($valor)
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function moeda($valor)
{
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../CSS/Vfuncionario.css">
        <title>Ver Funcionarios</title>
    </head>
    
    <body>
        <?php include "header.php"?>
        <section class="pagina">
            <section class="funcionario-card">
                <h2 class="funcionario-titulo">Ver Funcionarios</h2>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Salario anual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($funcionarios as $funcionario) { ?>
                            <tr>
                                <td><?php echo (int) $funcionario["id_funcionario"]; ?></td>
                                <td><?php echo e($funcionario["nomeCompleto"]); ?></td>
                                <td><?php echo e($funcionario["cargo"]); ?></td>
                                <td><?php echo e($funcionario["email"]); ?></td>
                                <td><?php echo e($funcionario["telefone"]); ?></td>
                                <td><?php echo moeda($funcionario["salarioAnual"]); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </section>
        <?php include "footer.php"?>
    </body>
</html>
