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
-- Dados de exemplo (migrados dos arquivos JSON originais)
-- ------------------------------------------------------------
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
