<?php
require_once __DIR__ . '/db.php';

// ── CONFIGURAÇÃO GERAL ──
define('SITE_NAME', 'Pé de Manga');
define('SITE_SLOGAN', 'Cultura e Afeto');
define('BASE_URL', '');   // ex: https://pedemanga.org.br

// ── DADOS CENTRALIZADOS ──
function get_contato()
{
    return [
        'telefone' => '(12) 99762-4486',
        'email'    => 'pedemangacpv@gmail.com',
        'pix'      => 'financasdopedemanga@gmail.com',
    ];
}

// ── DADOS: COLABORADORES ──
function get_colaboradores(): array
{
    return get_db()->query('SELECT * FROM colaboradores ORDER BY id')->fetchAll();
}

// ── DADOS: PARCEIROS ──
function get_parceiros(): array
{
    return get_db()->query('SELECT * FROM parceiros ORDER BY id')->fetchAll();
}

// ── DADOS: GALERIA ──
function get_galeria(): array
{
    return get_db()->query('SELECT * FROM galeria ORDER BY id')->fetchAll();
}

// ── DADOS: PRODUTOS ──
function get_produtos(): array
{
    return get_db()->query('SELECT * FROM produtos WHERE ativo = 1 ORDER BY id')->fetchAll();
}

// ── DADOS: EVENTOS ──
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

function get_evento_fotos(int $evento_id): array
{
    $st = get_db()->prepare('SELECT * FROM evento_fotos WHERE evento_id = ? ORDER BY id');
    $st->execute([$evento_id]);
    return $st->fetchAll();
}

// ── WHATSAPP ──
define('WHATSAPP_NUM', '5512997624486');
