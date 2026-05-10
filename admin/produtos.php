<?php
require '_auth.php';

$db       = get_db();
$msg      = '';
$tipo_msg = '';

$cores = [
  'linear-gradient(135deg,#f4e999,#f2be2c)' => 'Amarelo',
  'linear-gradient(135deg,#fde8c6,#f5c870)' => 'Laranja',
  'linear-gradient(135deg,#d4efbd,#83c155)' => 'Verde',
  'linear-gradient(135deg,#c8e6f5,#7ec8e3)' => 'Azul',
  'linear-gradient(135deg,#f5d5e0,#e87a9d)' => 'Rosa',
  'linear-gradient(135deg,#e8d5f5,#9b6fd4)' => 'Roxo',
];

// ── CRIAR ──────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {
    $nome = trim($_POST['nome']      ?? '');
    $desc = trim($_POST['descricao'] ?? '');
    $tag  = trim($_POST['tag']       ?? '');
    $cor  = $_POST['cor_fundo']      ?? array_key_first($cores);
    if (!array_key_exists($cor, $cores)) $cor = array_key_first($cores);
    $foto = fazer_upload('foto', 'produto_');

    if ($nome === '') {
        $msg = 'O nome do produto é obrigatório.';
        $tipo_msg = 'erro';
    } else {
        $db->prepare('INSERT INTO produtos (nome, descricao, tag, foto, cor_fundo) VALUES (?, ?, ?, ?, ?)')
           ->execute([$nome, $desc ?: null, $tag ?: null, $foto ?: null, $cor]);
        $msg = 'Produto criado com sucesso!';
        $tipo_msg = 'sucesso';
    }
}

// ── EDITAR ─────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {
    $id   = (int)($_POST['id'] ?? 0);
    $nome = trim($_POST['nome']      ?? '');
    $desc = trim($_POST['descricao'] ?? '');
    $tag  = trim($_POST['tag']       ?? '');
    $cor  = $_POST['cor_fundo']      ?? array_key_first($cores);
    if (!array_key_exists($cor, $cores)) $cor = array_key_first($cores);
    $nova_foto = fazer_upload('foto', 'produto_');

    if ($id > 0 && $nome !== '') {
        if ($nova_foto) {
            // apaga foto antiga
            $old = $db->prepare('SELECT foto FROM produtos WHERE id = ?');
            $old->execute([$id]);
            $old_foto = $old->fetchColumn();
            if ($old_foto) {
                $path = __DIR__ . '/../data/uploads/' . $old_foto;
                if (file_exists($path)) unlink($path);
            }
            $db->prepare('UPDATE produtos SET nome=?,descricao=?,tag=?,foto=?,cor_fundo=? WHERE id=?')
               ->execute([$nome, $desc ?: null, $tag ?: null, $nova_foto, $cor, $id]);
        } else {
            $db->prepare('UPDATE produtos SET nome=?,descricao=?,tag=?,cor_fundo=? WHERE id=?')
               ->execute([$nome, $desc ?: null, $tag ?: null, $cor, $id]);
        }
        $msg = 'Produto atualizado!';
        $tipo_msg = 'sucesso';
    }
}

// ── REMOVER FOTO ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'remover_foto') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $row = $db->prepare('SELECT foto FROM produtos WHERE id = ?');
        $row->execute([$id]);
        $arquivo = $row->fetchColumn();
        if ($arquivo) {
            $path = __DIR__ . '/../data/uploads/' . $arquivo;
            if (file_exists($path)) unlink($path);
            $db->prepare('UPDATE produtos SET foto = NULL WHERE id = ?')->execute([$id]);
        }
    }
    header('Location: produtos.php');
    exit;
}

// ── TOGGLE ATIVO ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'toggle_ativo') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) $db->prepare('UPDATE produtos SET ativo = NOT ativo WHERE id = ?')->execute([$id]);
    header('Location: produtos.php');
    exit;
}

// ── EXCLUIR ───────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $row = $db->prepare('SELECT foto FROM produtos WHERE id = ?');
        $row->execute([$id]);
        $arquivo = $row->fetchColumn();
        if ($arquivo) {
            $path = __DIR__ . '/../data/uploads/' . $arquivo;
            if (file_exists($path)) unlink($path);
        }
        $db->prepare('DELETE FROM produtos WHERE id = ?')->execute([$id]);
    }
    header('Location: produtos.php');
    exit;
}

$produtos = $db->query('SELECT * FROM produtos ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos – Admin Pé de Manga</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>
<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3><span class="material-symbols-outlined">local_mall</span> Produtos</h3>
        <div class="topbar-actions">
          <span class="topbar-user">Olá, <?= htmlspecialchars($_SESSION['adm_user']) ?></span>
          <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')">
            <span class="material-symbols-outlined">add</span> Novo produto
          </button>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>

      <div class="admin-content">
        <?php if ($msg): ?>
          <div class="alert alert-<?= $tipo_msg === 'sucesso' ? 'sucesso' : 'erro' ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4><span class="material-symbols-outlined">local_mall</span> Produtos cadastrados (<?= count($produtos) ?>)
            </h4>
            <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')">
              <span class="material-symbols-outlined">add</span> Novo produto
            </button>
          </div>
          <div class="admin-card-body" style="padding:0;">
            <?php if (empty($produtos)): ?>
              <p style="text-align:center;padding:40px;color:var(--marrom);opacity:.6;">Nenhum produto cadastrado ainda.
              </p>
            <?php else: ?>
              <table>
                <thead>
                  <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Tag</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($produtos as $p): ?>
                    <tr>
                      <td>
                        <?php if (!empty($p['foto'])): ?>
                          <img src="../data/uploads/<?= htmlspecialchars($p['foto']) ?>"
                               style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:2px solid var(--amarelo-pastel);">
                        <?php else: ?>
                          <div style="width:52px;height:52px;border-radius:8px;background:<?= htmlspecialchars($p['cor_fundo']) ?>;"></div>
                        <?php endif; ?>
                      </td>
                      <td><strong><?= htmlspecialchars($p['nome']) ?></strong></td>
                      <td>
                        <?php if (!empty($p['tag'])): ?>
                          <span class="badge badge-verde"><?= htmlspecialchars($p['tag']) ?></span>
                        <?php else: ?>&mdash;<?php endif; ?>
                      </td>
                      <td style="max-width:220px;font-size:.82rem;"><?= htmlspecialchars(mb_strimwidth($p['descricao'] ?? '', 0, 65, '...')) ?></td>
                      <td>
                        <span class="badge <?= $p['ativo'] ? 'badge-verde' : '' ?>"
                          style="<?= !$p['ativo'] ? 'background:#fde8e8;color:#c0392b;' : '' ?>">
                          <?= $p['ativo'] ? 'Ativo' : 'Inativo' ?>
                        </span>
                      </td>
                      <td>
                        <div class="td-actions">
                          <button class="btn-adm btn-adm-outline"
                            onclick="abrirEdicao(<?= htmlspecialchars(json_encode($p)) ?>)"
                            title="Editar"><span class="material-symbols-outlined">edit</span></button>
                          <?php if (!empty($p['foto'])): ?>
                          <form method="POST" style="display:inline;"
                            onsubmit="return confirm('Remover a foto deste produto?')">
                            <input type="hidden" name="acao" value="remover_foto">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn-adm btn-adm-outline" title="Remover foto">
                              <span class="material-symbols-outlined">hide_image</span>
                            </button>
                          </form>
                          <?php endif; ?>
                          <form method="POST" style="display:inline;">
                            <input type="hidden" name="acao" value="toggle_ativo">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit"
                              class="btn-adm <?= $p['ativo'] ? 'btn-adm-outline' : 'btn-adm-verde' ?>"
                              title="<?= $p['ativo'] ? 'Desativar' : 'Ativar' ?>">
                              <span class="material-symbols-outlined"><?= $p['ativo'] ? 'block' : 'check_circle' ?></span>
                            </button>
                          </form>
                          <form method="POST" style="display:inline;"
                            onsubmit="return confirm('Excluir o produto <?= htmlspecialchars(addslashes($p['nome'])) ?>?')">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
            <?php endif; ?>
          </div>
        </div>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4><span class="material-symbols-outlined">info</span> Como funciona o botão Comprar</h4>
          </div>
          <div class="admin-card-body">
            <p style="font-size:.86rem;color:var(--marrom);line-height:1.7;">
              O botão <strong>Comprar</strong> na loja abre o WhatsApp com a mensagem:<br>
              <code>"Olá! Gostaria de comprar: [Nome do produto]"</code><br>
              para o número <strong>(12) 99762-4486</strong>. Produtos <em>inativos</em> não aparecem na loja.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Criar produto -->
  <div class="modal-overlay" id="modalCriar">
    <div class="modal-box">
      <button class="modal-close" onclick="fecharModal('modalCriar')">&times;</button>
      <h3>Novo produto</h3>
      <p class="modal-sub">Preencha os dados. O botão "Comprar" enviará o cliente para o WhatsApp.</p>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="criar">
        <div class="form-group">
          <label>Nome do produto *</label>
          <input type="text" name="nome" required placeholder="Ex: Camisetas Pé de Manga">
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Tag (categoria)</label>
            <input type="text" name="tag" placeholder="Ex: Moda consciente">
          </div>
          <div class="form-group">
            <label>Cor do cartão (sem foto)</label>
            <select name="cor_fundo">
              <?php foreach ($cores as $val => $label): ?>
                <option value="<?= htmlspecialchars($val) ?>"><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Foto do produto (opcional)</label>
          <input type="file" name="foto" accept="image/*">
          <small style="font-size:.72rem;color:rgba(136,105,46,.5);">JPG, PNG, WEBP – máx. 5 MB. Se não enviar foto, a cor do cartão será usada.</small>
        </div>
        <div class="form-group">
          <label>Descrição</label>
          <textarea name="descricao" placeholder="Descreva o produto brevemente..."></textarea>
        </div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalCriar')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Criar produto</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Editar produto -->
  <div class="modal-overlay" id="modalEditar">
    <div class="modal-box">
      <button class="modal-close" onclick="fecharModal('modalEditar')">&times;</button>
      <h3>Editar produto</h3>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" id="edit_id">
        <div class="form-group">
          <label>Nome do produto *</label>
          <input type="text" name="nome" id="edit_nome" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Tag (categoria)</label>
            <input type="text" name="tag" id="edit_tag">
          </div>
          <div class="form-group">
            <label>Cor do cartão (sem foto)</label>
            <select name="cor_fundo" id="edit_cor">
              <?php foreach ($cores as $val => $label): ?>
                <option value="<?= htmlspecialchars($val) ?>"><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Nova foto (deixe vazio para manter)</label>
          <div id="edit_foto_preview" style="margin-bottom:8px;"></div>
          <input type="file" name="foto" accept="image/*">
          <small style="font-size:.72rem;color:rgba(136,105,46,.5);">JPG, PNG, WEBP – máx. 5 MB.</small>
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

  <script>
    function abrirModal(id) { document.getElementById(id).classList.add('open'); }
    function fecharModal(id) { document.getElementById(id).classList.remove('open'); }
    document.querySelectorAll('.modal-overlay').forEach(el => {
      el.addEventListener('click', e => { if (e.target === el) el.classList.remove('open'); });
    });
    function abrirEdicao(p) {
      document.getElementById('edit_id').value = p.id;
      document.getElementById('edit_nome').value = p.nome;
      document.getElementById('edit_tag').value  = p.tag || '';
      document.getElementById('edit_desc').value = p.descricao || '';
      const sel = document.getElementById('edit_cor');
      for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === p.cor_fundo) { sel.selectedIndex = i; break; }
      }
      const prev = document.getElementById('edit_foto_preview');
      prev.innerHTML = p.foto
        ? '<img src="../data/uploads/' + p.foto + '" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--amarelo);margin-bottom:4px;">'
        : '';
      abrirModal('modalEditar');
    }
  </script>
</body>
</html>
