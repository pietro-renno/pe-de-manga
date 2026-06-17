<?php
/**
 * api/evento.php
 * Retorna JSON com dados + fotos de um evento.
 * Uso: GET /api/evento.php?id=X
 */
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID inválido']);
    exit;
}

$db = get_db();

// Busca o evento
$st = $db->prepare(
    'SELECT e.*, COUNT(f.id) AS total_fotos
     FROM eventos e
     LEFT JOIN evento_fotos f ON f.evento_id = e.id
     WHERE e.id = ?
     GROUP BY e.id'
);
$st->execute([$id]);
$evento = $st->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    http_response_code(404);
    echo json_encode(['erro' => 'Evento não encontrado']);
    exit;
}

// Busca as fotos
$fotos = get_evento_fotos($id);

echo json_encode([
    'evento' => [
        'id'          => (int) $evento['id'],
        'nome'        => $evento['nome'],
        'descricao'   => $evento['descricao'],
        'data_evento' => $evento['data_evento'],
        'horario'     => $evento['horario'] ?? null,
        'total_fotos' => (int) $evento['total_fotos'],
    ],
    'fotos' => array_map(fn($f) => [
        'id'       => (int) $f['id'],
        'arquivo'  => $f['arquivo'],
        'descricao'=> $f['descricao'] ?? null,
    ], $fotos),
], JSON_UNESCAPED_UNICODE);
