<?php

require_once dirname(__DIR__) . "/model/BancoDeDados.php";
require_once dirname(__DIR__) . "/model/Cliente.php";
require_once dirname(__DIR__) . "/model/Funcionario.php";
require_once dirname(__DIR__) . "/model/Produto.php";

class Controller
{
    private $bancoDeDados;

    public function __construct()
    {
        $this->bancoDeDados = new BancoDeDados("localhost", "root", "", "xhopii");
    }

    public function cadastrarCliente($dados, $arquivos = array())
    {
        $senha = $this->prepararSenha($this->campo($dados, "senha"));
        $fotoPerfil = $this->salvarUpload($arquivos, "fotoPerfil", "cliente");

        $cliente = new Cliente(
            $this->campo($dados, "nome"),
            $this->campo($dados, "sobrenome"),
            $this->campo($dados, "cpf"),
            $this->campo($dados, "dataNascimento"),
            $this->campo($dados, "telefone"),
            $this->campo($dados, "email"),
            $senha,
            $fotoPerfil
        );

        return $this->bancoDeDados->inserirCliente($cliente);
    }

    public function cadastrarFuncionario($dados, $arquivos = array())
    {
        $senha = $this->prepararSenha($this->campo($dados, "senha"));
        $fotoPerfil = $this->salvarUpload($arquivos, "fotoPerfil", "funcionario");

        $funcionario = new Funcionario(
            $this->campo($dados, "cpf"),
            $this->campo($dados, "nome"),
            $this->campo($dados, "sobrenome"),
            $this->campo($dados, "dataNascimento"),
            $this->campo($dados, "telefone"),
            $this->campo($dados, "cargo"),
            $this->numero($this->campo($dados, "salario")),
            $this->campo($dados, "email"),
            $senha,
            $fotoPerfil
        );

        $nivelAcesso = $this->campo($dados, "nivel_acesso", "funcionario");

        if ($nivelAcesso != "admin") {
            $nivelAcesso = "funcionario";
        }

        return $this->bancoDeDados->inserirFuncionario($funcionario, $nivelAcesso);
    }

    public function cadastrarProduto($dados, $arquivos = array())
    {
        $imagem = $this->salvarUpload($arquivos, "imagem", "produto");

        $produto = new Produto(
            $this->campo($dados, "nome"),
            $this->campo($dados, "fabricante"),
            $this->campo($dados, "descricao"),
            $this->numero($this->campo($dados, "valor")),
            (int) $this->campo($dados, "quantidade", 0),
            $imagem,
            $this->campo($dados, "id_loja")
        );

        return $this->bancoDeDados->inserirProduto($produto);
    }

    public function cadastrarLoja($dados, $arquivos = array())
    {
        $logo = $this->salvarUpload($arquivos, "logo", "loja");

        $loja = array(
            "nome" => $this->campo($dados, "nome"),
            "cnpj" => $this->campo($dados, "cnpj"),
            "telefone" => $this->campo($dados, "telefone"),
            "email" => $this->campo($dados, "email"),
            "endereco" => $this->campo($dados, "endereco"),
            "descricao" => $this->campo($dados, "descricao"),
            "logo" => $logo
        );

        return $this->bancoDeDados->inserirLoja($loja);
    }

    public function cadastrarCupom($dados, $arquivos = array())
    {
        $imagem = $this->salvarUpload($arquivos, "imagem", "cupom");

        $cupom = array(
            "id_loja" => $this->campo($dados, "id_loja"),
            "codigo" => strtoupper($this->campo($dados, "codigo")),
            "desconto" => $this->numero($this->campo($dados, "desconto")),
            "dataValidade" => $this->campo($dados, "dataValidade"),
            "quantidadeDisponivel" => (int) $this->campo($dados, "quantidadeDisponivel", 0),
            "status" => "Ativo",
            "imagem" => $imagem
        );

        return $this->bancoDeDados->inserirCupom($cupom);
    }

    public function autenticarUsuario($dados)
    {
    $email = $this->campo($dados, "email");
    $senha = $this->campo($dados, "senha");

    if ($email == "" || $senha == "") {
        return false;
    }

    $cliente = $this->bancoDeDados->retornarClientePorEmail($email);

    if ($cliente && $this->senhaCorreta($senha, $cliente["senha"])) {
        $cliente["tipo_usuario"] = "cliente";
        $cliente["id_usuario"] = $cliente["id_cliente"];
        $cliente["id_funcionario"] = null;
        $cliente["nivel_acesso"] = $cliente["nivel_acesso"] == "" ? "cliente" : $cliente["nivel_acesso"];

        return $cliente;
    }

    $funcionario = $this->bancoDeDados->retornarFuncionarioPorEmail($email);

    if ($funcionario && $this->senhaCorreta($senha, $funcionario["senha"])) {
        $funcionario["tipo_usuario"] = "funcionario";
        $funcionario["id_usuario"] = $funcionario["id_funcionario"];
        $funcionario["id_cliente"] = null;
        $funcionario["nivel_acesso"] = $funcionario["nivel_acesso"] == "" ? "funcionario" : $funcionario["nivel_acesso"];

        return $funcionario;
    }

    return false;
    }

    public function autenticarCliente($dados)
    {
        return $this->autenticarUsuario($dados);
    }

    public function listarClientes()
    {
        $clientes = $this->bancoDeDados->retornarClientes();

        foreach ($clientes as $indice => $linha) {
            $cliente = new Cliente($linha["nome"], $linha["sobrenome"], $linha["cpf"], $linha["dataNascimento"], $linha["telefone"], $linha["email"], $linha["senha"], $linha["fotoPerfil"], $linha["id_cliente"]);
            $clientes[$indice]["nomeCompleto"] = $cliente->getNomeCompleto();
            $clientes[$indice]["maiorIdade"] = $cliente->verificarMaiorIdade() ? "Sim" : "Nao";
        }

        return $clientes;
    }

    public function listarFuncionarios()
    {
        $funcionarios = $this->bancoDeDados->retornarFuncionarios();

        foreach ($funcionarios as $indice => $linha) {
            $funcionario = new Funcionario($linha["cpf"], $linha["nome"], $linha["sobrenome"], $linha["dataNascimento"], $linha["telefone"], $linha["cargo"], $linha["salario"], $linha["email"], $linha["senha"], $linha["fotoPerfil"], $linha["id_funcionario"]);
            $funcionarios[$indice]["nomeCompleto"] = $funcionario->getNomeCompleto();
            $funcionarios[$indice]["salarioAnual"] = $funcionario->calcularSalarioAnual();
        }

        return $funcionarios;
    }

    public function listarProdutos()
    {
        return $this->bancoDeDados->retornarProdutos();
    }

    public function buscarCliente($idCliente)
    {
        return $this->bancoDeDados->retornarClientePorId((int) $idCliente);
    }

    public function buscarFuncionario($idFuncionario)
    {
        return $this->bancoDeDados->retornarFuncionarioPorId((int) $idFuncionario);
    }

    public function buscarUsuarioSessao($usuario)
    {
        if (!isset($usuario["tipo"])) {
            return false;
        }

        if ($usuario["tipo"] == "cliente") {
            return $this->buscarCliente($usuario["id_cliente"]);
        }

        return $this->buscarFuncionario($usuario["id_funcionario"]);
    }

    public function atualizarPerfil($dados, $arquivos, $usuario)
    {
        $usuarioAtual = $this->buscarUsuarioSessao($usuario);

        if (!$usuarioAtual) {
            return false;
        }

        $fotoPerfil = $this->salvarUpload($arquivos, "fotoPerfil", "perfil");

        if ($fotoPerfil == null) {
            $fotoPerfil = $usuarioAtual["fotoPerfil"];
        }

        $senha = $this->campo($dados, "nova_senha");

        if ($senha == "") {
            $senha = $usuarioAtual["senha"];
        } else {
            $senha = $this->prepararSenha($senha);
        }

        $dadosPerfil = array(
            "nome" => $this->campo($dados, "nome"),
            "sobrenome" => $this->campo($dados, "sobrenome"),
            "telefone" => $this->campo($dados, "telefone"),
            "email" => $this->campo($dados, "email"),
            "senha" => $senha,
            "fotoPerfil" => $fotoPerfil
        );

        if ($usuario["tipo"] == "cliente") {
            return $this->bancoDeDados->atualizarPerfilCliente($usuario["id_cliente"], $dadosPerfil);
        }

        return $this->bancoDeDados->atualizarPerfilFuncionario($usuario["id_funcionario"], $dadosPerfil);
    }

    public function buscarProduto($idProduto = null)
    {
        if ($idProduto) {
            $produto = $this->bancoDeDados->retornarProdutoPorId($idProduto);
        } else {
            $produto = $this->bancoDeDados->retornarPrimeiroProduto();
        }

        if ($produto) {
            $produtoModel = new Produto($produto["nome"], $produto["fabricante"], $produto["descricao"], $produto["valor"], $produto["quantidade"], $produto["imagem"], $produto["id_loja"], $produto["id_produto"]);
            $produto["valorComCupom10"] = $produtoModel->calcularValorComDesconto(10);
        }

        return $produto;
    }

    public function finalizarCompra($dados, $idCliente)
    {
        $idProduto = (int) $this->campo($dados, "id_produto", 0);
        $quantidade = (int) $this->campo($dados, "quantidade", 1);
        $formaPagamento = $this->campo($dados, "forma_pagamento");
        $enderecoEntrega = $this->campo($dados, "endereco_entrega");

        if ($idProduto <= 0 || $idCliente <= 0 || $quantidade <= 0 || $formaPagamento == "" || $enderecoEntrega == "") {
            return false;
        }

        return $this->bancoDeDados->finalizarCompra($idCliente, $idProduto, $quantidade, $formaPagamento, $enderecoEntrega);
    }

    public function buscarPedido($idPedido, $idCliente)
    {
        return $this->bancoDeDados->retornarPedidoPorId((int) $idPedido, (int) $idCliente);
    }

    public function buscarItensPedido($idPedido, $idCliente)
    {
        return $this->bancoDeDados->retornarItensPedido((int) $idPedido, (int) $idCliente);
    }

    public function adicionarAoCarrinho($dados, $idCliente)
    {
        $idProduto = (int) $this->campo($dados, "id_produto", 0);
        $quantidade = (int) $this->campo($dados, "quantidade", 1);

        if ($idCliente <= 0 || $idProduto <= 0 || $quantidade <= 0) {
            return false;
        }

        return $this->bancoDeDados->adicionarProdutoCarrinho($idCliente, $idProduto, $quantidade);
    }

    public function listarItensCarrinho($idCliente)
    {
        return $this->bancoDeDados->retornarItensCarrinho((int) $idCliente);
    }

    public function atualizarCarrinho($dados, $idCliente)
    {
        $idItem = (int) $this->campo($dados, "id_item", 0);
        $quantidade = (int) $this->campo($dados, "quantidade", 1);

        if ($idCliente <= 0 || $idItem <= 0) {
            return false;
        }

        return $this->bancoDeDados->atualizarItemCarrinho($idCliente, $idItem, $quantidade);
    }

    public function removerDoCarrinho($dados, $idCliente)
    {
        $idItem = (int) $this->campo($dados, "id_item", 0);

        if ($idCliente <= 0 || $idItem <= 0) {
            return false;
        }

        return $this->bancoDeDados->removerItemCarrinho($idCliente, $idItem);
    }

    public function finalizarCarrinho($dados, $idCliente)
    {
        $formaPagamento = $this->campo($dados, "forma_pagamento");
        $enderecoEntrega = $this->campo($dados, "endereco_entrega");

        if ($idCliente <= 0 || $formaPagamento == "" || $enderecoEntrega == "") {
            return false;
        }

        return $this->bancoDeDados->finalizarCarrinho($idCliente, $formaPagamento, $enderecoEntrega);
    }

    public function calcularTotalCarrinho($itens)
    {
        $total = 0;

        foreach ($itens as $item) {
            $total += (float) $item["subtotal"];
        }

        return $total;
    }

    public function listarLojas()
    {
        return $this->bancoDeDados->retornarLojas();
    }

    public function listarCupons()
    {
        $cupons = $this->bancoDeDados->retornarCupons();
        $hoje = date("Y-m-d");

        foreach ($cupons as $indice => $linha) {
            $cupons[$indice]["statusCalculado"] = ($linha["dataValidade"] < $hoje || strtolower($linha["status"]) == "expirado") ? "Expirado" : "Ativo";
        }

        return $cupons;
    }

    private function campo($dados, $campo, $padrao = "")
    {
        return isset($dados[$campo]) ? trim($dados[$campo]) : $padrao;
    }

    private function numero($valor)
    {
        $valor = trim($valor);

        if (strpos($valor, ",") !== false) {
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor);
        }

        return (float) $valor;
    }

    private function prepararSenha($senha)
    {
        if ($senha == "") {
            return "";
        }

        return password_hash($senha, PASSWORD_DEFAULT);
    }

    private function senhaCorreta($senhaDigitada, $senhaBanco)
    {
        return password_verify($senhaDigitada, $senhaBanco) || $senhaDigitada == $senhaBanco;
    }

    private function salvarUpload($arquivos, $campo, $prefixo)
    {
        if (!isset($arquivos[$campo]) || $arquivos[$campo]["error"] != UPLOAD_ERR_OK) {
            return null;
        }

        $nomeOriginal = $arquivos[$campo]["name"];
        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        $nomeBase = pathinfo($nomeOriginal, PATHINFO_FILENAME);
        $nomeBase = preg_replace("/[^a-zA-Z0-9_-]/", "_", $nomeBase);
        $nomeArquivo = $prefixo . "_" . time() . "_" . $nomeBase;

        if ($extensao != "") {
            $nomeArquivo .= "." . strtolower($extensao);
        }

        $caminhoRelativo = "uploads/" . $nomeArquivo;
        $caminhoAbsoluto = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $caminhoRelativo);
        $pasta = dirname($caminhoAbsoluto);

        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        if (move_uploaded_file($arquivos[$campo]["tmp_name"], $caminhoAbsoluto)) {
            return $caminhoRelativo;
        }

        return null;
    }
}

?>
