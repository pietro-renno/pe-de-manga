<?php
require_once 'includes/config.php';

$db = get_db();

// ── Todos os eventos (para o calendário JS) ────────────────
$eventos = get_eventos();

$meses_pt = [
  '', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
  'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
];

// ── Programação do mês atual (uma ou várias imagens) ────────
$mes_atual = (int) date('n');
$ano_atual = (int) date('Y');
try {
  $progs_atual = get_programacoes_mes($mes_atual, $ano_atual);
} catch (\Exception $e) {
  $progs_atual = [];
}

// ── Caminho base para o JS ──────────────────────────────────
// Detecta o subfolder caso o site não esteja na raiz
$base_url = rtrim(
  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']),
  '/'
) . '/';
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
  <link rel="stylesheet" href="assets/css/style.css?v=3">
  <link rel="stylesheet" href="assets/css/calendario.css">
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
    <div class="page-hero">
      <p class="ph-tag">Memórias &amp; Registros</p>
      <h1>Nossos <em>eventos</em></h1>
      <p>Programação cultural, encontros e registros do que acontece no Pé de Manga.</p>
    </div>

    <!-- ══════════════════════════════════════
         SEÇÃO PRINCIPAL
    ══════════════════════════════════════ -->
    <section style="background:var(--fundo-claro);">
      <div class="section-inner">
        <p class="section-tag reveal">Galeria &amp; Calendário</p>
        <h2 class="section-title reveal">Nossos <em>encontros</em></h2>
        <div class="divider reveal"></div>

        <?php if (empty($eventos)): ?>
          <div style="text-align:center;padding:60px 0;">
            <span class="material-symbols-outlined"
              style="font-size:3rem;color:var(--amarelo);display:block;margin-bottom:14px;">event_busy</span>
            <p style="color:var(--marrom-escuro);opacity:.75;">Nenhum evento cadastrado ainda.</p>
          </div>
        <?php else: ?>

          <!-- ══ Layout principal: calendário + painel ══ -->
          <div class="cal-shell reveal">

            <!-- ══ COLUNA ESQUERDA: Widget do Calendário ══ -->
            <div class="cal-widget" id="cal-widget" aria-label="Calendário de eventos">
              <div class="cal-widget-inner">

                <!-- Header: navegação de mês -->
                <div class="cal-header">
                  <button id="cal-prev" class="cal-nav-btn" type="button" aria-label="Mês anterior">
                    &#8249;
                  </button>
                  <span id="cal-mes-ano"></span>
                  <button id="cal-next" class="cal-nav-btn" type="button" aria-label="Próximo mês">
                    &#8250;
                  </button>
                </div>

                <!-- Grade de dias -->
                <div class="cal-grid" id="cal-grid" role="grid" aria-label="Dias do mês">
                  <!-- Cabeçalho com dias da semana -->
                  <div class="cal-dow" aria-hidden="true">Dom</div>
                  <div class="cal-dow" aria-hidden="true">Seg</div>
                  <div class="cal-dow" aria-hidden="true">Ter</div>
                  <div class="cal-dow" aria-hidden="true">Qua</div>
                  <div class="cal-dow" aria-hidden="true">Qui</div>
                  <div class="cal-dow" aria-hidden="true">Sex</div>
                  <div class="cal-dow" aria-hidden="true">Sáb</div>
                  <!-- Células geradas pelo JS -->
                </div>

                <!-- Lista de eventos do dia (quando há múltiplos) -->
                <div class="cal-events-list" id="cal-events-list" role="listbox"
                  aria-label="Eventos do dia selecionado"></div>

              </div><!-- /cal-widget-inner -->

              <!-- Legenda -->
              <div class="cal-legenda" aria-hidden="true">
                <div class="cal-legenda-item">
                  <span class="cal-legenda-dot cal-legenda-dot--evento"></span>
                  Evento
                </div>
                <div class="cal-legenda-item">
                  <span class="cal-legenda-dot cal-legenda-dot--hoje"></span>
                  Hoje
                </div>
                <div class="cal-legenda-item">
                  <span class="cal-legenda-dot cal-legenda-dot--sel"></span>
                  Selecionado
                </div>
              </div>
            </div><!-- /cal-widget -->

            <!-- ══ COLUNA DIREITA: programação + painel de fotos ══ -->
            <div class="cal-right">

              <!-- Programação mensal (se houver) — carrossel de imagens -->
              <?php if (!empty($progs_atual)): ?>
                <div class="cal-prog-card">
                  <div class="cal-prog-carousel" id="progCarousel">
                    <?php foreach ($progs_atual as $i => $prog): ?>
                      <img class="cal-prog-thumb<?= $i === 0 ? ' is-active' : '' ?>"
                        src="data/uploads/<?= htmlspecialchars($prog['imagem']) ?>"
                        alt="Programação de <?= $meses_pt[$mes_atual] ?> (<?= $i + 1 ?>)"
                        data-index="<?= $i ?>" <?= $i === 0 ? '' : 'hidden' ?>
                        onclick="abrirProg(this.src)">
                    <?php endforeach; ?>

                    <?php if (count($progs_atual) > 1): ?>
                      <button type="button" class="cal-prog-nav cal-prog-prev" id="progPrev"
                        aria-label="Imagem anterior">&#8249;</button>
                      <button type="button" class="cal-prog-nav cal-prog-next" id="progNext"
                        aria-label="Próxima imagem">&#8250;</button>
                      <span class="cal-prog-counter" id="progCounter">1/<?= count($progs_atual) ?></span>
                    <?php endif; ?>
                  </div>
                  <div class="cal-prog-info">
                    <h4>Programação de <em><?= $meses_pt[$mes_atual] ?></em></h4>
                    <p>
                      <?php if (count($progs_atual) > 1): ?>
                        <?= count($progs_atual) ?> imagens nesta programação. Use as setas para navegar e clique para ampliar.
                      <?php else: ?>
                        Confira nossa programação mensal! Clique na imagem para ampliar.
                      <?php endif; ?>
                    </p>
                    <a href="https://www.instagram.com/pedemangacpv/" target="_blank" rel="noopener"
                      class="insta-link">
                      <i class="fab fa-instagram"></i> Ver no Instagram
                    </a>
                  </div>
                </div>
              <?php endif; ?>

              <!-- Painel de fotos do evento selecionado -->
              <div class="cal-panel" id="cal-panel">

                <!-- Header: título + meta -->
                <div id="panel-header" hidden>
                  <h2 id="panel-titulo"></h2>
                  <p id="panel-meta"></p>
                </div>

                <!-- Estado: loading -->
                <div id="panel-loading" hidden>
                  <div class="cal-spinner"></div>
                  <span>Carregando fotos…</span>
                </div>

                <!-- Estado: sem fotos -->
                <div id="panel-sem-fotos" hidden>
                  <span class="material-symbols-outlined">photo_library</span>
                  <p>Nenhuma foto neste evento ainda.</p>
                </div>

                <!-- Grade de fotos -->
                <div id="panel-fotos-grid" hidden></div>

                <!-- Placeholder: nenhum evento selecionado -->
                <div id="panel-placeholder">
                  <span class="material-symbols-outlined">calendar_month</span>
                  <p>Selecione um dia no calendário para ver as fotos do evento.</p>
                </div>

              </div><!-- /cal-panel -->

            </div><!-- /cal-right -->

          </div><!-- /cal-shell -->

        <?php endif; ?>
      </div><!-- /section-inner -->
    </section>

  </div><!-- /page-wrap -->

  <!-- Lightbox fotos do evento -->
  <div id="lightbox" role="dialog" aria-modal="true" aria-label="Foto ampliada">
    <button onclick="fecharLightbox()"
      style="position:fixed;top:18px;right:24px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:401;"
      aria-label="Fechar lightbox">&times;</button>
    <img id="lightbox-img" src="" alt="">
    <p id="lightbox-cap"></p>
  </div>

  <!-- Lightbox imagem programação do mês -->
  <div id="progLightbox" onclick="this.style.display='none'"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:500;align-items:center;justify-content:center;cursor:zoom-out;"
    role="dialog" aria-modal="true" aria-label="Programação do mês ampliada">
    <button onclick="document.getElementById('progLightbox').style.display='none'"
      style="position:fixed;top:18px;right:24px;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;z-index:501;"
      aria-label="Fechar">&times;</button>
    <img id="progLightboxImg" src="" alt="Programação do mês"
      style="max-width:94vw;max-height:94vh;border-radius:6px;box-shadow:0 20px 60px rgba(0,0,0,.6);">
  </div>

  <?php require 'includes/footer.php'; ?>

  <!-- Dados dos eventos injetados para o JS -->
  <script>
    window.PdM_Eventos = <?= json_encode(
      array_map(fn($ev) => [
        'id'          => (int) $ev['id'],
        'nome'        => $ev['nome'],
        'data_evento' => $ev['data_evento'],
        'horario'     => $ev['horario'] ?? null,
        'total_fotos' => (int) $ev['total_fotos'],
      ], $eventos),
      JSON_UNESCAPED_UNICODE
    ) ?>;
    window.PdM_Base = <?= json_encode($base_url) ?>;
  </script>

  <script src="assets/js/calendario.js"></script>
</body>

</html>