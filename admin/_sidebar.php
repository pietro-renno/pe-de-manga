<?php
$adm_page = basename($_SERVER['PHP_SELF'], '.php');
function sa($p)
{
  global $adm_page;
  return $adm_page === $p ? 'active' : '';
}
?>
<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-header">
    <span class="material-symbols-outlined mi-brand">eco</span>
    <h2>Pé de Manga</h2>
    <p>Painel Admin</p>
  </div>
  <nav class="sidebar-nav">
    <div class="sn-sep">Principal</div>
    <a href="index.php" class="<?= sa('index') ?>"><span class="sn-icon"><span class="material-symbols-outlined">home</span></span> Dashboard</a>
    <div class="sn-sep">Conteúdo</div>
    <a href="colaboradores.php" class="<?= sa('colaboradores') ?>"><span class="sn-icon"><span class="material-symbols-outlined">group</span></span> Colaboradores</a>
    <a href="parceiros.php" class="<?= sa('parceiros') ?>"><span class="sn-icon"><span class="material-symbols-outlined">handshake</span></span> Parceiros</a>
    <a href="galeria.php" class="<?= sa('galeria') ?>"><span class="sn-icon"><span class="material-symbols-outlined">photo_camera</span></span> Galeria</a>
    <a href="produtos.php" class="<?= sa('produtos') ?>"><span class="sn-icon"><span class="material-symbols-outlined">local_mall</span></span> Produtos</a>
    <a href="eventos.php" class="<?= sa('eventos') ?>"><span class="sn-icon"><span class="material-symbols-outlined">event</span></span> Eventos</a>
    <?php if (($_SESSION['adm_perfil'] ?? '') === 'admin'): ?>
    <div class="sn-sep">Administração</div>
    <a href="usuarios.php" class="<?= sa('usuarios') ?>"><span class="sn-icon"><span class="material-symbols-outlined">manage_accounts</span></span> Usuários</a>
    <?php endif; ?>
    <div class="sn-sep">Site</div>
    <a href="../index.php" target="_blank"><span class="sn-icon"><span class="material-symbols-outlined">public</span></span> Ver site</a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php"><span class="material-symbols-outlined" style="font-size:1rem;">logout</span> Sair</a>
  </div>
</aside>