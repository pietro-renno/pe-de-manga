<?php
require '_auth.php';

$msg = '';
$db = get_db();

// ADICIONAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'adicionar') {
  $foto = fazer_upload('foto', 'colab_');
  $st = $db->prepare('INSERT INTO colaboradores (nome, funcao, descricao, foto) VALUES (?, ?, ?, ?)');
  $st->execute([
    trim($_POST['nome'] ?? ''),
    trim($_POST['funcao'] ?? ''),
    trim($_POST['descricao'] ?? ''),
    $foto ?: null,
  ]);
  $msg = 'sucesso:Colaborador adicionado com sucesso!';
}

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {
  $id = (int) ($_POST['id'] ?? 0);
  $nova_foto = fazer_upload('foto', 'colab_');
  if ($nova_foto) {
    $st = $db->prepare('UPDATE colaboradores SET nome=?, funcao=?, descricao=?, foto=? WHERE id=?');
    $st->execute([trim($_POST['nome'] ?? ''), trim($_POST['funcao'] ?? ''), trim($_POST['descricao'] ?? ''), $nova_foto, $id]);
  } else {
    $st = $db->prepare('UPDATE colaboradores SET nome=?, funcao=?, descricao=? WHERE id=?');
    $st->execute([trim($_POST['nome'] ?? ''), trim($_POST['funcao'] ?? ''), trim($_POST['descricao'] ?? ''), $id]);
  }
  $msg = 'sucesso:Colaborador atualizado!';
}

// REMOVER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'remover') {
  $id = (int) ($_POST['id'] ?? 0);
  $db->prepare('DELETE FROM colaboradores WHERE id=?')->execute([$id]);
  $msg = 'sucesso:Colaborador removido.';
}

$colaboradores = get_colaboradores();
[$tipo_msg, $texto_msg] = $msg ? explode(':', $msg, 2) : ['', ''];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Colaboradores – Admin Pé de Manga</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga e um Ponto de Cultura dedicado a arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3><span class="material-symbols-outlined pi-icon">group</span> Colaboradores</h3>
        <div class="topbar-actions">
          <button class="btn-adm btn-adm-primary" onclick="openModal('adicionar')"><span
              class="material-symbols-outlined">add</span> Adicionar colaborador</button>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>
      <div class="admin-content">
        <?php if ($texto_msg): ?>
          <div class="alert alert-<?= $tipo_msg ?>" style="margin-bottom:20px;"><?= htmlspecialchars($texto_msg) ?></div>
        <?php endif; ?>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4>Lista de colaboradores (<?= count($colaboradores) ?>)</h4>
          </div>
          <div class="admin-card-body">
            <?php if (empty($colaboradores)): ?>
              <p style="text-align:center;padding:40px;color:var(--marrom);opacity:.6;">Nenhum colaborador cadastrado
                ainda.</p>
            <?php else: ?>
              <div class="table-scroll">
              <table>
                <thead>
                  <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Função</th>
                    <th class="th-hide-mobile">Descrição</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($colaboradores as $c): ?>
                    <tr>
                      <td>
                        <?php if (!empty($c['foto'])): ?>
                          <img src="../data/uploads/<?= htmlspecialchars($c['foto']) ?>" alt="">
                        <?php else: ?>
                          <div
                            style="width:44px;height:44px;border-radius:50%;background:var(--amarelo-pastel);display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined"
                              style="font-size:1.4rem;color:var(--marrom);">person</span>
                          </div>
                        <?php endif; ?>
                      </td>
                      <td><strong><?= htmlspecialchars($c['nome']) ?></strong></td>
                      <td><span class="badge badge-verde"><?= htmlspecialchars($c['funcao']) ?></span></td>
                      <td class="td-hide-mobile" style="max-width:220px;font-size:.82rem;">
                        <?= htmlspecialchars(mb_strimwidth($c['descricao'] ?? '', 0, 80, '...')) ?>
                      </td>
                      <td>
                        <div class="td-actions">
                          <button class="btn-adm btn-adm-outline"
                            onclick="abrirEdicao(<?= htmlspecialchars(json_encode($c)) ?>)">&#9998; Editar</button>
                          <form method="POST" onsubmit="return confirm('Remover este colaborador?')">
                            <input type="hidden" name="acao" value="remover">
                            <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                            <button type="submit" class="btn-adm btn-adm-danger"><span
                                class="material-symbols-outlined pi-icon">delete</span> Remover</button>
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
      </div>
    </div>
  </div>

  <!-- Modal Adicionar -->
  <div class="modal-overlay" id="modal-adicionar" onclick="closeModal('adicionar')">
    <div class="modal-box" onclick="event.stopPropagation()">
      <button class="modal-close" onclick="closeModal('adicionar')">&times;</button>
      <h3>Adicionar Colaborador</h3>
      <p class="modal-sub">Preencha as informações do novo colaborador</p>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="adicionar">
        <div class="form-row">
          <div class="form-group"><label>Nome *</label><input type="text" name="nome" required></div>
          <div class="form-group"><label>Função *</label><input type="text" name="funcao" required
              placeholder="ex: Educadora Cultural"></div>
        </div>
        <div class="form-group"><label>Descrição</label><textarea name="descricao"
            placeholder="Breve descrição sobre o colaborador"></textarea></div>
        <div class="form-group"><label>Foto (opcional)</label><input type="file" name="foto" accept="image/*"></div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="closeModal('adicionar')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Salvar colaborador</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Editar -->
  <div class="modal-overlay" id="modal-editar" onclick="closeModal('editar')">
    <div class="modal-box" onclick="event.stopPropagation()">
      <button class="modal-close" onclick="closeModal('editar')">&times;</button>
      <h3>Editar Colaborador</h3>
      <p class="modal-sub">Atualize as informações</p>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" id="edit_id">
        <div class="form-row">
          <div class="form-group"><label>Nome *</label><input type="text" name="nome" id="edit_nome" required></div>
          <div class="form-group"><label>Função *</label><input type="text" name="funcao" id="edit_funcao" required>
          </div>
        </div>
        <div class="form-group"><label>Descrição</label><textarea name="descricao" id="edit_descricao"></textarea></div>
        <div class="form-group"><label>Nova foto (deixe vazio para manter)</label><input type="file" name="foto"
            accept="image/*"></div>
        <div id="edit_foto_preview" style="margin-top:8px;"></div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="closeModal('editar')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Salvar alterações</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(id) { document.getElementById('modal-' + id).classList.add('open'); }
    function closeModal(id) { document.getElementById('modal-' + id).classList.remove('open'); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open')); });

    function abrirEdicao(c) {
      document.getElementById('edit_id').value = c.id;
      document.getElementById('edit_nome').value = c.nome;
      document.getElementById('edit_funcao').value = c.funcao;
      document.getElementById('edit_descricao').value = c.descricao || '';
      const prev = document.getElementById('edit_foto_preview');
      prev.innerHTML = c.foto ? '<img src="../data/uploads/' + c.foto + '" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--amarelo);">' : '';
      openModal('editar');
    }
  </script>
</body>

</html>