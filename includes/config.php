<?php
require_once __DIR__ . '/db.php';

// ── CONFIGURAÇÃO GERAL ──
define('SITE_NAME', 'Pé de Manga');
define('SITE_SLOGAN', 'Cultura e Afeto');

// ── DADOS CENTRALIZADOS ──
function get_contato()
{
    return [
        'telefone' => '(12) 99762-4486',
        'email'    => 'pedemangacpv@gmail.com',
        'pix'      => 'financasdopedemanga@gmail.com',
    ];
}

function get_colaboradores(): array
{
    return get_db()->query('SELECT * FROM colaboradores ORDER BY id')->fetchAll();
}

function get_parceiros(): array
{
    return get_db()->query('SELECT * FROM parceiros ORDER BY id')->fetchAll();
}

function get_produtos(): array
{
    return get_db()->query('SELECT * FROM produtos WHERE ativo = 1 ORDER BY id')->fetchAll();
}

function get_eventos(): array
{
    return get_db()->query(
        'SELECT e.*, COUNT(f.id) AS total_fotos
         FROM eventos e
         LEFT JOIN evento_fotos f ON f.evento_id = e.id
         GROUP BY e.id
         ORDER BY e.data_evento DESC'
    )->fetchAll();
}

/**
 * Busca a imagem da programação mensal para um mês/ano específico.
 * Retorna null se não houver imagem cadastrada.
 */
function get_programacao_mes(int $mes, int $ano): ?array
{
    $st = get_db()->prepare(
        'SELECT * FROM programacao_mes WHERE mes = ? AND ano = ? LIMIT 1'
    );
    $st->execute([$mes, $ano]);
    $row = $st->fetch();
    return $row ?: null;
}

/**
 * Lista todas as programações mensais cadastradas (mais recentes primeiro).
 */
function get_todas_programacoes(): array
{
    return get_db()->query(
        'SELECT * FROM programacao_mes ORDER BY ano DESC, mes DESC'
    )->fetchAll();
}

function get_evento_fotos(int $evento_id): array
{
    $st = get_db()->prepare('SELECT * FROM evento_fotos WHERE evento_id = ? ORDER BY id');
    $st->execute([$evento_id]);
    return $st->fetchAll();
}

define('WHATSAPP_NUM', '5512997624486');
