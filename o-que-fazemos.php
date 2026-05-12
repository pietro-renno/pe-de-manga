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
  <title>O Que Fazemos - Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <div class="page-hero">
      <p class="ph-tag">Nosso trabalho</p>
      <h1>O que <em>fazemos</em></h1>
      <p>Quatro pilares que sustentam nossa proposta de cultura, cuidado e transformação coletiva.</p>
    </div>
    <section style="background:var(--fundo-claro);">
      <div class="section-inner">
        <p class="section-lead reveal">O Pé de Manga organiza suas acoes a partir de pilares estruturantes que se
          entrelacam e sustentam uma proposta que integra sensibilidade e organizacao, arte e responsabilidade, cuidado
          individual e transformacao coletiva.</p>
        <div class="pilares-grid" style="margin-top:50px;">
          <div class="pilar-card social reveal">
            <h3>Arte e Cultura</h3>
            <p>A arte e o eixo central do Pé de Manga. Desenvolvemos oficinas, vivências, apresentações, exposições e
              processos formativos que estimulam a criação, a expressão e o pensamento crítico. Acreditamos na cultura
              como direito fundamental e instrumento de transformação social.</p>
          </div>
          <div class="pilar-card social reveal">
            <h3>Saude Mental e Bem-Estar</h3>
            <p>Entendemos a arte como prática de cuidado. Nossas ações promovem espaços de escuta, convivência e
              fortalecimento emocional. O ambiente do Pé de Manga e estruturado para acolher, desacelerar e favorecer
              experiências que integrem corpo, mente e sensibilidade.</p>
          </div>
          <div class="pilar-card social reveal">
            <h3>Sustentabilidade e Natureza</h3>
            <p>O contato com a natureza e parte da nossa identidade. O jardim, os processos manuais e o cuidado com o
              espaço refletem nosso compromisso com praticas sustentaveis. Incentivamos o reaproveitamento de materiais
              e uma cultura de cuidado com o território.</p>
          </div>
          <div class="pilar-card social reveal">
            <h3>Responsabilidade Social e Impacto Comunitário</h3>
            <p>A cada atividade remunerada realizada, oferecemos uma atividade gratuita a grupos escolares ou projetos
              assistenciais. Promovemos o mutirão semanal de manutenção do espaço, fortalecendo redes locais e
              estimulando a participação ativa.</p>
          </div>
        </div>
        <div class="agenda-note reveal">
          <div class="an-icon">&#128197;</div>
          <div>
            <h3>Agenda de Atividades</h3>
            <p>Acompanhe nossa programação mensal no Instagram ou envie um e-mail para agendar uma visita. Atividades no nosso espaço (<strong>Vem pro Pé</strong>) e em
              outros espaços (<strong>Pé pra Fora</strong>). Cultura em movimento.</p>
            <div class="an-btns">
              <a href="mailto:pedemangacpv@gmail.com?subject=Agenda" class="btn btn-primary">Solicitar Agenda</a>
              <a href="https://instagram.com" target="_blank" class="btn btn-outline"
                style="color:#fff;border-color:rgba(255,255,255,.4);">Instagram</a>
            </div>
          </div>
        </div>

        <!-- MAPA -->
        <div class="mapa-section reveal">
          <p class="section-tag">Onde estamos</p>
          <h2 class="section-title">Nossa <em>localização</em></h2>
          <div class="divider"></div>
          <div class="mapa-wrap">
            <iframe
              src="https://maps.google.com/maps?q=Pé+de+Manga+Caçapava+SP&t=&z=17&ie=UTF8&iwloc=&output=embed"
              width="100%" height="420" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade" title="Localização do Pé de Manga">
            </iframe>
          </div>
          <div class="mapa-cta">
            <a href="https://maps.app.goo.gl/WJmJwxNSUe2Vjo5g7" target="_blank" class="btn btn-primary">
              <span style="vertical-align:middle;">&#9873;</span> Abrir no Google Maps
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>