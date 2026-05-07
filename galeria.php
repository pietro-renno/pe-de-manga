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
  <title>Galeria - Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <section class="galeria-section">
      <div class="section-inner">
        <p class="section-tag">Memória viva</p>
        <h2 class="section-title">Galeria de <em>eventos</em></h2>
        <div class="divider"></div>
        <p class="section-lead">Registro do que acontece no Pé de Manga - processos, encontros, criações e transformações.</p>
        <?php $fotos = get_galeria(); ?>
        <?php if (!empty($fotos)): ?>
          <div class="galeria-grid reveal">
            <?php foreach ($fotos as $f): ?>
              <div class="galeria-item"
                onclick="openLightbox('data/uploads/<?php echo htmlspecialchars($f['arquivo']); ?>')">
                <img src="data/uploads/<?php echo htmlspecialchars($f['arquivo']); ?>"
                  alt="<?php echo htmlspecialchars($f['descricao'] ?? ''); ?>">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="galeria-empty reveal">
            <span class="ge-icon">&#127916;</span>
            <p>As fotos dos nossos eventos serão exibidas aqui em breve.</p>
          </div>
        <?php endif; ?>
        <div class="galeria-yt reveal">
          <span class="yt-icon">&#9654;&#65039;</span>
          <div>
            <h4>Ponto de Cultura em Ação</h4>
            <p>Assista ao vídeo sobre o Pé de Manga e o Programa Cultura Viva.</p>
            <a href="https://www.youtube.com/watch?v=T-kSSKDsM8I" target="_blank">Assistir no YouTube &rarr;</a>
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="lightbox" id="lightbox" onclick="closeLightbox()">
    <button class="lb-close" onclick="closeLightbox()">&times;</button>
    <img src="" alt="Foto">
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>