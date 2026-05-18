<?php
$adm_page = basename($_SERVER['PHP_SELF'], '.php');
function sa($p)
{
  global $adm_page;
  return $adm_page === $p ? 'active' : '';
}
?>

<head>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" /></head>

<!-- overlay mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="admCloseSidebar()"></div>

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-header">
    <span class="material-symbols-outlined mi-brand">eco</span>
    <h2>Pé de Manga</h2>
    <p>Painel Admin</p>
  </div>
  <nav class="sidebar-nav">
    <div class="sn-sep">Principal</div>
    <a href="index.php" class="<?= sa('index') ?>"><span class="sn-icon"><span
          class="material-symbols-outlined">home</span></span> Dashboard</a>
    <div class="sn-sep">Conteúdo</div>
    <a href="colaboradores.php" class="<?= sa('colaboradores') ?>"><span class="sn-icon"><span class="material-symbols-outlined">group</span></span> Colaboradores</a>
    <a href="parceiros.php" class="<?= sa('parceiros') ?>"><span class="sn-icon"><span class="material-symbols-outlined">handshake</span></span> Parceiros</a>
    <a href="galeria.php" class="<?= sa('galeria') ?>"><span class="sn-icon"><span class="material-symbols-outlined">photo_camera</span></span> Galeria</a>
    <a href="produtos.php" class="<?= sa('produtos') ?>"><span class="sn-icon"><span class="material-symbols-outlined">local_mall</span></span> Produtos</a>
    <a href="eventos.php" class="<?= sa('eventos') ?>"><span class="sn-icon"><span class="material-symbols-outlined">event</span></span> Eventos</a>
    <?php if (($_SESSION['adm_perfil'] ?? '') === 'admin'): ?>
      <div class="sn-sep">Administração</div>
      <a href="usuarios.php" class="<?= sa('usuarios') ?>"><span class="sn-icon"><span
            class="material-symbols-outlined">manage_accounts</span></span> Usuários</a>
    <?php endif; ?>
    <div class="sn-sep">Site</div>
    <a href="../index.php" target="_blank"><span class="sn-icon"><span
          class="material-symbols-outlined">public</span></span> Ver site</a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php"><span class="material-symbols-outlined" style="font-size:1rem;">logout</span> Sair</a>
  </div>
</aside>

<script>
  function admToggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
  }
  function admCloseSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
  }
  // Fecha sidebar ao clicar em qualquer link dentro dela (mobile)
  document.querySelectorAll('.sidebar-nav a').forEach(function(a) {
    a.addEventListener('click', function() {
      if (window.innerWidth <= 900) admCloseSidebar();
    });
  });
  // Injeta botão hamburger no topbar ao carregar
  document.addEventListener('DOMContentLoaded', function() {
    var topbar = document.querySelector('.admin-topbar');
    if (!topbar) return;
    var btn = document.createElement('button');
    btn.className = 'adm-hamburger';
    btn.setAttribute('aria-label', 'Menu');
    btn.innerHTML = '<span class="material-symbols-outlined">menu</span>';
    btn.onclick = admToggleSidebar;
    topbar.insertBefore(btn, topbar.firstChild);
  });
</script>
