<?php
require_once __DIR__ . "/processamento/verificarLogin.php";
require_once __DIR__ . "/controller/Controller.php";

$controller = new Controller();
$produtos = $controller->listarProdutos();
$acessoNegado = isset($_GET["acesso"]) && $_GET["acesso"] == "negado";

function escaparIndex($valor){
  return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

function moedaIndex($valor){
  return "R$ " . number_format((float) $valor, 2, ",", ".");
}

function imagemProdutoIndex($imagem){
  if(empty($imagem)){
    return "img/produto1.png";
  }
  if(strpos($imagem, "http") === 0){
    return $imagem;
  }

  return ltrim($imagem, "/");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/index.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <title>Document</title>
    </head>
    <body>
      <?php include "view/header.php"?>
      <?php if ($acessoNegado) { ?>
        <p class="mensagem-acesso">Voce nao tem permissao para acessar essa pagina.</p>
      <?php } ?>
        <section id="carouselExampleIndicators" class="carousel slide">
            <section class="carousel-indicators">
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </section>
            <section class="carousel-inner">
              <section class="carousel-item active">
                <img src="img/img1.png" class="d-block w-100" alt="...">
              </section>
              <section class="carousel-item">
                <img src="img/img2.png" class="d-block w-100" alt="...">
              </section>
              <section class="carousel-item">
                <img src="img/img3.png" class="d-block w-100" alt="...">
              </section>
              <section class="carousel-item">
                <img src="img/img4.png" class="d-block w-100" alt="...">
              </section>
            </section>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </section>
          <section class="banner">
            <img src="img/banner.png" alt="">
          </section>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
          <h2 class="titulo-secao">DESCOBERTAS DO DIA</h2>
          <hr class ="linha-capenga">
          <section class = "produtos">
            <?php foreach($produtos as $produto) {?>
              <section class="card">
                <a href="view/verProduto.php?id=<?php echo (int) $produto["id_produto"];?>">
                  <img src="<?php echo escaparIndex(imagemProdutoIndex($produto["imagem"]));?>" alt="<?php echo escaparIndex($produto["nome"]);?>">
                  <p class="titulo">
                    <?php echo escaparIndex($produto["nome"]);?>
                  </p>
                  <section class="info">
                    <span class="preco">
                      <?php echo moedaIndex($produto["valor"]); ?>
                    </span>
                    <span class="estoque">
                      <?php echo (int) $produto["quantidade"]; ?> disponiveis
                    </span>
                  </section>
                </a>
              </section>
            <?php }?>
          </section>
        
        
          <?php include_once "view/footer.php" ?> 
      </body>
</html>
