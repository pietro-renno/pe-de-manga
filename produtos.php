<?php
require_once 'includes/config.php';
$produtos = get_produtos();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="Conheça os produtos do Pé de Manga. Camisetas, pães artesanais e muito mais. Cada compra apoia nossas ações culturais.">
  <meta name="keywords"
    content="Pé de Manga, produtos, loja solidária, camisetas, pão artesanal, cultura">
  <title>Produtos – Pé de Manga</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css?v=3">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>

  <div class="page-wrap">
    <div class="page-hero">
      <p class="ph-tag">Loja solidária</p>
      <h1>Nossos <em>produtos</em></h1>
      <p>Cada produto é feito com cuidado e intenção. Ao comprar, você apoia nossas ações culturais.</p>
    </div>

    <section>
      <div class="section-inner">
        <?php if (empty($produtos)): ?>
          <p style="text-align:center;padding:60px 0;color:var(--marrom-escuro);opacity:.85;">
            Nenhum produto disponível no momento.
          </p>
        <?php else: ?>
          <div class="produtos-grid">
            <?php foreach ($produtos as $p):
              $wa_msg  = rawurlencode('Olá! Gostaria de comprar: ' . $p['nome']);
              $wa_link = 'https://wa.me/' . WHATSAPP_NUM . '?text=' . $wa_msg;
            ?>
              <div class="produto-card reveal">
                <div class="produto-img" style="background:<?= htmlspecialchars($p['cor_fundo']) ?>;">
                  <?php if (!empty($p['foto'])): ?>
                    <img src="data/uploads/<?= htmlspecialchars($p['foto']) ?>"
                         alt="<?= htmlspecialchars($p['nome']) ?>">
                  <?php endif; ?>
                </div>
                <div class="produto-body">
                  <?php if (!empty($p['tag'])): ?>
                    <p class="produto-tag"><?= htmlspecialchars($p['tag']) ?></p>
                  <?php endif; ?>
                  <h3><?= htmlspecialchars($p['nome']) ?></h3>
                  <p><?= nl2br(htmlspecialchars($p['descricao'] ?? '')) ?></p>
                  <a href="<?= $wa_link ?>" target="_blank" rel="noopener" class="btn btn-verde">
                    Comprar
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <?php require 'includes/footer.php'; ?>
</body>

</html>
