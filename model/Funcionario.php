<?php

class Funcionario
{
    private $id;
    private $cpf;
    private $nome;
    private $sobrenome;
    private $dataNascimento;
    private $telefone;
    private $cargo;
    private $salario;
    private $email;
    private $senha;
    private $fotoPerfil;

    public function __construct($cpf, $nome, $sobrenome, $dataNascimento, $telefone, $cargo, $salario, $email, $senha = "", $fotoPerfil = null, $id = null)
    {
        $this->id = $id;
        $this->setCpf($cpf);
        $this->setNome($nome);
        $this->setSobrenome($sobrenome);
        $this->setDataNascimento($dataNascimento);
        $this->setTelefone($telefone);
        $this->setCargo($cargo);
        $this->setSalario($salario);
        $this->setEmail($email);
        $this->setSenha($senha);
        $this->setFotoPerfil($fotoPerfil);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = trim($cpf);
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = trim($nome);
    }

    public function getSobrenome()
    {
        return $this->sobrenome;
    }

    public function setSobrenome($sobrenome)
    {
        $this->sobrenome = trim($sobrenome);
    }

    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = trim($telefone);
    }

    public function getCargo()
    {
        return $this->cargo;
    }

    public function setCargo($cargo)
    {
        $this->cargo = trim($cargo);
    }

    public function getSalario()
    {
        return $this->salario;
    }

    public function setSalario($salario)
    {
        $this->salario = (float) $salario;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getFotoPerfil()
    {
        return $this->fotoPerfil;
    }

    public function setFotoPerfil($fotoPerfil)
    {
        $this->fotoPerfil = $fotoPerfil;
    }

    public function getNomeCompleto()
    {
        return trim($this->nome . " " . $this->sobrenome);
    }

    public function calcularSalarioAnual()
    {
        return $this->salario * 12;
    }

    public function aplicarAumento($percentual)
    {
        $this->salario = $this->salario + ($this->salario * ((float) $percentual / 100));
    }

    public function imprimir()
    {
        return $this->getNomeCompleto() . " - " . $this->cargo . " - R$ " . number_format($this->salario, 2, ",", ".");
    }
}

?>
