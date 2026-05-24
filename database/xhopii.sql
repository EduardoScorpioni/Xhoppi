-- Banco de dados do projeto Xhopii
-- Compativel com MySQL/MariaDB do EasyPHP.
-- Para importar: phpMyAdmin > Importar > escolha este arquivo.
-- Atencao: este script recria as tabelas do banco xhopii.

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS xhopii
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE xhopii;

DROP TABLE IF EXISTS cupom;
DROP TABLE IF EXISTS produto;
DROP TABLE IF EXISTS funcionario;
DROP TABLE IF EXISTS cliente;
DROP TABLE IF EXISTS loja;

CREATE TABLE loja (
  id_loja INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(120) NOT NULL,
  cnpj VARCHAR(18) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  email VARCHAR(120) NOT NULL,
  endereco VARCHAR(180) NOT NULL,
  descricao VARCHAR(255) DEFAULT NULL,
  logo VARCHAR(180) DEFAULT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_loja),
  UNIQUE KEY uk_loja_cnpj (cnpj),
  UNIQUE KEY uk_loja_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE cliente (
  id_cliente INT NOT NULL AUTO_INCREMENT,
  cpf VARCHAR(14) NOT NULL,
  nome VARCHAR(80) NOT NULL,
  sobrenome VARCHAR(100) NOT NULL,
  dataNascimento DATE NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  email VARCHAR(120) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  fotoPerfil VARCHAR(180) DEFAULT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_cliente),
  UNIQUE KEY uk_cliente_cpf (cpf),
  UNIQUE KEY uk_cliente_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE funcionario (
  id_funcionario INT NOT NULL AUTO_INCREMENT,
  cpf VARCHAR(14) NOT NULL,
  nome VARCHAR(80) NOT NULL,
  sobrenome VARCHAR(100) NOT NULL,
  dataNascimento DATE NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  cargo VARCHAR(80) NOT NULL,
  salario DECIMAL(10,2) NOT NULL,
  email VARCHAR(120) NOT NULL,
  senha VARCHAR(255) DEFAULT NULL,
  fotoPerfil VARCHAR(180) DEFAULT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_funcionario),
  UNIQUE KEY uk_funcionario_cpf (cpf),
  UNIQUE KEY uk_funcionario_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE produto (
  id_produto INT NOT NULL AUTO_INCREMENT,
  id_loja INT DEFAULT NULL,
  nome VARCHAR(120) NOT NULL,
  fabricante VARCHAR(120) NOT NULL,
  descricao TEXT NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  quantidade INT NOT NULL DEFAULT 0,
  imagem VARCHAR(180) DEFAULT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_produto),
  KEY fk_produto_loja (id_loja),
  CONSTRAINT fk_produto_loja
    FOREIGN KEY (id_loja) REFERENCES loja (id_loja)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE cupom (
  id_cupom INT NOT NULL AUTO_INCREMENT,
  id_loja INT DEFAULT NULL,
  codigo VARCHAR(40) NOT NULL,
  desconto DECIMAL(5,2) NOT NULL,
  dataValidade DATE NOT NULL,
  quantidadeDisponivel INT NOT NULL DEFAULT 0,
  status VARCHAR(20) NOT NULL DEFAULT 'Ativo',
  imagem VARCHAR(180) DEFAULT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_cupom),
  UNIQUE KEY uk_cupom_codigo (codigo),
  KEY fk_cupom_loja (id_loja),
  CONSTRAINT fk_cupom_loja
    FOREIGN KEY (id_loja) REFERENCES loja (id_loja)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO loja
  (nome, cnpj, telefone, email, endereco, descricao, logo)
VALUES
  ('Loja Tech Store', '12.345.678/0001-90', '(18) 99999-1111', 'contato@techstore.com', 'Rua das Compras, 100', 'Produtos eletronicos e acessorios', 'img/Loja.png'),
  ('Moda Style', '98.765.432/0001-10', '(18) 99999-2222', 'contato@modastyle.com', 'Avenida Central, 250', 'Roupas modernas e estilosas', 'img/Loja.png'),
  ('Casa e Decoracao', '45.678.901/0001-22', '(18) 99999-3333', 'contato@casaedecoracao.com', 'Rua do Lar, 45', 'Itens para sua casa', 'img/Loja.png');

INSERT INTO cliente
  (cpf, nome, sobrenome, dataNascimento, telefone, email, senha, fotoPerfil)
VALUES
  ('111.222.333-44', 'Igor', 'Marques', '2006-04-15', '(18) 99999-9999', 'igor@gmail.com', '123456', NULL),
  ('222.333.444-55', 'Eduardo', 'Scorpioni', '2006-08-22', '(11) 98888-8888', 'edu@email.com', '123456', NULL),
  ('333.444.555-66', 'Ana', 'Souza', '2003-01-10', '(18) 97777-7777', 'ana@email.com', '123456', NULL);

INSERT INTO funcionario
  (cpf, nome, sobrenome, dataNascimento, telefone, cargo, salario, email, senha, fotoPerfil)
VALUES
  ('444.555.666-77', 'Bruno', 'Lima', '1990-05-20', '(18) 96666-6666', 'Administrador', 3200.00, 'bruno@xhopii.com', '123456', NULL),
  ('555.666.777-88', 'Carla', 'Mendes', '1998-11-03', '(18) 95555-5555', 'Atendente', 2100.00, 'carla@xhopii.com', '123456', NULL),
  ('666.777.888-99', 'Lucas', 'Pereira', '1995-07-18', '(18) 94444-4444', 'Suporte', 2500.00, 'lucas@xhopii.com', '123456', NULL);

INSERT INTO produto
  (id_loja, nome, fabricante, descricao, valor, quantidade, imagem)
VALUES
  (2, 'Camisa Desenvolvedor Front-End CSS', 'Xhopii Wear', 'Camiseta preta para desenvolvedores front-end.', 56.90, 171, 'img/produto1.png'),
  (1, 'Fone Bluetooth Gamer', 'Tech Store', 'Fone sem fio com microfone e bateria de longa duracao.', 129.90, 45, 'img/produto2.png'),
  (1, 'Teclado Mecanico RGB', 'Tech Store', 'Teclado mecanico compacto com iluminacao RGB.', 199.90, 30, 'img/produto3.png'),
  (3, 'Luminaria de Mesa LED', 'Casa e Decoracao', 'Luminaria articulada com tres niveis de brilho.', 89.90, 60, 'img/produto4.png'),
  (2, 'Mochila Casual', 'Moda Style', 'Mochila resistente para estudos, trabalho e viagens curtas.', 99.90, 80, 'img/produto5.png');

INSERT INTO cupom
  (id_loja, codigo, desconto, dataValidade, quantidadeDisponivel, status, imagem)
VALUES
  (NULL, 'XHOP10', 10.00, '2026-12-31', 100, 'Ativo', NULL),
  (NULL, 'FRETEGRATIS', 0.00, '2026-11-30', 80, 'Ativo', NULL),
  (2, 'PROMO20', 20.00, '2026-07-31', 50, 'Ativo', NULL);

SET FOREIGN_KEY_CHECKS = 1;
