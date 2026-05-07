<?php $c = get_contato(); ?>
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <p class="fb-name">&#x1F96D; Pé de Manga</p>
      <p class="fb-sub">Cultura e Afeto</p>
      <p>Um território de encontros, formação e criação onde arte e cuidado se encontram para transformar vidas.</p>
      <div class="footer-contact">
        <div class="social-links">
          <a href="https://wa.me/5512997624486" target="_blank"><i class="fab fa-whatsapp"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="https://www.youtube.com/watch?v=T-kSSKDsM8I" target="_blank"><i class="fab fa-youtube"></i></a>
        </div>
        <a href="tel:+5512997624486"><?php echo $c['telefone']; ?></a>
        <a href="mailto:<?php echo $c['email']; ?>"><?php echo $c['email']; ?></a>
      </div>
    </div>
    <div class="footer-col">
      <h5>Navegação</h5>
      <ul>
        <li><a href="index.php">Início</a></li>
        <li><a href="quem-somos.php">Quem Somos</a></li>
        <li><a href="o-que-fazemos.php">O Que Fazemos</a></li>
        <li><a href="galeria.php">Galeria</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Participe</h5>
      <ul>
        <li><a href="produtos.php">Produtos</a></li>
        <li><a href="doacoes.php">Doações</a></li>
        <li><a href="parceiros.php">Parceiros</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h5>Institucional</h5>
      <ul>
        <li><a href="transparencia.php">Transparência</a></li>
        <li><a href="https://culturaviva.cultura.gov.br/agente/14621536/" target="_blank">Ponto de Cultura</a></li>
        <li><a href="mailto:<?php echo $c['email']; ?>">Contato</a></li>
        <li><a href="./admin/login.php">Login</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> Pé de Manga – Cultura e Afeto. CNPJ 23.456.588/0001-98</span>
    <span>Feito com <span class="fb-heart">&#9829;</span> e cultura</span>
  </div>
</footer>
<div id="toast"></div>
<script src="assets/js/main.js"></script>