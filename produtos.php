<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga e um Ponto de Cultura dedicado a arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>Produtos - Pé de Manga</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require 'includes/nav.php'; ?>
<div class="page-wrap">
  <div class="page-hero" data-emoji="&#128717;">
    <p class="ph-tag">Loja solidaria</p>
    <h1>Nossos <em>produtos</em></h1>
    <p>Cada produto e feito com cuidado e intencao. Ao comprar, voce apoia nossas acoes culturais.</p>
  </div>
  <section>
    <div class="section-inner">
      <div class="produtos-grid">
        <div class="produto-card prod-camiseta reveal">
          <div class="produto-img">&#128089;</div>
          <div class="produto-body">
            <p class="produto-tag">Moda consciente</p>
            <h3>Camisetas Pé de Manga</h3>
            <p>Colecao limitada em parceria com o <strong>Quintal Silk</strong>. Arte impressa com amor, raiz e identidade. Cada peca e unica.</p>
            <button class="btn btn-primary" onclick="openModal('camiseta')">Quero uma camiseta</button>
          </div>
        </div>
        <div class="produto-card prod-pao reveal">
          <div class="produto-img">&#127838;</div>
          <div class="produto-body">
            <p class="produto-tag">Artesanal e natural</p>
            <h3>Pe de Pao</h3>
            <p>Paes artesanais de <strong>fermentacao natural ou longa fermentacao</strong>. Feitos com cuidado, tempo e ingredientes de qualidade.</p>
            <button class="btn btn-primary" onclick="openModal('pao')">Encomendar agora</button>
          </div>
        </div>
        <div class="produto-card prod-doacao reveal">
          <div class="produto-img">&#128154;</div>
          <div class="produto-body">
            <p class="produto-tag">Apoio direto</p>
            <h3>Apoie o Pé de Manga</h3>
            <p>Voce tambem pode apoiar nossa causa com uma doacao. Todo valor fortalece nossas acoes culturais e gratuitas na comunidade.</p>
            <a href="doacoes.php" class="btn btn-verde">Fazer uma doacao</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal-overlay" id="modal-camiseta" onclick="closeModal('camiseta')">
  <div class="modal-box" onclick="event.stopPropagation()">
    <button class="modal-close" onclick="closeModal('camiseta')">&times;</button>
    <h3>&#128089; Camisetas Pé de Manga</h3>
    <p class="modal-sub">Colecao limitada em parceria com Quintal Silk</p>
    <div style="background:var(--fundo-claro);border-radius:10px;padding:22px;">
      <p style="font-size:.9rem;color:var(--marrom);line-height:1.7;margin-bottom:16px;">Para encomendar sua camiseta, entre em contato diretamente conosco. Estoque limitado!</p>
      <a href="mailto:pedemangacpv@gmail.com?subject=Camiseta" class="btn btn-primary">Encomendar por e-mail</a>&nbsp;
      <a href="https://wa.me/5512997624486?text=Ola!%20Gostaria%20de%20saber%20sobre%20as%20camisetas." class="btn btn-verde" target="_blank">WhatsApp</a>
    </div>
  </div>
</div>
<div class="modal-overlay" id="modal-pao" onclick="closeModal('pao')">
  <div class="modal-box" onclick="event.stopPropagation()">
    <button class="modal-close" onclick="closeModal('pao')">&times;</button>
    <h3>&#127838; Pe de Pao</h3>
    <p class="modal-sub">Paes artesanais de fermentacao natural</p>
    <div style="background:var(--fundo-claro);border-radius:10px;padding:22px;">
      <p style="font-size:.9rem;color:var(--marrom);line-height:1.7;margin-bottom:16px;">Encomende seu pao artesanal. Sujeito a disponibilidade semanal.</p>
      <a href="mailto:pedemangacpv@gmail.com?subject=Pao" class="btn btn-primary">Encomendar por e-mail</a>&nbsp;
      <a href="https://wa.me/5512997624486?text=Ola!%20Gostaria%20de%20encomendar%20um%20pao." class="btn btn-verde" target="_blank">WhatsApp</a>
    </div>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
</body>
</html>
