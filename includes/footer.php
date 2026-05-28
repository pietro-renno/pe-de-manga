<?php $c = get_contato(); ?>
<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <img src="./assets/img/logo.png" alt="Pé de Manga" style="filter: brightness(0) invert(1);">
      <p>Cultura Viva e afeto como estratégia de transformação em Caçapava.</p>
      <a href="https://maps.app.goo.gl/WJmJwxNSUe2Vjo5g7" target="_blank" class="btn-footer">Veja nossa localização</a>
      <div class="social-links">
        <a href="https://wa.me/5512997624486" target="_blank"><i class="fab fa-whatsapp"></i></a>
        <a href="https://www.instagram.com/pedemangacpv/" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.youtube.com/@PedeMangaCPV" target="_blank"><i class="fab fa-youtube"></i></a>
      </div>
    </div>
    <div class="footer-col">
      <h5>Navegação</h5>
      <ul>
        <li><a href="index.php">Início</a></li>
        <li><a href="quem-somos.php">Quem Somos</a></li>
        <li><a href="o-que-fazemos.php">O Que Fazemos</a></li>
        <li><a href="eventos.php">Eventos</a></li>
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
  </div>
  <div class="footer-devs">
    <span>Site desenvolvido pelos alunos do <strong>Sesi|Senai</strong> &nbsp;·&nbsp;</span>
    <div class="footer-devs-list">
      <a href="https://github.com/Tomate3181" target="_blank" rel="noopener">Samuel Mioni</a>
      <a href="https://github.com/felipenhoslol" target="_blank" rel="noopener">Luiz Felipe Leite</a>
      <a href="https://github.com/Vinicius3442" target="_blank" rel="noopener">Vinícius Montuani</a>
      <a href="https://github.com/pietro-renno" target="_blank" rel="noopener">Pietro Rennò</a>
      <a href="https://linktr.ee/cardoso30s" target="_blank" rel="noopener">Luis Felipe Cardoso</a>
      <a href="https://github.com/lucasmsdev" target="_blank" rel="noopener">Lucas Machado</a>
    </div>
  </div>
</footer>
<div id="toast"></div>
<script src="assets/js/main.js"></script>
