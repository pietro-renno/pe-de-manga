<?php
require_once 'includes/config.php';

$db = get_db();
$eventos = get_eventos();

// evento selecionado via URL ou o mais recente
$ev_id = isset($_GET['id']) ? (int) $_GET['id'] : ($eventos[0]['id'] ?? 0);
$ev_sel = null;
$fotos = [];

if ($ev_id > 0) {
  $st = $db->prepare('SELECT * FROM eventos WHERE id = ?');
  $st->execute([$ev_id]);
  $ev_sel = $st->fetch(PDO::FETCH_ASSOC);
  if ($ev_sel) {
    $fotos = get_evento_fotos($ev_id);
  }
}
// fallback para primeiro da lista se id inválido
if (!$ev_sel && !empty($eventos)) {
  $ev_sel = $eventos[0];
  $ev_id = $ev_sel['id'];
  $fotos = get_evento_fotos($ev_id);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description" content="Galeria de eventos do Pé de Manga — oficinas, apresentações e muito mais.">
  <title>Eventos – Pé de Manga</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php require 'includes/nav.php'; ?>

  <div class="page-wrap">
    <div class="page-hero">
      <p class="ph-tag">Memórias & Registros</p>
      <h1>Nossos <em>eventos</em></h1>
      <p>Registro do que acontece no Pé de Manga - processos, encontros, criações e transformações.</p>
      <p><strong>Selecione um evento para ver as fotos.</strong></p>
    </div>

    <section>
      <div class="section-inner">
        <?php if (empty($eventos)): ?>
          <p style="text-align:center;padding:60px 0;color:var(--marrom);opacity:.6;">
            Nenhum evento cadastrado ainda.
          </p>
        <?php else: ?>
          <div class="eventos-layout">

            <!-- LISTA DE EVENTOS -->
            <aside class="eventos-lista">
              <?php foreach ($eventos as $ev): ?>
                <a href="eventos.php?id=<?= $ev['id'] ?>" class="evento-item <?= $ev['id'] == $ev_id ? 'ativo' : '' ?>">
                  <div class="evento-item-data">
                    <span class="ev-dia"><?= date('d', strtotime($ev['data_evento'])) ?></span>
                    <span class="ev-mes"><?= strftime_mes($ev['data_evento']) ?></span>
                    <span class="ev-ano"><?= date('Y', strtotime($ev['data_evento'])) ?></span>
                  </div>
                  <div class="evento-item-info">
                    <strong><?= htmlspecialchars($ev['nome']) ?></strong>
                    <span><?= $ev['total_fotos'] ?> foto<?= $ev['total_fotos'] != 1 ? 's' : '' ?></span>
                  </div>
                </a>
              <?php endforeach; ?>
            </aside>

            <!-- FOTOS DO EVENTO SELECIONADO -->
            <div class="eventos-fotos">
              <?php if ($ev_sel): ?>
                <div class="eventos-fotos-header">
                  <h2><?= htmlspecialchars($ev_sel['nome']) ?></h2>
                  <p class="eventos-fotos-meta">
                    <?= date('d/m/Y', strtotime($ev_sel['data_evento'])) ?>
                    <?php if (!empty($ev_sel['descricao'])): ?>
                      &nbsp;·&nbsp; <?= htmlspecialchars($ev_sel['descricao']) ?>
                    <?php endif; ?>
                  </p>
                </div>
                <?php if (empty($fotos)): ?>
                  <p style="padding:40px 0;color:var(--marrom);opacity:.6;">Nenhuma foto neste evento ainda.</p>
                <?php else: ?>
                  <div class="eventos-fotos-grid" id="fotosGrid">
                    <?php foreach ($fotos as $foto): ?>
                      <div class="eventos-foto-item"
                        onclick="abrirLightbox('<?= htmlspecialchars($foto['arquivo']) ?>', '<?= htmlspecialchars(addslashes($foto['descricao'] ?? '')) ?>')">
                        <img src="data/uploads/<?= htmlspecialchars($foto['arquivo']) ?>"
                          alt="<?= htmlspecialchars($foto['descricao'] ?? $ev_sel['nome']) ?>" loading="lazy">
                        <?php if (!empty($foto['descricao'])): ?>
                          <div class="eventos-foto-legenda"><?= htmlspecialchars($foto['descricao']) ?></div>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>

          </div>
        <?php endif; ?>
        <div style="margin-top: 30px;" class="mvv-cards">
          <div class="mvv-card reveal">
            <div>
              <h4 style="text-transform: initial;">Ponto de Cultura em Ação</h4>
              <p>Assista ao vídeo sobre o Pé de Manga e o Programa Cultura Viva.</p>
              <a href="https://www.youtube.com/watch?v=T-kSSKDsM8I" target="_blank">Assistir no YouTube &rarr;</a>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

  <!-- Lightbox -->
  <div id="lightbox" onclick="fecharLightbox()">
    <button onclick="fecharLightbox()"
      style="position:fixed;top:18px;right:24px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:401;">&times;</button>
    <img id="lightbox-img" src="" alt="">
    <p id="lightbox-cap"></p>
  </div>

  <?php require 'includes/footer.php'; ?>
  <script src="assets/js/main.js"></script>
  <script>
    function abrirLightbox(arquivo, legenda) {
      document.getElementById('lightbox-img').src = 'data/uploads/' + arquivo;
      document.getElementById('lightbox-cap').textContent = legenda;
      document.getElementById('lightbox').classList.add('open');
    }
    function fecharLightbox() {
      document.getElementById('lightbox').classList.remove('open');
      document.getElementById('lightbox-img').src = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') fecharLightbox(); });
  </script>
</body>

</html>
<?php
function strftime_mes(string $date): string
{
  $meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
  return $meses[(int) date('n', strtotime($date)) - 1];
}
?>