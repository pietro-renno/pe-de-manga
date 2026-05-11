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
  <title>Colaboradores - Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <div class="page-hero">
      <p class="ph-tag">Quem faz acontecer</p>
      <h1>Nossos <em>colaboradores</em></h1>
      <p>O Pé de Manga e feito por pessoas comprometidas com a arte, o cuidado e a transformação social.</p>
    </div>
    <section>
      <div class="section-inner">
        <?php $colaboradores = get_colaboradores(); ?>
        <?php if (!empty($colaboradores)): ?>
          <div class="colab-grid">
            <?php foreach ($colaboradores as $c): ?>
              <div class="colab-card reveal">
                <div class="colab-avatar">
                  <?php if (!empty($c['foto'])): ?>
                    <img src="data/uploads/<?php echo htmlspecialchars($c['foto']); ?>"
                      alt="<?php echo htmlspecialchars($c['nome']); ?>">
                  <?php else: ?>
                    &#128100;
                  <?php endif; ?>
                </div>
                <h4><?php echo htmlspecialchars($c['nome']); ?></h4>
                <p class="colab-role"><?php echo htmlspecialchars($c['funcao']); ?></p>
                <?php if (!empty($c['descricao'])): ?>
                  <p><?php echo htmlspecialchars($c['descricao']); ?></p>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="colab-empty">
            <div style="font-size:3rem;margin-bottom:14px;">&#128101;</div>
            <p>A equipe de colaboradores será apresentada em breve.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>