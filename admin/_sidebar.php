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
    <div class="sh-mango">&#x1F96D;</div>
    <h2>Pé de Manga</h2>
    <p>Painel Admin</p>
  </div>
  <nav class="sidebar-nav">
    <div class="sn-sep">Principal</div>
    <a href="index.php" class="<?= sa('index') ?>"><span class="sn-icon">&#127968;</span> Dashboard</a>
    <div class="sn-sep">Conteudo</div>
    <a href="colaboradores.php" class="<?= sa('colaboradores') ?>"><span class="sn-icon">&#128101;</span>
      Colaboradores</a>
    <a href="parceiros.php" class="<?= sa('parceiros') ?>"><span class="sn-icon">&#129309;</span> Parceiros</a>
    <a href="galeria.php" class="<?= sa('galeria') ?>"><span class="sn-icon">&#128247;</span> Galeria</a>
    <div class="sn-sep">Site</div>
    <a href="../index.php" target="_blank"><span class="sn-icon">&#127758;</span> Ver site</a>
  </nav>
  <div class="sidebar-footer">
    <a href="logout.php">&#128274; Sair</a>
  </div>
</aside>