<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga é um Ponto de Cultura dedicado à arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Parceiros - Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <div class="page-hero">
      <p class="ph-tag">Rede de apoio</p>
      <h1>Nossos <em>parceiros</em></h1>
      <p>Construímos parcerias com organizações que compartilham nossos valores de cultura e responsabilidade social.</p>
    </div>
    <section>
      <div class="section-inner">
        <?php $parceiros = get_parceiros(); ?>
        <?php if (!empty($parceiros)): ?>
          <div class="parceiros-grid">
            <?php foreach ($parceiros as $p): ?>
              <div class="parceiro-card">
                <div class="parceiro-img-wrapper">
                  <?php if (!empty($p['logo'])): ?>
                    <img src="data/uploads/<?php echo htmlspecialchars($p['logo']); ?>"
                      alt="<?php echo htmlspecialchars($p['nome']); ?>">
                  <?php else: ?>
                    <div class="parceiro-placeholder">&#129309;</div>
                  <?php endif; ?>
                </div>
                <div class="parceiro-info">
                  <h3 class="pc-name"><?php echo htmlspecialchars($p['nome']); ?></h3>
                  <?php if (!empty($p['desc'])): ?>
                    <p class="pc-desc"><?php echo htmlspecialchars($p['desc']); ?></p>
                  <?php endif; ?>
                  <?php if (!empty($p['site'])): ?>
                    <a href="<?php echo htmlspecialchars($p['site']); ?>" target="_blank"
                      class="pc-link">Ver site &rarr;</a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div style="text-align:center;padding:60px 20px;color:var(--marrom-escuro);opacity:.85;">
            <div style="font-size:3rem;margin-bottom:16px;">&#129309;</div>
            <p>Os parceiros serão apresentados em breve.</p>
          </div>
        <?php endif; ?>
        <div class="parceiros-cta reveal">
          <p>Quer ser parceiro do Pé de Manga? Entre em contato e construa junto conosco.</p>
          <a href="mailto:pedemangacpv@gmail.com?subject=Parceria" class="btn btn-primary">Propor parceria</a>
        </div>
      </div>
    </section>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>