<?php
$adm_page = basename($_SERVER['PHP_SELF'], '.php');
function sa($p)
{
  global $adm_page;
  return $adm_page === $p ? 'active' : '';
}
?>
<head>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=delete,edit,group,handshake,home,person,photo_camera,visibility" /> 
</head>
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-header">
    <div class="sh-mango">&#x1F96D;</div>
    <h2>Pé de Manga</h2>
    <p>Painel Admin</p>
  </div>
  <nav class="sidebar-nav">
    <div class="sn-sep">Principal</div>
    <a href="index.php" class="<?= sa('index') ?>"><span class="sn-icon">&#127968;</span> Dashboard</a>
    <div class="sn-sep">Conteudo</div>
    <a href="colaboradores.php" class="<?= sa('colaboradores') ?>"><span class="material-symbols-outlined pi-icon">group</span>
      Colaboradores</a>
    <a href="parceiros.php" class="<?= sa('parceiros') ?>"><span class="material-symbols-outlined pi-icon">handshake</span> Parceiros</a>
    <a href="galeria.php" class="<?= sa('galeria') ?>"><span class="material-symbols-outlined pi-icon">photo_camera</span> Galeria</a>
    <?php if (($_SESSION['adm_perfil'] ?? '') === 'admin'): ?>
    <div class="sn-sep">Administração</div>
    <a href="usuarios.php" class="<?= sa('usuarios') ?>"><span class="material-symbols-outlined pi-icon">person</span> Usuários</a>
    <?php endif; ?>
    <div class="sn-sep">Site</div>
    <a href="../index.php" target="_blank"><span class="material-symbols-outlined pi-icon">visibility</span> Ver site</a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php">&#128274; Sair</a>
  </div>
</aside>