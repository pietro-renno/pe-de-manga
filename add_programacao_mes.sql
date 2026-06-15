-- ============================================================
-- Migração: Programação do Mês + Horário por Evento
-- Execute este arquivo no phpMyAdmin (banco: pedemanga)
-- As tabelas e dados existentes NÃO são afetados.
-- ============================================================

-- 1. Nova tabela para a imagem da programação mensal
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `programacao_mes` (
  `id`        INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `mes`       TINYINT(2)       NOT NULL COMMENT '1=Jan … 12=Dez',
  `ano`       SMALLINT(4)      NOT NULL,
  `imagem`    VARCHAR(255)     NOT NULL,
  `criado_em` TIMESTAMP        NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mes_ano` (`mes`, `ano`)   -- apenas 1 imagem por mês
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Adiciona campo de horário (opcional) na tabela eventos
-- --------------------------------------------------------
-- Roda somente se a coluna ainda não existir
SET @col_exists = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'eventos'
    AND COLUMN_NAME  = 'horario'
);

SET @sql = IF(@col_exists = 0,
  'ALTER TABLE `eventos` ADD COLUMN `horario` VARCHAR(50) DEFAULT NULL COMMENT \'ex: "14h" ou "14:00–17:00"\' AFTER `data_evento`',
  'SELECT "coluna horario ja existe, nenhuma acao necessaria"'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;