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

        return $this->bancoDeDados->inserirFuncionario($funcionario);
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

    public function autenticarCliente($dados)
    {
    $email = $this->campo($dados, "email");
    $senha = $this->campo($dados, "senha");

    if ($email == "" || $senha == "") {
        return false;
    }

    $cliente = $this->bancoDeDados->retornarClientePorEmail($email);

    if (!$cliente) {
        return false;
    }

    $senhaBanco = $cliente["senha"];

    $senhaCorreta = password_verify($senha, $senhaBanco) || $senha == $senhaBanco;

    if (!$senhaCorreta) {
        return false;
    }

    return $cliente;
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
