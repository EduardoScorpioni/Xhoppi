<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/Vcupons.css">
    <title>Ver Cupons</title>
</head>

<body>
    <?php include 'header.php'?>

    <section class="pagina">
    <section class="cupom-card">
        <h2 class="cupom-titulo">Cupons Disponíveis</h2>

        <table class="tabela">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Desconto</th>
                    <th>Validade</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td><span class="cupom">XHOP10</span></td>
                    <td>10%</td>
                    <td>30/04/2026</td>
                    <td class="ativo">Ativo</td>
                </tr>

                <tr>
                    <td>2</td>
                    <td><span class="cupom">FRETEGRATIS</span></td>
                    <td>Frete Grátis</td>
                    <td>15/04/2026</td>
                    <td class="ativo">Ativo</td>
                </tr>

                <tr>
                    <td>3</td>
                    <td><span class="cupom">PROMO20</span></td>
                    <td>20%</td>
                    <td>01/03/2026</td>
                    <td class="expirado">Expirado</td>
                </tr>
            </tbody>
        </table>
    </section>
</section>

    <?php include 'footer.php'?>
</body>
</html>