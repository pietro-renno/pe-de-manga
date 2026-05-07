<?php
session_start();
if (empty($_SESSION['adm_logado'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../includes/config.php';

// Helper: upload de imagem
function fazer_upload(string $campo, string $prefixo = ''): string
{
    if (empty($_FILES[$campo]['name'])) return '';
    $file = $_FILES[$campo];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return '';
    if ($file['size'] > 5 * 1024 * 1024) return '';
    $nome = $prefixo . uniqid() . '.' . $ext;
    $dest = __DIR__ . '/../data/uploads/' . $nome;
    return move_uploaded_file($file['tmp_name'], $dest) ? $nome : '';
}
