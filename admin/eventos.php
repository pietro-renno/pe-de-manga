<?php
require '_auth.php';

$db       = get_db();
$msg      = '';
$tipo_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar_evento') {
    $nome = trim($_POST['nome']        ?? '');
    $desc = trim($_POST['descricao']   ?? '');
    $data = trim($_POST['data_evento'] ?? '');
    if ($nome === '' || $data === '') {
        $msg = 'Nome e data do evento são obrigatórios.';
        $tipo_msg = 'erro';
    } else {
        $db->prepare('INSERT INTO eventos (nome, descricao, data_evento) VALUES (?, ?, ?)')
           ->execute([$nome, $desc ?: null, $data]);
        $msg = 'Evento criado!';
        $tipo_msg = 'sucesso';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar_evento') {
    $id   = (int)($_POST['id']          ?? 0);
    $nome = trim($_POST['nome']         ?? '');
    $desc = trim($_POST['descricao']    ?? '');
    $data = trim($_POST['data_evento']  ?? '');
    if ($id > 0 && $nome !== '' && $data !== '') {
        $db->prepare('UPDATE eventos SET nome=?, descricao=?, data_evento=? WHERE id=?')
           ->execute([$nome, $desc ?: null, $data, $id]);
        $msg = 'Evento atualizado!';
        $tipo_msg = 'sucesso';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_evento') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $fotos = $db->prepare('SELECT arquivo FROM evento_fotos WHERE evento_id = ?');
        $fotos->execute([$id]);
        foreach ($fotos->fetchAll(PDO::FETCH_COLUMN) as $arq) {
            $path = __DIR__ . '/../data/uploads/' . $arq;
            if (file_exists($path)) unlink($path);
        }
        $db->prepare('DELETE FROM eventos WHERE id = ?')->execute([$id]);
    }
    header('Location: eventos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'upload_fotos') {
    $evento_id = (int)($_POST['evento_id'] ?? 0);
    $desc_foto = trim($_POST['descricao'] ?? '');
    if ($evento_id > 0 && !empty($_FILES['fotos']['name'][0])) {
        $files     = $_FILES['fotos'];
        $adicionadas = 0;
        $st = $db->prepare('INSERT INTO evento_fotos (evento_id, arquivo, descricao) VALUES (?, ?, ?)');
        foreach ($files['name'] as $i => $fname) {
            if ($files['error'][$i] !== 0) continue;
            $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) continue;
            if ($files['size'][$i] > 5 * 1024 * 1024) continue;
            $nome_arq = 'evento_' . uniqid() . '.' . $ext;
            $dest     = __DIR__ . '/../data/uploads/' . $nome_arq;
            if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                $st->execute([$evento_id, $nome_arq, $desc_foto ?: null]);
                $adicionadas++;
            }
        }
        $msg = "{$adicionadas} foto(s) adicionada(s)!";
        $tipo_msg = 'sucesso';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_foto') {
    $foto_id   = (int)($_POST['foto_id']   ?? 0);
    $evento_id = (int)($_POST['evento_id'] ?? 0);
    if ($foto_id > 0) {
        $row = $db->prepare('SELECT arquivo FROM evento_fotos WHERE id = ?');
        $row->execute([$foto_id]);
        $arq = $row->fetchColumn();
        if ($arq) {
            $path = __DIR__ . '/../data/uploads/' . $arq;
            if (file_exists($path)) unlink($path);
        }
        $db->prepare('DELETE FROM evento_fotos WHERE id = ?')->execute([$foto_id]);
    }
    header('Location: eventos.php?id=' . $evento_id);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'upload_mes_imagem') {
    $mes = (int)($_POST['mes'] ?? 0);
    $ano = (int)($_POST['ano'] ?? 0);
    if ($mes >= 1 && $mes <= 12 && $ano > 2000 && !empty($_FILES['imagem_mes']['name'])) {
        $file = $_FILES['imagem_mes'];
        if ($file['error'] === 0) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) && $file['size'] <= 5 * 1024 * 1024) {
                // Checa se já existe imagem para este mês e ano
                $st_chk = $db->prepare('SELECT id, arquivo_imagem FROM eventos_mes_imagem WHERE mes = ? AND ano = ?');
                $st_chk->execute([$mes, $ano]);
                $old = $st_chk->fetch(PDO::FETCH_ASSOC);
                
                $nome_arq = 'mes_' . $ano . '_' . $mes . '_' . uniqid() . '.' . $ext;
                $dest = __DIR__ . '/../data/uploads/' . $nome_arq;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    if ($old) {
                        $old_path = __DIR__ . '/../data/uploads/' . $old['arquivo_imagem'];
                        if (file_exists($old_path)) unlink($old_path);
                        $db->prepare('UPDATE eventos_mes_imagem SET arquivo_imagem = ? WHERE id = ?')->execute([$nome_arq, $old['id']]);
                    } else {
                        $db->prepare('INSERT INTO eventos_mes_imagem (mes, ano, arquivo_imagem) VALUES (?, ?, ?)')->execute([$mes, $ano, $nome_arq]);
                    }
                    $msg = 'Imagem do mês enviada com sucesso!';
                    $tipo_msg = 'sucesso';
                }
            } else {
                $msg = 'Arquivo inválido ou muito grande.';
                $tipo_msg = 'erro';
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir_mes_imagem') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $row = $db->prepare('SELECT arquivo_imagem FROM eventos_mes_imagem WHERE id = ?');
        $row->execute([$id]);
        $arq = $row->fetchColumn();
        if ($arq) {
            $path = __DIR__ . '/../data/uploads/' . $arq;
            if (file_exists($path)) unlink($path);
            $db->prepare('DELETE FROM eventos_mes_imagem WHERE id = ?')->execute([$id]);
        }
        $msg = 'Imagem do mês excluída.';
        $tipo_msg = 'sucesso';
    }
}

$eventos = $db->query(
    'SELECT e.*, COUNT(f.id) AS total_fotos
     FROM eventos e
     LEFT JOIN evento_fotos f ON f.evento_id = e.id
     GROUP BY e.id ORDER BY e.data_evento DESC'
)->fetchAll(PDO::FETCH_ASSOC);

$ev_id    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ev_sel   = null;
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

// Busca as imagens dos meses
$imagens_mes = [];
try {
    $imagens_mes = $db->query('SELECT * FROM eventos_mes_imagem ORDER BY ano DESC, mes DESC')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Ignora se a tabela ainda não existir (antes da migração)
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eventos – Admin Pé de Manga</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="../assets/css/admin.css">
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
          <?php else: ?>
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
        <div class="admin-card">
          <div class="admin-card-header">
            <h4><span class="material-symbols-outlined">photo_library</span>
              <?= htmlspecialchars($ev_sel['nome']) ?> —
              <?= date('d/m/Y', strtotime($ev_sel['data_evento'])) ?>
            </h4>
            <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalUpload')">
              <span class="material-symbols-outlined">add_photo_alternate</span> Adicionar fotos
            </button>
          </div>
          <div class="admin-card-body">
            <?php if (empty($ev_fotos)): ?>
              <p style="text-align:center;padding:40px;color:var(--marrom);opacity:.6;">Nenhuma foto neste evento ainda.</p>
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
                      <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.55);color:#fff;font-size:.65rem;padding:4px 6px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                        <?= htmlspecialchars($foto['descricao']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="modal-overlay" id="modalUpload">
          <div class="modal-box" style="max-width:560px;">
            <button class="modal-close" onclick="fecharModal('modalUpload')">&times;</button>
            <h3>Adicionar Fotos</h3>
            <p class="modal-sub">Envie uma ou várias fotos para <strong><?= htmlspecialchars($ev_sel['nome']) ?></strong></p>
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
                  <div class="ua-icon"><span class="material-symbols-outlined mi-xl">upload</span></div>
                  <p>Clique ou arraste as imagens aqui</p>
                  <small>JPG, PNG, WEBP – máx. 5 MB por arquivo</small>
                </div>
                <input type="file" id="fotosInput" name="fotos[]" multiple accept="image/*" style="display:none"
                       onchange="previewImagens(this)">
                <div class="upload-preview" id="uploadPreview"></div>
              </div>
              <div class="form-actions">
                <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalUpload')">Cancelar</button>
                <button type="submit" class="btn-adm btn-adm-primary">Enviar fotos</button>
              </div>
            </form>
          </div>
        </div>

        <?php else: ?>
        <div class="admin-card">
          <div class="admin-card-header">
            <h4><span class="material-symbols-outlined">event</span> Eventos cadastrados (<?= count($eventos) ?>)</h4>
            <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')">
              <span class="material-symbols-outlined">add</span> Novo evento
            </button>
          </div>
          <div class="admin-card-body" style="padding:0;">
            <?php if (empty($eventos)): ?>
              <p style="text-align:center;padding:40px;color:var(--marrom);opacity:.6;">Nenhum evento cadastrado ainda.</p>
            <?php else: ?>
              <div class="table-scroll">
              <table>
                <thead>
                  <tr>
                    <th>Data</th>
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
                      <td><strong><?= htmlspecialchars($ev['nome']) ?></strong></td>
                      <td class="td-hide-mobile" style="max-width:220px;font-size:.82rem;"><?= htmlspecialchars(mb_strimwidth($ev['descricao'] ?? '', 0, 60, '...')) ?></td>
                      <td>
                        <span class="badge badge-verde"><?= $ev['total_fotos'] ?> foto(s)</span>
                      </td>
                      <td>
                        <div class="td-actions">
                          <a href="eventos.php?id=<?= $ev['id'] ?>" class="btn-adm btn-adm-verde" title="Gerenciar fotos">
                            <span class="material-symbols-outlined">photo_library</span>
                          </a>
                          <button class="btn-adm btn-adm-outline"
                            onclick="abrirEdicao(<?= htmlspecialchars(json_encode($ev)) ?>)"
                            title="Editar"><span class="material-symbols-outlined">edit</span></button>
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

        <div class="admin-card" style="margin-top: 2rem;">
          <div class="admin-card-header">
            <h4><span class="material-symbols-outlined">calendar_month</span> Flyer do Mês (Calendário)</h4>
            <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalMesImagem')">
              <span class="material-symbols-outlined">add</span> Adicionar Flyer
            </button>
          </div>
          <div class="admin-card-body">
            <p style="font-size:.9rem;color:var(--marrom);opacity:.8;margin-bottom:15px;">A imagem adicionada aparecerá em destaque na agenda pública. Formato ideal: Vertical (Stories/Instagram).</p>
            <?php if (empty($imagens_mes)): ?>
              <p style="text-align:center;padding:20px;color:var(--marrom);opacity:.6;">Nenhum flyer mensal cadastrado.</p>
            <?php else: ?>
              <div class="galeria-adm-grid">
                <?php foreach ($imagens_mes as $img): ?>
                  <div class="gal-thumb" style="aspect-ratio: 9/16; border: 2px solid #eee;">
                    <img src="../data/uploads/<?= htmlspecialchars($img['arquivo_imagem']) ?>" alt="Mês <?= $img['mes'] ?>/<?= $img['ano'] ?>">
                    <div class="gal-overlay">
                      <form method="POST" onsubmit="return confirm('Remover a imagem de <?= $img['mes'] ?>/<?= $img['ano'] ?>?')" style="display:contents;">
                        <input type="hidden" name="acao" value="excluir_mes_imagem">
                        <input type="hidden" name="id" value="<?= $img['id'] ?>">
                        <button type="submit" title="Remover"><span class="material-symbols-outlined">delete</span></button>
                      </form>
                    </div>
                    <div style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.7);color:#fff;font-size:.8rem;padding:6px;text-align:center;">
                      <?= sprintf('%02d/%04d', $img['mes'], $img['ano']) ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Modal de criar evento -->
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
                <label>Descrição (opcional)</label>
                <textarea name="descricao" placeholder="Descreva brevemente o evento..."></textarea>
              </div>
              <div class="form-actions">
                <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalCriar')">Cancelar</button>
                <button type="submit" class="btn-adm btn-adm-primary">Criar evento</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal de editar evento -->
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
                <label>Descrição</label>
                <textarea name="descricao" id="edit_desc"></textarea>
              </div>
              <div class="form-actions">
                <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn-adm btn-adm-primary">Salvar alterações</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal Imagem do Mês -->
        <div class="modal-overlay" id="modalMesImagem">
          <div class="modal-box">
            <button class="modal-close" onclick="fecharModal('modalMesImagem')">&times;</button>
            <h3>Flyer do Mês</h3>
            <p class="modal-sub">Envie a imagem resumo com a programação do mês.</p>
            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="acao" value="upload_mes_imagem">
              <div class="form-row">
                <div class="form-group">
                  <label>Mês *</label>
                  <select name="mes" required>
                    <?php 
                    $meses_nome = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
                    $mes_atual = (int)date('n');
                    for($i=1; $i<=12; $i++): ?>
                      <option value="<?= $i ?>" <?= $i === $mes_atual ? 'selected' : '' ?>><?= sprintf('%02d', $i) ?> - <?= $meses_nome[$i-1] ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Ano *</label>
                  <input type="number" name="ano" required value="<?= date('Y') ?>" min="2000" max="2099">
                </div>
              </div>
              <div class="form-group">
                <label>Imagem (JPG, PNG, WEBP) *</label>
                <input type="file" name="imagem_mes" required accept="image/*" style="padding:10px 0;">
              </div>
              <div class="form-actions">
                <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalMesImagem')">Cancelar</button>
                <button type="submit" class="btn-adm btn-adm-primary">Enviar</button>
              </div>
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function abrirModal(id) { document.getElementById(id).classList.add('open'); }
    function fecharModal(id) { document.getElementById(id).classList.remove('open'); }
    document.querySelectorAll('.modal-overlay').forEach(el => {
      el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
    });
    function abrirEdicao(ev) {
      document.getElementById('edit_id').value   = ev.id;
      document.getElementById('edit_nome').value = ev.nome;
      document.getElementById('edit_data').value = ev.data_evento;
      document.getElementById('edit_desc').value = ev.descricao || '';
      abrirModal('modalEditar');
    }
    function previewImagens(input) {
      const preview = document.getElementById('uploadPreview');
      preview.innerHTML = '';
      Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
          const div = document.createElement('div');
          div.className = 'up-thumb';
          div.innerHTML = '<img src="' + e.target.result + '" alt="">';
          preview.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }
    const area = document.getElementById('uploadArea');
    const inp  = document.getElementById('fotosInput');
    if (area && inp) {
      area.addEventListener('dragover',  e => { e.preventDefault(); area.classList.add('drag'); });
      area.addEventListener('dragleave', ()  => area.classList.remove('drag'));
      area.addEventListener('drop', e => {
        e.preventDefault(); area.classList.remove('drag');
        inp.files = e.dataTransfer.files; previewImagens(inp);
      });
    }
  </script>
  <style>
    #uploadArea { cursor: pointer; }
  </style>
</body>
</html>
