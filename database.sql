-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/05/2026 às 19:06
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pedemanga`
--
CREATE DATABASE IF NOT EXISTS `pedemanga` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pedemanga`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `colaboradores`
--

DROP TABLE IF EXISTS `colaboradores`;
CREATE TABLE `colaboradores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `funcao` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `colaboradores`
--

INSERT INTO `colaboradores` (`id`, `nome`, `funcao`, `descricao`, `foto`, `criado_em`) VALUES
(1, 'Prof Luis Felipe Cardoso', 'Professor', 'Teste', 'colab_69fc846657a86.jpg', '2026-05-08 13:54:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_evento` date NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id`, `nome`, `descricao`, `data_evento`, `criado_em`) VALUES
(1, 'AWS', 'Viagem a SP', '2026-05-11', '2026-05-10 22:03:50'),
(2, 'Oficina de Artesanatao', 'Testando', '2026-05-06', '2026-05-10 22:09:48'),
(3, 'ewewew', 'awerfaerf', '2026-04-15', '2026-05-10 22:21:56'),
(4, 'eawefwerfsea', 'dsgzvbdfvzsf', '2026-05-12', '2026-05-10 22:22:06'),
(5, 'Prof Luis Felipe Cardoso', 'arvsd\\cc', '2026-05-01', '2026-05-10 22:22:14'),
(6, 'WFDGBZFds', 'fgdthrthr', '2026-04-16', '2026-05-10 22:22:26'),
(7, 'LUIS FELIPE', 'tARWESDYTUFYDFTDZGR', '2026-04-05', '2026-05-10 22:22:50'),
(8, 'ADSFHGSFZG', 'DAGHSFGDKJFH', '2026-05-08', '2026-05-10 22:23:38'),
(9, 'S', 'gehjklgçhjgsd vd fhglkjçhyotilrukeyjthadgrsdhtjdyuki', '2025-10-16', '2026-05-11 00:09:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento_fotos`
--

DROP TABLE IF EXISTS `evento_fotos`;
CREATE TABLE `evento_fotos` (
  `id` int(10) UNSIGNED NOT NULL,
  `evento_id` int(10) UNSIGNED NOT NULL,
  `arquivo` varchar(255) NOT NULL,
  `descricao` varchar(500) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `evento_fotos`
--

INSERT INTO `evento_fotos` (`id`, `evento_id`, `arquivo`, `descricao`, `criado_em`) VALUES
(1, 1, 'evento_6a010116aa049.png', 'AWS SEDE', '2026-05-10 22:05:10'),
(2, 1, 'evento_6a010116ab6e4.png', 'AWS SEDE', '2026-05-10 22:05:10'),
(3, 1, 'evento_6a010116ac44d.png', 'AWS SEDE', '2026-05-10 22:05:10'),
(4, 1, 'evento_6a010116ad4be.png', 'AWS SEDE', '2026-05-10 22:05:10'),
(5, 2, 'evento_6a010237a4ccb.png', NULL, '2026-05-10 22:09:59'),
(6, 2, 'evento_6a010237a5ffb.png', NULL, '2026-05-10 22:09:59'),
(7, 2, 'evento_6a010237a69e5.png', NULL, '2026-05-10 22:09:59'),
(8, 2, 'evento_6a010237a77f0.png', NULL, '2026-05-10 22:09:59'),
(9, 2, 'evento_6a010237a8296.png', NULL, '2026-05-10 22:09:59'),
(10, 2, 'evento_6a010237a9099.png', NULL, '2026-05-10 22:09:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `parceiros`
--

DROP TABLE IF EXISTS `parceiros`;
CREATE TABLE `parceiros` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `site` varchar(500) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `parceiros`
--

INSERT INTO `parceiros` (`id`, `nome`, `site`, `desc`, `logo`, `criado_em`) VALUES
(1, 'Senai Taubaté', 'https://www.sp.senai.br/unidade/taubate/', 'Escola e Faculdade Félix Guisard', 'parceiro_6a14652591eed.png', '2026-05-08 13:54:53'),
(2, 'Chef Thais Okamoto', 'https://www.instagram.com/chefthaisokamoto?igsh=a3Q3MTQyZGZhN3Iz', NULL, 'parceiro_6a1463c49c2d5.jpg', '2026-05-25 14:59:16'),
(3, 'Alambique Sítio São João', 'http://instagram.com/alambiquesitiosaojoao?igsh=NnR5cmQxcGF5N3Jw', NULL, 'parceiro_6a1463f059d1e.jpg', '2026-05-25 15:00:00'),
(4, 'Turistando Por Aí', 'https://www.instagram.com/turistando_por_ai8?igsh=MWM4ZTZsbTE0ZzU5bg%3D%3D', NULL, 'parceiro_6a146455563d8.jpg', '2026-05-25 15:01:41'),
(5, 'Ohquidea', 'https://www.ohquidea.com.br/sobre', NULL, 'parceiro_6a1464a418629.png', '2026-05-25 15:03:00'),
(6, 'Smart link telecom', 'https://smartlinktelecom.net.br/', NULL, 'parceiro_6a1464e8d4a51.png', '2026-05-25 15:04:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `cor_fundo` varchar(150) NOT NULL DEFAULT 'linear-gradient(135deg,#f4e999,#f2be2c)',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `tag`, `foto`, `cor_fundo`, `ativo`, `criado_em`) VALUES
(1, 'Camiseta Pé de Manga', 'Coleção limitada em parceria com o Quintal Silk. Arte impressa com amor, raiz e identidade. Cada peça é única.', 'Moda consciente', NULL, 'linear-gradient(135deg,#f4e999,#f2be2c)', 1, '2026-05-08 19:00:30'),
(2, 'Pé de Pão', 'Pães artesanais de fermentação natural ou longa fermentação. Feitos com cuidado, tempo e ingredientes de qualidade.', 'Artesanal e natural', NULL, 'linear-gradient(135deg,#fde8c6,#f5c870)', 1, '2026-05-08 19:00:30'),
(3, 'Caderno Cultural', 'Caderno artesanal com ilustrações exclusivas do Pé de Manga. Perfeito para anotações, desenhos e registros.', 'Produto exclusivo', NULL, 'linear-gradient(135deg,#d4efbd,#83c155)', 1, '2026-05-08 19:00:30'),
(4, 'Ecobag Pé de Manga', 'Sacola de pano estampada com arte local. Sustentável, resistente e com identidade cultural única.', 'Sustentabilidade', NULL, 'linear-gradient(135deg,#c8e6f5,#7ec8e3)', 1, '2026-05-08 19:00:30');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `ativo`, `criado_em`) VALUES
(1, 'Administrador', 'admin@pedemanga.org', '$2y$10$dg02UFPZhM/8HEit7pvFe.7/RbHpz1Q6wOpuq..HB84SXYQiCEdDS', 'admin', 1, '2026-05-08 14:15:43'),
(2, 'Teste Funcionário', 'teste@teste.com', '$2y$10$vtJ/z2xc5lYd0dih.jE4xul7/S2TkGMWx9x1Q52qt5qeNsktBkVlu', 'editor', 1, '2026-05-08 14:23:44');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `evento_fotos`
--
ALTER TABLE `evento_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_evfoto_evento` (`evento_id`);

--
-- Índices de tabela `parceiros`
--
ALTER TABLE `parceiros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `evento_fotos`
--
ALTER TABLE `evento_fotos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `parceiros`
--
ALTER TABLE `parceiros`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `evento_fotos`
--
ALTER TABLE `evento_fotos`
  ADD CONSTRAINT `fk_evfoto_evento` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
