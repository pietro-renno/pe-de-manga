<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga e um Ponto de Cultura dedicado a arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Quem Somos - Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=mood_heart,nest_eco_leaf,partner_heart,shield_with_heart,volunteer_activism" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <div class="page-hero" data-emoji="&#129365;">
      <p class="ph-tag">Nossa história</p>
      <h1>Quem <em>somos</em></h1>
      <p>Um espaço dedicado a arte como caminho de cuidado, pertencimento e transformação social.</p>
    </div>
    <section>
      <div class="section-inner">
        <div class="quem-grid">
          <div class="quem-text">
            <p class="reveal">O <strong>Pé de Manga &#x2013; Cultura e Afeto</strong> e um espaço dedicado a arte como
              caminho de cuidado, pertencimento e transformacao social. Atuamos na interseccao entre cultura, saude
              mental, sustentabilidade e bem-estar, promovendo experiências que fortalecem vinculos e ampliam
              possibilidades individuais e coletivas.</p>
            <p class="reveal">Somos um territorio de encontros, formação e criação. Aqui, o fazer artístico dialoga com
              a escuta, a natureza e a responsabilidade social. Nosso espaço acolhe pessoas de diferentes idades para
              vivencias culturais, ações formativas e práticas de cuidado coletivo.</p>
            <p class="reveal">Cada atividade realizada no Pé de Manga carrega um compromisso: tornar a cultura acessível
              e gerar impacto positivo na comunidade. Acreditamos que a arte não é apenas expressão, é
              ferramenta de desenvolvimento humano e construção de futuros mais sensíveis.</p>
            <div class="ponto-cultura reveal">
              <div class="ponto-icon">&#127963;</div>
              <div class="ponto-text">
                <h4>Ponto de Cultura &#x2013; Programa Cultura Viva</h4>
                <p>O Pé de Manga e reconhecido pelo Ministério da Cultura como Ponto de Cultura, integrando uma rede
                  nacional comprometida com a democratização do acesso à cultura.</p>
                <a href="https://culturaviva.cultura.gov.br/agente/14621536/" target="_blank">Ver certificação
                  &rarr;</a>
              </div>
            </div>
          </div>
          <div class="mvv-cards">
            <div class="mvv-card reveal">
              <h4>Missão</h4>
              <p>Promover a arte como prática de cuidado, fortalecendo a saude mental, os vinculos comunitários e o
                desenvolvimento humano por meio de ações culturais acessíveis, sustentáveis e socialmente responsáveis.
              </p>
            </div>
            <div class="mvv-card reveal">
              <h4>Visão</h4>
              <p>Ser referência como espaço cultural de base comunitaria que integra arte, natureza e bem-estar,
                consolidando-se como territorio de pertencimento, formação e transformação social.</p>
            </div>
            <div class="mvv-card reveal">
              <h4>Valores</h4>
              <p>Cultura como direito. Arte como ferramenta de cuidado. Ética, responsabilidade social, acolhimento,
                escuta e respeito as diversidades. Sustentabilidade, pertencimento e autonomia como pilares para um
                desenvolvimento humano consciente e colaborativo.</p>
            </div>
          </div>
        </div>

        <div style="margin-top:70px;" class="reveal">
          <p class="section-tag">Nossa identidade</p>
          <h2 class="section-title">O que a nossa marca <em>comunica</em></h2>
          <div class="divider"></div>
          <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-top:30px;">
            <?php
            $valores = [
              ['<span style="font-size:2.5rem;" class="material-symbols-outlined">mood_heart</span>', 'Acolhimento'],
              ['<span style="font-size:2.5rem;" class="material-symbols-outlined">partner_heart</span>', 'Cuidado'],
              ['<span style="font-size:2.5rem;" class="material-symbols-outlined">nest_eco_leaf</span>', 'Raiz'],
              ['<span style="font-size:2.5rem;" class="material-symbols-outlined">volunteer_activism</span>', 'Coletividade'],
              ['<span style="font-size:2.5rem;" class="material-symbols-outlined">shield_with_heart</span>', 'Afeto com Responsabilidade'],
            ];
            foreach ($valores as $v): ?>
              <div
                style="text-align:center;padding:20px 12px;background:var(--fundo-claro);border-radius:12px;border-top:3px solid var(--amarelo);">
                <div style="font-size:1.7rem;margin-bottom:9px;"><?php echo $v[0]; ?></div>
                <p style="font-size:.78rem;font-weight:600;color:var(--marrom);line-height:1.4;"><?php echo $v[1]; ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div style="margin-top:60px;text-align:center;" class="reveal">
          <a href="https://www.youtube.com/watch?v=T-kSSKDsM8I" target="_blank" class="btn btn-primary"
            style="margin-right:12px;">&#9654; Assista ao nosso vídeo</a>
          <a href="doacoes.php" class="btn btn-verde">Apoie o Pé de Manga</a>
        </div>
      </div>
    </section>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>