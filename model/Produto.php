<?php

class Produto
{
    private $id;
    private $idLoja;
    private $nome;
    private $fabricante;
    private $descricao;
    private $valor;
    private $quantidade;
    private $imagem;

    public function __construct($nome, $fabricante, $descricao, $valor, $quantidade = 0, $imagem = null, $idLoja = null, $id = null)
    {
        $this->id = $id;
        $this->setIdLoja($idLoja);
        $this->setNome($nome);
        $this->setFabricante($fabricante);
        $this->setDescricao($descricao);
        $this->setValor($valor);
        $this->setQuantidade($quantidade);
        $this->setImagem($imagem);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdLoja()
    {
        return $this->idLoja;
    }

    public function setIdLoja($idLoja)
    {
        $this->idLoja = empty($idLoja) ? null : (int) $idLoja;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function get_Nome()
    {
        return $this->getNome();
    }

    public function setNome($nome)
    {
        $this->nome = trim($nome);
    }

    public function getFabricante()
    {
        return $this->fabricante;
    }

    public function get_Fabricante()
    {
        return $this->getFabricante();
    }

    public function setFabricante($fabricante)
    {
        $this->fabricante = trim($fabricante);
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function get_Descricao()
    {
        return $this->getDescricao();
    }

    public function setDescricao($descricao)
    {
        $this->descricao = trim($descricao);
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function get_Valor()
    {
        return $this->getValor();
    }

    public function setValor($valor)
    {
        $this->valor = (float) $valor;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = (int) $quantidade;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    public function calcularValorComDesconto($percentual)
    {
        $percentual = max(0, min(100, (float) $percentual));
        return $this->valor - ($this->valor * ($percentual / 100));
    }

    public function atualizarValor($percentual)
    {
        $this->valor = $this->valor + ($this->valor * ((float) $percentual / 100));
    }

    public function aplicarCupom($cupomTaxa)
    {
        return $this->calcularValorComDesconto($cupomTaxa);
    }

    public function imprimir()
    {
        return $this->nome . " - " . $this->fabricante . " - R$ " . number_format($this->valor, 2, ",", ".");
    }
}

?>
