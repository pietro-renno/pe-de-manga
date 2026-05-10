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
  <title>Transparência - Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <div class="page-hero" data-emoji="&#9989;">
      <p class="ph-tag">Prestacao de contas</p>
      <h1><em>Transparência</em></h1>
      <p>Compartilhamos nossas informações para que parceiros e a comunidade possam acompanhar nosso trabalho.</p>
    </div>
    <section>
      <div class="section-inner">
        <p class="transp-intro reveal">O Pé de Manga acredita na transparência como princípio ético. Compartilhamos
          nossas informações institucionais, financeiras e de impacto para que parceiros, apoiadores e a comunidade
          possam acompanhar e confiar no nosso trabalho.</p>
        <div class="transp-grid">
          <div class="transp-card reveal">
            <div class="transp-icon"><span style="font-size:2.5rem;"
                class="material-symbols-outlined pi-icon">article_shortcut</span></div>
            <h4>Informações Institucionais</h4>
            <p>Somos um coletivo organizado em processo de formalização como OSC (Organização da Sociedade Civil). CNPJ:
              23.456.588/0001-98</p>
            <span class="transp-badge">Em formalização</span>
          </div>
          <div class="transp-card reveal">
            <div class="transp-icon">
              <span style="font-size:2.5rem;" class="material-symbols-outlined pi-icon">comedy_mask</span>
            </div>
            <h4>Ponto de Cultura</h4>
            <p>Reconhecidos pelo Programa Cultura Viva do Ministério da Cultura como Ponto de Cultura, certificando
              nossas ações culturais de base comunitaria.</p>
            <span class="transp-badge">Certificado ativo</span>
          </div>
          <div class="transp-card reveal">
            <div class="transp-icon"><span style="font-size:2.5rem;"
                class="material-symbols-outlined pi-icon">money_range</span></div>
            <h4>Uso de Recursos</h4>
            <p>Todos os recursos arrecadados são destinados à manutenção do espaço e as ações culturais. Relatórios
              financeiros serão disponibilizados periodicamente.</p>
            <span class="transp-badge">Em breve</span>
          </div>
        </div>
        <div class="transp-ponto reveal">
          <div class="tp-icon"><span style="font-size:3.5rem;" class="material-symbols-outlined">globe</span></div>
          <div>
            <h4>Verifique nossa certificação no portal oficial</h4>
            <p>O Pé de Manga está registrado e certificado na plataforma do Programa Cultura Viva. Consulte nossa ficha
              no portal do Ministério da Cultura.</p>
            <a href="https://culturaviva.cultura.gov.br/agente/14621536/" target="_blank">Acessar portal Cultura Viva
              &rarr;</a>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>