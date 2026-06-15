<?php
require_once 'includes/config.php';

$db = get_db();

// ── Dados dos eventos ──────────────────────────────────────
$eventos = get_eventos();

$meses_pt = [
  '',
  'Janeiro',
  'Fevereiro',
  'Março',
  'Abril',
  'Maio',
  'Junho',
  'Julho',
  'Agosto',
  'Setembro',
  'Outubro',
  'Novembro',
  'Dezembro'
];

// Organiza por ano → mês
$por_ano_mes = [];
foreach ($eventos as $ev) {
  $ts = strtotime($ev['data_evento']);
  $ano = (int) date('Y', $ts);
  $mes = (int) date('n', $ts);
  $por_ano_mes[$ano][$mes][] = $ev;
}
krsort($por_ano_mes);
foreach ($por_ano_mes as &$meses) {
  krsort($meses);
}
unset($meses);

// ── Evento selecionado (para ver fotos) ──────────────────────
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
if (!$ev_sel && !empty($eventos)) {
  $ev_sel = $eventos[0];
  $ev_id = $ev_sel['id'];
  $fotos = get_evento_fotos($ev_id);
}

$ano_ativo = $ev_sel ? (int) date('Y', strtotime($ev_sel['data_evento'])) : 0;
$mes_ativo = $ev_sel ? (int) date('n', strtotime($ev_sel['data_evento'])) : 0;

// ── Programação do mês atual ────────────────────────────────
$mes_atual = (int) date('n');
$ano_atual = (int) date('Y');
try {
  $prog_atual = get_programacao_mes($mes_atual, $ano_atual);
} catch (\Exception $e) {
  $prog_atual = null;
}

function strftime_mes_abr(string $date): string
{
  $meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
  return $meses[(int) date('n', strtotime($date)) - 1];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="./favicon.ico">
  <meta name="description"
    content="Confira os eventos e a programação cultural do Pé de Manga em Caçapava. Arte, cultura e afeto em cada encontro.">
  <meta name="keywords"
    content="Pé de Manga, eventos culturais, programação, Caçapava, Ponto de Cultura, oficinas, arte">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    rel="stylesheet" />
  <title>Eventos – Pé de Manga</title>
</head>

<body>
  <?php require 'includes/nav.php'; ?>

  <div class="page-wrap">

    <!-- ══════════════════════════════════════
         HERO DA PÁGINA
    ══════════════════════════════════════ -->
    <div class="page-hero" data-emoji="📅">
      <p class="ph-tag">Memórias &amp; Registros</p>
      <h1>Nossos <em>eventos</em></h1>
      <p>Programação cultural, encontros e registros do que acontece no Pé de Manga.</p>
    </div>

    <!-- ══════════════════════════════════════
         CALENDÁRIO DE EVENTOS & PROGRAMAÇÃO
    ══════════════════════════════════════ -->
    <section style="background:var(--fundo-claro);">
      <div class="section-inner">
        <p class="section-tag reveal">Galeria & Calendário</p>
        <h2 class="section-title reveal">Nossos <em>encontros</em></h2>
        <div class="divider reveal"></div>

        <?php if (empty($eventos)): ?>
          <div style="text-align:center;padding:60px 0;">
            <span class="material-symbols-outlined"
              style="font-size:3rem;color:var(--amarelo);display:block;margin-bottom:14px;">event_busy</span>
            <p style="color:var(--marrom-escuro);opacity:.75;">Nenhum evento cadastrado ainda.</p>
          </div>
        <?php else: ?>

          <div class="eventos-layout reveal <?= $prog_atual ? 'has-prog' : '' ?>">

            <!-- Programação Lateral do Mês (Instagram Card) -->
            <?php if ($prog_atual): ?>
              <aside class="eventos-prog-lateral">
                <h3>Programação de <em><?= $meses_pt[$prog_atual['mes']] ?></em></h3>
                <img src="data/uploads/<?= htmlspecialchars($prog_atual['imagem']) ?>"
                  alt="Programação de <?= $meses_pt[$prog_atual['mes']] ?>"
                  onclick="abrirProg(this.src)">
                <p>Confira a programação mensal do Pé de Manga! Clique na imagem para ampliar.</p>
                <a href="https://www.instagram.com/pedemangacpv/" target="_blank" rel="noopener" class="insta-link">
                  <i class="fab fa-instagram"></i> Ver no Instagram
                </a>
              </aside>
            <?php endif; ?>

            <!-- LISTA LATERAL: Acordeão por Ano → Mês -->
            <aside class="eventos-lista">
              <?php foreach ($por_ano_mes as $ano => $meses): ?>
                <div class="ev-ano-label"><?= $ano ?></div>
                <?php foreach ($meses as $mes_num => $evs_mes): ?>
                  <?php $aberto = ($ano === $ano_ativo && $mes_num === $mes_ativo); ?>
                  <div class="ev-grupo <?= $aberto ? 'open' : '' ?>">
                    <button class="ev-grupo-header" onclick="toggleGrupo(this)"
                      aria-expanded="<?= $aberto ? 'true' : 'false' ?>">
                      <span class="ev-grupo-mes"><?= $meses_pt[$mes_num] ?></span>
                      <span class="ev-grupo-count"><?= count($evs_mes) ?></span>
                      <span class="ev-grupo-arrow">&#8250;</span>
                    </button>
                    <div class="ev-grupo-body">
                      <?php foreach ($evs_mes as $ev): ?>
                        <a href="eventos.php?id=<?= $ev['id'] ?>" class="evento-item <?= $ev['id'] == $ev_id ? 'ativo' : '' ?>">
                          <div class="evento-item-data">
                            <span class="ev-dia"><?= date('d', strtotime($ev['data_evento'])) ?></span>
                            <span class="ev-mes"><?= strftime_mes_abr($ev['data_evento']) ?></span>
                          </div>
                          <div class="evento-item-info">
                            <strong><?= htmlspecialchars($ev['nome']) ?></strong>
                            <span>
                              <?php if (!empty($ev['horario'])): ?>
                                <span class="ev-horario-tag"><?= htmlspecialchars($ev['horario']) ?></span> ·
                              <?php endif; ?>
                              <?= $ev['total_fotos'] ?> foto<?= $ev['total_fotos'] != 1 ? 's' : '' ?>
                            </span>
                          </div>
                        </a>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </aside>

          </div><!-- /eventos-layout -->

          <!-- PAINEL DE FOTOS DO EVENTO SELECIONADO -->
          <div class="eventos-fotos reveal">
            <?php if ($ev_sel): ?>
              <div class="eventos-fotos-header">
                <h2><?= htmlspecialchars($ev_sel['nome']) ?></h2>
                <p class="eventos-fotos-meta">
                  <?= date('d/m/Y', strtotime($ev_sel['data_evento'])) ?>
                  <?php if (!empty($ev_sel['horario'])): ?>
                    &nbsp;·&nbsp;
                    <span class="ev-horario-pill">
                      <span class="material-symbols-outlined" style="font-size:.9rem;vertical-align:middle;">schedule</span>
                      <?= htmlspecialchars($ev_sel['horario']) ?>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($ev_sel['descricao'])): ?>
                    &nbsp;·&nbsp; <?= htmlspecialchars($ev_sel['descricao']) ?>
                  <?php endif; ?>
                </p>
              </div>
              <?php if (empty($fotos)): ?>
                <div class="eventos-sem-fotos">
                  <span class="material-symbols-outlined">photo_library</span>
                  <p>Nenhuma foto neste evento ainda.</p>
                </div>
              <?php else: ?>
                <div class="eventos-fotos-grid" id="fotosGrid">
                  <?php foreach ($fotos as $foto): ?>
                    <div class="eventos-foto-item"
                      onclick="abrirLightbox('<?= htmlspecialchars($foto['arquivo']) ?>','<?= htmlspecialchars(addslashes($foto['descricao'] ?? '')) ?>')">
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
        <?php endif; ?>
      </div>
    </section>

  </div><!-- /page-wrap -->

  <!-- Lightbox fotos do evento -->
  <div id="lightbox" onclick="fecharLightbox()">
    <button onclick="fecharLightbox()"
      style="position:fixed;top:18px;right:24px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:401;">&times;</button>
    <img id="lightbox-img" src="" alt="">
    <p id="lightbox-cap"></p>
  </div>

  <!-- Lightbox imagem programação do mês -->
  <div id="progLightbox" onclick="this.style.display='none'"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:500;align-items:center;justify-content:center;cursor:zoom-out;">
    <button onclick="document.getElementById('progLightbox').style.display='none'"
      style="position:fixed;top:18px;right:24px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:501;">&times;</button>
    <img id="progLightboxImg" src="" alt=""
      style="max-width:94vw;max-height:94vh;border-radius:6px;box-shadow:0 20px 60px rgba(0,0,0,.6);">
  </div>

  <?php require 'includes/footer.php'; ?>
  <script src="assets/js/main.js"></script>
  <script>
    function toggleGrupo(btn) {
      const grupo = btn.parentElement;
      // Determine whether we're opening this group (if not already open)
      const abrir = !grupo.classList.contains('open');
      // Close other groups inside the eventos-lista
      document.querySelectorAll('.eventos-lista .ev-grupo').forEach(g => {
        if (g !== grupo) {
          g.classList.remove('open');
          const header = g.querySelector('.ev-grupo-header');
          if (header) header.setAttribute('aria-expanded', 'false');
        }
      });
      // Toggle current group according to abrir
      if (abrir) {
        grupo.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      } else {
        grupo.classList.remove('open');
        btn.setAttribute('aria-expanded', 'false');
      }
    }
    function abrirLightbox(arquivo, legenda) {
      document.getElementById('lightbox-img').src = 'data/uploads/' + arquivo;
      document.getElementById('lightbox-cap').textContent = legenda;
      document.getElementById('lightbox').classList.add('open');
    }
    function fecharLightbox() {
      document.getElementById('lightbox').classList.remove('open');
      document.getElementById('lightbox-img').src = '';
    }
    function abrirProg(src) {
      const lb = document.getElementById('progLightbox');
      document.getElementById('progLightboxImg').src = src;
      lb.style.display = 'flex';
    }
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        fecharLightbox();
        document.getElementById('progLightbox').style.display = 'none';
      }
    });
  </script>
</body>

</html>