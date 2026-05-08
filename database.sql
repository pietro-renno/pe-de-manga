-- ============================================================
--  Pé de Manga – Script de banco de dados MySQL / phpMyAdmin
--  Charset: utf8mb4  |  Engine: InnoDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS `pedemanga`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `pedemanga`;

-- ------------------------------------------------------------
-- Tabela: colaboradores
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `colaboradores` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(255)    NOT NULL,
  `funcao`      VARCHAR(255)    NOT NULL,
  `descricao`   TEXT                    DEFAULT NULL,
  `foto`        VARCHAR(255)            DEFAULT NULL,
  `criado_em`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: parceiros
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `parceiros` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(255)    NOT NULL,
  `site`        VARCHAR(500)            DEFAULT NULL,
  `desc`        TEXT                    DEFAULT NULL,
  `logo`        VARCHAR(255)            DEFAULT NULL,
  `criado_em`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: galeria
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galeria` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `arquivo`     VARCHAR(255)    NOT NULL,
  `descricao`   VARCHAR(500)            DEFAULT NULL,
  `criado_em`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: produtos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `produtos` (
  `id`          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nome`        VARCHAR(255)    NOT NULL,
  `descricao`   TEXT                    DEFAULT NULL,
  `tag`         VARCHAR(100)            DEFAULT NULL,
  `cor_fundo`   VARCHAR(150)    NOT NULL DEFAULT 'linear-gradient(135deg,#f4e999,#f2be2c)',
  `ativo`       TINYINT(1)      NOT NULL DEFAULT 1,
  `criado_em`   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Tabela: usuarios
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id`         INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  `nome`       VARCHAR(255)   NOT NULL,
  `email`      VARCHAR(320)   NOT NULL,
  `senha`      VARCHAR(255)   NOT NULL,
  `perfil`     ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  `ativo`      TINYINT(1)     NOT NULL DEFAULT 1,
  `criado_em`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Dados de exemplo (migrados dos arquivos JSON originais)
-- ------------------------------------------------------------
-- Produtos iniciais
INSERT INTO `produtos` (`nome`, `descricao`, `tag`, `cor_fundo`) VALUES
  ('Camisetas Pé de Manga', 'Coleção limitada em parceria com o Quintal Silk. Arte impressa com amor, raiz e identidade. Cada peça é única.', 'Moda consciente', 'linear-gradient(135deg,#f4e999,#f2be2c)'),
  ('Pé de Pão', 'Pães artesanais de fermentação natural ou longa fermentação. Feitos com cuidado, tempo e ingredientes de qualidade.', 'Artesanal e natural', 'linear-gradient(135deg,#fde8c6,#f5c870)'),
  ('Caderno Cultural', 'Caderno artesanal com ilustrações exclusivas do Pé de Manga. Perfeito para anotações, desenhos e registros.', 'Produto exclusivo', 'linear-gradient(135deg,#d4efbd,#83c155)'),
  ('Ecobag Pé de Manga', 'Sacola de pano estampada com arte local. Sustentável, resistente e com identidade cultural única.', 'Sustentabilidade', 'linear-gradient(135deg,#c8e6f5,#7ec8e3)');

-- Admin padrão (senha: pedemanga2025)
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `perfil`) VALUES
  ('Administrador', 'admin@pedemanga.org', '$2y$10$dg02UFPZhM/8HEit7pvFe.7/RbHpz1Q6wOpuq..HB84SXYQiCEdDS', 'admin');

INSERT INTO `colaboradores` (`nome`, `funcao`, `descricao`, `foto`) VALUES
  ('Prof Luis Felipe Cardoso', 'Professor', 'Teste', 'colab_69fc846657a86.jpg');

INSERT INTO `parceiros` (`nome`, `site`, `desc`, `logo`) VALUES
  ('Senai Taubaté', 'https://www.sp.senai.br/unidade/taubate/', 'Escola e Faculdade Félix Guisard', 'parceiro_69fc84a733ca8.png');

INSERT INTO `galeria` (`arquivo`, `descricao`) VALUES
  ('galeria_69fc84bce4214.png', 'Oficina de Desenvolvimento'),
  ('galeria_69fc84bce46ae.png', 'Oficina de Desenvolvimento'),
  ('galeria_69fc84bce48df.png', 'Oficina de Desenvolvimento'),
  ('galeria_69fc84bce4aa9.png', 'Oficina de Desenvolvimento'),
  ('galeria_69fc84bce4c23.png', 'Oficina de Desenvolvimento'),
  ('galeria_69fc84bce4db7.png', 'Oficina de Desenvolvimento');
