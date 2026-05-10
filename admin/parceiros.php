<?php
require '_auth.php';

$msg = '';
$db = get_db();

// ADICIONAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'adicionar') {
  $logo = fazer_upload('logo', 'parceiro_');
  $st = $db->prepare('INSERT INTO parceiros (nome, site, `desc`, logo) VALUES (?, ?, ?, ?)');
  $st->execute([
    trim($_POST['nome'] ?? ''),
    trim($_POST['site'] ?? '') ?: null,
    trim($_POST['desc'] ?? '') ?: null,
    $logo ?: null,
  ]);
  $msg = 'sucesso:Parceiro adicionado!';
}

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {
  $id = (int) ($_POST['id'] ?? 0);
  $novo_logo = fazer_upload('logo', 'parceiro_');
  if ($novo_logo) {
    $st = $db->prepare('UPDATE parceiros SET nome=?, site=?, `desc`=?, logo=? WHERE id=?');
    $st->execute([trim($_POST['nome'] ?? ''), trim($_POST['site'] ?? '') ?: null, trim($_POST['desc'] ?? '') ?: null, $novo_logo, $id]);
  } else {
    $st = $db->prepare('UPDATE parceiros SET nome=?, site=?, `desc`=? WHERE id=?');
    $st->execute([trim($_POST['nome'] ?? ''), trim($_POST['site'] ?? '') ?: null, trim($_POST['desc'] ?? '') ?: null, $id]);
  }
  $msg = 'sucesso:Parceiro atualizado!';
}

// REMOVER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'remover') {
  $id = (int) ($_POST['id'] ?? 0);
  $db->prepare('DELETE FROM parceiros WHERE id=?')->execute([$id]);
  $msg = 'sucesso:Parceiro removido.';
}

$parceiros = get_parceiros();
[$tipo_msg, $texto_msg] = $msg ? explode(':', $msg, 2) : ['', ''];
?>
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
  <title>Parceiros – Admin Pé de Manga</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3><span class="material-symbols-outlined">handshake</span> Parceiros</h3>
        <div class="topbar-actions">
          <button class="btn-adm btn-adm-primary" onclick="openModal('adicionar')"><span
              class="material-symbols-outlined">add</span> Adicionar parceiro</button>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>
      <div class="admin-content">
        <?php if ($texto_msg): ?>
          <div class="alert alert-<?= $tipo_msg ?>" style="margin-bottom:20px;"><?= htmlspecialchars($texto_msg) ?></div>
        <?php endif; ?>
        <div class="admin-card">
          <div class="admin-card-header">
            <h4>Lista de parceiros (<?= count($parceiros) ?>)</h4>
          </div>
          <div class="admin-card-body">
            <?php if (empty($parceiros)): ?>
              <p style="text-align:center;padding:40px;color:var(--marrom);opacity:.6;">Nenhum parceiro cadastrado ainda.
              </p>
            <?php else: ?>
              <table>
                <thead>
                  <tr>
                    <th>Logo</th>
                    <th>Nome</th>
                    <th>Site</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($parceiros as $p): ?>
                    <tr>
                      <td>
                        <?php if (!empty($p['logo'])): ?>
                          <img src="../data/uploads/<?= htmlspecialchars($p['logo']) ?>"
                            style="width:44px;height:44px;object-fit:contain;border-radius:6px;">
                        <?php else: ?>
                          <div
                            style="width:44px;height:44px;border-radius:6px;background:var(--amarelo-pastel);display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined"
                              style="font-size:1.4rem;color:var(--marrom);">handshake</span>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                      <td>
                        <?php if (!empty($p['site'])): ?>
                          <a href="<?= htmlspecialchars($p['site']) ?>" target="_blank"
                            style="color:var(--verde);font-size:.82rem;"><?= htmlspecialchars($p['site']) ?></a>
                        <?php else: ?>—<?php endif; ?>
                      </td>
                      <td style="max-width:200px;font-size:.82rem;">
                        <?= htmlspecialchars(mb_strimwidth($p['desc'] ?? '', 0, 60, '...')) ?>
                      </td>
                      <td>
                        <div class="td-actions">
                          <button class="btn-adm btn-adm-outline"
                            onclick="abrirEdicao(<?= htmlspecialchars(json_encode($p)) ?>)"><span
                              class="material-symbols-outlined">edit</span> Editar</button>
                          <form method="POST" onsubmit="return confirm('Remover este parceiro?')">
                            <input type="hidden" name="acao" value="remover">
                            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                            <button type="submit" class="btn-adm btn-adm-danger"><span
                                class="material-symbols-outlined">delete</span> Remover</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-overlay" id="modal-adicionar" onclick="closeModal('adicionar')">
    <div class="modal-box" onclick="event.stopPropagation()">
      <button class="modal-close" onclick="closeModal('adicionar')">&times;</button>
      <h3>Adicionar Parceiro</h3>
      <p class="modal-sub">Preencha as informações do parceiro</p>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="adicionar">
        <div class="form-row">
          <div class="form-group"><label>Nome *</label><input type="text" name="nome" required></div>
          <div class="form-group"><label>Site</label><input type="url" name="site" placeholder="https://"></div>
        </div>
        <div class="form-group"><label>Descrição</label><textarea name="desc"
            placeholder="Breve descrição do parceiro"></textarea></div>
        <div class="form-group"><label>Logo (opcional)</label><input type="file" name="logo" accept="image/*"></div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="closeModal('adicionar')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Salvar parceiro</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal-overlay" id="modal-editar" onclick="closeModal('editar')">
    <div class="modal-box" onclick="event.stopPropagation()">
      <button class="modal-close" onclick="closeModal('editar')">&times;</button>
      <h3>Editar Parceiro</h3>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" id="edit_id">
        <div class="form-row">
          <div class="form-group"><label>Nome *</label><input type="text" name="nome" id="edit_nome" required></div>
          <div class="form-group"><label>Site</label><input type="url" name="site" id="edit_site"></div>
        </div>
        <div class="form-group"><label>Descrição</label><textarea name="desc" id="edit_desc"></textarea></div>
        <div class="form-group"><label>Nova logo (deixe vazio para manter)</label><input type="file" name="logo"
            accept="image/*"></div>
        <div id="edit_logo_prev" style="margin-top:8px;"></div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="closeModal('editar')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(id) { document.getElementById('modal-' + id).classList.add('open'); }
    function closeModal(id) { document.getElementById('modal-' + id).classList.remove('open'); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open')); });
    function abrirEdicao(p) {
      document.getElementById('edit_id').value = p.id;
      document.getElementById('edit_nome').value = p.nome;
      document.getElementById('edit_site').value = p.site || '';
      document.getElementById('edit_desc').value = p.desc || '';
      const prev = document.getElementById('edit_logo_prev');
      prev.innerHTML = p.logo ? '<img src="../data/uploads/' + p.logo + '" style="height:48px;object-fit:contain;border-radius:6px;">' : '';
      openModal('editar');
    }
  </script>
</body>

</html>