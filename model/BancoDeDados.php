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
}

?>
