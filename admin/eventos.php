<?php
require '_auth.php';

$db = get_db();
$msg = '';
$tipo_msg = '';

// ── CRIAR EVENTO ────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar_evento') {
  $nome = trim($_POST['nome'] ?? '');
  $desc = trim($_POST['descricao'] ?? '');
  $data = trim($_POST['data_evento'] ?? '');
  $horario = trim($_POST['horario'] ?? '');
  if ($nome === '' || $data === '') {
    $msg = 'Nome e data do evento são obrigatórios.';
    $tipo_msg = 'erro';
  } else {
    $db->prepare('INSERT INTO eventos (nome, descricao, data_evento, horario) VALUES (?, ?, ?, ?)')
      ->execute([$nome, $desc ?: null, $data, $horario ?: null]);
    $msg = 'Evento criado!';
    $tipo_msg = 'sucesso';
  }
}

// ── EDITAR EVENTO ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar_evento') {
  $id = (int) ($_POST['id'] ?? 0);
  $nome = trim($_POST['nome'] ?? '');
  $desc = trim($_POST['descricao'] ?? '');
  $data = trim($_POST['data_evento'] ?? '');
  $horario = trim($_POST['horario'] ?? '');
  if ($id > 0 && $nome !== '' && $data !== '') {
    $db->prepare('UPDATE eventos SET nome=?, descricao=?, data_evento=?, horario=? WHERE id=?')
      ->execute([$nome, $desc ?: null, $data, $horario ?: null, $id]);
    $msg = 'Evento atualizado!';
    $tipo_msg = 'sucesso';
  }
}

// ── EXCLUIR EVENTO ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_evento') {
  $id = (int) ($_POST['id'] ?? 0);
  if ($id > 0) {
    $fotos = $db->prepare('SELECT arquivo FROM evento_fotos WHERE evento_id = ?');
    $fotos->execute([$id]);
    foreach ($fotos->fetchAll(PDO::FETCH_COLUMN) as $arq) {
      $path = __DIR__ . '/../data/uploads/' . $arq;
      if (file_exists($path))
        unlink($path);
    }
    $db->prepare('DELETE FROM eventos WHERE id = ?')->execute([$id]);
  }
  header('Location: eventos.php');
  exit;
}

// ── UPLOAD FOTOS DO EVENTO ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'upload_fotos') {
  $evento_id = (int) ($_POST['evento_id'] ?? 0);
  $desc_foto = trim($_POST['descricao'] ?? '');
  if ($evento_id > 0 && !empty($_FILES['fotos']['name'][0])) {
    $files = $_FILES['fotos'];
    $adicionadas = 0;
    $st = $db->prepare('INSERT INTO evento_fotos (evento_id, arquivo, descricao) VALUES (?, ?, ?)');
    foreach ($files['name'] as $i => $fname) {
      if ($files['error'][$i] !== 0)
        continue;
      $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
        continue;
      if ($files['size'][$i] > 5 * 1024 * 1024)
        continue;
      $nome_arq = 'evento_' . uniqid() . '.' . $ext;
      $dest = __DIR__ . '/../data/uploads/' . $nome_arq;
      if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
        $st->execute([$evento_id, $nome_arq, $desc_foto ?: null]);
        $adicionadas++;
      }
    }
    $msg = "{$adicionadas} foto(s) adicionada(s)!";
    $tipo_msg = 'sucesso';
  }
}

// ── EXCLUIR FOTO DO EVENTO ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_foto') {
  $foto_id = (int) ($_POST['foto_id'] ?? 0);
  $evento_id = (int) ($_POST['evento_id'] ?? 0);
  if ($foto_id > 0) {
    $row = $db->prepare('SELECT arquivo FROM evento_fotos WHERE id = ?');
    $row->execute([$foto_id]);
    $arq = $row->fetchColumn();
    if ($arq) {
      $path = __DIR__ . '/../data/uploads/' . $arq;
      if (file_exists($path))
        unlink($path);
    }
    $db->prepare('DELETE FROM evento_fotos WHERE id = ?')->execute([$foto_id]);
  }
  header('Location: eventos.php?id=' . $evento_id);
  exit;
}

// ── SALVAR PROGRAMAÇÃO DO MÊS (uma ou várias imagens) ───────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'salvar_programacao') {
  $mes = (int) ($_POST['mes'] ?? 0);
  $ano = (int) ($_POST['ano'] ?? 0);
  if ($mes >= 1 && $mes <= 12 && $ano >= 2000 && !empty($_FILES['imagens']['name'][0])) {
    $files = $_FILES['imagens'];
    $st = $db->prepare('INSERT INTO programacao_mes (mes, ano, imagem) VALUES (?,?,?)');
    $adicionadas = 0;
    $ignoradas = 0;
    foreach ($files['name'] as $i => $fname) {
      if ($files['error'][$i] !== 0) {
        continue;
      }
      $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) || $files['size'][$i] > 10 * 1024 * 1024) {
        $ignoradas++;
        continue;
      }
      $nome_arq = 'prog_' . $mes . '_' . $ano . '_' . uniqid() . '.' . $ext;
      $dest = __DIR__ . '/../data/uploads/' . $nome_arq;
      if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
        $st->execute([$mes, $ano, $nome_arq]);
        $adicionadas++;
      }
    }
    if ($adicionadas > 0) {
      $msg = "{$adicionadas} imagem(ns) adicionada(s) à programação!"
        . ($ignoradas > 0 ? " {$ignoradas} ignorada(s) (formato/tamanho inválido)." : '');
      $tipo_msg = 'sucesso';
    } else {
      $msg = 'Nenhuma imagem válida enviada. Use JPG/PNG/WEBP até 10 MB.';
      $tipo_msg = 'erro';
    }
  } else {
    $msg = 'Selecione mês, ano e ao menos uma imagem.';
    $tipo_msg = 'erro';
  }
}

// ── EXCLUIR PROGRAMAÇÃO DO MÊS ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_programacao') {
  $prog_id = (int) ($_POST['prog_id'] ?? 0);
  if ($prog_id > 0) {
    $row = $db->prepare('SELECT imagem FROM programacao_mes WHERE id=?');
    $row->execute([$prog_id]);
    $arq = $row->fetchColumn();
    if ($arq) {
      $path = __DIR__ . '/../data/uploads/' . $arq;
      if (file_exists($path))
        unlink($path);
    }
    $db->prepare('DELETE FROM programacao_mes WHERE id=?')->execute([$prog_id]);
    $msg = 'Programação removida.';
    $tipo_msg = 'sucesso';
  }
}

// ── DADOS ───────────────────────────────────────────────────
// Tenta incluir horario; se coluna não existir (antes da migration), ignora
try {
  $eventos = $db->query(
    'SELECT e.*, COUNT(f.id) AS total_fotos
         FROM eventos e
         LEFT JOIN evento_fotos f ON f.evento_id = e.id
         GROUP BY e.id ORDER BY e.data_evento DESC'
  )->fetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  $eventos = [];
}

$programacoes = get_todas_programacoes();

$ev_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$ev_sel = null;
$ev_fotos = [];
if ($ev_id > 0) {
  $st = $db->prepare('SELECT * FROM eventos WHERE id = ?');
  $st->execute([$ev_id]);
  $ev_sel = $st->fetch(PDO::FETCH_ASSOC);
  if ($ev_sel) {
    $st2 = $db->prepare('SELECT * FROM evento_fotos WHERE evento_id = ? ORDER BY id');
    $st2->execute([$ev_id]);
    $ev_fotos = $st2->fetchAll(PDO::FETCH_ASSOC);
  }
}

// Aba ativa (eventos | programacao)
$aba_ativa = ($_GET['aba'] ?? 'eventos') === 'programacao' ? 'programacao' : 'eventos';
if ($ev_sel)
  $aba_ativa = 'eventos';

$meses_nome = [
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
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos – Admin Pé de Manga</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    rel="stylesheet" />
</head>

<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3><span class="material-symbols-outlined">event</span>
          <?= $ev_sel ? 'Fotos: ' . htmlspecialchars($ev_sel['nome']) : 'Eventos' ?>
        </h3>
        <div class="topbar-actions">
          <span class="topbar-user">Olá, <?= htmlspecialchars($_SESSION['adm_user']) ?></span>
          <?php if ($ev_sel): ?>
            <a href="eventos.php" class="btn-adm btn-adm-outline">
              <span class="material-symbols-outlined">arrow_back</span> Voltar
            </a>
          <?php elseif ($aba_ativa === 'eventos'): ?>
            <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')">
              <span class="material-symbols-outlined">add</span> Novo evento
            </button>
          <?php endif; ?>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>

      <div class="admin-content">
        <?php if ($msg): ?>
          <div class="alert alert-<?= $tipo_msg === 'sucesso' ? 'sucesso' : 'erro' ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <?php if ($ev_sel): ?>
          <!-- ══════════════════════════════════════
             GERENCIAR FOTOS DO EVENTO
        ══════════════════════════════════════ -->
          <div class="admin-card">
            <div class="admin-card-header">
              <h4><span class="material-symbols-outlined">photo_library</span>
                <?= htmlspecialchars($ev_sel['nome']) ?> —
                <?= date('d/m/Y', strtotime($ev_sel['data_evento'])) ?>
                <?php if (!empty($ev_sel['horario'])): ?>
                  <span class="badge badge-amarelo" style="margin-left:8px;font-size:.7rem;">
                    <?= htmlspecialchars($ev_sel['horario']) ?>
                  </span>
                <?php endif; ?>
              </h4>
              <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalUpload')">
                <span class="material-symbols-outlined">add_photo_alternate</span> Adicionar fotos
              </button>
            </div>
            <div class="admin-card-body">
              <?php if (empty($ev_fotos)): ?>
                <p style="text-align:center;padding:40px;color:var(--marrom-escuro);opacity:.85;">Nenhuma foto neste evento
                  ainda.</p>
              <?php else: ?>
                <div class="galeria-adm-grid">
                  <?php foreach ($ev_fotos as $foto): ?>
                    <div class="gal-thumb">
                      <img src="../data/uploads/<?= htmlspecialchars($foto['arquivo']) ?>"
                        alt="<?= htmlspecialchars($foto['descricao'] ?? '') ?>">
                      <div class="gal-overlay">
                        <form method="POST" onsubmit="return confirm('Remover esta foto?')" style="display:contents;">
                          <input type="hidden" name="acao" value="excluir_foto">
                          <input type="hidden" name="foto_id" value="<?= $foto['id'] ?>">
                          <input type="hidden" name="evento_id" value="<?= $ev_id ?>">
                          <button type="submit" title="Remover">
                            <span class="material-symbols-outlined">delete</span>
                          </button>
                        </form>
                      </div>
                      <?php if (!empty($foto['descricao'])): ?>
                        <div
                          style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.55);color:#fff;font-size:.65rem;padding:4px 6px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                          <?= htmlspecialchars($foto['descricao']) ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Modal Upload Fotos -->
          <div class="modal-overlay" id="modalUpload">
            <div class="modal-box" style="max-width:560px;">
              <button class="modal-close" onclick="fecharModal('modalUpload')">&times;</button>
              <h3>Adicionar Fotos</h3>
              <p class="modal-sub">Envie uma ou várias fotos para
                <strong><?= htmlspecialchars($ev_sel['nome']) ?></strong></p>
              <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="acao" value="upload_fotos">
                <input type="hidden" name="evento_id" value="<?= $ev_id ?>">
                <div class="form-group">
                  <label>Legenda (opcional — aplica-se a todas as fotos)</label>
                  <input type="text" name="descricao" placeholder="Ex: Apresentação na praça">
                </div>
                <div class="form-group">
                  <label>Imagens *</label>
                  <div class="upload-area" onclick="document.getElementById('fotosInput').click()">
                    <div class="ua-icon"><span class="material-symbols-outlined">upload</span></div>
                    <p>Clique ou arraste as imagens aqui</p>
                    <small>JPG, PNG, WEBP – máx. 5 MB por arquivo</small>
                  </div>
                  <input type="file" id="fotosInput" name="fotos[]" multiple accept="image/*" style="display:none"
                    onchange="previewImagens(this)">
                  <div id="fotos_preview_container" style="margin-top:12px; display:none;">
                    <div class="upload-preview" id="uploadPreview"></div>
                  </div>
                </div>
                <div class="form-actions">
                  <button type="button" class="btn-adm btn-adm-outline"
                    onclick="fecharModal('modalUpload')">Cancelar</button>
                  <button type="submit" class="btn-adm btn-adm-primary">Enviar fotos</button>
                </div>
              </form>
            </div>
          </div>

        <?php else: ?>
          <!-- ══════════════════════════════════════
             ABAS: EVENTOS | PROGRAMAÇÃO DO MÊS
        ══════════════════════════════════════ -->
          <div class="adm-tab-switcher">
            <a href="eventos.php?aba=eventos" class="adm-tab <?= $aba_ativa === 'eventos' ? 'active' : '' ?>">
              <span class="material-symbols-outlined">event</span> Eventos
            </a>
            <a href="eventos.php?aba=programacao" class="adm-tab <?= $aba_ativa === 'programacao' ? 'active' : '' ?>">
              <span class="material-symbols-outlined">calendar_month</span> Programação do Mês
            </a>
          </div>

          <?php if ($aba_ativa === 'eventos'): ?>
            <!-- ── ABA EVENTOS ──────────────────── -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h4><span class="material-symbols-outlined">event</span> Eventos cadastrados (<?= count($eventos) ?>)</h4>
                <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')">
                  <span class="material-symbols-outlined">add</span> Novo evento
                </button>
              </div>
              <div class="admin-card-body" style="padding:0;">
                <?php if (empty($eventos)): ?>
                  <p style="text-align:center;padding:40px;color:var(--marrom-escuro);opacity:.85;">Nenhum evento cadastrado
                    ainda.</p>
                <?php else: ?>
                  <div class="table-scroll">
                    <table>
                      <thead>
                        <tr>
                          <th>Data</th>
                          <th>Horário</th>
                          <th>Nome</th>
                          <th class="th-hide-mobile">Descrição</th>
                          <th>Fotos</th>
                          <th>Ações</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($eventos as $ev): ?>
                          <tr>
                            <td style="white-space:nowrap;">
                              <strong><?= date('d/m/Y', strtotime($ev['data_evento'])) ?></strong>
                            </td>
                            <td style="white-space:nowrap;font-size:.8rem;">
                              <?php if (!empty($ev['horario'])): ?>
                                <span class="badge badge-amarelo"><?= htmlspecialchars($ev['horario']) ?></span>
                              <?php else: ?>
                                <span style="opacity:.4;">—</span>
                              <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($ev['nome']) ?></strong></td>
                            <td class="td-hide-mobile" style="max-width:220px;font-size:.82rem;">
                              <?= htmlspecialchars(mb_strimwidth($ev['descricao'] ?? '', 0, 60, '...')) ?></td>
                            <td>
                              <span class="badge badge-verde"><?= $ev['total_fotos'] ?> foto(s)</span>
                            </td>
                            <td>
                              <div class="td-actions">
                                <a href="eventos.php?id=<?= $ev['id'] ?>" class="btn-adm btn-adm-verde" title="Gerenciar fotos">
                                  <span class="material-symbols-outlined">photo_library</span>
                                </a>
                                <button class="btn-adm btn-adm-outline"
                                  onclick="abrirEdicao(<?= htmlspecialchars(json_encode($ev)) ?>)" title="Editar"><span
                                    class="material-symbols-outlined">edit</span></button>
                                <form method="POST" style="display:inline;"
                                  onsubmit="return confirm('Excluir o evento «<?= htmlspecialchars(addslashes($ev['nome'])) ?>» e todas as suas fotos?')">
                                  <input type="hidden" name="acao" value="excluir_evento">
                                  <input type="hidden" name="id" value="<?= $ev['id'] ?>">
                                  <button type="submit" class="btn-adm btn-adm-danger" title="Excluir">
                                    <span class="material-symbols-outlined">delete</span>
                                  </button>
                                </form>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Modal Criar Evento -->
            <div class="modal-overlay" id="modalCriar">
              <div class="modal-box">
                <button class="modal-close" onclick="fecharModal('modalCriar')">&times;</button>
                <h3>Novo evento</h3>
                <p class="modal-sub">Preencha as informações. Depois adicione as fotos na lista.</p>
                <form method="POST">
                  <input type="hidden" name="acao" value="criar_evento">
                  <div class="form-row">
                    <div class="form-group">
                      <label>Nome do evento *</label>
                      <input type="text" name="nome" required placeholder="Ex: Oficina de Artesanato">
                    </div>
                    <div class="form-group">
                      <label>Data do evento *</label>
                      <input type="date" name="data_evento" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Horário <span style="font-weight:400;opacity:.6;">(opcional)</span></label>
                    <input type="text" name="horario" placeholder="Ex: 14h  ou  14:00–17:00">
                  </div>
                  <div class="form-group">
                    <label>Descrição (opcional)</label>
                    <textarea name="descricao" placeholder="Descreva brevemente o evento..."></textarea>
                  </div>
                  <div class="form-actions">
                    <button type="button" class="btn-adm btn-adm-outline"
                      onclick="fecharModal('modalCriar')">Cancelar</button>
                    <button type="submit" class="btn-adm btn-adm-primary">Criar evento</button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Modal Editar Evento -->
            <div class="modal-overlay" id="modalEditar">
              <div class="modal-box">
                <button class="modal-close" onclick="fecharModal('modalEditar')">&times;</button>
                <h3>Editar evento</h3>
                <form method="POST">
                  <input type="hidden" name="acao" value="editar_evento">
                  <input type="hidden" name="id" id="edit_id">
                  <div class="form-row">
                    <div class="form-group">
                      <label>Nome do evento *</label>
                      <input type="text" name="nome" id="edit_nome" required>
                    </div>
                    <div class="form-group">
                      <label>Data do evento *</label>
                      <input type="date" name="data_evento" id="edit_data" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Horário <span style="font-weight:400;opacity:.6;">(opcional)</span></label>
                    <input type="text" name="horario" id="edit_horario" placeholder="Ex: 14h  ou  14:00–17:00">
                  </div>
                  <div class="form-group">
                    <label>Descrição</label>
                    <textarea name="descricao" id="edit_desc"></textarea>
                  </div>
                  <div class="form-actions">
                    <button type="button" class="btn-adm btn-adm-outline"
                      onclick="fecharModal('modalEditar')">Cancelar</button>
                    <button type="submit" class="btn-adm btn-adm-primary">Salvar alterações</button>
                  </div>
                </form>
              </div>
            </div>

          <?php else: ?>
            <!-- ── ABA PROGRAMAÇÃO DO MÊS ──────── -->
            <div class="admin-card">
              <div class="admin-card-header">
                <h4><span class="material-symbols-outlined">calendar_month</span> Upload da Programação Mensal</h4>
              </div>
              <div class="admin-card-body">
                <p style="font-size:.88rem;color:var(--marrom-escuro);margin-bottom:20px;line-height:1.6;">
                  Faça o upload das imagens de programação do mês. Você pode enviar
                  <strong>várias imagens para o mesmo mês</strong> — elas aparecem num
                  carrossel na página de eventos. As imagens já cadastradas são mantidas.
                </p>
                <form method="POST" enctype="multipart/form-data" style="max-width:480px;">
                  <input type="hidden" name="acao" value="salvar_programacao">
                  <div class="form-row">
                    <div class="form-group">
                      <label>Mês *</label>
                      <select name="mes" required>
                        <option value="">Selecione…</option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                          <option value="<?= $m ?>" <?= $m == date('n') ? 'selected' : '' ?>>
                            <?= $meses_nome[$m] ?>
                          </option>
                        <?php endfor; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Ano *</label>
                      <select name="ano" required>
                        <?php for ($a = date('Y'); $a >= date('Y') - 2; $a--): ?>
                          <option value="<?= $a ?>"><?= $a ?></option>
                        <?php endfor; ?>
                        <?php $prox = date('Y') + 1; ?>
                        <option value="<?= $prox ?>"><?= $prox ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Imagens da Programação *</label>
                    <div class="upload-area" onclick="document.getElementById('progImgInput').click()">
                      <div class="ua-icon"><span class="material-symbols-outlined">image</span></div>
                      <p>Clique para selecionar uma ou várias imagens</p>
                      <small>JPG, PNG, WEBP – máx. 10 MB por arquivo</small>
                    </div>
                    <input type="file" id="progImgInput" name="imagens[]" accept="image/*" multiple style="display:none"
                      onchange="previewProgImg(this)">
                    <div id="prog_img_preview_container" style="margin-top:12px; display:none;">
                      <div class="upload-preview" id="progImgPreview"></div>
                    </div>
                  </div>
                  <div class="form-actions" style="justify-content:flex-start;">
                    <button type="submit" class="btn-adm btn-adm-primary">
                      <span class="material-symbols-outlined">upload</span> Salvar programação
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <!-- Lista de programações cadastradas -->
            <?php if (!empty($programacoes)): ?>
              <div class="admin-card" style="margin-top:24px;">
                <div class="admin-card-header">
                  <h4><span class="material-symbols-outlined">collections</span> Programações cadastradas
                    (<?= count($programacoes) ?>)</h4>
                </div>
                <div class="admin-card-body">
                  <div class="prog-adm-grid">
                    <?php foreach ($programacoes as $prog): ?>
                      <div class="prog-adm-item">
                        <img src="../data/uploads/<?= htmlspecialchars($prog['imagem']) ?>"
                          alt="Programação <?= $meses_nome[$prog['mes']] ?> <?= $prog['ano'] ?>"
                          onclick="abrirProgImg('../data/uploads/<?= htmlspecialchars($prog['imagem']) ?>')">
                        <div class="prog-adm-info">
                          <strong><?= $meses_nome[$prog['mes']] ?>         <?= $prog['ano'] ?></strong>
                          <form method="POST"
                            onsubmit="return confirm('Remover a programação de <?= $meses_nome[$prog['mes']] ?> <?= $prog['ano'] ?>?')">
                            <input type="hidden" name="acao" value="excluir_programacao">
                            <input type="hidden" name="prog_id" value="<?= $prog['id'] ?>">
                            <button type="submit" class="btn-adm btn-adm-danger" style="padding:5px 10px;font-size:.72rem;">
                              <span class="material-symbols-outlined" style="font-size:1rem;">delete</span> Remover
                            </button>
                          </form>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>

          <?php endif; // aba ?>
        <?php endif; // ev_sel ?>
      </div>
    </div>
  </div>

  <!-- Lightbox simples para ver imagem da programação ampliada -->
  <div id="progLightbox" onclick="this.style.display='none'"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:500;align-items:center;justify-content:center;cursor:zoom-out;">
    <img id="progLightboxImg" src="" alt=""
      style="max-width:92vw;max-height:92vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
  </div>

  <script>
    function abrirModal(id) { document.getElementById(id).classList.add('open'); }
    function fecharModal(id) { document.getElementById(id).classList.remove('open'); }
    document.querySelectorAll('.modal-overlay').forEach(el => {
      el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
    });

    function abrirEdicao(ev) {
      document.getElementById('edit_id').value = ev.id;
      document.getElementById('edit_nome').value = ev.nome;
      document.getElementById('edit_data').value = ev.data_evento;
      document.getElementById('edit_desc').value = ev.descricao || '';
      document.getElementById('edit_horario').value = ev.horario || '';
      abrirModal('modalEditar');
    }

    let fotosSelecionadas = [];

    function previewImagens(input) {
      if (input.files && input.files.length > 0) {
        fotosSelecionadas = Array.from(input.files);
      } else {
        fotosSelecionadas = [];
      }
      renderPreviewFotos();
    }

    function renderPreviewFotos() {
      const container = document.getElementById('fotos_preview_container');
      const preview = document.getElementById('uploadPreview');
      const input = document.getElementById('fotosInput');
      preview.innerHTML = '';
      
      if (fotosSelecionadas.length > 0) {
        container.style.display = 'block';
        
        // Use Promise.all to read all files and keep their relative orders correct
        const promises = fotosSelecionadas.map(file => {
          return new Promise(resolve => {
            const reader = new FileReader();
            reader.onload = e => resolve(e.target.result);
            reader.readAsDataURL(file);
          });
        });
        
        Promise.all(promises).then(results => {
          preview.innerHTML = ''; // Clear preview first
          results.forEach((src, index) => {
            const div = document.createElement('div');
            div.className = 'up-thumb';
            div.innerHTML = `
              <img src="${src}" alt="">
              <button type="button" class="up-remove" onclick="removerFotoSelecionada(${index})">&times;</button>
            `;
            preview.appendChild(div);
          });
        });
      } else {
        container.style.display = 'none';
        input.value = '';
      }
    }

    function removerFotoSelecionada(index) {
      const input = document.getElementById('fotosInput');
      fotosSelecionadas.splice(index, 1);
      
      const dt = new DataTransfer();
      fotosSelecionadas.forEach(file => dt.items.add(file));
      input.files = dt.files;
      
      renderPreviewFotos();
    }

    let progImgsSelecionadas = [];

    function previewProgImg(input) {
      if (input.files && input.files.length > 0) {
        progImgsSelecionadas = Array.from(input.files);
      } else {
        progImgsSelecionadas = [];
      }
      renderPreviewProgImgs();
    }

    function renderPreviewProgImgs() {
      const container = document.getElementById('prog_img_preview_container');
      const prev = document.getElementById('progImgPreview');
      const input = document.getElementById('progImgInput');
      prev.innerHTML = '';
      
      if (progImgsSelecionadas.length > 0) {
        container.style.display = 'block';
        
        const promises = progImgsSelecionadas.map(file => {
          return new Promise(resolve => {
            const reader = new FileReader();
            reader.onload = e => resolve(e.target.result);
            reader.readAsDataURL(file);
          });
        });
        
        Promise.all(promises).then(results => {
          prev.innerHTML = ''; // Clear preview first
          results.forEach((src, index) => {
            const div = document.createElement('div');
            div.className = 'up-thumb';
            div.innerHTML = `
              <img src="${src}" alt="">
              <button type="button" class="up-remove" onclick="removerProgImgSelecionada(${index})">&times;</button>
            `;
            prev.appendChild(div);
          });
        });
      } else {
        container.style.display = 'none';
        input.value = '';
      }
    }

    function removerProgImgSelecionada(index) {
      const input = document.getElementById('progImgInput');
      progImgsSelecionadas.splice(index, 1);
      
      const dt = new DataTransfer();
      progImgsSelecionadas.forEach(file => dt.items.add(file));
      input.files = dt.files;
      
      renderPreviewProgImgs();
    }

    function abrirProgImg(src) {
      const lb = document.getElementById('progLightbox');
      document.getElementById('progLightboxImg').src = src;
      lb.style.display = 'flex';
    }
  </script>
</body>

</html>