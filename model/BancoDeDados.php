<?php

class BancoDeDados
{
    private $host;
    private $login;
    private $senha;
    private $dataBase;
    private $conexao;

    public function __construct($host = "localhost", $login = "root", $senha = "", $dataBase = "xhopii")
    {
        $this->host = $host;
        $this->login = $login;
        $this->senha = $senha;
        $this->dataBase = $dataBase;
        $this->conexao = null;
    }

    public function conectarBD()
    {
        if ($this->conexao) {
            return $this->conexao;
        }

        $this->conexao = mysqli_connect($this->host, $this->login, $this->senha, $this->dataBase);

        if (!$this->conexao) {
            die("Erro ao conectar com o banco de dados: " . mysqli_connect_error());
        }

        mysqli_set_charset($this->conexao, "utf8");

        return $this->conexao;
    }

    public function inserirCliente($cliente)
    {
        $conexao = $this->conectarBD();
        $consulta = "INSERT INTO cliente (cpf, nome, sobrenome, dataNascimento, telefone, email, senha, fotoPerfil)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conexao, $consulta);
        $cpf = $cliente->getCpf();
        $nome = $cliente->getNome();
        $sobrenome = $cliente->getSobrenome();
        $dataNascimento = $cliente->getDataNascimento();
        $telefone = $cliente->getTelefone();
        $email = $cliente->getEmail();
        $senha = $cliente->getSenha();
        $fotoPerfil = $cliente->getFotoPerfil();

        mysqli_stmt_bind_param($stmt, "ssssssss", $cpf, $nome, $sobrenome, $dataNascimento, $telefone, $email, $senha, $fotoPerfil);

        return mysqli_stmt_execute($stmt);
    }

    public function inserirFuncionario($funcionario)
    {
        $conexao = $this->conectarBD();
        $consulta = "INSERT INTO funcionario (cpf, nome, sobrenome, dataNascimento, telefone, cargo, salario, email, senha, fotoPerfil)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conexao, $consulta);
        $cpf = $funcionario->getCpf();
        $nome = $funcionario->getNome();
        $sobrenome = $funcionario->getSobrenome();
        $dataNascimento = $funcionario->getDataNascimento();
        $telefone = $funcionario->getTelefone();
        $cargo = $funcionario->getCargo();
        $salario = $funcionario->getSalario();
        $email = $funcionario->getEmail();
        $senha = $funcionario->getSenha();
        $fotoPerfil = $funcionario->getFotoPerfil();

        mysqli_stmt_bind_param($stmt, "ssssssdsss", $cpf, $nome, $sobrenome, $dataNascimento, $telefone, $cargo, $salario, $email, $senha, $fotoPerfil);

        return mysqli_stmt_execute($stmt);
    }

    public function inserirProduto($produto)
    {
        $conexao = $this->conectarBD();
        $idLoja = $produto->getIdLoja();
        $nome = $produto->getNome();
        $fabricante = $produto->getFabricante();
        $descricao = $produto->getDescricao();
        $valor = $produto->getValor();
        $quantidade = $produto->getQuantidade();
        $imagem = $produto->getImagem();

        if ($idLoja === null) {
            $consulta = "INSERT INTO produto (nome, fabricante, descricao, valor, quantidade, imagem)
                         VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $consulta);
            mysqli_stmt_bind_param($stmt, "sssdis", $nome, $fabricante, $descricao, $valor, $quantidade, $imagem);
        } else {
            $consulta = "INSERT INTO produto (id_loja, nome, fabricante, descricao, valor, quantidade, imagem)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $consulta);
            mysqli_stmt_bind_param($stmt, "isssdis", $idLoja, $nome, $fabricante, $descricao, $valor, $quantidade, $imagem);
        }

        return mysqli_stmt_execute($stmt);
    }

    public function inserirLoja($dados)
    {
        $conexao = $this->conectarBD();
        $consulta = "INSERT INTO loja (nome, cnpj, telefone, email, endereco, descricao, logo)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conexao, $consulta);
        mysqli_stmt_bind_param($stmt, "sssssss", $dados["nome"], $dados["cnpj"], $dados["telefone"], $dados["email"], $dados["endereco"], $dados["descricao"], $dados["logo"]);

        return mysqli_stmt_execute($stmt);
    }

    public function inserirCupom($dados)
    {
        $conexao = $this->conectarBD();

        if (empty($dados["id_loja"])) {
            $consulta = "INSERT INTO cupom (codigo, desconto, dataValidade, quantidadeDisponivel, status, imagem)
                         VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $consulta);
            mysqli_stmt_bind_param($stmt, "sdsiss", $dados["codigo"], $dados["desconto"], $dados["dataValidade"], $dados["quantidadeDisponivel"], $dados["status"], $dados["imagem"]);
        } else {
            $consulta = "INSERT INTO cupom (id_loja, codigo, desconto, dataValidade, quantidadeDisponivel, status, imagem)
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $consulta);
            mysqli_stmt_bind_param($stmt, "isdsiss", $dados["id_loja"], $dados["codigo"], $dados["desconto"], $dados["dataValidade"], $dados["quantidadeDisponivel"], $dados["status"], $dados["imagem"]);
        }

        return mysqli_stmt_execute($stmt);
    }
    public function retornarClientePorEmail($email)
    {
    $conexao = $this->conectarBD();

    $consulta = "SELECT * FROM cliente WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conexao, $consulta);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($resultado);
    }

    public function retornarClientePorId($idCliente)
    {
        $conexao = $this->conectarBD();
        $consulta = "SELECT * FROM cliente WHERE id_cliente = ? LIMIT 1";
        $stmt = mysqli_prepare($conexao, $consulta);

        mysqli_stmt_bind_param($stmt, "i", $idCliente);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($resultado);
    }

    public function finalizarCompra($idCliente, $idProduto, $quantidade, $formaPagamento, $enderecoEntrega)
    {
        $conexao = $this->conectarBD();

        try {
            mysqli_begin_transaction($conexao);

            $consultaProduto = "SELECT id_produto, valor, quantidade FROM produto WHERE id_produto = ? LIMIT 1 FOR UPDATE";
            $stmtProduto = mysqli_prepare($conexao, $consultaProduto);
            mysqli_stmt_bind_param($stmtProduto, "i", $idProduto);
            mysqli_stmt_execute($stmtProduto);
            $resultadoProduto = mysqli_stmt_get_result($stmtProduto);
            $produto = mysqli_fetch_assoc($resultadoProduto);

            if (!$produto || $quantidade <= 0 || (int) $produto["quantidade"] < $quantidade) {
                mysqli_rollback($conexao);
                return false;
            }

            $valorUnitario = (float) $produto["valor"];
            $valorTotal = $valorUnitario * $quantidade;
            $status = "Pendente";

            $consultaPedido = "INSERT INTO pedido (id_cliente, valor_total, forma_pagamento, endereco_entrega, status)
                               VALUES (?, ?, ?, ?, ?)";
            $stmtPedido = mysqli_prepare($conexao, $consultaPedido);
            mysqli_stmt_bind_param($stmtPedido, "idsss", $idCliente, $valorTotal, $formaPagamento, $enderecoEntrega, $status);
            mysqli_stmt_execute($stmtPedido);

            $idPedido = mysqli_insert_id($conexao);

            $consultaItem = "INSERT INTO pedido_item (id_pedido, id_produto, quantidade, valor_unitario, subtotal)
                             VALUES (?, ?, ?, ?, ?)";
            $stmtItem = mysqli_prepare($conexao, $consultaItem);
            mysqli_stmt_bind_param($stmtItem, "iiidd", $idPedido, $idProduto, $quantidade, $valorUnitario, $valorTotal);
            mysqli_stmt_execute($stmtItem);

            $consultaEstoque = "UPDATE produto SET quantidade = quantidade - ? WHERE id_produto = ?";
            $stmtEstoque = mysqli_prepare($conexao, $consultaEstoque);
            mysqli_stmt_bind_param($stmtEstoque, "ii", $quantidade, $idProduto);
            mysqli_stmt_execute($stmtEstoque);

            mysqli_commit($conexao);

            return $idPedido;
        } catch (Throwable $erro) {
            mysqli_rollback($conexao);
            return false;
        }
    }

    public function retornarPedidoPorId($idPedido, $idCliente)
    {
        $conexao = $this->conectarBD();
        $consulta = "SELECT pedido.*, pedido_item.quantidade, pedido_item.valor_unitario, pedido_item.subtotal,
                            produto.nome AS nome_produto, produto.imagem
                     FROM pedido
                     INNER JOIN pedido_item ON pedido.id_pedido = pedido_item.id_pedido
                     INNER JOIN produto ON pedido_item.id_produto = produto.id_produto
                     WHERE pedido.id_pedido = ? AND pedido.id_cliente = ?
                     LIMIT 1";
        $stmt = mysqli_prepare($conexao, $consulta);

        mysqli_stmt_bind_param($stmt, "ii", $idPedido, $idCliente);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($resultado);
    }

    public function retornarItensPedido($idPedido, $idCliente)
    {
        $conexao = $this->conectarBD();
        $consulta = "SELECT pedido_item.*, produto.nome AS nome_produto, produto.fabricante, produto.imagem
                     FROM pedido_item
                     INNER JOIN pedido ON pedido_item.id_pedido = pedido.id_pedido
                     INNER JOIN produto ON pedido_item.id_produto = produto.id_produto
                     WHERE pedido_item.id_pedido = ? AND pedido.id_cliente = ?
                     ORDER BY pedido_item.id_item ASC";
        $stmt = mysqli_prepare($conexao, $consulta);
        $itens = array();

        mysqli_stmt_bind_param($stmt, "ii", $idPedido, $idCliente);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        while ($linha = mysqli_fetch_assoc($resultado)) {
            $itens[] = $linha;
        }

        return $itens;
    }

    public function adicionarProdutoCarrinho($idCliente, $idProduto, $quantidade)
    {
        $conexao = $this->conectarBD();
        $idCarrinho = $this->retornarCarrinhoId($idCliente);
        $produto = $this->retornarProdutoPorId($idProduto);

        if (!$produto || $quantidade <= 0) {
            return false;
        }

        $consultaItem = "SELECT quantidade FROM carrinho_item WHERE id_carrinho = ? AND id_produto = ? LIMIT 1";
        $stmtItem = mysqli_prepare($conexao, $consultaItem);
        mysqli_stmt_bind_param($stmtItem, "ii", $idCarrinho, $idProduto);
        mysqli_stmt_execute($stmtItem);
        $resultadoItem = mysqli_stmt_get_result($stmtItem);
        $itemAtual = mysqli_fetch_assoc($resultadoItem);
        $quantidadeAtual = $itemAtual ? (int) $itemAtual["quantidade"] : 0;
        $novaQuantidade = $quantidadeAtual + $quantidade;

        if ($novaQuantidade > (int) $produto["quantidade"]) {
            return false;
        }

        if ($itemAtual) {
            $consulta = "UPDATE carrinho_item SET quantidade = ? WHERE id_carrinho = ? AND id_produto = ?";
            $stmt = mysqli_prepare($conexao, $consulta);
            mysqli_stmt_bind_param($stmt, "iii", $novaQuantidade, $idCarrinho, $idProduto);
        } else {
            $consulta = "INSERT INTO carrinho_item (id_carrinho, id_produto, quantidade) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conexao, $consulta);
            mysqli_stmt_bind_param($stmt, "iii", $idCarrinho, $idProduto, $quantidade);
        }

        return mysqli_stmt_execute($stmt);
    }

    public function retornarItensCarrinho($idCliente)
    {
        $conexao = $this->conectarBD();
        $consulta = "SELECT carrinho_item.id_item, carrinho_item.id_produto, carrinho_item.quantidade,
                            produto.nome, produto.fabricante, produto.valor, produto.imagem,
                            produto.quantidade AS estoque,
                            (carrinho_item.quantidade * produto.valor) AS subtotal
                     FROM carrinho
                     INNER JOIN carrinho_item ON carrinho.id_carrinho = carrinho_item.id_carrinho
                     INNER JOIN produto ON carrinho_item.id_produto = produto.id_produto
                     WHERE carrinho.id_cliente = ?
                     ORDER BY carrinho_item.id_item DESC";
        $stmt = mysqli_prepare($conexao, $consulta);
        $itens = array();

        mysqli_stmt_bind_param($stmt, "i", $idCliente);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);

        while ($linha = mysqli_fetch_assoc($resultado)) {
            $itens[] = $linha;
        }

        return $itens;
    }

    public function atualizarItemCarrinho($idCliente, $idItem, $quantidade)
    {
        if ($quantidade <= 0) {
            return $this->removerItemCarrinho($idCliente, $idItem);
        }

        $conexao = $this->conectarBD();
        $consultaItem = "SELECT carrinho_item.id_item, produto.quantidade AS estoque
                         FROM carrinho
                         INNER JOIN carrinho_item ON carrinho.id_carrinho = carrinho_item.id_carrinho
                         INNER JOIN produto ON carrinho_item.id_produto = produto.id_produto
                         WHERE carrinho.id_cliente = ? AND carrinho_item.id_item = ?
                         LIMIT 1";
        $stmtItem = mysqli_prepare($conexao, $consultaItem);
        mysqli_stmt_bind_param($stmtItem, "ii", $idCliente, $idItem);
        mysqli_stmt_execute($stmtItem);
        $resultadoItem = mysqli_stmt_get_result($stmtItem);
        $item = mysqli_fetch_assoc($resultadoItem);

        if (!$item || $quantidade > (int) $item["estoque"]) {
            return false;
        }

        $consulta = "UPDATE carrinho_item SET quantidade = ? WHERE id_item = ?";
        $stmt = mysqli_prepare($conexao, $consulta);
        mysqli_stmt_bind_param($stmt, "ii", $quantidade, $idItem);

        return mysqli_stmt_execute($stmt);
    }

    public function removerItemCarrinho($idCliente, $idItem)
    {
        $conexao = $this->conectarBD();
        $consulta = "DELETE carrinho_item FROM carrinho_item
                     INNER JOIN carrinho ON carrinho_item.id_carrinho = carrinho.id_carrinho
                     WHERE carrinho.id_cliente = ? AND carrinho_item.id_item = ?";
        $stmt = mysqli_prepare($conexao, $consulta);
        mysqli_stmt_bind_param($stmt, "ii", $idCliente, $idItem);

        return mysqli_stmt_execute($stmt);
    }

    public function finalizarCarrinho($idCliente, $formaPagamento, $enderecoEntrega)
    {
        $conexao = $this->conectarBD();

        try {
            mysqli_begin_transaction($conexao);

            $idCarrinho = $this->retornarCarrinhoId($idCliente, false);

            if (!$idCarrinho) {
                mysqli_rollback($conexao);
                return false;
            }

            $consultaItens = "SELECT carrinho_item.id_produto, carrinho_item.quantidade,
                                     produto.valor, produto.quantidade AS estoque
                              FROM carrinho_item
                              INNER JOIN produto ON carrinho_item.id_produto = produto.id_produto
                              WHERE carrinho_item.id_carrinho = ?
                              FOR UPDATE";
            $stmtItens = mysqli_prepare($conexao, $consultaItens);
            mysqli_stmt_bind_param($stmtItens, "i", $idCarrinho);
            mysqli_stmt_execute($stmtItens);
            $resultadoItens = mysqli_stmt_get_result($stmtItens);
            $itens = array();
            $valorTotal = 0;

            while ($item = mysqli_fetch_assoc($resultadoItens)) {
                if ((int) $item["quantidade"] <= 0 || (int) $item["estoque"] < (int) $item["quantidade"]) {
                    mysqli_rollback($conexao);
                    return false;
                }

                $item["subtotal"] = (float) $item["valor"] * (int) $item["quantidade"];
                $valorTotal += $item["subtotal"];
                $itens[] = $item;
            }

            if (count($itens) == 0) {
                mysqli_rollback($conexao);
                return false;
            }

            $status = "Pendente";
            $consultaPedido = "INSERT INTO pedido (id_cliente, valor_total, forma_pagamento, endereco_entrega, status)
                               VALUES (?, ?, ?, ?, ?)";
            $stmtPedido = mysqli_prepare($conexao, $consultaPedido);
            mysqli_stmt_bind_param($stmtPedido, "idsss", $idCliente, $valorTotal, $formaPagamento, $enderecoEntrega, $status);
            mysqli_stmt_execute($stmtPedido);

            $idPedido = mysqli_insert_id($conexao);

            foreach ($itens as $item) {
                $idProduto = (int) $item["id_produto"];
                $quantidade = (int) $item["quantidade"];
                $valorUnitario = (float) $item["valor"];
                $subtotal = (float) $item["subtotal"];

                $consultaPedidoItem = "INSERT INTO pedido_item (id_pedido, id_produto, quantidade, valor_unitario, subtotal)
                                       VALUES (?, ?, ?, ?, ?)";
                $stmtPedidoItem = mysqli_prepare($conexao, $consultaPedidoItem);
                mysqli_stmt_bind_param($stmtPedidoItem, "iiidd", $idPedido, $idProduto, $quantidade, $valorUnitario, $subtotal);
                mysqli_stmt_execute($stmtPedidoItem);

                $consultaEstoque = "UPDATE produto SET quantidade = quantidade - ? WHERE id_produto = ?";
                $stmtEstoque = mysqli_prepare($conexao, $consultaEstoque);
                mysqli_stmt_bind_param($stmtEstoque, "ii", $quantidade, $idProduto);
                mysqli_stmt_execute($stmtEstoque);
            }

            $consultaLimpar = "DELETE FROM carrinho_item WHERE id_carrinho = ?";
            $stmtLimpar = mysqli_prepare($conexao, $consultaLimpar);
            mysqli_stmt_bind_param($stmtLimpar, "i", $idCarrinho);
            mysqli_stmt_execute($stmtLimpar);

            mysqli_commit($conexao);

            return $idPedido;
        } catch (Throwable $erro) {
            mysqli_rollback($conexao);
            return false;
        }
    }

    public function retornarClientes()
    {
        return $this->buscarTodos("SELECT * FROM cliente ORDER BY id_cliente DESC");
    }

    public function retornarFuncionarios()
    {
        return $this->buscarTodos("SELECT * FROM funcionario ORDER BY id_funcionario DESC");
    }

    public function retornarProdutos()
    {
        return $this->buscarTodos("SELECT * FROM produto ORDER BY id_produto DESC");
    }

    public function retornarProdutoPorId($idProduto)
    {
        $idProduto = (int) $idProduto;
        $conexao = $this->conectarBD();
        $resultado = mysqli_query($conexao, "SELECT * FROM produto WHERE id_produto = " . $idProduto . " LIMIT 1");

        return mysqli_fetch_assoc($resultado);
    }

    public function retornarPrimeiroProduto()
    {
        $conexao = $this->conectarBD();
        $resultado = mysqli_query($conexao, "SELECT * FROM produto ORDER BY id_produto ASC LIMIT 1");

        return mysqli_fetch_assoc($resultado);
    }

    public function retornarLojas()
    {
        return $this->buscarTodos("SELECT * FROM loja ORDER BY id_loja DESC");
    }

    public function retornarCupons()
    {
        return $this->buscarTodos("SELECT * FROM cupom ORDER BY id_cupom DESC");
    }

    private function buscarTodos($consulta)
    {
        $conexao = $this->conectarBD();
        $resultado = mysqli_query($conexao, $consulta);
        $dados = array();

        while ($linha = mysqli_fetch_assoc($resultado)) {
            $dados[] = $linha;
        }

        return $dados;
    }

    private function retornarCarrinhoId($idCliente, $criar = true)
    {
        $conexao = $this->conectarBD();
        $consulta = "SELECT id_carrinho FROM carrinho WHERE id_cliente = ? LIMIT 1";
        $stmt = mysqli_prepare($conexao, $consulta);

        mysqli_stmt_bind_param($stmt, "i", $idCliente);
        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);
        $carrinho = mysqli_fetch_assoc($resultado);

        if ($carrinho) {
            return (int) $carrinho["id_carrinho"];
        }

        if (!$criar) {
            return null;
        }

        $consultaCriar = "INSERT INTO carrinho (id_cliente) VALUES (?)";
        $stmtCriar = mysqli_prepare($conexao, $consultaCriar);
        mysqli_stmt_bind_param($stmtCriar, "i", $idCliente);
        mysqli_stmt_execute($stmtCriar);

        return mysqli_insert_id($conexao);
    }
}

?>
