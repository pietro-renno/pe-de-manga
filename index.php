<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pé de Manga - Cultura e Afeto</title>
  <link rel="stylesheet" href="assets/css/style.css?v=3">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga é um Ponto de Cultura dedicado à arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>

  <section id="hero">
    <div class="container hero-content2">
      <div class="hero-text" data-reveal="left">
        <span class="hero-eyebrow">&#10022; Ponto de Cultura - Cultura Viva em Caçapava</span>
        <h1 class="hero-title">Arte como<br><em>cuidado</em> e<br>pertencimento</h1>
        <p class="hero-sub">O Pé de Manga é um espaço onde cultura, saúde mental, sustentabilidade e afeto se encontram
          para transformar vidas e comunidades.</p>
        <div class="hero-btns">
          <a href="eventos.php" class="btn btn-primary">Ver a agenda de eventos</a>
          <a href="https://wa.me/5512997624486" target="_blank" class="btn btn-outline">Contato</a>
          <a href="doacoes.php" class="btn btn-verde">Apoie</a>
        </div>
        <blockquote class="hero-quote quote-block" style="margin-top:56px;">
          "Não há mudança sem sonho como não há sonho sem esperança."
          <cite>Paulo Freire</cite>
        </blockquote>
      </div>
      <div class="hero-image" data-reveal="right">
        <div class="blob-wrapper">
          <img src="./assets/img/logo.png" alt="Pé de Manga" class="floating-logo">
          <div class="blob"></div>
        </div>
      </div>
    </div>
  </section>

  <div class="pillars-strip">
    <div class="pillar-item"><span class="material-symbols-outlined pi-icon">comedy_mask</span>Arte & Cultura</div>
    <div class="pillar-item"><span class="material-symbols-outlined pi-icon">cognition_2</span>Saúde Mental</div>
    <div class="pillar-item"><span class="material-symbols-outlined pi-icon">compost</span>Sustentabilidade</div>
    <div class="pillar-item"><span class="material-symbols-outlined pi-icon">partner_heart</span>Impacto Comunitário</div>
  </div>

  <section style="background:var(--fundo-claro);">
    <div class="section-inner">
      <p class="section-tag reveal">Nossa essência</p>
      <h2 class="section-title reveal">Um lugar de <em>encontro</em></h2>
      <div class="divider reveal"></div>
      <p class="section-lead reveal">Somos um coletivo organizado que une arte, natureza e bem-estar para criar
        experiências que transformam pessoas e comunidades. Reconhecidos como Ponto de Cultura pelo Ministério da
        Cultura.</p>
      <div style="margin-top:36px;display:flex;gap:14px;flex-wrap:wrap;" class="reveal">
        <a href="quem-somos.php" class="btn btn-primary">Saiba mais sobre nós</a>
        <a href="o-que-fazemos.php" class="btn btn-outline">Nossas atividades</a>
      </div>
    </div>
  </section>

  <section>
    <div class="section-inner">
      <p class="section-tag reveal">Explore</p>
      <h2 class="section-title reveal">Tudo que o Pé de Manga <em>oferece</em></h2>
      <div class="divider reveal"></div>
      <div class="features-grid reveal">
        <a href="o-que-fazemos.php" style="text-decoration:none;">
          <div class="card" style="padding:28px;background:var(--fundo-claro);text-align:center;">
            <span class="card-cta-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#4a2f2a"><path d="m560-240-56-58 142-142H160v-80h486L504-662l56-58 240 240-240 240Z"/></svg>
            </span>
            <div style="margin-bottom:12px; color:#4A2f2A;"><span style="font-size:3.5rem;"
                class="material-symbols-outlined pi-icon">comedy_mask</span></div>
            <h3
              style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--marrom-escuro);margin-bottom:8px;">
              Atividades Culturais</h3>
            <p style="font-size:.84rem;color:var(--marrom-escuro);line-height:1.65;">Oficinas, vivências, apresentações e formações que estimulam a criação e o pensamento crítico.</p>
          </div>
        </a>
        <a href="produtos.php" style="text-decoration:none;">
          <div class="card" style="padding:28px;background:var(--fundo-claro);text-align:center;">
            <span class="card-cta-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#4a2f2a"><path d="m560-240-56-58 142-142H160v-80h486L504-662l56-58 240 240-240 240Z"/></svg>
            </span>
            <div style="margin-bottom:12px; color:#4A2f2A;"><span style="font-size:3.5rem;"
                class="material-symbols-outlined pi-icon">local_mall</span></div>
            <h3
              style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--marrom-escuro);margin-bottom:8px;">
              Loja Solidária</h3>
            <p style="font-size:.84rem;color:var(--marrom-escuro);line-height:1.65;">Camisetas artesanais, pães de fermentação
              natural e outros produtos que sustentam nossa missão.</p>
          </div>
        </a>
        <a href="doacoes.php" style="text-decoration:none;">
          <div class="card" style="padding:28px;background:var(--fundo-claro);text-align:center;">
            <span class="card-cta-icon" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" fill="#4a2f2a"><path d="m560-240-56-58 142-142H160v-80h486L504-662l56-58 240 240-240 240Z"/></svg>
            </span>
            <div style="margin-bottom:12px; color:#4A2f2A;"><span style="font-size:3.5rem;"
                class="material-symbols-outlined pi-icon">diversity_1</span></div>
            <h3
              style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--marrom-escuro);margin-bottom:8px;">
              Apoie</h3>
            <p style="font-size:.84rem;color:var(--marrom-escuro);line-height:1.65;">Cada contribuição financia ações culturais gratuitas para a comunidade. Doe via Pix com facilidade.</p>
          </div>
        </a>
      </div>
    </div>
  </section>

  <section style="background:var(--marrom-escuro);">
    <div class="section-inner" style="text-align:center;padding:70px 5vw;">
      <blockquote
        style="font-family:'Playfair Display',serif;font-style:italic;font-size:clamp(1.2rem,3vw,1.7rem);color:var(--branco);line-height:1.5;max-width:720px;margin:0 auto;"
        class="reveal">
        "Tudo está interligado: a terra, a água, o ar, as plantas e os animais."
      </blockquote>
      <cite
        style="display:block;margin-top:16px;font-size:.75rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--amarelo);font-style:normal;"
        class="reveal">Ana Primavesi</cite>
    </div>
  </section>

  <?php require 'includes/footer.php'; ?>
</body>

</html>