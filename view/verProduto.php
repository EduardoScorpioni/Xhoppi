<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/verProduto.css">
  <title>Xhopii - Produto</title>
</head>

    <body>
        <?php include "header.php"?>
        <section class="pagina">
            <section class="produto-card">
                <section class="galeria">
                    <section class="miniaturas">
                        <section class="thumb active">
                            <img src="../img/produto1.png"/>
                        </section>
                        <section class="thumb">
                            <img src="../img/produto2.png"/>
                        </section>
                        <section class="thumb">
                            <img src="../img/produto3.png"/>
                        </section>
                        <section class="thumb">
                            <img src="../img/produto4.png"/>
                        </section>
                        <section class="thumb">
                            <img src="../img/produto5.png"/>
                        </section>
                    </section>
                    <section class="imagem-principal">
                        <img src="../img/produto1.png" alt="Camiseta Desenvolvedor Front-End CSS" />
                    </section>
                </section>
                <section class="produto-info">
                    <h1 class="produto-titulo">Camisa Desenvolvedor Front-End CSS</h1>
                    <p class="preco">R$56,90</p>
                    <p class="estoque">171 peças disponíveis</p>
                    <hr/>
                    <section>
                        <p class="label-opcao">Modelos:</p>
                        <section class="opcoes-cor">
                            <button class="btn-cor active">Preto</button>
                            <button class="btn-cor">Azul</button>
                            <button class="btn-cor">Verde</button>
                            <button class="btn-cor">Cinza</button>
                            <button class="btn-cor">Rosa</button>
                        </section>
                    </section>
                    <hr/>
                    <section>
                        <p class="label-opcao">Tamanhos:</p>
                        <section class="opcoes-tamanho">
                            <button class="btn-tamanho active">P</button>
                            <button class="btn-tamanho">M</button>
                            <button class="btn-tamanho">G</button>
                            <button class="btn-tamanho">GG</button>
                        </section>
                    </section>
                    <p class="tamanho-selecionado">Tamanho Selecionado:<span>P</span></p>
                    <button class="btn-comprar">Comprar Agora</button>
                </section>
            </section>
        </section>
        <?php include "footer.php"?>
    </body>
</html>