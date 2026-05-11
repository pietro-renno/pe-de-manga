<?php
// Detecta página atual para highlight no nav
$current = basename($_SERVER['PHP_SELF'], '.php');
function nav_active($page)
{
  global $current;
  return $current === $page ? 'active' : '';
}
?>
<nav>
  <a href="index.php" class="nav-logo">
    <span class="nav-mango"><img src="./assets/img/logo-semtexto.png" alt="Pé de Manga Icon" id="nav-logo"></span>
    <div class="logo-text">
      Pé de Manga |
      <span>Cultura e Afeto</span>
    </div>
  </a>
  <ul class="nav-links" id="navLinks">
    <li><a href="index.php" class="<?= nav_active('index') ?>">Início</a></li>
    <li><a href="quem-somos.php" class="<?= nav_active('quem-somos') ?>">Quem Somos</a></li>
    <li><a href="o-que-fazemos.php" class="<?= nav_active('o-que-fazemos') ?>">O Que Fazemos</a></li>
    <li><a href="produtos.php" class="<?= nav_active('produtos') ?>">Produtos</a></li>
    <li><a href="parceiros.php" class="<?= nav_active('parceiros') ?>">Parceiros</a></li>
    <!-- <li><a href="galeria.php" class="<?= nav_active('galeria') ?>">Galeria</a></li> -->
    <li><a href="eventos.php" class="<?= nav_active('eventos') ?>">Eventos</a></li>
    <li><a href="transparencia.php" class="<?= nav_active('transparencia') ?>">Transparência</a></li>
    <li><a href="doacoes.php" class="nav-cta <?= nav_active('doacoes') ?>">Apoie</a></li>
  </ul>
  <div class="hamburger" onclick="toggleNav()">
    <span></span><span></span><span></span>
  </div>
</nav>