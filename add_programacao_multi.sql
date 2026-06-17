-- ============================================================
-- Migração: Programação do Mês com MÚLTIPLAS imagens por mês
-- Execute este arquivo no phpMyAdmin (banco: pedemanga)
-- Os dados existentes NÃO são afetados.
-- ============================================================
-- Antes: a tabela `programacao_mes` aceitava apenas 1 imagem
-- por mês (UNIQUE KEY uq_mes_ano). Esta migração remove essa
-- restrição para permitir várias imagens no mesmo mês, exibidas
-- num carrossel na página de eventos.
-- ============================================================

-- 1. Remove a chave única (mes, ano), se existir
-- --------------------------------------------------------
SET @idx_exists = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'programacao_mes'
    AND INDEX_NAME   = 'uq_mes_ano'
);

SET @sql = IF(@idx_exists > 0,
  'ALTER TABLE `programacao_mes` DROP INDEX `uq_mes_ano`',
  'SELECT "indice uq_mes_ano ja removido, nenhuma acao necessaria"'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2. Adiciona índice (não único) para acelerar a busca por mês/ano
-- --------------------------------------------------------
SET @idx2_exists = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'programacao_mes'
    AND INDEX_NAME   = 'idx_mes_ano'
);

SET @sql2 = IF(@idx2_exists = 0,
  'ALTER TABLE `programacao_mes` ADD INDEX `idx_mes_ano` (`mes`, `ano`)',
  'SELECT "indice idx_mes_ano ja existe, nenhuma acao necessaria"'
);

PREPARE stmt2 FROM @sql2;
EXECUTE stmt2;
DEALLOCATE PREPARE stmt2;
